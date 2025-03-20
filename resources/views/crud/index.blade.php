<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Book List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Daftar Buku</h1>
            <div>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
                <a class="btn btn-success" href="{{ route('crud.create') }}">Tambah Data</a>
            </div>
        </div>

        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Judul</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
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
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
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
                    <!-- End View Modal -->

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
