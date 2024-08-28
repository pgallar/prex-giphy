<?php

namespace App\Infrastructure\Adapter\Out\Clients;

use App\Domain\Contracts\GiphyClientContract;
use App\Domain\Entities\Gif;
use App\Domain\Entities\Gifs;
use App\Domain\Entities\Pagination;
use Illuminate\Support\Facades\Http;

final class GiphyClient implements GiphyClientContract
{
    public function search(string $query, int $limit=1, $offset=0): Gifs
    {
        $response = Http::get(env('GIPHY_URL') . '/gifs/search', [
            'api_key' => env('GIPHY_KEY'),
            'q' => $query,
            'limit' => $limit === 0 ? 1 : $limit,
            'offset' => $offset,
        ]);

        $gifSearch = new Gifs;
        $data =  $response->json();

        $gifSearch->pagination = new Pagination(
            $data['pagination']['total_count'],
            $data['pagination']['count'],
            $data['pagination']['offset'],
        );

        foreach ($data['data'] as $gif) {
            $gifSearch->gifs[] = new Gif(
                $gif['id'],
                $gif['url'],
                $gif['title'],
            );
        }

        return $gifSearch;
    }

    public function findByID(string $id): ?Gif
    {
        $response = Http::get(env('GIPHY_URL') . '/gifs', [
            'api_key' => env('GIPHY_KEY'),
            'ids' => $id
        ]);

        $data = $response->json();

        if (empty($data['data'])) {
            return null;
        }

        return new Gif(
            $data['data'][0]['id'],
            $data['data'][0]['url'],
            $data['data'][0]['title'],
        );
    }
}
