<?php

namespace Botble\Tours\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Slug\Facades\SlugHelper;
use Botble\Tours\Models\Tour;
use Botble\Tours\Models\TourCategory;
use Botble\Tours\Models\TourCity;

class TourSlugHookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Add filters to modify slug prefix and URL generation
        add_filter(FILTER_SLUG_PREFIX, [$this, 'addPublicToSlugPrefix'], 124, 2);
        add_filter('slug_filter_url', [$this, 'filterTourUrl'], 124, 1);
    }

    /**
     * Add /public/ prefix to tour URLs
     * 
     * @param string $prefix
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return string
     */
    public function addPublicToSlugPrefix(string $prefix, $model = null): string
    {
        // Only modify prefix for Tour models (not categories or cities)
        if (!$model instanceof Tour) {
            return $prefix;
        }

        // If the prefix is 'tours', prepend 'public/'
        if ($prefix === 'tours') {
            return 'public/tours';
        }

        // If prefix already contains 'tours' but not 'public/', add it
        if (str_contains($prefix, 'tours') && !str_contains($prefix, 'public/')) {
            return str_replace('tours', 'public/tours', $prefix);
        }

        return $prefix;
    }

    /**
     * Filter the final tour URL to ensure correct format
     * This ensures URLs are generated as /public/tours/{slug} instead of /tours/{slug}
     * 
     * @param string $url
     * @return string
     */
    public function filterTourUrl(string $url): string
    {
        // Get the current request to check what model is being processed
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 10);
        
        foreach ($backtrace as $trace) {
            if (isset($trace['object']) && $trace['object'] instanceof Tour) {
                // Ensure the URL has /public/tours/ format
                // Fix any double slashes first
                $url = preg_replace('#/+#', '/', $url);
                
                // If URL contains /tours/ but not /public/tours/, fix it
                if (preg_match('#/tours/([^/]+)#', $url) && !str_contains($url, '/public/tours/')) {
                    $url = preg_replace('#/tours/#', '/public/tours/', $url);
                }
                
                // Fix double public if it exists
                $url = str_replace('/public/public/', '/public/', $url);
                
                break;
            }
        }
        
        return $url;
    }
}
