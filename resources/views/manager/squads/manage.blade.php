@extends('layouts.dashboard')

@section('title', 'Squad Management')
@section('page-title', 'Squad Management')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Squad Management</h1>
        <p class="page-subtitle">Register players and manage your team roster</p>
    </div>

    <!-- Team Info -->
    <div class="card mb-xl">
        <div class="card-header">
            <div>
                <h2 class="card-title">Wellington Warriors</h2>
                <p class="card-subtitle">Pool A • Tournament: Summer Rugby Championship 2026</p>
            </div>
            <div class="card-actions">
                <span class="badge badge-success">Registered</span>
                <span class="badge badge-warning">Payment Pending</span>
            </div>
        </div>
    </div>

    <!-- Add Player Form -->
    <div class="card mb-xl">
        <div class="card-header">
            <h2 class="card-title">Register New Player</h2>
        </div>
        <div class="card-body">
            <form id="player-registration-form">
                @csrf
                <div class="grid grid-cols-3" style="gap: var(--spacing-lg);">
                    <div class="form-group">
                        <label class="form-label required">First Name</label>
                        <input type="text" class="form-input" name="first_name" placeholder="John" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Last Name</label>
                        <input type="text" class="form-input" name="last_name" placeholder="Smith" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Jersey Number</label>
                        <input type="number" class="form-input" name="jersey_number" min="1" max="99" placeholder="15"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-3" style="gap: var(--spacing-lg);">
                    <div class="form-group">
                        <label class="form-label required">Position</label>
                        <select class="form-select" name="position" required>
                            <option value="">Select position...</option>
                            <optgroup label="Forwards">
                                <option value="Prop">Prop</option>
                                <option value="Hooker">Hooker</option>
                                <option value="Lock">Lock</option>
                                <option value="Flanker">Flanker</option>
                                <option value="Number 8">Number 8</option>
                            </optgroup>
                            <optgroup label="Backs">
                                <option value="Scrum-half">Scrum-half</option>
                                <option value="Fly-half">Fly-half</option>
                                <option value="Centre">Centre</option>
                                <option value="Wing">Wing</option>
                                <option value="Fullback">Fullback</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Date of Birth</label>
                        <input type="date" class="form-input" name="dob" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Email</label>
                        <input type="email" class="form-input" name="email" placeholder="john.smith@example.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Medical Notes</label>
                    <textarea class="form-textarea" name="medical_notes"
                        placeholder="Any medical conditions, allergies, or special requirements..."></textarea>
                    <div class="form-help">Optional: Important for player safety</div>
                </div>

                <div style="display: flex; gap: var(--spacing-md); margin-top: var(--spacing-xl);">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Add Player
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear Form
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Team Roster -->
    <div class="card mb-xl">
        <div class="card-header">
            <div>
                <h2 class="card-title">Team Roster</h2>
                <p class="card-subtitle">15 players registered • Maximum 23 allowed</p>
            </div>
            <div class="card-actions">
                <button class="btn btn-sm btn-secondary">
                    <i class="fas fa-download"></i> Export CSV
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Player Name</th>
                            <th>Position</th>
                            <th>Age</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-bold">15</td>
                            <td>James Carter</td>
                            <td>Fullback</td>
                            <td>24</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold">10</td>
                            <td>Michael Thompson</td>
                            <td>Fly-half</td>
                            <td>26</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold">7</td>
                            <td>David Wilson</td>
                            <td>Flanker</td>
                            <td>23</td>
                            <td><span class="badge badge-warning">Medical Form Pending</span></td>
                            <td>
                                <button class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold">2</td>
                            <td>Robert Brown</td>
                            <td>Hooker</td>
                            <td>28</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Payment Section -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Team Registration Fee</h2>
        </div>
        <div class="card-body">
            <div
                style="display: flex; justify-content: space-between; align-items: center; padding: var(--spacing-xl); background: var(--color-bg-tertiary); border-radius: 8px; margin-bottom: var(--spacing-lg);">
                <div>
                    <div
                        style="font-size: var(--font-size-sm); color: var(--color-text-tertiary); margin-bottom: var(--spacing-xs);">
                        Registration Fee
                    </div>
                    <div style="font-size: var(--font-size-3xl); font-weight: 800; color: var(--color-text-primary);">
                        $250.00 <span
                            style="font-size: var(--font-size-base); font-weight: 400; color: var(--color-text-tertiary);">NZD</span>
                    </div>
                </div>
                <div>
                    <span class="badge badge-warning"
                        style="font-size: var(--font-size-sm); padding: var(--spacing-sm) var(--spacing-lg);">
                        Payment Pending
                    </span>
                </div>
            </div>

            <div class="alert alert-info">
                <div class="alert-icon"><i class="fas fa-info-circle"></i></div>
                <div class="alert-content">
                    <div class="alert-title">Secure Payment via Stripe</div>
                    <div>Your payment is processed securely through Stripe. You will be redirected to complete the
                        transaction.</div>
                </div>
            </div>

            <button class="btn btn-primary btn-lg" onclick="Tournamate.PaymentManager.initiatePayment('team-123', 250.00)">
                <i class="fas fa-credit-card"></i> Pay Now with Stripe
            </button>

            <div
                style="margin-top: var(--spacing-lg); padding-top: var(--spacing-lg); border-top: 1px solid var(--color-border);">
                <h3 style="font-size: var(--font-size-lg); font-weight: 600; margin-bottom: var(--spacing-md);">
                    Payment History
                </h3>
                <div style="color: var(--color-text-muted); text-align: center; padding: var(--spacing-xl);">
                    <i class="fas fa-receipt"
                        style="font-size: var(--font-size-2xl); opacity: 0.3; margin-bottom: var(--spacing-sm);"></i>
                    <p>No payments recorded yet</p>
                </div>
            </div>
        </div>
    </div>

@endsection