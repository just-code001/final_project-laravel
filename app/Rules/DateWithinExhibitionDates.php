<?php

namespace App\Rules;

use Closure;
use App\Models\Tblexihibition;
use Illuminate\Contracts\Validation\Rule;

class DateWithinExhibitionDates implements Rule
{
    protected $exhibitionId;

    public function __construct($exhibitionId)
    {
        $this->exhibitionId = $exhibitionId;
    }

    public function passes($attribute, $value)
    {
        // Get the starting and ending dates of the exhibition
        $exhibition = Tblexihibition::findOrFail($this->exhibitionId);
        $startingDate = $exhibition->event_starting_date;
        $endingDate = $exhibition->event_ending_date;

        // Check if the booking date falls within the exhibition dates
        return ($value >= $startingDate && $value <= $endingDate);
    }

    public function message()
    {
        return 'The booking date must be between the starting and ending dates of the exhibition.';
    }
}

