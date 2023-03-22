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
        Log::debug('trying to authorize');

        $user = $this->user();

        if($user == null) {
            Log::debug('user is null');
            return false;
        }

        Log::debug("$user");

        $playlistId = $this->playlistId;

        if($playlistId != null) {
            $result = Playlist::where([
                ['id', '=', $playlistId],
                ['user_id', '=', $user['id']]
            ])->get()->isEmpty();

            Log::debug("$result");

            if(!$result)
            {
                Log::debug("passed playlist video authorization");
                return true;
            } else {
                Log::debug('playlist does not belong to user');
            }
        } else {
            Log::debug('playlist id is null');
        }

        return false; 
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
            'videoId' => ['required', 'numeric', 'integer']
        ];
    }
}
