<?php

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can fetch books with author and comments count', function () {
    $this->getJson(route('books.index'))->assertStatus(200)->assertJsonStructure([
        'data'
    ]);
});

it('can create book', function () {
    $response = $this->actingAs($this->user, "api")
    ->postJson(route('books.store'), [
        "name" => "New book.",
        "author" => "Babalola Macaulay",
        "release_date" => "2022-10-03"
    ]);

    $response->assertStatus(201)->assertJsonStructure([
        'data' => [
            'uuid'
        ]
    ]);
});

it('returns 401 for unauthenticated book create', function () {
    $response = $this->postJson(route('books.store'), [
        "name" => "New book.",
        "author" => "Babalola Macaulay",
        "release_date" => "2022-10-03"
    ]);

    $response->assertStatus(401);
});

it('can add comment to book', function () {
    $response = $this->actingAs($this->user, "api")
    ->postJson(route('books.store'), [
        "name" => "New book.",
        "author" => "Babalola Macaulay",
        "release_date" => "2022-10-03"
    ]);

    $book = json_decode($response->decodeResponseJson()->json)->data;

    $response = $this->postJson(route('books.comment', ['book' => $book->uuid]), [
        "comment" => "New book."
    ])->assertStatus(201);
});

it('returns 422 when adding comment to book with incomplete data', function () {
    $response = $this->actingAs($this->user, "api")
    ->postJson(route('books.store'), [
        "name" => "New book.",
        "author" => "Babalola Macaulay",
        "release_date" => "2022-10-03"
    ]);

    $book = json_decode($response->decodeResponseJson()->json)->data;

    $response = $this->postJson(route('books.comment', ['book' => $book->uuid]), [
        "comment" => ""
    ])->assertStatus(422);
});

it('returns 422 when adding comment with more than 500 characters', function () {
    $response = $this->actingAs($this->user, "api")
    ->postJson(route('books.store'), [
        "name" => "New book.",
        "author" => "Babalola Macaulay",
        "release_date" => "2022-10-03"
    ]);

    $book = json_decode($response->decodeResponseJson()->json)->data;

    $response = $this->postJson(route('books.comment', ['book' => $book->uuid]), [
        "comment" => Str::random(501)
    ])->assertStatus(422);
});

it('can fetch book', function () {
    $response = $this->actingAs($this->user, "api")
    ->postJson(route('books.store'), [
        "name" => "New book.",
        "author" => "Babalola Macaulay",
        "release_date" => "2022-10-03"
    ]);

    $book = json_decode($response->decodeResponseJson()->json)->data;

    $response = $this->getJson(route('books.show', ['book' => $book->uuid]))->assertStatus(200)->assertJsonStructure([
        'data' => [
            'uuid',
            'author',
            'release_date',
            'comments_count'
        ]
    ]);
});

it('can fetch book comments', function () {
    $response = $this->actingAs($this->user, "api")
    ->postJson(route('books.store'), [
        "name" => "New book.",
        "author" => "Babalola Macaulay",
        "release_date" => "2022-10-03"
    ]);

    $book = json_decode($response->decodeResponseJson()->json)->data;

    $response = $this->getJson(route('books.comments', ['book' => $book->uuid]))->assertStatus(200)->assertJsonStructure([
        'data' => [
            '*' => [
                'uuid',
                'content',
                'ip',
                'created_at',
            ]
        ]
    ]);
});

test('books are sorted in order of release date, from earliest to latest', function () {
    $this->actingAs($this->user, "api")
    ->postJson(route('books.store'), [
        "name" => "Middle book book.",
        "author" => "Babalola Macaulay",
        "release_date" => now()->subtract(2, 'days')
    ]);
    $this->actingAs($this->user, "api")
    ->postJson(route('books.store'), [
        "name" => "Oldest book.",
        "author" => "Babalola Macaulay",
        "release_date" => now()->subtract(5, 'days')
    ]);
    $this->actingAs($this->user, "api")
    ->postJson(route('books.store'), [
        "name" => "Latest book.",
        "author" => "Babalola Macaulay",
        "release_date" => now()
    ]);

    $fetchBooksResponse = $this->getJson(route('books.index'))->assertStatus(200);

    $books = json_decode($fetchBooksResponse->decodeResponseJson()->json)->data;

    if (count($books) >= 3) {
        $this->assertTrue($books[0]->release_date <= $books[1]->release_date);
    }

});
