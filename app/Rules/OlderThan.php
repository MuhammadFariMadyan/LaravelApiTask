<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class OlderThan implements Rule
{
	public $parameter,$minAge;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($parameter)
    {
	    $this->parameter = $parameter;
	    $this->minAge = (!empty($this->parameter)) ? (int)$this->parameter : 13;
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
	    try {
		    $bool = Carbon::now()->diff(new Carbon($value))->y >= $this->minAge;
	    } catch (\Exception $e) {
		    return false;
	    }
	    return $bool;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Your age must be '.$this->minAge.' years or above.';
    }
}
