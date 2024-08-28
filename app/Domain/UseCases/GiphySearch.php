<?php
namespace App\Domain\UseCases;

use App\Infrastructure\Adapter\Out\Clients\GiphyClient;

class GiphySearch
{
    private GiphyClient $repository;

    public function __construct(GiphyClient $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $search, int $limit=1, $offset=0)
    {
        try {
            $gifs = $this->repository->search($search, $limit, $offset);
        } catch (\Exception $ex) {
            return $ex->getTraceAsString();
        }

        return $gifs;
    }
}
