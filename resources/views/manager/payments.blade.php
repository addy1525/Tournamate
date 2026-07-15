@extends('layouts.dashboard')

@section('title', 'Payments')
@section('page-title', 'Payment Center')

@section('content')
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pay Registration Fees</h3>
                    <div style="display: flex; gap: 10px;">
                        <i class="fab fa-cc-visa" style="font-size: 2rem;"></i>
                        <i class="fab fa-cc-mastercard" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Payments are processed securely via <strong>Stripe</strong>.
                    </div>

                    <form onsubmit="event.preventDefault(); alert('Stripe API integration would handle this!');">
                        <div class="form-group" style="margin-bottom: var(--spacing-lg);">
                            <label>Select Fee</label>
                            <select class="form-input">
                                <option>Tournament Entrance Fee (Early Bird) - RM 350.00</option>
                                <option>Tournament Entrance Fee (Standard) - RM 450.00</option>
                            </select>
                        </div>

                        <div
                            style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e9ecef; margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 10px; color: #495057;">Card Details</label>
                            <!-- Fake Stripe Element -->
                            <div
                                style="background: white; padding: 12px; border: 1px solid #ced4da; border-radius: 4px; display: flex; align-items: center;">
                                <i class="far fa-credit-card" style="color: #6c757d; margin-right: 10px;"></i>
                                <input type="text" placeholder="Card number"
                                    style="border: none; outline: none; flex-grow: 1; font-family: inherit;">
                                <input type="text" placeholder="MM / YY"
                                    style="border: none; outline: none; width: 60px; font-family: inherit;">
                                <input type="text" placeholder="CVC"
                                    style="border: none; outline: none; width: 40px; font-family: inherit; margin-left: 10px;">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="width: 100%;">Pay Now</button>
                        <!-- Stripe Powered Badge -->
                        <div style="text-align: center; margin-top: 10px; color: #6c757d; font-size: 0.8rem;">
                            <i class="fab fa-stripe"></i> Powered by Stripe
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment History</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item" style="border-bottom: 1px solid var(--color-border); padding: 10px 0;">
                            <div style="display: flex; justify-content: space-between;">
                                <strong>Team Registration Deposit</strong>
                                <span class="badge badge-success">Paid</span>
                            </div>
                            <div style="font-size: 0.85rem; color: var(--color-text-muted);">Dec 15, 2025 • RM 100.00</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection