<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $availableBooks = Book::whereDoesntHave('loans', function ($query) {
            $query->whereNull('returned_at');
        })->get();

        $borrowedBooks = Book::whereHas('loans', function ($query) use ($user) {
            $query->where('member_id', $user->id)->whereNull('returned_at');
        })->get();

        $books = $availableBooks->merge($borrowedBooks);

        return view('loans.index', compact('books'));
    }

    public function create()
    {
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

        Loan::create($request->all());

        return redirect()->route('loans.index')->with('success', 'Loan created successfully.');
    }

    public function edit(Loan $loan)
    {
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

        $loan->update($request->all());

        return redirect()->route('loans.index')->with('success', 'Loan updated.');
    }
    
    public function borrow($bookId)
    {
        $user = Auth::user();
    
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }
    
        if (Loan::where('member_id', $user->id)->where('book_id', $bookId)->whereNull('returned_at')->exists()) {
            return back()->with('error', 'You already borrowed this book.');
        }
    
        Loan::create([
            'member_id' => $user->id,
            'book_id' => $bookId,
            'loan_at' => now(),
        ]);
    
        return redirect()->route('loans.index')->with('success', 'Book borrowed successfully!');
    }
    

    public function return($bookId)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $loan = Loan::where('member_id', $user->id)
                    ->where('book_id', $bookId)
                    ->whereNull('returned_at')
                    ->first();

        if (!$loan) {
            return back()->with('error', 'You have not borrowed this book.');
        }

        $loan->update([
            'returned_at' => now(),
        ]);

        return redirect()->route('loans.index')->with('success', 'Buku telah dikembalikan.');
    }

    public function borrowedBooks()
    {
        $borrowedBooks = Loan::whereNull('returned_at')
            ->with(['book', 'member'])
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

        $loan = Loan::where('book_id', $bookId)
                    ->where('member_id', $user->id)
                    ->whereNull('returned_at')
                    ->first();

        if (!$loan) {
            return back()->with('error', 'Anda belum meminjam buku ini.');
        }

        $loan->update(['note' => $request->note]);

        return back()->with('success', 'Komentar telah disimpan!');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Loan deleted.');
    }
}

