<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCaseFileRequest extends FormRequest
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
        return [
            'matter' => ['required', 'String'],
            'type' => ['required', 'String'],
            'file_number' => [
                'required', 
                'String', 
                Rule::unique('case_files')->ignore($this->case_file)->where(fn ($query) => $query->whereIn('created_by', User::where('company_id', Auth::user()->company_id)->get('id') ))
            ],
            'client_id' => ['required', 'exists:clients,id'],
        ];
    }
}
