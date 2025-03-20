@extends('layouts.app')

@section('title', 'Perbarui Buku')

@section('content')
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

     <!-- Bootstrap Modal: Add Category -->
     <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Nama Kategori</label>
                            <input type="text" name="category_name" id="category_name" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

   <!-- Bootstrap Modal: Delete Category -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('categories.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Pilih Kategori</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                @if ($category->books()->count() == 0) <!-- Only deletable if no books -->
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

