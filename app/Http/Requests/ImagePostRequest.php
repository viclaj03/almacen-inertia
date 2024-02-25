<?php

namespace App\Http\Requests;

use App\Rules\UniqHash;
use App\Rules\UniqDanbooruUrl;
use Illuminate\Foundation\Http\FormRequest;

class ImagePostRequest extends FormRequest
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
            'danbooru_url'=>[new UniqDanbooruUrl],
            //'image'=>['exclude_if:hash_ignore,1',new UniqHash()],
            'image'=>[new UniqHash]

        ];
    }
}
