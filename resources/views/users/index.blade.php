@extends('layouts.dashboard')

@section('title', 'Users & Roles')
@section('page-title', 'User Management')

@section('content')
        <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-border);">
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <h2 class="card-title" style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--color-text-primary); line-height: 1.2; display: block;">Registered Users</h2>
                <p class="card-subtitle" style="margin: 0; font-size: 0.875rem; color: var(--color-text-tertiary); display: block;">Manage system access and permissions</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-user-plus"></i> Add User
            </a>
        </div>
        <div class="card-body">
            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-wrapper">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th width="200px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="font-bold">
                                    <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
                                        <div
                                            style="width: 32px; height: 32px; background: var(--color-bg-tertiary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-text-primary);">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php
                                        $roleColor = match ($user->role) {
                                            'admin' => 'var(--color-electric-blue)',
                                            'manager' => 'var(--color-rugby-green)',
                                            'referee' => '#f59e0b',
                                            default => 'var(--color-text-muted)'
                                        };
                                    @endphp
                                    <span class="badge"
                                        style="background: {{ $roleColor }}20; color: {{ $roleColor }}; border: 1px solid {{ $roleColor }}40;">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusColor = match ($user->status ?? 'active') {
                                            'pending' => '#f59e0b',
                                            'active' => 'var(--color-rugby-green)',
                                            'inactive' => '#ef4444',
                                            default => 'var(--color-text-muted)'
                                        };
                                    @endphp
                                    <span class="badge"
                                        style="background: {{ $statusColor }}20; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }}40;">
                                        {{ ucfirst($user->status ?? 'active') }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <div style="display: flex; gap: var(--spacing-sm);">
                                        @if($user->role === 'manager' && ($user->status ?? 'active') === 'pending')
                                            {{-- Approve Button --}}
                                            <form action="{{ route('users.approve', $user->id) }}" method="POST"
                                                style="margin:0;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Approve this manager?')" title="Approve Manager">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            {{-- Reject Button --}}
                                            <form action="{{ route('users.reject', $user->id) }}" method="POST"
                                                style="margin:0;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Reject this manager?')" title="Reject Manager">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Edit Button --}}
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="margin:0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete user?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection