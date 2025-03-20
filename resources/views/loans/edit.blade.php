@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Loan</h2>
    <form action="{{ route('loans.update', $loan->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div class="mb-3">
            <label>Return Date</label>
            <input type="datetime-local" name="returned_at" class="form-control" value="{{ $loan->returned_at }}">
        </div>

        <div class="mb-3">
            <label>Note</label>
            <textarea name="note" class="form-control">{{ $loan->note }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Loan</button>
    </form>
</div>
@endsection
