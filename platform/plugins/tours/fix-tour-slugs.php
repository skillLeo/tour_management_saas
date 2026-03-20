<?php

/**
 * Fix Tour Slugs Script
 * This script generates slugs for tours that don't have them
 * 
 * Usage: php fix-tour-slugs.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Botble\Tours\Models\Tour;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

echo "Starting to fix tour slugs...\n\n";

// Get all tours
$tours = Tour::all();

$fixed = 0;
$skipped = 0;

foreach ($tours as $tour) {
    echo "Processing Tour #{$tour->id}: {$tour->name}\n";
    
    // Check if tour already has a slug in tours table
    if (!empty($tour->slug)) {
        echo "  ✓ Already has slug in tours table: {$tour->slug}\n";
        
        // Check if slug exists in slugs table
        $slugExists = Slug::where('reference_type', Tour::class)
            ->where('reference_id', $tour->id)
            ->exists();
        
        if (!$slugExists) {
            echo "  → Creating slug entry in slugs table...\n";
            Slug::create([
                'key' => $tour->slug,
                'reference_type' => Tour::class,
                'reference_id' => $tour->id,
                'prefix' => 'tours',
            ]);
            echo "  ✓ Slug entry created\n";
            $fixed++;
        } else {
            echo "  ✓ Slug entry already exists\n";
            $skipped++;
        }
        continue;
    }
    
    // Check if tour has slug in slugs table only
    $slugModel = Slug::where('reference_type', Tour::class)
        ->where('reference_id', $tour->id)
        ->first();
    
    if ($slugModel) {
        echo "  ✓ Found slug in slugs table: {$slugModel->key}\n";
        echo "  → Updating tours table...\n";
        $tour->slug = $slugModel->key;
        $tour->save();
        echo "  ✓ Tours table updated\n";
        $fixed++;
        continue;
    }
    
    // Generate new slug
    echo "  → No slug found, generating new one...\n";
    $baseSlug = Str::slug($tour->name);
    $slug = $baseSlug;
    $counter = 1;
    
    // Make sure slug is unique in tours table
    while (Tour::where('slug', $slug)->where('id', '!=', $tour->id)->exists()) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }
    
    // Make sure slug is unique in slugs table
    while (Slug::where('key', $slug)->where('prefix', 'tours')->exists()) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }
    
    echo "  → Generated slug: {$slug}\n";
    
    // Update tour
    $tour->slug = $slug;
    $tour->save();
    
    // Create slug entry
    Slug::create([
        'key' => $slug,
        'reference_type' => Tour::class,
        'reference_id' => $tour->id,
        'prefix' => 'tours',
    ]);
    
    echo "  ✓ Slug created and saved\n";
    $fixed++;
}

echo "\n";
echo "========================================\n";
echo "Summary:\n";
echo "  Total tours processed: " . $tours->count() . "\n";
echo "  Tours fixed: {$fixed}\n";
echo "  Tours skipped: {$skipped}\n";
echo "========================================\n";
echo "\nDone! ✓\n";
