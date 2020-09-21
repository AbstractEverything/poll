<?php

namespace AbstractEverything\Poll\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePollRequest extends FormRequest
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
            'title' => 'required|max:140',
            'description' => 'required',
            'options.*' => 'required|max:140',
            'multichoice' => 'boolean',
            'ends_at' => 'nullable|date',
        ];
    }

    /**
     * Custom validation messages
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'options.*.required'  => 'Option is required',
            'options.*.max'  => 'Option exceeded maximum length',
        ];
    }
}
