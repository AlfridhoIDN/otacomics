@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm bg-dark text-light border-purple">
        <div class="card-header bg-purple text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Admin Dashboard</h4>
            <span class="badge bg-dark text-purple border border-purple">{{ isset($users) ? $users->count() : 0 }} Users</span>
        </div>
        <div class="card-body">
            @if(isset($users) && !$users->isEmpty())
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle">
                        <thead class="table-purple">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-purple text-white me-2">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->role == 'publisher' ? 'bg-purple' : 'bg-dark border border-purple text-purple' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.changeRole', $user->id) }}" method="POST" class="role-change-form">
                                        @csrf
                                        @method('POST')
                                        <div class="d-flex gap-2">
                                            <select name="role" class="form-select form-select-sm role-select bg-dark text-light border-purple" data-user-id="{{ $user->id }}">
                                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                                <option value="publisher" {{ $user->role == 'publisher' ? 'selected' : '' }}>Publisher</option>
                                            </select>
                                            <button type="submit" class="btn btn-purple btn-sm">
                                                <i class="bi bi-check-lg"></i> Save
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert bg-dark text-purple border border-purple d-flex align-items-center" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <div>No users found in the system.</div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Dark mode with purple accent colors */
    :root {
        --bs-purple: #8e24aa;
        --bs-purple-dark: #5e1670;
        --bs-purple-light: #c158dc;
    }

    body {
        background-color: #121212;
        color: #f8f9fa;
    }

    .bg-dark {
        background-color: #1e1e1e !important;
    }

    .bg-purple {
        background-color: var(--bs-purple) !important;
    }

    .text-purple {
        color: var(--bs-purple-light) !important;
    }

    .border-purple {
        border-color: var(--bs-purple) !important;
    }

    .btn-purple {
        background-color: var(--bs-purple);
        border-color: var(--bs-purple);
        color: white;
    }

    .btn-purple:hover {
        background-color: var(--bs-purple-dark);
        border-color: var(--bs-purple-dark);
        color: white;
    }

    .table-purple {
        background-color: var(--bs-purple-dark);
    }

    .table-dark {
        --bs-table-bg: #1e1e1e;
        --bs-table-striped-bg: #2d2d2d;
        --bs-table-hover-bg: #303030;
        color: #f8f9fa;
        border-color: #373737;
    }

    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .role-select {
        min-width: 120px;
    }

    .table th {
        font-weight: 600;
    }

    /* Animation for role changes */
    .role-change-success {
        animation: fadeBackgroundDark 2s ease;
    }

    @keyframes fadeBackgroundDark {
        0% { background-color: rgba(142, 36, 170, 0.3); }
        100% { background-color: transparent; }
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #1e1e1e;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--bs-purple);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--bs-purple-light);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Auto-submit on role change
        const roleSelects = document.querySelectorAll('.role-select');
        roleSelects.forEach(select => {
            select.addEventListener('change', function() {
                const form = this.closest('form');
                form.querySelector('button[type="submit"]').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                form.submit();
            });
        });

        // Highlight the row if it was just updated (requires session flash)
        @if(session('updated_user_id'))
            const updatedRow = document.querySelector('[data-user-id="{{ session('updated_user_id') }}"]').closest('tr');
            updatedRow.classList.add('role-change-success');
        @endif
    });
</script>
@endpush
@endsection
