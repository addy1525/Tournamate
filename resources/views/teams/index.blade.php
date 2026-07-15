@extends('layouts.dashboard')

@section('title', 'Manage Teams')
@section('page-title', 'Team Management')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Team Management</h1>
        <p class="page-subtitle">Manage all registered teams</p>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
            <div class="alert-content">
                <div>{{ $message }}</div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div>
                <h2 class="card-title">All Teams</h2>
                <p class="card-subtitle">{{ $teams->count() }} teams registered</p>
            </div>
            <a href="{{ route('teams.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Team
            </a>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Team Name</th>
                            <th>Manager Name</th>
                            <th>Phone No</th>
                            <th>Address</th>
                            <th width="280px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teams as $team)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="font-bold">{{ $team->name }}</td>
                                <td>{{ $team->manager_name }}</td>
                                <td>{{ $team->phone_number }}</td>
                                <td>{{ $team->address }}</td>
                                <td>
                                    <form action="{{ route('teams.destroy', $team->id) }}" method="POST"
                                        style="display: inline-flex; gap: var(--spacing-xs);">
                                        <a class="btn btn-sm btn-secondary" href="{{ route('teams.show', $team->id) }}">
                                            <i class="fas fa-eye"></i> Show
                                        </a>
                                        <a class="btn btn-sm btn-primary" href="{{ route('teams.edit', $team->id) }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this team?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    style="text-align: center; padding: var(--spacing-2xl); color: var(--color-text-muted);">
                                    <i class="fas fa-inbox"
                                        style="font-size: 3rem; margin-bottom: var(--spacing-md); opacity: 0.3;"></i>
                                    <div>No teams found. Please add a new team.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection