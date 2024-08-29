<?php
namespace App\Domain\UseCases;

use App\Infrastructure\Adapter\Out\Clients\GiphyClient;

class GiphySearch
{
    private GiphyClient $client;

    /**
     * @param GiphyClient $client
     */
    public function __construct(GiphyClient $client)
    {
        $this->client = $client;
    }

    /**
     * Realiza una búsqueda de gifs en base a query, limit y offset
     *
     * @param string $query
     * @param int $limit
     * @param int $offset
     * @return \App\Domain\Entities\Gifs|string
     */
    public function execute(string $query, int $limit=1, int $offset=0): string|\App\Domain\Entities\Gifs
    {
        try {
            $gifs = $this->client->search($query, $limit, $offset);
        } catch (\Exception $ex) {
            return $ex->getTraceAsString();
        }

        return $gifs;
    }
}
