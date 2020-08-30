<?php

namespace Tests\Unit;

use App\Book;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function aBookCanBeAddedToLibrary()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => 'Very Cool Book',
            'author' => 'malis42'
        ]);

        $response->assertOk();
        $this->assertCount(1, Book::all());
    }

    /** @test */
    public function aTitleIsRequired()
    {
        $response = $this->post('/books', [
            'title' => '',
            'author' => 'malis42'
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function anAuthorIsRequired()
    {
        $response = $this->post('/books', [
            'title' => 'Very Cool Book',
            'author' => ''
        ]);

        $response->assertSessionHasErrors('author');
    }

    /** @test */
    public function aBookCanBeUpdated()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Very Cool Book',
            'author' => 'malis42'
        ]);

        $book = Book::first();

        $response = $this->patch('/books/' . $book->id, [
              'title' => 'New Title',
              'author' => 'New Author'
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);
    }
}
