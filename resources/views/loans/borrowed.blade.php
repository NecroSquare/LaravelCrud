<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-3">Borrowed Books</h1>
        <a href="{{ route('loans.index') }}" class="btn btn-secondary mb-3">Back to Loan Page</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Borrower</th>
                    <th>Borrowed At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($borrowedBooks as $loan)
                    <tr>
                        <td>{{ $loan->book->title }}</td>
                        <td>{{ $loan->member->name }}</td>
                        <td>{{ $loan->loan_at }}</td>
                        <td>
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#loanModal{{ $loan->id }}">
                                View Details
                            </button>
                        </td>
                    </tr>

                    <!-- MODAL -->
                    <div class="modal fade" id="loanModal{{ $loan->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $loan->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel{{ $loan->id }}">Loan Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Book Title:</strong> {{ $loan->book->title }}</p>
                                    <p><strong>Borrower:</strong> {{ $loan->member->name }}</p>
                                    <p><strong>Email:</strong> {{ $loan->member->email }}</p>
                                    <p><strong>Phone:</strong> {{ $loan->member->phone ?? 'No phone number' }}</p>
                                    <p><strong>Address:</strong> {{ $loan->member->address ?? 'No address provided' }}</p>
                                    <p><strong>Loan Date:</strong> {{ $loan->loan_at }}</p>
                                    <p><strong>Notes:</strong></p>
                                    @if ($loan->note)
                                        <div class="p-2 border rounded bg-light">
                                            <p class="mb-1">{{ $loan->note }}</p>
                                            <small class="text-muted">— {{ $loan->member->name }}</small>
                                        </div>
                                    @else
                                        <p class="text-muted">Tidak ada komentar.</p>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No borrowed books.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
