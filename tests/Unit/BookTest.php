<?php

namespace Tests\Unit;

use App\Author;
use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anAuthorIdIsRecorded()
    {
          Book::create([
              'title' => 'Cool Book Title',
              'author_id' => 1,
          ]);

          $this->assertCount(1, Book::all());
    }
}
