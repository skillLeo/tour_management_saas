<?php

namespace Botble\Tours\Listeners;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Slug\Events\UpdatedSlugEvent;
use Botble\Slug\Models\Slug;
use Botble\Tours\Models\Tour;
use Botble\Tours\Models\TourCategory;
use Illuminate\Support\Facades\Schema;

class SyncTourSlug
{
    public function handleUpdatedSlug(UpdatedSlugEvent $event): void
    {
        if ($event->slug && $event->data) {
            if ($event->data instanceof TourCategory) {
                $this->syncCategorySlug($event->data->id, $event->slug);
            } elseif ($event->data instanceof Tour) {
                $this->syncTourSlug($event->data->id, $event->slug);
            }
        }
    }

    public function handleCreatedContent(CreatedContentEvent $event): void
    {
        $this->handleContentEvent($event->data);
    }

    public function handleUpdatedContent(UpdatedContentEvent $event): void
    {
        $this->handleContentEvent($event->data);
    }

    protected function handleContentEvent($model): void
    {
        if ($model instanceof TourCategory) {
            $slug = Slug::query()
                ->where('reference_type', TourCategory::class)
                ->where('reference_id', $model->id)
                ->first();

            if ($slug) {
                $this->syncCategorySlug($model->id, $slug);
            } else {
                // Create slug if it doesn't exist
                $this->createCategorySlug($model);
            }
        } elseif ($model instanceof Tour) {
            $slug = Slug::query()
                ->where('reference_type', Tour::class)
                ->where('reference_id', $model->id)
                ->first();

            if ($slug) {
                $this->syncTourSlug($model->id, $slug);
            } else {
                // Create slug if it doesn't exist
                $this->createTourSlug($model);
            }
        }
    }

    protected function syncCategorySlug(int $categoryId, Slug $slug): void
    {
        if (! Schema::hasColumn('tour_categories', 'slug')) {
            return;
        }

        TourCategory::query()
            ->where('id', $categoryId)
            ->update(['slug' => $slug->key]);
    }

    protected function syncTourSlug(int $tourId, Slug $slug): void
    {
        if (! Schema::hasColumn('tours', 'slug')) {
            return;
        }

        Tour::query()
            ->where('id', $tourId)
            ->update(['slug' => $slug->key]);
    }

    protected function createCategorySlug(TourCategory $category): void
    {
        if (empty($category->slug)) {
            return;
        }

        // Create slug entry in slugs table
        Slug::query()->create([
            'key' => $category->slug,
            'reference_type' => TourCategory::class,
            'reference_id' => $category->id,
            'prefix' => 'tour-categories',
        ]);
    }

    protected function createTourSlug(Tour $tour): void
    {
        // Generate slug from name if not provided
        $slug = $tour->slug ?: \Illuminate\Support\Str::slug($tour->name);
        
        if (empty($slug)) {
            $slug = (string) time();
        }

        // Ensure slug is unique
        $slug = $this->ensureUniqueSlug($slug, $tour->id);

        // Create slug entry in slugs table
        Slug::query()->create([
            'key' => $slug,
            'reference_type' => Tour::class,
            'reference_id' => $tour->id,
            'prefix' => 'tours',
        ]);
    }

    protected function ensureUniqueSlug(string $slug, int $tourId): string
    {
        $baseSlug = $slug;
        $counter = 1;

        while (Slug::query()
            ->where('key', $slug)
            ->where('reference_type', Tour::class)
            ->where('reference_id', '!=', $tourId)
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}
