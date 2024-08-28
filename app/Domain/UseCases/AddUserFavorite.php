<?php
namespace App\Domain\UseCases;

use App\Domain\Entities\UserFavorite;
use App\Infrastructure\Adapter\Out\Clients\GiphyClient;
use App\Infrastructure\Adapter\Out\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AddUserFavorite
{
    private UserRepository $repository;
    private GiphyClient $giphyClient;

    public function __construct(UserRepository $repository, GiphyClient $giphyClient)
    {
        $this->repository = $repository;
        $this->giphyClient = $giphyClient;
    }

    public function execute(string $gifID, int $userID, string $alias): UserFavorite|string
    {
        $gif = $this->giphyClient->findByID($gifID);

        if (!$gif) {
            throw new BadRequestException("Gif not found for ID: ". $gifID);
        }

        return $this->repository->addFavorite($gifID, $userID, $alias);
    }
}
