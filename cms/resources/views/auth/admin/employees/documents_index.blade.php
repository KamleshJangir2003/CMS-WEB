@extends('auth.layouts.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-semibold">Employee Documents</h4>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Phone</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://i.pravatar.cc/40?u={{ $emp->id }}"
                                     class="rounded-circle me-2"
                                     width="40" height="40">
                                <span class="fw-medium">{{ $emp->full_name }}</span>
                            </div>
                        </td>
                        <td>{{ $emp->email }}</td>
                        <td>{{ ucfirst($emp->department ?? 'N/A') }}</td>
                        <td>{{ $emp->phone ?? 'N/A' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.employees.document', ['userId' => $emp->id]) }}"
                               class="btn btn-sm btn-primary"
                               title="View Documents">
                                <i class="fa-solid fa-file-lines me-1"></i> View Documents
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No employees found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <small class="text-muted">Showing {{ $employees->count() }} employees</small>
        </div>
    </div>
</div>
@endsection
