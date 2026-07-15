@extends('layouts.dashboard')

@section('title', 'Squad Registration')
@section('page-title', 'Squad Registration')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Register New Player</h3>
        </div>
        <div class="card-body">
            <form>
                <div class="grid grid-cols-2" style="gap: var(--spacing-lg);">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: var(--spacing-xs);">Full Name</label>
                        <input type="text" class="form-input" placeholder="e.g. John Doe">
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: var(--spacing-xs);">IC / Passport</label>
                        <input type="text" class="form-input" placeholder="e.g. 900101-10-1234">
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: var(--spacing-xs);">Jersey Number</label>
                        <input type="number" class="form-input" placeholder="e.g. 10">
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: var(--spacing-xs);">Position</label>
                        <select class="form-input">
                            <option>Forward</option>
                            <option>Back</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-top: var(--spacing-lg);">
                    <label style="display: block; margin-bottom: var(--spacing-xs);">Medical Information</label>
                    <textarea class="form-input" rows="3" placeholder="Allergies, conditions, etc."></textarea>
                </div>

                <div style="margin-top: var(--spacing-xl); text-align: right;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Register Player</button>
                </div>
            </form>
        </div>
    </div>
@endsection