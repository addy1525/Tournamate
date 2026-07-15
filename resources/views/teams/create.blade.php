@extends('layouts.dashboard')

@section('title', 'Create Team')
@section('page-title', 'Register New Team')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Register New Team</h1>
        <p class="page-subtitle">Add a new team to the system</p>
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
            <form action="{{ route('teams.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label required">Team Name</label>
                    <input type="text" name="name" class="form-input" placeholder="Enter team name"
                        value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Manager Name</label>
                    <input type="text" name="manager_name" class="form-input" placeholder="Enter manager name"
                        value="{{ old('manager_name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Phone Number</label>
                    <input type="text" name="phone_number" class="form-input" placeholder="Enter phone number"
                        value="{{ old('phone_number') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-input" rows="3"
                        placeholder="Enter team address">{{ old('address') }}</textarea>
                </div>

                <div style="display: flex; gap: var(--spacing-md); margin-top: var(--spacing-xl);">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Team
                    </button>
                    <a href="{{ route('teams.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection