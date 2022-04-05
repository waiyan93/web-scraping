<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'csv' => [
                'bail',
                'required', 
                'mimes:csv,txt', 
                'max:2048',
            ],
            'keywords' => ['bail', 'array', 'min:1', 'max:100']
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'keywords' => collect(
                array_map('str_getcsv', file($this->file('csv')))
            )
            ->flatten()
            ->reject(function($keyword) {
                return $keyword === "" || $keyword === null;
            })
            ->toArray()
        ]);
    }
}
