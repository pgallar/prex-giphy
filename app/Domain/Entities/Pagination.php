<?php

namespace App\Domain\Entities;

class Pagination
{
    public int $total_count;
    public int $count;
    public int $offset;

    public function __construct($total_count, $count, $offset)
    {
        $this->total_count = $total_count;
        $this->count = $count;
        $this->offset = $offset;
    }
}
