<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Playlist;
use Illuminate\Support\Facades\Log;

class PlaylistVideoRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'playlistId' => ['required', 'numeric', 'integer'],
            'videoId' => ['required', 'numeric', 'integer'],
        ];
    }
}
