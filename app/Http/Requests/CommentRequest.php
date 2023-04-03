<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ChildComment;
use Illuminate\Support\Facades\Log;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->parent != null) {
            $parentComment = Comment::find($this->parent);

            if($parentComment->video_id != $this->videoId) {
                Log::error("Tried to reply to comment of a different video.");
                
                return false;
            }
        }

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
            'videoId' => ['required', 'integer', 'numeric'],
            'body' => ['required', 'string'],
            'parent' => ['nullable', 'integer', 'numeric', new ChildComment]
        ];
    }
}
