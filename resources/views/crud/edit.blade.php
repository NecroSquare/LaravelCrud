<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbarui Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Perbarui Buku</h1>
        <form action="{{ route('crud.update', $book->id) }}" method="POST">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="book_title" class="form-label">Judul</label>
                <input type="text" name="book_title" id="book_title" class="form-control" value="{{ old('book_title', $book->title) }}" required>
            </div>
            <div class="mb-3">
                <label for="book_authors" class="form-label">Author</label>
                <input type="text" name="book_authors" id="book_authors" class="form-control" value="{{ old('book_authors', $book->authors) }}" required>
            </div>
            <div class="mb-3">
                <label for="book_isbn" class="form-label">ISBN</label>
                <input type="text" name="book_isbn" id="book_isbn" class="form-control" value="{{ old('book_isbn', $book->isbn) }}" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <div class="d-flex">
                    <select name="category_id" id="category_id" class="form-control me-2" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $book->categories->first()->id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addCategoryModal">+ Tambah</button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal">- Hapus</button>
                </div>
            </div>
            <div class="mb-3">
                <label for="book_description" class="form-label">Deskripsi</label>
                <textarea name="book_description" id="book_description" class="form-control" required>{{ old('book_description', $book->description) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui Buku</button>
            <a href="{{ route('crud.index') }}" class="btn btn-secondary">Kembali</a>
        </form>

        <!-- Modal Tambah Kategori -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="categoryForm">
                            @csrf
                            <div class="mb-3">
                                <label for="category_name" class="form-label">Nama Kategori</label>
                                <input type="text" name="category_name" id="category_name" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Hapus Kategori -->
        <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="deleteCategorySelect">Pilih Kategori untuk Dihapus</label>
                        <select id="deleteCategorySelect" class="form-control">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                @if ($category->books()->count() == 0)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteCategory">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#categoryForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('categories.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if(response.success) {
                        $('#category_id').append(`<option value="${response.category.id}" selected>${response.category.name}</option>`);
                        $('#addCategoryModal').modal('hide');
                        $('#category_name').val('');
                    } else {
                        alert("Gagal menambahkan kategori");
                    }
                },
                error: function() {
                    alert("Terjadi kesalahan!");
                }
            });
        });
    });

    $(document).on('click', '#confirmDeleteCategory', function () {
        let categoryId = $('#deleteCategorySelect').val();
        if (!categoryId) {
            alert('Pilih kategori yang akan dihapus.');
            return;
        }
        $.ajax({
            url: `/categories/${categoryId}`,
            type: 'DELETE',
            data: { _token: "{{ csrf_token() }}" },
            success: function (response) {
                alert(response.message);
                setTimeout(function () {
                    location.reload();
                }, 500);
            },
            error: function () {
                alert('Kategori masih digunakan, tidak dapat dihapus.');
            }
        });
    });
    </script>
</body>
</html>
