<?php

namespace App\Infrastructure\Adapter\In\Web\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiphySearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => 'required|string|max:255',
            'limit' => 'int|min:1',
            'offset' => 'int|min:0',
        ];
    }

    public function getQuery(): string {
        return $this['query'];
    }

    public function getLimit(): int {
        return $this->limit;
    }

    public function getOffset(): int {
        return $this->offset;
    }
}
