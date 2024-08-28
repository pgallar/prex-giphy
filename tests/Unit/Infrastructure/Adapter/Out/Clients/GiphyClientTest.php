<?php

namespace Tests\Unit\Infrastructure\Adapter\Out\Clients;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Domain\Entities\Gif;
use App\Domain\Entities\Gifs;
use App\Domain\Entities\Pagination;
use App\Infrastructure\Adapter\Out\Clients\GiphyClient;

class GiphyClientTest extends TestCase
{
    public function testSearchReturnsGifs()
    {
        $client = new GiphyClient();
        $result = $client->search('cat', 1, 0);

        $this->assertInstanceOf(Gifs::class, $result);
        $this->assertNotCount(0, $result->gifs);
        $this->assertInstanceOf(Gif::class, $result->gifs[0]);
        $this->assertTrue($result->pagination->total_count > 0);
        $this->assertEquals(0, $result->pagination->offset);
    }

    public function testSearchReturnsEmptyGifsWhenNoResults()
    {
        $client = new GiphyClient();
        $result = $client->search('5asda22dasddAXGGAsaXTSFDFSlleAKzaNoUwp79Ob', 0, 0);

        $this->assertInstanceOf(Gifs::class, $result);
        $this->assertEmpty($result->gifs);
        $this->assertEquals(0, $result->pagination->total_count);
        $this->assertEquals(0, $result->pagination->count);
        $this->assertEquals(0, $result->pagination->offset);
    }

    public function testFindByIDReturnsGif()
    {
        $client = new GiphyClient();
        $result = $client->findByID('BBNYBoYa5VwtO');

        $this->assertInstanceOf(Gif::class, $result);
        $this->assertEquals('BBNYBoYa5VwtO', $result->id);
        $this->assertNotEmpty($result->title);
        $this->assertNotEmpty($result->url);
    }
}
