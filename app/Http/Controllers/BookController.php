<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Private Helper: Read from storage/app/books.json
    private function getBooks()
    {
        $path = storage_path('app/books.json');
        return json_decode(file_get_contents($path), true);
    }

    // Private Helper: Write back to storage/app/books.json
    private function saveBooks(array $books)
    {
        file_put_contents(storage_path('app/books.json'), json_encode($books, JSON_PRETTY_PRINT));
    }

    // GET /books
    public function index(Request $request)
    {
        $books = $this->getBooks();
        $genre = $request->query('genre');

        if ($genre) {
            $books = array_filter($books, fn($book) => strtolower($book['genre']) === strtolower($genre));
        }

        return view('books.catalog', compact('books', 'genre'));
    }

    // GET /books/create
    public function create()
    {
        return view('books.create');
    }

    // POST /books
    public function store(Request $request)
    {
        // Part C: Validation Rules
        $validated = $request->validate([
            'title'  => 'required|min:3|max:255',
            'author' => 'required|min:3|max:255',
            'genre'  => 'required|in:Fiction,Dystopian,Sci-Fi,Non-Fiction,Mystery',
            'price'  => 'required|numeric|min:0.99',
            'year'   => 'required|numeric|min:1800|max:2026',
        ]);

        $books = $this->getBooks();

        // Generate new ID
        $newId = count($books) > 0 ? max(array_column($books, 'id')) + 1 : 1;

        $newBook = [
            'id'     => $newId,
            'title'  => $validated['title'],
            'author' => $validated['author'],
            'genre'  => $validated['genre'],
            'price'  => (float) $validated['price'],
            'year'   => (int) $validated['year'],
        ];

        $books[] = $newBook;
        $this->saveBooks($books);

        // Part E: Post-Redirect-Get pattern with flash message
        return redirect()->route('books.index')->with('success', 'Book added successfully!');
    }

    // GET /books/{id}
    public function show($id)
    {
        $books = $this->getBooks();
        $book = collect($books)->firstWhere('id', (int)$id);

        if (!$book) {
            abort(404);
        }

        return view('books.details', compact('book'));
    }
}
