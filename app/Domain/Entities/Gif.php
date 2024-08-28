<?php

namespace App\Domain\Entities;

class Gif
{
    public string $id;
    public string $url;
    public string $title;

    public function __construct($id, $url, $title)
    {
        $this->id = $id;
        $this->url = $url;
        $this->title = $title;
    }
}
