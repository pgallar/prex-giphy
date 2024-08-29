<?php

namespace App\Domain\Entities;

class Gifs
{
    public array $gifs;
    public Pagination $pagination;

    /**
     * @param array $gifs
     * @param Pagination $pagination
     */
    public function __construct(array $gifs, Pagination $pagination)
    {
        $this->gifs = $gifs;
        $this->pagination = $pagination;
    }
}
