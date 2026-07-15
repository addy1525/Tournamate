@extends('layouts.dashboard')

@section('title', 'My Applications')
@section('page-title', 'My Applications')

@section('content')
    <div class="glass-card">
        <div class="glass-header d-flex justify-content-between align-items-center p-3" style="border-bottom: 1px solid rgba(255,255,255,0.06) !important;">
            <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 1rem;">
                <i class="fas fa-clipboard-list text-success mr-2"></i> Tournament Registrations
            </h3>
            <a href="{{ route('manager.browse-tournaments') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Register for Tournament
            </a>
        </div>
        <div class="card-body p-0">
            @if($registrations->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                    <h4 class="text-white">No Applications Yet</h4>
                    <p class="text-tertiary">You haven't registered for any tournaments.</p>
                    <a href="{{ route('manager.browse-tournaments') }}" class="btn btn-primary">
                        <i class="fas fa-search"></i> Browse Tournaments
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-valign-middle text-white m-0">
                        <thead>
                            <tr>
                                <th class="text-tertiary border-top-0 pl-3" style="font-size: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.06);">Tournament</th>
                                <th class="text-tertiary border-top-0" style="font-size: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.06);">Team Name</th>
                                <th class="text-tertiary border-top-0" style="font-size: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.06);">Registration Date</th>
                                <th class="text-tertiary border-top-0" style="font-size: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.06);">Payment Status</th>
                                <th class="text-tertiary border-top-0 text-right pr-3" style="font-size: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.06);">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $registration)
                                <tr>
                                    <td class="font-weight-bold pl-3 border-secondary" style="vertical-align: middle; font-size: 0.85rem;">
                                        <i class="fas fa-trophy text-warning mr-2"></i>
                                        {{ $registration->tournament->name }}
                                    </td>
                                    <td class="border-secondary" style="vertical-align: middle; font-size: 0.85rem;">{{ $registration->team->name }}</td>
                                    <td class="border-secondary text-secondary" style="vertical-align: middle; font-size: 0.85rem;">{{ $registration->created_at->format('M d, Y') }}</td>
                                    <td class="border-secondary" style="vertical-align: middle;">
                                        @if($registration->payment_status === 'paid')
                                            <span class="badge badge-success rounded-pill" style="font-size: 0.65rem; padding: 3px 8px;">
                                                <i class="fas fa-check-circle mr-1"></i> Paid
                                            </span>
                                        @elseif($registration->payment_status === 'pending')
                                            <span class="badge badge-warning rounded-pill" style="font-size: 0.65rem; padding: 3px 8px; color: #1e293b;">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge badge-danger rounded-pill" style="font-size: 0.65rem; padding: 3px 8px;">
                                                <i class="fas fa-times-circle mr-1"></i> Failed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-right pr-3 border-secondary" style="vertical-align: middle;">
                                        @if($registration->payment_status === 'paid')
                                            <a href="{{ route('manager.registrations.show', $registration->id) }}"
                                                class="btn btn-sm btn-outline-success" style="border-radius: 6px;">
                                                <i class="fas fa-eye mr-1"></i> View Ticket
                                            </a>
                                        @else
                                            <a href="{{ route('manager.tournaments.register', $registration->tournament_id) }}"
                                                class="btn btn-sm btn-warning" style="color: #1e293b; font-weight: 600; border-radius: 6px;">
                                                <i class="fas fa-credit-card mr-1"></i> Complete Payment
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection