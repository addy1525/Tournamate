@extends('layouts.dashboard')

@section('title', 'Payment History')
@section('page-title', 'Payment History')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Payment Records & Receipts</h3>
        </div>
        <div class="card-body">
            @if($payments->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-file-invoice-dollar fa-4x text-muted mb-3"></i>
                    <h4>No Payment History</h4>
                    <p class="text-muted">You haven't made any payments yet.</p>
                </div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Receipt #</th>
                            <th>Tournament</th>
                            <th>Team</th>
                            <th>Payment Date</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>
                                    <span class="text-monospace">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <i class="fas fa-trophy" style="color: var(--color-rugby-green);"></i>
                                    {{ $payment->tournament->name }}
                                </td>
                                <td>{{ $payment->team->name }}</td>
                                <td>{{ $payment->registered_at ? $payment->registered_at->format('M d, Y H:i') : 'N/A' }}</td>
                                <td>
                                    <strong>RM {{ number_format($payment->amount_paid, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <i class="fas fa-credit-card"></i> Stripe
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('manager.registrations.show', $payment->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-receipt"></i> View Receipt
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Total Paid:</strong></td>
                            <td colspan="3">
                                <strong>RM {{ number_format($payments->sum('amount_paid'), 2) }}</strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            @endif
        </div>
    </div>
@endsection