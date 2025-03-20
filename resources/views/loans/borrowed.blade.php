@extends('layouts.app')

@section('title', 'Library - Borrowed Books')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Borrowed Books</h1>
        <a href="{{ route('loans.index') }}" class="btn btn-secondary">Back to Loan Page</a>
    </div>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Borrower</th>
                <th>Borrowed At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($borrowedBooks as $loan)
                <tr>
                    <td>{{ Str::limit($loan->book->title, 50) }}</td>
                    <td>{{ Str::limit($loan->member->name, 50) }}</td>
                    <td>{{ $loan->loan_at }}</td>
                    <td>
                        <button class="btn btn-info btn-sm view-details" 
                            data-bs-toggle="modal" 
                            data-bs-target="#loanModal" 
                            data-title="{{ $loan->book->title }}"
                            data-borrower="{{ $loan->member->name }}"
                            data-email="{{ $loan->member->email }}"
                            data-phone="{{ $loan->member->phone ?? 'No phone number' }}"
                            data-address="{{ $loan->member->address ?? 'No address provided' }}"
                            data-loan-date="{{ $loan->loan_at }}"
                            data-note="{{ $loan->note ?? 'No comments' }}">
                            View
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No borrowed books.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Single Loan Modal -->
<div class="modal fade" id="loanModal" tabindex="-1" aria-labelledby="loanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loanModalLabel">Loan Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Book Title:</strong> <span id="modal-title"></span></p>
                <p><strong>Borrower:</strong> <span id="modal-borrower"></span></p>
                <p><strong>Email:</strong> <span id="modal-email"></span></p>
                <p><strong>Phone:</strong> <span id="modal-phone"></span></p>
                <p><strong>Address:</strong> <span id="modal-address"></span></p>
                <p><strong>Loan Date:</strong> <span id="modal-loan-date"></span></p>
                <p><strong>Notes:</strong></p>
                <div class="p-2 border rounded bg-light">
                    <p class="mb-1" id="modal-note"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let detailButtons = document.querySelectorAll(".view-details");
        let loanModal = document.getElementById("loanModal");

        detailButtons.forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("modal-title").textContent = this.getAttribute("data-title");
                document.getElementById("modal-borrower").textContent = this.getAttribute("data-borrower");
                document.getElementById("modal-email").textContent = this.getAttribute("data-email");
                document.getElementById("modal-phone").textContent = this.getAttribute("data-phone");
                document.getElementById("modal-address").textContent = this.getAttribute("data-address");
                document.getElementById("modal-loan-date").textContent = this.getAttribute("data-loan-date");
                document.getElementById("modal-note").textContent = this.getAttribute("data-note");
            });
        });
    });
</script>

@endsection
