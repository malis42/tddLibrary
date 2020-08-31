<?php

namespace Tests\Feature;

use App\Author;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anAuthorCanBeCreated()
    {
        $this->withoutExceptionHandling();
          $this->post('/author', [
              'name' => 'Author name',
              'dob' => '04/20/1992'
          ]);

        $author = Author::all();

          $this->assertCount(1, $author);
          $this->assertInstanceOf(Carbon::class, $author->first()->dob);
          $this->assertEquals('1992/20/04', $author->first()->dob->format('Y/d/m'));
    }
}
