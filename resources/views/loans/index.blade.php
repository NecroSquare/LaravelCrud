@extends('layouts.app')

@section('title', 'Library - Loans Books')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Loan Book</h1>
        <div>
            <a href="{{ route('loans.borrowed') }}" class="btn btn-secondary">See Borrowed Books</a>
        </div>
    </div>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                @php
                    $user = Auth::user();
                    $borrowed = $user 
                        ? \App\Models\Loan::where('member_id', $user->id)
                                          ->where('book_id', $book->id)
                                          ->whereNull('returned_at')
                                          ->exists()
                        : false;
                @endphp
                <tr>
                    <td>{{ Str::limit($book->title, 50) }}</td>  
                    <td>{{ Str::limit($book->authors, 50) }}</td>  
                    <td>{{ Str::limit($book->isbn, 20) }}</td>  
                    <td>
                        <span class="badge {{ $borrowed ? 'bg-danger' : 'bg-success' }}">
                            {{ $borrowed ? 'Loaned' : 'Available' }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal{{ $book->id }}">
                                View
                            </button>

                            @if ($borrowed)
                                <form action="{{ route('loans.return', $book->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mengembalikan buku ini?')">
                                    @csrf
                                    @method('PUT') 
                                    <button type="submit" class="btn btn-warning btn-sm">Return</button>
                                </form>
                                <button class="btn btn-primary btn-sm comment-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#commentModal" 
                                    data-book-id="{{ $book->id }}" 
                                    data-title="{{ $book->title }}">
                                    Comment
                                </button>
                            @elseif ($book->loans->whereNull('returned_at')->isEmpty())
                                <form action="{{ route('loans.borrow', $book->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Loan</button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>Sudah Dipinjam</button>
                            @endif
                        </div>
                    </td>
                </tr>

                <!-- View Modal (kept per book) -->
                <div class="modal fade" id="viewModal{{ $book->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $book->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Buku</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <strong>Judul:</strong> {{ $book->title }} <br>
                                <strong>Penulis:</strong> {{ $book->authors }} <br>
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

            @empty
                <tr>
                    <td colspan="6" class="text-center">There are no books available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Single Comment Modal (Only One) -->
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">Tambahkan Komentar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST"> {{-- Action will be updated via JS --}}
                @csrf
                <div class="modal-body">
                    <textarea name="note" class="form-control" rows="4" placeholder="Tulis komentar di sini..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Komentar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let commentButtons = document.querySelectorAll(".comment-btn");
        let commentModal = document.getElementById("commentModal");
        let commentForm = commentModal.querySelector("form");
        let modalTitle = commentModal.querySelector(".modal-title");
        
        commentButtons.forEach(button => {
            button.addEventListener("click", function () {
                let bookId = this.getAttribute("data-book-id");
                let bookTitle = this.getAttribute("data-title");

                // Update modal title
                modalTitle.textContent = "Tambahkan Komentar untuk " + bookTitle;

                // Update form action dynamically
                commentForm.action = "{{ route('loans.comment', '') }}/" + bookId;
            });
        });
    });
</script>

@endsection
