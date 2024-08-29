<?php
namespace App\Domain\UseCases;

use App\Domain\Entities\UserFavorite;
use App\Infrastructure\Adapter\Out\Clients\GiphyClient;
use App\Infrastructure\Adapter\Out\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class AddUserFavorite
{
    private UserRepository $repository;
    private GiphyClient $giphyClient;

    /**
     * @param UserRepository $repository
     * @param GiphyClient $giphyClient
     */
    public function __construct(UserRepository $repository, GiphyClient $giphyClient)
    {
        $this->repository = $repository;
        $this->giphyClient = $giphyClient;
    }

    /**
     * Adiciona el favorito al usuario autenticado.
     * Si el gif no es encontrado por gifID,
     * entonces, dispara una excepción BadRequestException
     *
     * @param string $gifID
     * @param int $userID
     * @param string $alias
     * @return UserFavorite|string
     */
    public function execute(string $gifID, int $userID, string $alias): UserFavorite|string
    {
        $gif = $this->giphyClient->findByID($gifID);

        if (!$gif) {
            throw new BadRequestException("Gif not found for ID: ". $gifID);
        }

        return $this->repository->addFavorite($gifID, $userID, $alias);
    }
}
