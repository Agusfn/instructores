<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class InstructorWorkHours implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $validHours = [9, 11, 13, 15, 17];

        $hours = explode(",", $value);

        if(sizeof($hours) != 2 && sizeof($hours) != 4)
            return false;

        foreach($hours as $hour) {
            if(!in_array($hour, $validHours)) {
                return false;
            }
        }


        for($i=0; $i<sizeof($hours); $i++) 
        {
            if($i < sizeof($hours)-1) 
            {
                if($hours[$i] >= $hours[$i+1])
                    return false;
            } 
        }

        return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The working hours are invalid.';
    }
}
