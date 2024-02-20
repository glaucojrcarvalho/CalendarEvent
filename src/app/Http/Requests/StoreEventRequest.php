<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string',
            'startDate' => 'required|date_format:Y-m-d\TH:i:sP',
            'endDate' => 'required|date_format:Y-m-d\TH:i:sP|after_or_equal:startDate'
            ];

        if($this->description){
            $rules['description'] = 'string';
        }

        if ($this->recurringPattern) {
            $rules['recurringPattern'] = 'required';
            $rules['frequency'] = 'in:daily,weekly,monthly,yearly';
            $rules['repeatUntil'] = 'required|date_format:Y-m-d\TH:i:sP|after_or_equal:endDate';
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'title.string' => 'Title must be a string',
            'startDate.required' => 'Start date is required',
            'startDate.date_format' => 'Start date must be in the format ISO 8601 (Y-m-d\TH:i:sP)',
            'endDate.required' => 'End date is required',
            'endDate.date_format' => 'End date must be in the format ISO 8601 (Y-m-d\TH:i:sP)',
            'endDate.after' => 'End date must be after start date',
            'description.string' => 'Description must be a string',
            'frequency.in' => 'Frequency must be daily, weekly, monthly or yearly',
            'repeatUntil.required' => 'Repeat until is required',
            'repeatUntil.date_format' => 'Repeat until must be in the format ISO 8601 (Y-m-d\TH:i:sP)',
            'repeatUntil.after' => 'Repeat until must be after end date'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = response()->json(['errors' => $validator->errors()], 422);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
