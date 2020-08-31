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
        $response = $this->post('/books', [
            'title' => 'Very Cool Book',
            'author' => 'malis42'
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
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
        $this->post('/books', [
            'title' => 'Very Cool Book',
            'author' => 'malis42'
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(), [
              'title' => 'New Title',
              'author' => 'New Author'
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);

        // Grab the fresh record
        $response->assertRedirect($book->fresh()->path());
    }


    /** @test */
    public function aBookCanBeDeleted()
    {

        $this->post('/books', [
            'title' => 'Very Cool Book',
            'author' => 'malis42'
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $response->assertRedirect('/books');
        $this->assertCount(0, Book::all());
    }
}
