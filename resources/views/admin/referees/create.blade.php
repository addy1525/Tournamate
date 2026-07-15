@extends('layouts.dashboard')

@section('title', 'Add New Referee')
@section('page-title', 'Create Referee Account')

@push('styles')
<style>
    .form-control {
        background-color: var(--color-bg-primary) !important;
        border: 1px solid var(--color-border) !important;
        color: var(--color-text-primary) !important;
        border-radius: 10px !important;
        padding: 0.65rem 1rem !important;
        height: auto !important;
        transition: all var(--transition-fast) !important;
    }

    .form-control:focus {
        border-color: var(--color-electric-blue) !important;
        box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.15) !important;
        background-color: var(--color-bg-primary) !important;
    }

    .form-control::placeholder {
        color: var(--color-text-muted) !important;
    }

    .form-group label {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--color-text-secondary);
        margin-bottom: 6px;
    }
</style>
@endpush

@section('content')
    <div class="card mb-4" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 16px; overflow: hidden; max-width: 600px; margin: 0 auto;">
        <div class="card-header" style="background: var(--color-bg-tertiary); border-bottom: 1px solid var(--color-border); padding: 1.25rem 1.5rem;">
            <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-whistle" style="color: var(--color-rugby-green-light);"></i>
                Create Referee Account
            </h3>
        </div>
        
        <form method="POST" action="{{ route('admin.referees.store') }}">
            @csrf
            <div class="card-body" style="padding: 1.75rem;">
                
                <div class="form-group mb-4">
                    <label for="name">Full Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name') }}" placeholder="Enter referee's full name" required autofocus autocomplete="off">
                    @error('name')
                        <span class="invalid-feedback d-block" style="color: #ef4444; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" placeholder="referee@example.com" required autocomplete="off">
                    @error('email')
                        <span class="invalid-feedback d-block" style="color: #ef4444; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" placeholder="Enter password (min. 8 characters)" required>
                    @error('password')
                        <span class="invalid-feedback d-block" style="color: #ef4444; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation"
                        placeholder="Confirm password" required>
                </div>

            </div>

            <div class="card-footer" style="background: rgba(0, 0, 0, 0.15); border-top: 1px solid var(--color-border); padding: 1.25rem 1.5rem;">
                <button type="submit" class="btn btn-success font-weight-bold shadow-glow-green px-4 py-2">
                    <i class="fas fa-check-circle mr-1"></i> Create Referee Account
                </button>
                <a href="{{ route('admin.referees.index') }}" class="btn btn-secondary float-right px-4 py-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection