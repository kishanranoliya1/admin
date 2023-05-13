<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZodiacSignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return
        [
			'Aries' => 'required',
			'Taurus' => 'required',
			'Gemini' => 'required',
			'Cancer' => 'required',
			'Leo' => 'required',
			'Virgo' => 'required',
			'Libra' => 'required',
			'Scorpio' => 'required',
			'Sagittarius' => 'required',
			'Capricorn' => 'required',
			'Aquarius' => 'required',
			'Pisces' => 'required',
        ];
    }
}
