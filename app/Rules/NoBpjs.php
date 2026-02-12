<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoBpjs implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return is_string($value) && preg_match('/^\d{13}$/', $value) === 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Nomor BPJS harus berupa 13 digit angka.';
    }
}
