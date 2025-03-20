@extends('layouts.app')

@section('title', 'Library - Book List')

@section('page-title', 'Daftar Buku')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Book's</h1>
        <a href="{{ route('crud.create') }}" class="btn btn-secondary">Add Book</a>
    </div>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Category</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                <tr>
                    <td>{{ Str::limit($book->title, 50) }}</td>
                    <td>{{ Str::limit($book->authors, 50) }}</td>
                    <td>{{ Str::limit($book->isbn, 20) }}</td>
                    <td>{{ implode(', ', $book->categories->pluck('name')->toArray()) }}</td>
                    <td>{{ Str::limit($book->description, 100) }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal{{ $book->id }}">View</button>
                            <a href="{{ route('crud.edit', $book->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('crud.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- View Modal -->
                <div class="modal fade" id="viewModal{{ $book->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $book->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewModalLabel{{ $book->id }}">Detail Buku</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <strong>Judul:</strong> {{ $book->title }} <br>
                                <strong>Author:</strong> {{ $book->authors }} <br>
                                <strong>ISBN:</strong> {{ $book->isbn }} <br>
                                <strong>Kategori:</strong> {{ implode(', ', $book->categories->pluck('name')->toArray()) }} <br>
                                <strong>Deskripsi:</strong> {{ $book->description }} <br>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <!-- End View Modal -->

            @empty
                <tr>
                    <td colspan="6" class="text-center">There are no books available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
