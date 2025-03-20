@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Loan</h2>
    <form action="{{ route('loans.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Book</label>
            <select name="book_id" class="form-control">
                @foreach ($books as $book)
                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Member</label>
            <select name="member_id" class="form-control">
                @foreach ($members as $member)
                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Librarian</label>
            <select name="librarian_id" class="form-control">
                @foreach ($librarians as $librarian)
                    <option value="{{ $librarian->id }}">{{ $librarian->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Loan Date</label>
            <input type="datetime-local" name="loan_at" class="form-control">
        </div>

        <div class="mb-3">
            <label>Note</label>
            <textarea name="note" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Create Loan</button>
    </form>
</div>
@endsection
