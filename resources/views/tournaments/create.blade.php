@extends('layouts.dashboard')

@section('title', 'Create Tournament')
@section('page-title', 'New Tournament Setup')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="margin-bottom: var(--spacing-2xl);">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div style="display: flex; align-items: center; gap: var(--spacing-md); margin-bottom: var(--spacing-sm);">
                    <div
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--color-rugby-green), var(--color-rugby-green-dark)); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-plus-circle" style="font-size: 1.5rem; color: white;"></i>
                    </div>
                    <div>
                        <h1 class="page-title" style="margin: 0;">Create New Tournament</h1>
                        <p class="page-subtitle" style="margin: 0;">Set up a new rugby tournament</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('tournaments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Tournaments
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: var(--spacing-xl);">
            <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="alert-content">
                <div class="alert-title">Please fix the following errors:</div>
                <ul style="margin: var(--spacing-sm) 0 0; padding-left: var(--spacing-xl);">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('tournaments.store') }}" method="POST">
        @csrf

        <!-- Main Information Section -->
        <div class="card" style="margin-bottom: var(--spacing-xl);">
            <div class="card-header">
                <div>
                    <h2 class="card-title">
                        <i class="fas fa-info-circle"
                            style="color: var(--color-electric-blue); margin-right: var(--spacing-sm);"></i>
                        Tournament Information
                    </h2>
                    <p class="card-subtitle">Basic details about your tournament</p>
                </div>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1">
                    <!-- Tournament Name -->
                    <div class="form-group">
                        <label class="form-label required">
                            <i class="fas fa-trophy"></i> Tournament Name
                        </label>
                        <input type="text" name="name" class="form-input" value="{{ old('name') }}"
                            placeholder="e.g., Summer Rugby Championship 2026" required
                            style="font-size: var(--font-size-lg); font-weight: 600;">
                        <small
                            style="color: var(--color-text-muted); font-size: var(--font-size-xs); display: block; margin-top: var(--spacing-xs);">
                            This will be displayed on all tournament materials and promotions
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Details Section -->
        <div class="card" style="margin-bottom: var(--spacing-xl);">
            <div class="card-header">
                <div>
                    <h2 class="card-title">
                        <i class="fas fa-calendar-alt"
                            style="color: var(--color-rugby-green); margin-right: var(--spacing-sm);"></i>
                        Event Details
                    </h2>
                    <p class="card-subtitle">When and where the tournament takes place</p>
                </div>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2" style="gap: var(--spacing-xl);">
                    <!-- Tournament Date -->
                    <div class="form-group">
                        <label class="form-label required">
                            <i class="fas fa-calendar-day"></i> Tournament Date
                        </label>
                        <input type="date" name="tournament_date" class="form-input" value="{{ old('tournament_date') }}"
                            required style="font-size: var(--font-size-base);">
                        <small
                            style="color: var(--color-text-muted); font-size: var(--font-size-xs); display: block; margin-top: var(--spacing-xs);">
                            <i class="fas fa-info-circle"></i> Main tournament start date
                        </small>
                    </div>

                    <!-- Venue -->
                    <div class="form-group">
                        <label class="form-label required">
                            <i class="fas fa-map-marker-alt"></i> Venue Location
                        </label>
                        <input type="text" name="venue" class="form-input" value="{{ old('venue') }}"
                            placeholder="e.g., Wellington Rugby Stadium" required>
                        <small
                            style="color: var(--color-text-muted); font-size: var(--font-size-xs); display: block; margin-top: var(--spacing-xs);">
                            <i class="fas fa-map-pin"></i> Primary playing venue
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="card" style="margin-bottom: var(--spacing-2xl);">
            <div class="card-header">
                <div>
                    <h2 class="card-title">
                        <i class="fas fa-align-left"
                            style="color: var(--color-electric-blue); margin-right: var(--spacing-sm);"></i>
                        Tournament Description
                    </h2>
                    <p class="card-subtitle">Provide details about format, rules, and key information</p>
                </div>
                <span class="badge badge-neutral">Optional</span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file-alt"></i> Description & Details
                    </label>
                    <textarea name="description" class="form-input" rows="6"
                        placeholder="Describe the tournament format, pool structure, knockout stages, age groups, regulations, prizes, etc."
                        style="resize: vertical; font-family: inherit;">{{ old('description') }}</textarea>
                    <small
                        style="color: var(--color-text-muted); font-size: var(--font-size-xs); display: block; margin-top: var(--spacing-xs);">
                        <i class="fas fa-lightbulb"></i> Include tournament format, eligibility criteria, and any special
                        rules
                    </small>
                </div>
            </div>
        </div>

        <!-- Quick Setup Tips -->
        <div class="card"
            style="border: 2px solid var(--color-electric-blue); background: linear-gradient(135deg, rgba(0, 212, 255, 0.05), transparent); margin-bottom: var(--spacing-2xl);">
            <div class="card-body" style="padding: var(--spacing-lg);">
                <div style="display: flex; gap: var(--spacing-md); align-items: start;">
                    <div
                        style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--color-electric-blue), var(--color-electric-blue-dark)); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-lightbulb" style="color: white; font-size: 1.25rem;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: var(--color-electric-blue); margin-bottom: var(--spacing-xs);">
                            <i class="fas fa-star"></i> Quick Setup Tips
                        </div>
                        <div style="color: var(--color-text-secondary); font-size: var(--font-size-sm); line-height: 1.6;">
                            After creating the tournament, you can add teams, set pool structures, assign referees, and
                            configure match schedules from the tournament management dashboard.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div
            style="position: sticky; bottom: 0; background: var(--color-bg-primary); padding: var(--spacing-xl) 0; border-top: 1px solid var(--color-border); margin: 0 calc(-1 * var(--spacing-xl)); padding-left: var(--spacing-xl); padding-right: var(--spacing-xl); z-index: 10;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; gap: var(--spacing-md);">
                    <button type="submit" class="btn btn-primary" style="min-width: 200px; font-weight: 600;">
                        <i class="fas fa-check-circle"></i> Create Tournament
                    </button>
                    <a href="{{ route('tournaments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>

                <!-- Quick Guide -->
                <div style="display: flex; gap: var(--spacing-md); align-items: center;">
                    <div style="text-align: right;">
                        <div style="font-size: var(--font-size-xs); color: var(--color-text-muted);">Need Help?</div>
                        <a href="#"
                            style="font-size: var(--font-size-sm); color: var(--color-electric-blue); font-weight: 600; text-decoration: none;">
                            <i class="fas fa-question-circle"></i> Setup Guide
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('styles')
    <style>
        /* Enhanced form styling for tournament create */
        .form-input:focus {
            border-color: var(--color-rugby-green);
            box-shadow: 0 0 0 3px rgba(0, 168, 107, 0.1);
        }

        .form-label i {
            color: var(--color-rugby-green);
            margin-right: var(--spacing-xs);
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Required field indicator */
        .form-label.required::after {
            content: '*';
            color: #ff4444;
            margin-left: var(--spacing-xs);
        }

        /* Tips card hover effect */
        .card:has(.fa-lightbulb) {
            transition: all var(--transition-base);
        }

        .card:has(.fa-lightbulb):hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
    </style>
@endpush