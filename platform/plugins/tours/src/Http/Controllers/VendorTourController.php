<?php

namespace Botble\Tours\Http\Controllers;
use Illuminate\Support\Facades\Schema;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\Customer;
use Botble\Tours\Models\Tour;
use Botble\Tours\Models\TourFaq;
use Botble\Tours\Models\TourPlace;
use Botble\Tours\Models\TourSchedule;
use Botble\Tours\Models\TourTimeSlot;
use Botble\Tours\Models\TourCity;
use Botble\Tours\Models\TourLanguage;
use Botble\Tours\Repositories\Interfaces\TourInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Tours\Tables\VendorTourTable;
use Botble\Media\Facades\RvMedia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Botble\SeoHelper\Facades\SeoHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class VendorTourController extends BaseController
{
    public function __construct(protected TourInterface $tourRepository)
    {
    }

    public function index(Request $request)
    {
        $this->pageTitle(__('Tours'));

        $query = Tour::query()
            ->leftJoin('slugs', function ($join) {
                $join->on('slugs.reference_id', '=', 'tours.id')
                    ->where('slugs.reference_type', '=', Tour::class);
            })
            ->select([
                'tours.id',
                'tours.name',
                'slugs.key as slug',
                'tours.image',
                'tours.price',
                'tours.category_id',
                'tours.duration_days',
                'tours.duration_nights',
                'tours.duration_hours',
                'tours.created_at',
                'tours.status',
                'tours.store_id',
                'tours.author_id',
                'tours.author_type',
            ])
            ->with(['category'])
            ->whereNotNull('slugs.key');
        
        // Check if store_id column exists
        if (Schema::hasColumn('tours', 'store_id')) {
            $query->where('store_id', auth('customer')->user()->store->id);
        } else {
            // Fallback to author_id for existing installations
            $query->where('author_id', auth('customer')->id())
                  ->where('author_type', \Botble\Ecommerce\Models\Customer::class);
        }

        // Add search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        // Add status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        
        $tours = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Keep query parameters in pagination links
        $tours->appends($request->query());

        return view('plugins/tours::themes.vendor-dashboard.tours.index', compact('tours'));
    }

    public function create()
    {
        $this->pageTitle(__('Create New Tour'));

        $categories = \Botble\Tours\Models\TourCategory::query()
            ->where('status', 'published')
            ->pluck('name', 'id')
            ->all();
            
        $cities = TourCity::query()
            ->where('status', 'published')
            ->pluck('name', 'id')
            ->all();

        return view('plugins/tours::themes.vendor-dashboard.tours.create', compact('categories', 'cities'));
    }

    public function store(Request $request, BaseHttpResponse $response)
    {
        // Validate request data (excluding currency)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|file|image|max:10240', // 10MB max
            'gallery' => 'nullable|array',
            'gallery.*' => 'file|image|max:10240',
            'duration_days' => 'nullable|integer|min:0',
            'duration_hours' => 'nullable|integer|min:0',
            'duration_nights' => 'nullable|integer|min:0',
            'max_people' => 'required|integer|min:1',
            'min_people' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'children_price' => 'nullable|numeric|min:0',
            'infants_price' => 'nullable|numeric|min:0',
            'sale_percentage' => 'nullable|numeric|min:0|max:100',
            'location' => 'nullable|string|max:255',
            'departure_location' => 'nullable|string|max:255',
            'return_location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'included_services' => 'nullable|string',
            'excluded_services' => 'nullable|string',
            'activities' => 'nullable|string',
            'tour_highlights' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'allow_booking' => 'nullable|boolean',
            'booking_advance_days' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:tour_categories,id',
            'city_id' => 'nullable|exists:tour_cities,id',
            'places' => 'nullable|array',
            'places.*.name' => 'required|string|max:255',
            'places.*.image_file' => 'nullable|file|image|max:5120', // 5MB max
            'places.*.image' => 'nullable|string',
            'places.*.order' => 'nullable|integer|min:0',
        ]);
        
        Log::info('=== TOUR STORE REQUEST START ===');
        Log::info('All Request Data:', $request->all());
        Log::info('Has Gallery Files:', ['has_files' => $request->hasFile('gallery')]);
        Log::info('Gallery Input:', ['gallery' => $request->input('gallery')]);
        Log::info('All Files:', $request->allFiles());
        Log::info('Places Data:', ['places' => $request->input('places')]);
        Log::info('Places Files:', ['places_files' => $request->file('places')]);
        
        $data = $validatedData;
        
        // Set vendor-specific data
        $customer = auth('customer')->user();
        $data['store_id'] = $customer->store->id;
        $data['author_id'] = $customer->id;
        $data['author_type'] = Customer::class;
        
        // Set status based on marketplace settings
        $data['status'] = MarketplaceHelper::getSetting('enable_tour_approval', true) 
            ? BaseStatusEnum::PENDING 
            : BaseStatusEnum::PUBLISHED;

        // Store slug temporarily for the listener
        $slugSource = $request->input('slug') ?: $request->input('name');
        $tempSlug = $this->generateUniqueSlug($slugSource);

        // Handle file uploads
        if ($request->hasFile('image')) {
            Log::info('Processing featured image...');
            $data['image'] = $this->handleImageUpload($request->file('image'));
            Log::info('Featured image processed:', ['image' => $data['image']]);
        }
        
        if ($request->hasFile('gallery')) {
            $galleryFiles = $request->file('gallery');
            Log::info('Gallery files received:', ['count' => count($galleryFiles), 'files' => array_map(fn($f) => $f->getClientOriginalName(), $galleryFiles)]);
            $galleryUrls = $this->handleGalleryUpload($galleryFiles);
            Log::info('Gallery processed:', ['gallery' => $galleryUrls]);
            
            // Store gallery as JSON string, not array
            $data['gallery'] = !empty($galleryUrls) ? json_encode($galleryUrls) : null;
            Log::info('Gallery data to store:', ['gallery' => $data['gallery']]);
        } else {
            Log::info('No gallery files received');
        }

        // Convert comma-separated strings to arrays for certain fields
        $listFields = ['included_services', 'excluded_services', 'activities', 'tour_highlights'];
        foreach ($listFields as $field) {
            if (!empty($data[$field])) {
                $data[$field] = array_map('trim', explode(',', $data[$field]));
            }
        }

        Log::info('Data before creating tour:', ['gallery' => $data['gallery'] ?? 'NOT SET', 'slug' => $tempSlug]);
        
        $tour = $this->tourRepository->createOrUpdate($data);
        
        // Set the slug attribute temporarily so the listener can use it
        $tour->slug = $tempSlug;
        
        Log::info('Tour created:', ['id' => $tour->id, 'gallery_stored' => $tour->gallery, 'slug' => $tempSlug]);

        // Handle related data
        $this->handleTourFaqs($tour, $request->input('faqs', []));
        $this->handleTourPlaces($tour, $request->input('places', []), $request);
        $this->handleTourSchedules($tour, $request->input('schedules', []));
        $this->handleTourTimeSlots($tour, $request->input('time_slots', []));
        $this->handleTourLanguages($tour, $request->input('languages', []));
        
        // Save SEO metadata
        SeoHelper::saveMetaData(TOUR_MODULE_SCREEN_NAME, $request, $tour);

        // Refresh to get latest data
        $tour->refresh();
        Log::info('Tour after refresh:', ['id' => $tour->id, 'gallery_final' => $tour->gallery, 'slug_final' => $tour->slug]);
        Log::info('=== TOUR STORE REQUEST END ===');

        event(new CreatedContentEvent(TOUR_MODULE_SCREEN_NAME, $request, $tour));

        return $response
            ->setPreviousUrl(route('marketplace.vendor.tours.index'))
            ->setNextUrl(route('marketplace.vendor.tours.edit', $tour->id))
            ->setMessage(__('Tour created successfully!'));
    }

    public function edit(int|string $id)
    {
        $tour = $this->tourRepository->findOrFail($id);
        
        // Ensure vendor can only edit their own tours
        abort_if($tour->store_id != auth('customer')->user()->store->id, 404);

        // Load relationships for advanced sections
        $tour->load([
            'faqs' => function($query) {
                $query->orderBy('order', 'asc');
            },
            'places' => function($query) {
                $query->orderBy('order', 'asc');
            },
            'schedules' => function($query) {
                $query->orderBy('order', 'asc');
            },
            'timeSlots' => function($query) {
                $query->orderBy('order', 'asc');
            }
        ]);

        $this->pageTitle(__('Edit Tour: :name', ['name' => $tour->name]));

        $categories = \Botble\Tours\Models\TourCategory::query()
            ->where('status', 'published')
            ->pluck('name', 'id')
            ->all();
            
        $cities = TourCity::query()
            ->where('status', 'published')
            ->pluck('name', 'id')
            ->all();

        return view('plugins/tours::themes.vendor-dashboard.tours.edit', compact('tour', 'categories', 'cities'));
    }

    public function update(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $tour = $this->tourRepository->findOrFail($id);
        
        // Ensure vendor can only edit their own tours
        abort_if($tour->store_id != auth('customer')->user()->store->id, 404);

        // Validate request data (excluding currency)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|file|image|max:10240', // 10MB max
            'gallery' => 'nullable|array',
            'gallery.*' => 'file|image|max:10240',
            'duration_days' => 'nullable|integer|min:0',
            'duration_hours' => 'nullable|integer|min:0',
            'duration_nights' => 'nullable|integer|min:0',
            'max_people' => 'required|integer|min:1',
            'min_people' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'children_price' => 'nullable|numeric|min:0',
            'infants_price' => 'nullable|numeric|min:0',
            'sale_percentage' => 'nullable|numeric|min:0|max:100',
            'location' => 'nullable|string|max:255',
            'departure_location' => 'nullable|string|max:255',
            'return_location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'included_services' => 'nullable|string',
            'excluded_services' => 'nullable|string',
            'activities' => 'nullable|string',
            'tour_highlights' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'allow_booking' => 'nullable|boolean',
            'booking_advance_days' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:tour_categories,id',
            'city_id' => 'nullable|exists:tour_cities,id',
            'removed_gallery_images' => 'nullable|string',
            'places' => 'nullable|array',
            'places.*.name' => 'required|string|max:255',
            'places.*.image_file' => 'nullable|file|image|max:5120', // 5MB max
            'places.*.image' => 'nullable|string',
            'places.*.order' => 'nullable|integer|min:0',
        ]);

        Log::info('=== TOUR UPDATE REQUEST START ===');
        Log::info('All Request Data:', $request->all());
        Log::info('Has Gallery Files:', ['has_files' => $request->hasFile('gallery')]);
        Log::info('Gallery Input:', ['gallery' => $request->input('gallery')]);
        Log::info('All Files:', $request->allFiles());
        Log::info('Places Data:', ['places' => $request->input('places')]);
        Log::info('Places Files:', ['places_files' => $request->file('places')]);

        $data = $validatedData;
        
        // Ensure store_id doesn't change
        $data['store_id'] = $tour->store_id;

        // Store slug temporarily for the listener
        $slugSource = $request->input('slug') ?: $request->input('name');
        $tempSlug = $this->generateUniqueSlug($slugSource, $tour->id);

        // Handle file uploads
        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpload($request->file('image'));
        }
        
        // Handle removed gallery images
        $existingGallery = $tour->gallery ?: [];
        if ($request->has('removed_gallery_images') && !empty($request->input('removed_gallery_images'))) {
            $removedImages = json_decode($request->input('removed_gallery_images'), true);
            if (is_array($removedImages)) {
                Log::info('Removing gallery images:', ['removed' => $removedImages]);
                $existingGallery = array_diff($existingGallery, $removedImages);
                Log::info('Gallery after removal:', ['remaining' => $existingGallery]);
            }
        }
        
        // Handle gallery uploads (add to existing gallery)
        if ($request->hasFile('gallery')) {
            $galleryFiles = $request->file('gallery');
            Log::info('Gallery files received for update:', ['count' => count($galleryFiles), 'files' => array_map(fn($f) => $f->getClientOriginalName(), $galleryFiles)]);
            $newGallery = $this->handleGalleryUpload($galleryFiles);
            $mergedGallery = array_merge($existingGallery, $newGallery);
            Log::info('Gallery updated:', ['existing' => $existingGallery, 'new' => $newGallery, 'merged' => $mergedGallery]);
            
            // Store gallery as JSON string, not array
            $data['gallery'] = !empty($mergedGallery) ? json_encode($mergedGallery) : null;
        } else {
            // If no new uploads but images were removed, update gallery
            if ($request->has('removed_gallery_images')) {
                $data['gallery'] = !empty($existingGallery) ? json_encode($existingGallery) : null;
                Log::info('Gallery updated with only removals:', ['final' => $data['gallery']]);
            }
        }

        // Convert comma-separated strings to arrays for certain fields
        $listFields = ['included_services', 'excluded_services', 'activities', 'tour_highlights'];
        foreach ($listFields as $field) {
            if (!empty($data[$field])) {
                $data[$field] = array_map('trim', explode(',', $data[$field]));
            }
        }

        $tour = $this->tourRepository->createOrUpdate($data, ['id' => $id]);

        // Set the slug attribute temporarily so the listener can use it
        $tour->slug = $tempSlug;

        // Handle related data
        $this->handleTourFaqs($tour, $request->input('faqs', []));
        $this->handleTourPlaces($tour, $request->input('places', []), $request);
        $this->handleTourSchedules($tour, $request->input('schedules', []));
        $this->handleTourTimeSlots($tour, $request->input('time_slots', []));
        $this->handleTourLanguages($tour, $request->input('languages', []));
        
        // Save SEO metadata
        SeoHelper::saveMetaData(TOUR_MODULE_SCREEN_NAME, $request, $tour);

        event(new UpdatedContentEvent(TOUR_MODULE_SCREEN_NAME, $request, $tour));

        return $response
            ->setPreviousUrl(route('marketplace.vendor.tours.index'))
            ->setMessage(__('Tour updated successfully!'));
    }

    public function destroy(int|string $id, BaseHttpResponse $response)
    {
        $tour = $this->tourRepository->findOrFail($id);
        
        // Ensure vendor can only delete their own tours
        abort_if($tour->store_id != auth('customer')->user()->store->id, 404);

        try {
            // Delete SEO metadata
            SeoHelper::deleteMetaData(TOUR_MODULE_SCREEN_NAME, $tour);
            
            $this->tourRepository->delete($tour);
            
            event(new DeletedContentEvent('TOUR_MODULE_SCREEN_NAME', request(), $tour));

            return $response->setMessage(__('Tour deleted successfully!'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    protected function handleImageUpload($file)
    {
        if (!$file || !$file->isValid()) {
            Log::error('Invalid file provided to handleImageUpload');
            return null;
        }

        $customer = auth('customer')->user();
        $uploadFolder = $customer->store?->upload_folder ?: $customer->upload_folder;

        Log::info('Uploading image for customer:', [
            'customer_id' => $customer->id,
            'upload_folder' => $uploadFolder,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize()
        ]);

        $result = RvMedia::handleUpload($file, 0, $uploadFolder);

        if ($result['error']) {
            Log::error('Image upload failed:', $result);
            return null;
        }

        Log::info('Image upload successful:', [
            'url' => $result['data']->url,
            'file_id' => $result['data']->id
        ]);

        return $result['data']->url;
    }

    protected function handleGalleryUpload($files)
    {
        if (!$files || !is_array($files)) {
            return [];
        }

        $customer = auth('customer')->user();
        $uploadFolder = $customer->store?->upload_folder ?: $customer->upload_folder;
        $gallery = [];
        
        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $result = RvMedia::handleUpload($file, 0, $uploadFolder);
                
                if (!$result['error']) {
                    $gallery[] = $result['data']->url;
                }
            }
        }
        
        return $gallery;
    }

    protected function generateUniqueSlug(string $value, int|string|null $ignoreId = null): string
    {
        $slug = Str::slug($value);
        $slug = $slug ?: (string) time();

        $baseSlug = $slug;
        $counter = 1;

        while (Tour::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }



    public function duplicate(int|string $id, BaseHttpResponse $response)
    {
        $tour = $this->tourRepository->findOrFail($id);
        
        // Ensure vendor can only duplicate their own tours
        abort_if($tour->store_id != auth('customer')->user()->store->id, 404);

        try {
            $newTour = $tour->replicate();
            $newTour->name = $tour->name . ' (Copy)';
            $newTour->slug = null; // Will be auto-generated
            $newTour->status = BaseStatusEnum::DRAFT;
            $newTour->save();

            // Duplicate related data
            foreach ($tour->faqs as $faq) {
                $newFaq = $faq->replicate();
                $newFaq->tour_id = $newTour->id;
                $newFaq->save();
            }

            foreach ($tour->places as $place) {
                $newPlace = $place->replicate();
                $newPlace->tour_id = $newTour->id;
                $newPlace->save();
            }

            foreach ($tour->schedules as $schedule) {
                $newSchedule = $schedule->replicate();
                $newSchedule->tour_id = $newTour->id;
                $newSchedule->save();
            }

            return $response
                ->setNextUrl(route('marketplace.vendor.tours.edit', $newTour->id))
                ->setMessage(__('Tour duplicated successfully!'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    protected function handleTourFaqs(Tour $tour, array $faqs): void
    {
        TourFaq::where('tour_id', $tour->id)->delete();

        foreach ($faqs as $faqData) {
            if (empty($faqData['question']) || empty($faqData['answer'])) {
                continue;
            }

            TourFaq::create([
                'tour_id' => $tour->id,
                'question' => $faqData['question'],
                'answer' => $faqData['answer'],
                'order' => $faqData['order'] ?? 0,
            ]);
        }
    }

    protected function handleTourPlaces(Tour $tour, array $places, $request = null): void
    {
        Log::info('=== HANDLE TOUR PLACES START ===');
        Log::info('Tour ID:', ['tour_id' => $tour->id]);
        Log::info('Places data:', $places);

        // Delete existing places
        $deletedCount = TourPlace::where('tour_id', $tour->id)->delete();
        Log::info('Deleted existing places:', ['count' => $deletedCount]);

        // Get files from request if available
        $placeFiles = $request ? $request->file('places') : [];

        foreach ($places as $index => $placeData) {
            Log::info("Processing place {$index}:", $placeData);
            
            if (empty($placeData['name'])) {
                Log::warning("Skipping place {$index} - empty name");
                continue;
            }

            $imageUrl = $placeData['image'] ?? null;
            Log::info("Initial image URL for place {$index}:", ['image_url' => $imageUrl]);
            
            // Handle new image upload - check if there's a file for this place index
            if (isset($placeFiles[$index]['image_file']) && $placeFiles[$index]['image_file']) {
                Log::info("Found image file for place index {$index}:", ['file' => $placeFiles[$index]['image_file']->getClientOriginalName()]);
                $newImageUrl = $this->handleImageUpload($placeFiles[$index]['image_file']);
                if ($newImageUrl) {
                    $imageUrl = $newImageUrl;
                    Log::info("New image uploaded successfully:", ['new_url' => $imageUrl]);
                } else {
                    Log::error("Failed to upload image for place {$index}");
                }
            }

            $placeDataToCreate = [
                'tour_id' => $tour->id,
                'name' => $placeData['name'],
                'image' => $imageUrl,
                'order' => $placeData['order'] ?? 0,
            ];

            Log::info("Creating place with data:", $placeDataToCreate);

            try {
                $place = TourPlace::create($placeDataToCreate);
                Log::info("Place created successfully:", ['place_id' => $place->id]);
            } catch (Exception $e) {
                Log::error("Failed to create place {$index}:", ['error' => $e->getMessage()]);
            }
        }

        Log::info('=== HANDLE TOUR PLACES END ===');
    }

    protected function handleTourSchedules(Tour $tour, array $schedules): void
    {
        TourSchedule::where('tour_id', $tour->id)->delete();

        foreach ($schedules as $scheduleData) {
            if (empty($scheduleData['title'])) {
                continue;
            }

            TourSchedule::create([
                'tour_id' => $tour->id,
                'title' => $scheduleData['title'],
                'description' => $scheduleData['description'] ?? '',
                'duration' => $scheduleData['duration'] ?? null,
                'order' => $scheduleData['order'] ?? 0,
            ]);
        }
    }

    protected function handleTourTimeSlots(Tour $tour, array $timeSlots): void
    {
        // Get existing time slot IDs
        $existingSlotIds = collect($timeSlots)
            ->filter(fn($slot) => !empty($slot['id']))
            ->pluck('id')
            ->toArray();

        // Delete time slots that are not in the request
        $tour->timeSlots()->whereNotIn('id', $existingSlotIds)->delete();

        // Create or update time slots
        foreach ($timeSlots as $slotData) {
            if (empty($slotData['start_time'])) {
                continue;
            }

            $slotData['tour_id'] = $tour->id;
            $slotData['status'] = $slotData['status'] ?? 'available';
            $slotData['order'] = $slotData['order'] ?? 0;
            
            // Handle restricted days
            $restrictedDays = $slotData['restricted_days'] ?? [];
            if (!empty($restrictedDays)) {
                $slotData['restricted_days'] = array_map('strtolower', $restrictedDays);
            } else {
                $slotData['restricted_days'] = null;
            }

            if (!empty($slotData['id'])) {
                // Update existing time slot
                TourTimeSlot::where('id', $slotData['id'])
                    ->where('tour_id', $tour->id)
                    ->update([
                        'start_time' => $slotData['start_time'],
                        'order' => $slotData['order'],
                        'status' => $slotData['status'],
                        'restricted_days' => $slotData['restricted_days'],
                    ]);
            } else {
                // Create new time slot
                TourTimeSlot::create($slotData);
            }
        }
    }
    
    protected function handleTourLanguages(Tour $tour, array $languages): void
    {
        // Process languages array to ensure proper format
        $languageIds = [];
        foreach ($languages as $key => $value) {
            // If the key is numeric and the value is also numeric, use the value as language ID
            if (is_numeric($key) && is_numeric($value)) {
                $languageIds[] = $value;
            }
            // If the value is an array (this happens with languages[] format)
            elseif (is_numeric($key) && is_array($value)) {
                foreach ($value as $langId) {
                    if (is_numeric($langId)) {
                        $languageIds[] = $langId;
                    }
                }
            }
        }
        
        // Sync languages with the tour using clean array of IDs
        $tour->languages()->sync($languageIds);
    }

    public function postUpload(Request $request)
    {
        $customer = auth('customer')->user();

        if (!$customer || !$customer->is_vendor) {
            return $this->httpResponse()
                ->setError()
                ->setMessage(__('Unauthorized'));
        }

        $uploadFolder = $customer->store?->upload_folder ?: $customer->upload_folder;

        if (!RvMedia::isChunkUploadEnabled()) {
            $validator = Validator::make($request->all(), [
                'file.0' => ['required', 'image', 'mimes:jpg,jpeg,png,gif'],
            ]);

            if ($validator->fails()) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage($validator->getMessageBag()->first());
            }

            $result = RvMedia::handleUpload($request->file('file')[0], 0, $uploadFolder);

            if ($result['error']) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage($result['message']);
            }

            return $this->httpResponse()
                ->setData($result['data']);
        }

        // Handle chunk upload if enabled
        return $this->httpResponse()
            ->setError()
            ->setMessage(__('Chunk upload not implemented yet'));
    }
}
