<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Loans Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Pinjam Buku</h1>
            <div>
                <a href="{{ route('loans.borrowed') }}" class="btn btn-secondary">See Borrowed Books</a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>

        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>ISBN</th>
                    <th>Status</th>
                    <th>Aksi</th>
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
                                {{ $borrowed ? 'Sedang Dipinjam' : 'Tersedia' }}
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
                                        <button type="submit" class="btn btn-warning btn-sm">Kembalikan</button>
                                    </form>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#commentModal{{ $book->id }}">
                                        Komentar
                                    </button>
                                @elseif ($book->loans->whereNull('returned_at')->isEmpty())
                                    <form action="{{ route('loans.borrow', $book->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Pinjam</button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>Sudah Dipinjam</button>
                                @endif
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
                    <!-- End View Modal -->

                    <!-- Comment Modal -->
                    <div class="modal fade" id="commentModal{{ $book->id }}" tabindex="-1" aria-labelledby="commentModalLabel{{ $book->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="commentModalLabel{{ $book->id }}">Tambahkan Komentar</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('loans.comment', $book->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <textarea name="note" class="form-control" rows="4" placeholder="Tulis komentar di sini...">{{ old('note') }}</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Komentar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Comment Modal -->

                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada buku yang tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
