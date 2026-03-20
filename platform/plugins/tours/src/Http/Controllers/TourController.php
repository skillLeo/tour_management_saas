<?php

namespace Botble\Tours\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Tours\Forms\TourForm;
use Botble\Tours\Http\Requests\TourRequest;
use Botble\Tours\Models\Tour;
use Botble\Tours\Models\TourFaq;
use Botble\Tours\Models\TourPlace;
use Botble\Tours\Models\TourSchedule;
use Botble\Tours\Models\TourTimeSlot;
use Botble\Tours\Repositories\Interfaces\TourInterface;
use Botble\Tours\Tables\TourTable;
use Botble\SeoHelper\Facades\SeoHelper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TourController extends BaseController
{
    public function __construct(protected TourInterface $tourRepository)
    {
    }

    public function index(TourTable $table)
    {
        $this->pageTitle(trans('plugins/tours::tours.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        $this->pageTitle(trans('plugins/tours::tours.create'));

        return $formBuilder->create(TourForm::class)->renderForm();
    }

    public function store(TourRequest $request, BaseHttpResponse $response)
    {
        $data = $request->input();
        $data['author_id'] = Auth::guard()->id();

        $tour = $this->tourRepository->createOrUpdate($data);
        
        // Handle languages
        $this->handleTourLanguages($tour, $request->input('languages', []));

        // Handle FAQs
        $this->handleTourFaqs($tour, $request->input('faqs', []));

        // Handle Places
        $this->handleTourPlaces($tour, $request->input('places', []));

        // Handle Schedules
        $this->handleTourSchedules($tour, $request->input('schedules', []));

        // Handle Time Slots
        $this->handleTourTimeSlots($tour, $request->input('time_slots', []));
        
        // Save SEO metadata
        SeoHelper::saveMetaData(TOUR_MODULE_SCREEN_NAME, $request, $tour);

        event(new CreatedContentEvent(TOUR_MODULE_SCREEN_NAME, $request, $tour));

        return $response
            ->setPreviousUrl(route('tours.index'))
            ->setNextUrl(route('tours.edit', $tour->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function show(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $tour = $this->tourRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $tour));

        return view('plugins/tours::tours.show', compact('tour'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $tour = $this->tourRepository->findOrFail($id);
        $tour->load([
            'places' => function($query) {
                $query->orderBy('order');
            },
            'schedules' => function($query) {
                $query->orderBy('order');
            }
        ]);

        event(new BeforeEditContentEvent($request, $tour));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $tour->name]));

        return $formBuilder->create(TourForm::class, ['model' => $tour])->renderForm();
    }

    public function update(int|string $id, TourRequest $request, BaseHttpResponse $response)
    {
        $tour = $this->tourRepository->findOrFail($id);

        $tour->fill($request->input());

        $this->tourRepository->createOrUpdate($tour);
        
        // Handle languages
        $this->handleTourLanguages($tour, $request->input('languages', []));

        // Handle FAQs
        $this->handleTourFaqs($tour, $request->input('faqs', []));

        // Handle Places
        $this->handleTourPlaces($tour, $request->input('places', []));

        // Handle Schedules
        $this->handleTourSchedules($tour, $request->input('schedules', []));

        // Handle Time Slots
        $this->handleTourTimeSlots($tour, $request->input('time_slots', []));
        
        // Save SEO metadata
        SeoHelper::saveMetaData(TOUR_MODULE_SCREEN_NAME, $request, $tour);

        event(new UpdatedContentEvent(TOUR_MODULE_SCREEN_NAME, $request, $tour));

        return $response
            ->setPreviousUrl(route('tours.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $tour = $this->tourRepository->findOrFail($id);

            $this->tourRepository->delete($tour);
            
            // Delete SEO metadata
            SeoHelper::deleteMetaData(TOUR_MODULE_SCREEN_NAME, $tour);

            event(new DeletedContentEvent(TOUR_MODULE_SCREEN_NAME, $request, $tour));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    protected function handleTourFaqs(Tour $tour, array $faqs): void
    {
        // Get existing FAQ IDs
        $existingFaqIds = collect($faqs)
            ->filter(fn($faq) => !empty($faq['id']))
            ->pluck('id')
            ->toArray();

        // Delete FAQs that are not in the request
        $tour->faqs()->whereNotIn('id', $existingFaqIds)->delete();

        // Create or update FAQs
        foreach ($faqs as $faqData) {
            if (empty($faqData['question']) || empty($faqData['answer'])) {
                continue;
            }

            $faqData['tour_id'] = $tour->id;
            $faqData['status'] = $faqData['status'] ?? 'published';
            $faqData['order'] = $faqData['order'] ?? 0;

            if (!empty($faqData['id'])) {
                // Update existing FAQ
                TourFaq::where('id', $faqData['id'])
                    ->where('tour_id', $tour->id)
                    ->update([
                        'question' => $faqData['question'],
                        'answer' => $faqData['answer'],
                        'order' => $faqData['order'],
                        'status' => $faqData['status'],
                    ]);
            } else {
                // Create new FAQ
                TourFaq::create($faqData);
            }
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
            }

            if (!empty($slotData['id'])) {
                // Update existing time slot
                TourTimeSlot::where('id', $slotData['id'])
                    ->where('tour_id', $tour->id)
                    ->update([
                        'start_time' => $slotData['start_time'],
                        'order' => $slotData['order'],
                        'status' => $slotData['status'],
                        'restricted_days' => $slotData['restricted_days'] ?? null,
                    ]);
            } else {
                // Create new time slot
                TourTimeSlot::create($slotData);
            }
        }
    }

    protected function handleTourPlaces(Tour $tour, array $places): void
    {
        // Get existing place IDs
        $existingPlaceIds = collect($places)
            ->filter(fn($place) => !empty($place['id']))
            ->pluck('id')
            ->toArray();

        // Delete places that are not in the request
        $tour->places()->whereNotIn('id', $existingPlaceIds)->delete();

        // Create or update places
        foreach ($places as $placeData) {
            if (empty($placeData['name'])) {
                continue;
            }

            $placeData['tour_id'] = $tour->id;
            $placeData['status'] = $placeData['status'] ?? 'published';
            $placeData['order'] = $placeData['order'] ?? 0;

            if (!empty($placeData['id'])) {
                // Update existing place
                TourPlace::where('id', $placeData['id'])
                    ->where('tour_id', $tour->id)
                    ->update([
                        'name' => $placeData['name'],
                        'image' => $placeData['image'] ?? null,
                        'order' => $placeData['order'],
                        'status' => $placeData['status'],
                    ]);
            } else {
                // Create new place
                TourPlace::create($placeData);
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
    
    protected function handleTourSchedules(Tour $tour, array $schedules): void
    {
        // Get existing schedule IDs
        $existingScheduleIds = collect($schedules)
            ->filter(fn($schedule) => !empty($schedule['id']))
            ->pluck('id')
            ->toArray();

        // Delete schedules that are not in the request
        $tour->schedules()->whereNotIn('id', $existingScheduleIds)->delete();

        // Create or update schedules
        foreach ($schedules as $scheduleData) {
            if (empty($scheduleData['title'])) {
                continue;
            }

            $scheduleData['tour_id'] = $tour->id;
            $scheduleData['status'] = $scheduleData['status'] ?? 'published';
            $scheduleData['order'] = $scheduleData['order'] ?? 0;

            if (!empty($scheduleData['id'])) {
                // Update existing schedule
                TourSchedule::where('id', $scheduleData['id'])
                    ->where('tour_id', $tour->id)
                    ->update([
                        'title' => $scheduleData['title'],
                        'description' => $scheduleData['description'] ?? '',
                        'order' => $scheduleData['order'],
                        'status' => $scheduleData['status'],
                    ]);
            } else {
                // Create new schedule
                TourSchedule::create($scheduleData);
            }
        }
    }
}