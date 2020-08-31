<?php

namespace Tests\Unit;

use App\Author;
use App\Book;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function aBookCanBeAddedToLibrary()
    {
        $response = $this->post('/books', $this->getDataArray());

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    /** @test */
    public function aTitleIsRequired()
    {
        $response = $this->post('/books', [
            'title' => '',
            'author_id' => 'malis42'
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function anAuthorIsRequired()
    {
        $response = $this->post('/books', array_merge($this->getDataArray(), ['author_id' => '']));

        $response->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function aBookCanBeUpdated()
    {
        $this->post('/books', $this->getDataArray());

        $book = Book::first();

        $response = $this->patch($book->path(), [
              'title' => 'New Title',
              'author_id' => 'New Author'
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);

        // Grab the fresh record
        $response->assertRedirect($book->fresh()->path());
    }


    /** @test */
    public function aBookCanBeDeleted()
    {
        $this->post('/books', $this->getDataArray());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $response->assertRedirect('/books');
        $this->assertCount(0, Book::all());
    }

    /** @test */
    public function aNewAuthorIsAutomaticallyAdded()
    {
        $this->post('/books', $this->getDataArray());

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    /**
     * @return array
     */
    private function getDataArray(): array
    {
        return [
            'title' => 'Very Cool Book',
            'author_id' => 'malis42'
        ];
    }
}
