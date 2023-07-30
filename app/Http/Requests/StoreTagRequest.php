<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreTagRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:1250',
                Rule::unique('tags', 'name')->where(function ($query) {
                    return $query->where('name', $this->name);
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'El tag :input ya existe. puedes verlo aqui <a class="text-white" href="' . route('tags.show', (DB::table('tags')->where('name', $this->name)->get()->value('id')??  0)) . '">aquí</a>.',
        ];
    }

    public function withValidator($validator)
    {
        // Validar si el tag ya existe después de la validación inicial
        $validator->after(function ($validator) {
            $tagId = DB::table('tags')->where('name', $this->name)->value('id');
            if ($tagId) {
                $this->tag_id = $tagId;
            }
        });
    }
}
