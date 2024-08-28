<?php
namespace App\Domain\UseCases;

use App\Infrastructure\Adapter\Out\Clients\GiphyClient;

class GiphySearch
{
    private GiphyClient $client;

    public function __construct(GiphyClient $client)
    {
        $this->client = $client;
    }

    public function execute(string $search, int $limit=1, $offset=0)
    {
        try {
            $gifs = $this->client->search($search, $limit, $offset);
        } catch (\Exception $ex) {
            return $ex->getTraceAsString();
        }

        return $gifs;
    }
}
