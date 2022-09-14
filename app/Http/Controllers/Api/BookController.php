<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\CommentResource;
use App\Models\Book;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth')->only([
            'store'
        ]);
    }

    public function index()
    {
        $books = Book::orderBy('release_date')->paginate(10);

        return BookResource::collection($books)->additional([
            'message' => 'Books fetched successfully.',
            'errors' => false
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'bail|required|string',
                'author' => 'bail|required|string',
                'release_date' => 'bail|required|date',
            ]);

            $book = new Book();
            $book->name = $request->name;
            $book->author = $request->author;
            $book->release_date = $request->release_date;
            $book->save();

            return (new BookResource($book))->additional([
                'message' => 'Book added successfully.',
                'errors' => false
            ])->response()->setStatusCode(201);

        } catch (ValidationException $th) {
            return response()->json([
                'message' => $th->validator->errors()->first(),
                'errors' => true
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'errors' => true
            ], 500);
        }
    }

    public function comment(Request $request, Book $book)
    {
        try {
            $request->validate([
                'comment' => 'bail|required|string|max:500'
            ]);

            $comment = new Comment();
            $comment->content = $request->comment;
            $comment->book_id = $book->id;
            $comment->ip = $request->ip();
            $comment->save();

            return (new BookResource($book->fresh()))->additional([
                'message' => 'Comment added successfully.',
                'errors' => false
            ])->response()->setStatusCode(201);

        } catch (ValidationException $th) {
            return response()->json([
                'message' => $th->validator->errors()->first(),
                'errors' => true
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'errors' => true
            ], 500);
        }
    }

    public function comments(Book $book)
    {
        try {
            return CommentResource::collection($book->comments)->additional([
                'message' => 'Comments fetched successfully.',
                'errors' => false
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'errors' => true
            ], 500);
        }
    }

    public function show(Book $book)
    {
        return (new BookResource($book))->additional([
            'message' => 'Book fetched successfully.',
            'errors' => false
        ]);
    }
}
