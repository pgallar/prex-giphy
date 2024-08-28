<?php
namespace App\Domain\Contracts;

use App\Domain\Entities\Gif;
use App\Domain\Entities\Gifs;

interface GiphyClientContract {
    public function search(string $query, int $limit=1, $offset=0): Gifs;
    public function findByID(string $id): ?Gif;
}
