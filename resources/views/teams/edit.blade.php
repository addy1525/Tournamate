@extends('layouts.dashboard')

@section('title', 'Edit Team')
@section('page-title', 'Edit Team')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Edit Team</h1>
        <p class="page-subtitle">Update team information</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="alert-content">
                <div class="alert-title">There were some errors</div>
                <ul style="margin: 0; padding-left: var(--spacing-lg);">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Team Information</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('teams.update', $team->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label required">Team Name</label>
                    <input type="text" name="name" class="form-input" value="{{ $team->name }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Manager Name</label>
                    <input type="text" name="manager_name" class="form-input" value="{{ $team->manager_name }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Phone Number</label>
                    <input type="text" name="phone_number" class="form-input" value="{{ $team->phone_number }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-input" rows="3">{{ $team->address }}</textarea>
                </div>

                <div style="display: flex; gap: var(--spacing-md); margin-top: var(--spacing-xl);">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Team
                    </button>
                    <a href="{{ route('teams.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection