@extends('layouts.dashboard')

@section('title', 'Media Center')
@section('page-title', 'Media Hub')

@section('content')
    <div class="grid grid-cols-2" style="gap: var(--spacing-xl);">
        <!-- Live Streams -->
        <a href="{{ route('spectator.live-stream') }}" class="card hover-card media-card">
            <div class="card-body"
                style="text-align: center; padding: var(--spacing-4xl) var(--spacing-xl); display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%;">
                <div
                    style="width: 80px; height: 80px; background: rgba(239, 68, 68, 0.1); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: var(--spacing-lg);">
                    <i class="fas fa-broadcast-tower" style="font-size: 3rem; color: #ef4444;"></i>
                </div>
                <h2 class="card-title" style="font-size: var(--font-size-2xl); margin-bottom: var(--spacing-sm);">Live
                    Streams</h2>
                <p style="color: var(--color-text-muted); font-size: var(--font-size-lg);">Watch live matches, replays, and
                    highlights</p>
            </div>
        </a>

        <!-- Brackets -->
        <a href="{{ route('shared.brackets') }}" class="card hover-card media-card">
            <div class="card-body"
                style="text-align: center; padding: var(--spacing-4xl) var(--spacing-xl); display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%;">
                <div
                    style="width: 80px; height: 80px; background: rgba(59, 130, 246, 0.1); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: var(--spacing-lg);">
                    <i class="fas fa-sitemap" style="font-size: 3rem; color: #3b82f6;"></i>
                </div>
                <h2 class="card-title" style="font-size: var(--font-size-2xl); margin-bottom: var(--spacing-sm);">Tournament
                    Brackets</h2>
                <p style="color: var(--color-text-muted); font-size: var(--font-size-lg);">View match progression and
                    standings</p>
            </div>
        </a>
    </div>
@endsection

@push('styles')
    <style>
        .media-card {
            transition: all 0.3s ease;
            border: 1px solid transparent;
            text-decoration: none;
            color: inherit;
            min-height: 300px;
        }

        .media-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
            border-color: var(--color-border-light);
            text-decoration: none;
            color: inherit;
        }
    </style>
@endpush