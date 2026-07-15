@extends('layouts.dashboard')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Team</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Column: Team Details -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Team Information</h3>
                        </div>
                        <form action="{{ route('manage-teams.update', $team->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Team Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $team->name }}"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="manager_id">Manager</label>
                                    <select class="form-control" id="manager_id" name="manager_id" required>
                                        @foreach($managers as $manager)
                                            <option value="{{ $manager->id }}" {{ $team->manager_id == $manager->id ? 'selected' : '' }}>
                                                {{ $manager->name }} ({{ $manager->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="phone_number">Contact Number</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number"
                                        value="{{ $team->phone_number }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address"
                                        rows="3">{{ $team->address }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="logo">Logo</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="logo" name="logo">
                                            <label class="custom-file-label" for="logo">Choose new logo</label>
                                        </div>
                                    </div>
                                    @if($team->logo)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($team->logo) }}" alt="Current Logo" width="100">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update Details</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Payment Management -->
                <div class="col-md-6">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Payment Management</h3>
                        </div>
                        <form action="{{ route('manage-teams.updatePayment', $team->id) }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="payment_status">Payment Status</label>
                                    <select class="form-control" id="payment_status" name="payment_status">
                                        <option value="unpaid" {{ $team->payment_status == 'unpaid' ? 'selected' : '' }}>
                                            Unpaid</option>
                                        <option value="partial" {{ $team->payment_status == 'partial' ? 'selected' : '' }}>
                                            Partial</option>
                                        <option value="paid" {{ $team->payment_status == 'paid' ? 'selected' : '' }}>Paid
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="amount_paid">Amount Paid (RM)</label>
                                    <input type="number" step="0.01" class="form-control" id="amount_paid"
                                        name="amount_paid" value="{{ $team->amount_paid }}">
                                </div>
                                <button type="submit" class="btn btn-success">Update Payment Status</button>
                            </div>
                        </form>

                        <div class="card-footer bg-light">
                            <label>Receipt Upload</label>
                            <form action="{{ route('manage-teams.uploadReceipt', $team->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="receipt" name="receipt" required>
                                        <label class="custom-file-label" for="receipt">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit">Upload</button>
                                    </div>
                                </div>
                            </form>
                            @if($team->receipt_path)
                                <div class="mt-2">
                                    <a href="{{ Storage::url($team->receipt_path) }}" target="_blank"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-file-download"></i> View Current Receipt
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection