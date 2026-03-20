<?php

namespace Botble\Tours\Console\Commands;

use Botble\Slug\Models\Slug;
use Botble\Tours\Models\Tour;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class BackfillTourSlugs extends Command
{
    protected $signature = 'tours:backfill-slugs';
    
    protected $description = 'Backfill missing slugs for existing tours in the slugs table';

    public function handle(): int
    {
        $this->info('Starting to backfill tour slugs...');

        // Get all tours
        $tours = Tour::query()->get();

        if ($tours->isEmpty()) {
            $this->info('No tours found.');
            return self::SUCCESS;
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($tours as $tour) {
            // Check if slug entry exists in slugs table
            $slugEntry = Slug::query()
                ->where('reference_type', Tour::class)
                ->where('reference_id', $tour->id)
                ->first();

            if ($slugEntry) {
                // Slug entry exists, check if it matches the tour's slug
                if ($tour->slug && $slugEntry->key !== $tour->slug) {
                    $slugEntry->update(['key' => $tour->slug]);
                    $this->info(sprintf('Updated slug for tour "%s" (ID: %d): %s', 
                        $tour->name, $tour->id, $tour->slug));
                    $updated++;
                } else {
                    $skipped++;
                }
            } else {
                // No slug entry, create one
                if (empty($tour->slug)) {
                    // Generate slug from name if missing
                    $tour->slug = $this->generateUniqueSlug($tour->name, $tour->id);
                    $tour->save();
                }

                Slug::query()->create([
                    'key' => $tour->slug,
                    'reference_type' => Tour::class,
                    'reference_id' => $tour->id,
                    'prefix' => 'tours',
                ]);

                $this->info(sprintf('Created slug for tour "%s" (ID: %d): %s', 
                    $tour->name, $tour->id, $tour->slug));
                $created++;
            }
        }

        $this->info('');
        $this->info('Backfill complete!');
        $this->info(sprintf('Created: %d', $created));
        $this->info(sprintf('Updated: %d', $updated));
        $this->info(sprintf('Skipped: %d', $skipped));
        $this->info(sprintf('Total: %d', $tours->count()));

        return self::SUCCESS;
    }

    protected function generateUniqueSlug(string $name, int|string $ignoreId = null): string
    {
        $slug = Str::slug($name);
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
}
