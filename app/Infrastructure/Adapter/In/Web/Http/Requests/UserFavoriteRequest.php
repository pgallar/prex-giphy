<?php

namespace App\Infrastructure\Adapter\In\Web\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFavoriteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gif_id' => 'required|string|max:50',
            'alias' => 'required|string|max:100',
        ];
    }

    public function getGifID(): string {
        return $this->gif_id;
    }

    public function getAlias(): string {
        return $this->alias;
    }
}
