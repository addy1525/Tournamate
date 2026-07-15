@extends('layouts.dashboard')

@section('title', 'Reports')
@section('page-title', 'System Reports')

@section('content')
    <div class="grid grid-cols-2" style="gap: var(--spacing-xl);">
        <!-- Participation Report -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Participation Overview</h3>
            </div>
            <div class="card-body">
                <div
                    style="font-size: 3rem; font-weight: 700; color: var(--color-rugby-green); margin-bottom: var(--spacing-xs);">
                    {{ $activeTeamsCount }}
                </div>
                <p style="color: var(--color-text-muted);">Active Teams this Season</p>
                <div style="margin-top: var(--spacing-lg);">
                    <button class="btn btn-secondary btn-sm"><i class="fas fa-download"></i> Download CSV</button>
                </div>
            </div>
        </div>

        <!-- Financial Report -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Financial Summary</h3>
            </div>
            <div class="card-body">
                <div
                    style="font-size: 3rem; font-weight: 700; color: var(--color-electric-blue); margin-bottom: var(--spacing-xs);">
                    RM {{ number_format($totalRevenue, 2) }}
                </div>
                <p style="color: var(--color-text-muted);">Total Revenue (YTD)</p>
                <div style="margin-top: var(--spacing-lg);">
                    <button class="btn btn-secondary btn-sm"><i class="fas fa-download"></i> Download PDF</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: var(--spacing-xl);">
        <div class="card-header">
            <h3 class="card-title">Recent Activity Log</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivities as $activity)
                        <tr>
                            <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $activity->user->name ?? 'System' }}</td>
                            <td>{{ $activity->description }}</td>
                            <td>
                                @if(stripos($activity->description, 'failed') !== false)
                                    <span class="badge badge-danger">Failed</span>
                                @elseif(stripos($activity->description, 'pending') !== false)
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-success">Success</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No recent activity found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection