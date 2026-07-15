@extends('layouts.dashboard')

@section('title', 'Edit Tournament')
@section('page-title', 'Tournament Configuration')

@section('content')
    <!-- Page Header with Tournament Preview -->
    <div class="page-header" style="margin-bottom: var(--spacing-2xl);">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div style="display: flex; align-items: center; gap: var(--spacing-md); margin-bottom: var(--spacing-sm);">
                    <div
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--color-rugby-green), var(--color-rugby-green-dark)); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-trophy" style="font-size: 1.5rem; color: white;"></i>
                    </div>
                    <div>
                        <h1 class="page-title" style="margin: 0;">Edit Tournament</h1>
                        <p class="page-subtitle" style="margin: 0;">{{ $tournament->name }}</p>
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

    <form action="{{ route('tournaments.update', $tournament->id) }}" method="POST">
        @csrf
        @method('PUT')

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
                        <input type="text" name="name" class="form-input" value="{{ $tournament->name }}"
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
                        <input type="date" name="tournament_date" class="form-input"
                            value="{{ $tournament->tournament_date }}" required style="font-size: var(--font-size-base);">
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
                        <input type="text" name="venue" class="form-input" value="{{ $tournament->venue }}"
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
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file-alt"></i> Description & Details
                    </label>
                    <textarea name="description" class="form-input" rows="6"
                        placeholder="Describe the tournament format, pool structure, knockout stages, age groups, regulations, prizes, etc."
                        style="resize: vertical; font-family: inherit;">{{ $tournament->description }}</textarea>
                    <small
                        style="color: var(--color-text-muted); font-size: var(--font-size-xs); display: block; margin-top: var(--spacing-xs);">
                        <i class="fas fa-lightbulb"></i> Include tournament format, eligibility criteria, and any special
                        rules
                    </small>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div
            style="position: sticky; bottom: 0; background: var(--color-bg-primary); padding: var(--spacing-xl) 0; border-top: 1px solid var(--color-border); margin: 0 calc(-1 * var(--spacing-xl)); padding-left: var(--spacing-xl); padding-right: var(--spacing-xl); z-index: 10;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; gap: var(--spacing-md);">
                    <button type="submit" class="btn btn-primary" style="min-width: 200px; font-weight: 600;">
                        <i class="fas fa-save"></i> Update Tournament
                    </button>
                    <a href="{{ route('tournaments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel Changes
                    </a>
                </div>

                <!-- Quick Stats Preview -->
                <div style="display: flex; gap: var(--spacing-lg); align-items: center;">
                    <div style="text-align: right;">
                        <div style="font-size: var(--font-size-xs); color: var(--color-text-muted);">Last Updated</div>
                        <div style="font-size: var(--font-size-sm); color: var(--color-text-secondary); font-weight: 600;">
                            {{ $tournament->updated_at->diffForHumans() }}
                        </div>
                    </div>
                    <div style="width: 1px; height: 40px; background: var(--color-border);"></div>
                    <div style="text-align: right;">
                        <div style="font-size: var(--font-size-xs); color: var(--color-text-muted);">Tournament ID</div>
                        <div
                            style="font-size: var(--font-size-sm); color: var(--color-text-secondary); font-weight: 600; font-family: monospace;">
                            #{{ str_pad($tournament->id, 4, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('styles')
    <style>
        /* Enhanced form styling for tournament edit */
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

        /* Sticky action bar animation */
        .sticky-action-bar {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
@endpush