@extends('layouts.dashboard')

@section('title', 'My Teams')
@section('page-title', 'My Teams')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Managed Teams</h3>
            <button class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Team</button>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Team Name</th>
                        <th>Category</th>
                        <th>Players</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class="fas fa-shield-alt" style="color: var(--color-electric-blue);"></i> Thunder Bolts</td>
                        <td>U-21</td>
                        <td>22 / 24</td>
                        <td><span class="badge badge-success">Active</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary">Manage</button>
                        </td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-shield-alt" style="color: var(--color-secondary);"></i> Junior Bolts</td>
                        <td>U-18</td>
                        <td>15 / 24</td>
                        <td><span class="badge badge-warning">Registration Open</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary">Manage</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection