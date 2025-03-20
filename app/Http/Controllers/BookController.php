<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Categories;
use App\Models\Loan;
use Illuminate\Http\Request;
use App\Models\User;

class BookController extends Controller
{
    public function index()
    {
        $categories = Categories::all();
        $books = Book::with('categories', 'loans')->get();

        return view('crud.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Categories::all();
        return view('crud.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'book_title' => 'required|string',
            'book_description' => 'required|string',
            'book_authors' => 'required|string',
            'book_isbn' => 'required|string|unique:books,isbn',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Create a new book
        $book = Book::create([
            'title' => $request->book_title,
            'description' => $request->book_description,
            'authors' => $request->book_authors,
            'isbn' => $request->book_isbn,
        ]);

        // Assign category to the book
        $book->categories()->sync([$request->category_id]);

        return redirect()->route('crud.index')->with('success', 'Book created successfully.');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Categories::all();
        return view('crud.edit', compact('book', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'book_title' => 'required|string|max:255',
            'book_description' => 'nullable|string',
            'book_authors' => 'required|string|max:255',
            'book_isbn' => 'required|string|max:255|unique:books,isbn,' . $id,
            'category_id' => 'required|exists:categories,id'
        ]);

        // Retrieve the book
        $book = Book::findOrFail($id);

        // Update book details
        $book->update([
            'title' => $request->book_title,
            'description' => $request->book_description,
            'authors' => $request->book_authors,
            'isbn' => $request->book_isbn,
        ]);

        // Update category association
        $book->categories()->sync([$request->category_id]);

        return redirect()->route('crud.index')->with('success', 'Book updated successfully.');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        
        // Delete loans associated with the book
        $book->loans()->delete();
        
        // Delete the book
        $book->delete();

        return redirect()->route('crud.index')->with('success', 'Book deleted successfully.');
    }

    /**
     * Handle book loaning process
     */
    public function loan(Request $request, $id)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'librarian_id' => 'required|exists:users,id'
        ]);

        $book = Book::findOrFail($id);

        // Check if the book is already on loan
        if ($book->loans()->whereNull('returned_at')->exists()) {
            return redirect()->back()->with('error', 'This book is already on loan.');
        }

        // Create a new loan record
        Loan::create([
            'book_id' => $book->id,
            'member_id' => $request->member_id,
            'librarian_id' => $request->librarian_id,
            'loan_at' => now(),
        ]);

        return redirect()->route('crud.index')->with('success', 'Book loaned successfully.');
    }

    /**
     * Handle book return process
     */
    public function returnBook($id)
    {
        $loan = Loan::where('book_id', $id)->whereNull('returned_at')->first();

        if (!$loan) {
            return redirect()->back()->with('error', 'This book is not currently loaned.');
        }

        // Mark the book as returned
        $loan->update(['returned_at' => now()]);

        return redirect()->route('crud.index')->with('success', 'Book returned successfully.');
    }
}
