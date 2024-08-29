<?php
namespace App\Domain\UseCases;

use App\Infrastructure\Adapter\Out\Clients\GiphyClient;

class GiphyFindByID
{
    private GiphyClient $repository;

    /**
     * @param GiphyClient $repository
     */
    public function __construct(GiphyClient $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retorna los datos del gif en base al id enviado
     *
     * @param string $id
     * @return \App\Domain\Entities\Gif|string|null
     */
    public function execute(string $id): \App\Domain\Entities\Gif|string|null
    {
        try {
            $gif = $this->repository->findByID($id);
        } catch (\Exception $ex) {
            return $ex->getTraceAsString();
        }

        return $gif;
    }
}
