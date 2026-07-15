@extends('layouts.dashboard')

@section('title', 'Registration Confirmed')
@section('page-title', 'Registration Ticket')

@push('styles')
    <style>
        .ticket-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .ticket-card {
            background: linear-gradient(135deg, rgba(0, 168, 107, 0.15), rgba(0, 132, 255, 0.1));
            border: 2px solid var(--color-rugby-green);
            border-radius: 16px;
            padding: var(--spacing-2xl);
            margin-bottom: var(--spacing-xl);
            position: relative;
            overflow: hidden;
        }

        .ticket-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 168, 107, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .ticket-header {
            text-align: center;
            margin-bottom: var(--spacing-2xl);
            position: relative;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: var(--color-rugby-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--spacing-lg);
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }

            to {
                transform: scale(1);
            }
        }

        .success-icon i {
            font-size: 40px;
            color: white;
        }

        .ticket-title {
            font-size: var(--font-size-2xl);
            font-weight: 700;
            color: var(--color-rugby-green);
            margin-bottom: var(--spacing-sm);
        }

        .ticket-subtitle {
            font-size: var(--font-size-base);
            color: var(--color-text-secondary);
        }

        .ticket-body {
            background: var(--color-bg-secondary);
            border-radius: 12px;
            padding: var(--spacing-xl);
            margin-bottom: var(--spacing-xl);
        }

        .ticket-section {
            margin-bottom: var(--spacing-xl);
            padding-bottom: var(--spacing-xl);
            border-bottom: 1px solid var(--color-border);
        }

        .ticket-section:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .section-title {
            font-size: var(--font-size-lg);
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: var(--spacing-md);
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: var(--spacing-sm);
            color: var(--color-rugby-green);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--spacing-md);
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: var(--font-size-xs);
            color: var(--color-text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: var(--spacing-xs);
        }

        .info-value {
            font-size: var(--font-size-base);
            color: var(--color-text-primary);
            font-weight: 600;
        }

        .player-list {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-sm);
        }

        .player-item {
            display: flex;
            justify-content: space-between;
            padding: var(--spacing-md);
            background: var(--color-bg-tertiary);
            border-radius: 8px;
        }

        .player-name {
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .player-position {
            color: var(--color-text-secondary);
            font-size: var(--font-size-sm);
        }

        .status-badge-large {
            display: inline-flex;
            align-items: center;
            padding: var(--spacing-sm) var(--spacing-lg);
            background: var(--color-rugby-green);
            color: white;
            border-radius: 20px;
            font-weight: 700;
            font-size: var(--font-size-base);
        }

        .status-badge-large i {
            margin-right: var(--spacing-sm);
        }

        .ticket-actions {
            display: flex;
            gap: var(--spacing-md);
            justify-content: center;
        }

        @media print {

            .sidebar,
            .dashboard-header,
            .safety-bar,
            .ticket-actions {
                display: none !important;
            }

            .ticket-container {
                max-width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="ticket-container">
        <div class="ticket-card">
            <div class="ticket-header">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="ticket-title">Registration Confirmed!</h1>
                <p class="ticket-subtitle">Your team has been successfully registered for the tournament</p>
                <div style="margin-top: var(--spacing-md);">
                    <span class="status-badge-large">
                        <i class="fas fa-check-circle"></i> Confirmed
                    </span>
                </div>
            </div>

            <div class="ticket-body">
                <!-- Tournament Information -->
                <div class="ticket-section">
                    <h3 class="section-title">
                        <i class="fas fa-trophy"></i> Tournament Details
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Tournament Name</span>
                            <span class="info-value">{{ $registration->tournament->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Date</span>
                            <span class="info-value">
                                {{ $registration->tournament->start_date ? $registration->tournament->start_date->format('M d, Y') : ($registration->tournament->tournament_date ? $registration->tournament->tournament_date->format('M d, Y') : 'TBD') }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Venue</span>
                            <span
                                class="info-value">{{ $registration->tournament->venue_name ?? $registration->tournament->venue ?? 'TBD' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Registration ID</span>
                            <span class="info-value">#{{ str_pad($registration->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Team Information -->
                <div class="ticket-section">
                    <h3 class="section-title">
                        <i class="fas fa-users"></i> Team Information
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Team Name</span>
                            <span class="info-value">{{ $registration->team->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Manager</span>
                            <span class="info-value">{{ $registration->manager->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Head Coach</span>
                            <span class="info-value">{{ $registration->team->manager_name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Squad Size</span>
                            <span class="info-value">{{ $registration->team->players->count() }} Players</span>
                        </div>
                    </div>
                </div>

                <!-- Squad Roster -->
                <div class="ticket-section">
                    <h3 class="section-title">
                        <i class="fas fa-clipboard-list"></i> Squad Roster
                    </h3>
                    <div class="player-list">
                        @foreach($registration->team->players as $player)
                            <div class="player-item">
                                <span class="player-name">{{ $player->name }}</span>
                                <span class="player-position">
                                    <span class="badge badge-neutral">{{ ucfirst($player->position) }}</span>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="ticket-section">
                    <h3 class="section-title">
                        <i class="fas fa-credit-card"></i> Payment Information
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Payment Status</span>
                            <span class="info-value">
                                <span class="badge badge-success">{{ ucfirst($registration->payment_status) }}</span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Amount Paid</span>
                            <span class="info-value">RM {{ number_format($registration->amount_paid, 2) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Payment Date</span>
                            <span
                                class="info-value">{{ $registration->registered_at ? $registration->registered_at->format('M d, Y H:i') : 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Transaction ID</span>
                            <span class="info-value"
                                style="font-size: var(--font-size-xs);">{{ substr($registration->payment_intent_id, 0, 20) }}...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ticket-actions">
            <a href="{{ route('manager.dashboard') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Dashboard
            </a>
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="fas fa-print"></i> Print Ticket
            </button>
            <a href="{{ route('manager.my-applications') }}" class="btn btn-secondary">
                <i class="fas fa-users"></i> View Team
            </a>
        </div>
    </div>
@endsection