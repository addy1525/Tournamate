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
                                    <td class="border-secondary" style="vertical-align: middle; font-size: 0.85rem;">
                                        <a href="{{ route('manager.teams.detail', $registration->team_id) }}" class="text-white font-weight-bold text-decoration-none" title="Manage Team Details & Roster" style="transition: color 0.2s;">
                                            <i class="fas fa-shield-alt text-success mr-1"></i> {{ $registration->team->name }}
                                        </a>
                                    </td>
                                    <td class="border-secondary text-secondary" style="vertical-align: middle; font-size: 0.85rem;">{{ $registration->created_at->format('M d, Y') }}</td>
                                    <td class="border-secondary" style="vertical-align: middle;">
                                        @if($registration->payment_status === 'paid')
                                            <span class="badge badge-success rounded-pill" style="font-size: 0.65rem; padding: 3px 8px;">
                                                <i class="fas fa-check-circle mr-1"></i> Paid
                                            </span>
                                        @elseif($registration->receipt_path)
                                            <span class="badge badge-info rounded-pill" style="font-size: 0.65rem; padding: 3px 8px; background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3);">
                                                <i class="fas fa-spinner fa-spin mr-1"></i> Pending Verification
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
                                        <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                            <a href="{{ route('manager.teams.detail', $registration->team_id) }}"
                                                class="btn btn-sm btn-primary font-weight-bold" style="border-radius: 6px; padding: 5px 12px; font-size: 0.78rem; background: linear-gradient(135deg, #0084ff, #0056b3); border: none; box-shadow: 0 2px 6px rgba(0, 132, 255, 0.3);">
                                                <i class="fas fa-users-cog mr-1"></i> Manage Team
                                            </a>
                                            @if($registration->payment_status === 'paid')
                                                <a href="{{ route('manager.registrations.show', $registration->id) }}"
                                                    class="btn btn-sm btn-outline-success" style="border-radius: 6px; padding: 5px 12px; font-size: 0.78rem;">
                                                    <i class="fas fa-eye mr-1"></i> Ticket
                                                </a>
                                            @else
                                                @if(!$registration->receipt_path)
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                        style="border-radius: 6px; padding: 5px 12px; font-size: 0.78rem;"
                                                        data-toggle="modal" 
                                                        data-target="#uploadReceiptModal-{{ $registration->id }}">
                                                        <i class="fas fa-upload mr-1"></i> Upload Receipt
                                                    </button>
                                                @endif
                                                <a href="{{ route('manager.tournaments.register', $registration->tournament_id) }}"
                                                    class="btn btn-sm btn-warning" style="color: #1e293b; font-weight: 600; border-radius: 6px; padding: 5px 12px; font-size: 0.78rem;">
                                                    <i class="fas fa-credit-card mr-1"></i> Pay Online
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                @if($registration->payment_status !== 'paid' && !$registration->receipt_path)
                                    <!-- Upload Receipt Modal -->
                                    <div class="modal fade" id="uploadReceiptModal-{{ $registration->id }}" tabindex="-1" role="dialog" aria-labelledby="uploadReceiptModalLabel-{{ $registration->id }}" aria-hidden="true" style="z-index: 10000;">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content" style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; color: #fff;">
                                                <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding: 1.25rem 1.5rem;">
                                                    <h5 class="modal-title font-weight-bold" id="uploadReceiptModalLabel-{{ $registration->id }}">
                                                        <i class="fas fa-file-invoice-dollar text-success mr-2"></i>
                                                        Upload Payment Receipt
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.7; border: none; background: transparent; font-size: 1.5rem;">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('manager.registrations.upload-receipt', $registration->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body" style="padding: 1.5rem;">
                                                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 1rem; margin-bottom: 1.25rem;">
                                                            <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: rgba(255,255,255,0.45); margin-bottom: 6px;">Organizer Bank Details</div>
                                                            <div style="font-size: 0.95rem; font-weight: 700; color: #fff;">Maybank Islamic</div>
                                                            <div style="font-size: 1.1rem; font-weight: 800; color: #34d399; margin: 2px 0;">5640 1234 5678</div>
                                                            <div style="font-size: 0.8rem; color: rgba(255,255,255,0.6);">TOURNAMATE VENTURES SDN BHD</div>
                                                            <div style="margin-top: 8px; font-size: 0.8rem; color: rgba(255,255,255,0.45);">
                                                                Amount Due: <strong style="color: #fff;">RM {{ number_format($registration->tournament->fee ?? 250, 2) }}</strong>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="receipt" style="font-weight: 600; font-size: 0.85rem; color: rgba(255,255,255,0.7); display: block; margin-bottom: 8px;">Upload Bank Transfer Receipt (PDF, PNG, JPG)</label>
                                                            <input type="file" name="receipt" class="form-control-file" accept=".pdf,.png,.jpg,.jpeg" required style="color: #fff; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 10px; border-radius: 8px; width: 100%;">
                                                            <small class="form-text text-muted" style="margin-top: 6px; font-size: 0.72rem;">Maximum file size: 5MB</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.08); padding: 1rem 1.5rem;">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success btn-sm font-weight-bold" style="box-shadow: 0 4px 12px rgba(0, 168, 107, 0.2);">
                                                            <i class="fas fa-paper-plane mr-1"></i> Submit Receipt
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection