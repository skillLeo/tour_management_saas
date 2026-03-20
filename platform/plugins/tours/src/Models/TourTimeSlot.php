<?php

namespace Botble\Tours\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourTimeSlot extends BaseModel
{
    protected $table = 'tour_time_slots';

    protected $fillable = [
        'tour_id',
        'start_time',
        'order',
        'status',
        'restricted_days', // Days of the week when this slot is not available
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'max_capacity' => 'integer',
        'booked_capacity' => 'integer',
        'price' => 'decimal:2',
        'order' => 'integer',
        'restricted_days' => 'array',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Check if the time slot is available on a specific date
     * 
     * @param \Carbon\Carbon|string $date
     * @return bool
     */
    public function isAvailableOnDate($date): bool
    {
        $carbonDate = \Carbon\Carbon::parse($date);
        $dayOfWeek = strtolower($carbonDate->englishDayOfWeek);

        // If no restricted days are set, the slot is always available
        if (empty($this->restricted_days)) {
            return true;
        }

        // The slot is available UNLESS the current day is in the restricted days list
        return !in_array($dayOfWeek, $this->restricted_days);
    }

    /**
     * Get the list of available days for this time slot
     * 
     * @return array
     */
    public function getAvailableDays(): array
    {
        $allDays = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        
        // If no restricted days are set, all days are available
        if (empty($this->restricted_days)) {
            return $allDays;
        }

        // Return days that are not in the restricted days list
        return array_values(array_diff($allDays, $this->restricted_days));
    }

    /**
     * Get a human-readable list of available days
     * 
     * @return string
     */
    public function getAvailableDaysLabel(): string
    {
        $availableDays = $this->getAvailableDays();
        
        // Capitalize first letter of each day
        $availableDaysLabels = array_map(function($day) {
            return ucfirst($day);
        }, $availableDays);

        return implode(', ', $availableDaysLabels);
    }

    /**
     * Get the end time based on tour's duration
     * 
     * @return \Carbon\Carbon
     */
    public function getEndTimeAttribute(): \Carbon\Carbon
    {
        $tour = $this->tour;
        $durationMinutes = 0;
        
        // Prioritize duration in minutes
        if ($tour->duration_hours) {
            $durationMinutes += $tour->duration_hours * 60;
        }
        
        if ($tour->duration_days) {
            $durationMinutes += $tour->duration_days * 24 * 60;
        }
        
        if ($tour->duration_nights) {
            $durationMinutes += $tour->duration_nights * 24 * 60;
        }

        return $this->start_time->copy()->addMinutes($durationMinutes);
    }

    /**
     * Get the formatted time range
     * 
     * @return string
     */
    public function getFormattedTimeRangeAttribute(): string
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    /**
     * Get duration in a human-readable format
     * 
     * @return string
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} hr {$minutes} min";
        } elseif ($hours > 0) {
            return "{$hours} hr";
        } else {
            return "{$minutes} min";
        }
    }

    /**
     * Set restricted days for the time slot
     * 
     * @param array $days Array of day names (lowercase, e.g., ['monday', 'wednesday'])
     * @return self
     */
    public function setRestrictedDays(array $days): self
    {
        $validDays = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $this->restricted_days = array_intersect(array_map('strtolower', $days), $validDays);
        return $this;
    }
}