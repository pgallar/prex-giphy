<?php

namespace App\Domain\Entities;

class Pagination
{
    public int $total_count;
    public int $limit;
    public int $offset;

    public function __construct(int $total_count, int $limit, int $offset)
    {
        $this->total_count = $total_count;
        $this->limit = $limit;
        $this->offset = $offset;
    }
}
