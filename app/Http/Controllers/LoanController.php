<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // 🔥 Tambahkan ini

class LoanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        // Books that are currently available (not borrowed by anyone)
        $availableBooks = Book::whereDoesntHave('loans', function ($query) {
            $query->whereNull('returned_at');
        })->get();

        // Books that the logged-in user has borrowed
        $borrowedBooks = Book::whereHas('loans', function ($query) use ($user) {
            $query->where('member_id', $user->id)->whereNull('returned_at');
        })->get();

        // Merge both collections into one
        $books = $availableBooks->merge($borrowedBooks);

        return view('loans.index', compact('books'));
    }

    public function create()
    {
        // Load all books, members, and librarians
        $books = Book::all();
        $members = User::where('role', 'member')->get();
        $librarians = User::where('role', 'librarian')->get();
        return view('loans.create', compact('books', 'members', 'librarians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:users,id',
            'librarian_id' => 'required|exists:users,id',
            'loan_at' => 'required|date',
            'note' => 'nullable|string',
        ]);

        // Create a new loan and associate it with a book, member, and librarian
        Loan::create($request->all());

        return redirect()->route('loans.index')->with('success', 'Loan created successfully.');
    }

    public function edit(Loan $loan)
    {
        // Fetch books, members, and librarians for the edit form
        $books = Book::all();
        $members = User::where('role', 'member')->get();
        $librarians = User::where('role', 'librarian')->get();
        return view('loans.edit', compact('loan', 'books', 'members', 'librarians'));
    }

    public function update(Request $request, Loan $loan)
    {
        $request->validate([
            'returned_at' => 'nullable|date',
            'note' => 'nullable|string',
        ]);

        // Update the loan details
        $loan->update($request->all());

        return redirect()->route('loans.index')->with('success', 'Loan updated.');
    }
    
    public function borrow($bookId)
    {
        $user = Auth::user(); // ✅ Get the logged-in user
    
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }
    
        // Check if the book is already borrowed by this user and not yet returned
        if (Loan::where('member_id', $user->id)->where('book_id', $bookId)->whereNull('returned_at')->exists()) {
            return back()->with('error', 'You already borrowed this book.');
        }
    
        // Create a new loan (without librarian_id)
        Loan::create([
            'member_id' => $user->id,  // Automatically assign the logged-in user
            'book_id' => $bookId,
            'loan_at' => now(),
        ]);
    
        return redirect()->route('loans.index')->with('success', 'Book borrowed successfully!');
    }
    

    public function return($bookId)
    {
        $user = Auth::user(); // ✅ Get the logged-in user

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        // Find the active loan for this book and user
        $loan = Loan::where('member_id', $user->id)
                    ->where('book_id', $bookId)
                    ->whereNull('returned_at')
                    ->first();

        if (!$loan) {
            return back()->with('error', 'You have not borrowed this book.');
        }

        // Update the loan record to mark it as returned
        $loan->update([
            'returned_at' => now(),
        ]);

        return redirect()->route('loans.index')->with('success', 'Buku telah dikembalikan.');
    }

    public function borrowedBooks()
    {
        $borrowedBooks = Loan::whereNull('returned_at') // Only books that are still on loan
            ->with(['book', 'member']) // Load book and member details
            ->get();

        return view('loans.borrowed', compact('borrowedBooks'));
    }

    public function addComment(Request $request, $bookId)
    {
        $request->validate([
            'note' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Find the user's loan for this book
        $loan = Loan::where('book_id', $bookId)
                    ->where('member_id', $user->id)
                    ->whereNull('returned_at')
                    ->first();

        if (!$loan) {
            return back()->with('error', 'Anda belum meminjam buku ini.');
        }

        // Update the note
        $loan->update(['note' => $request->note]);

        return back()->with('success', 'Komentar telah disimpan!');
    }

    public function destroy(Loan $loan)
    {
        // Delete the loan
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Loan deleted.');
    }
}

