<?php

namespace App\Rules;

use App\Utils\AppConstant;
use Illuminate\Contracts\Validation\Rule;

class ValidOSType implements Rule
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
	    return in_array($value, AppConstant::OS_TYPE);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid OS Type given.';
    }
}
