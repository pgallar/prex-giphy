<?php
namespace App\Domain\UseCases;

use App\Infrastructure\Adapter\Out\Clients\GiphyClient;

class GiphyFindByID
{
    private GiphyClient $repository;

    public function __construct(GiphyClient $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $id)
    {
        try {
            $gif = $this->repository->findByID($id);
        } catch (\Exception $ex) {
            return $ex->getTraceAsString();
        }

        return $gif;
    }
}
