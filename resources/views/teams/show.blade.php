@extends('layouts.dashboard')

@section('title', 'View Team')
@section('page-title', 'Team Details')

@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $team->name }}</h1>
        <p class="page-subtitle">Team Details</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Team Information</h2>
            <div style="display: flex; gap: var(--spacing-sm);">
                <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('teams.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--spacing-xl);">
                <div>
                    <div
                        style="font-size: var(--font-size-sm); color: var(--color-text-muted); margin-bottom: var(--spacing-xs);">
                        Team Name
                    </div>
                    <div style="font-size: var(--font-size-lg); font-weight: 600; color: var(--color-text-primary);">
                        {{ $team->name }}
                    </div>
                </div>

                <div>
                    <div
                        style="font-size: var(--font-size-sm); color: var(--color-text-muted); margin-bottom: var(--spacing-xs);">
                        Manager Name
                    </div>
                    <div style="font-size: var(--font-size-lg); font-weight: 600; color: var(--color-text-primary);">
                        {{ $team->manager_name }}
                    </div>
                </div>

                <div>
                    <div
                        style="font-size: var(--font-size-sm); color: var(--color-text-muted); margin-bottom: var(--spacing-xs);">
                        Phone Number
                    </div>
                    <div style="font-size: var(--font-size-lg); font-weight: 600; color: var(--color-text-primary);">
                        {{ $team->phone_number }}
                    </div>
                </div>

                <div>
                    <div
                        style="font-size: var(--font-size-sm); color: var(--color-text-muted); margin-bottom: var(--spacing-xs);">
                        Address
                    </div>
                    <div style="font-size: var(--font-size-lg); font-weight: 600; color: var(--color-text-primary);">
                        {{ $team->address ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection