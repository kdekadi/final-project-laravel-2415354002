@extends('layouts.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Subscriptions List</h2>
    <button class="btn btn-primary" onclick="openModal('addDataModal')">+ Add Data</button>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Services</th>
                <th>Services Period</th>
                <th>Status</th>
                <th style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subscriptions as $index => $sub)
            <tr>
                <td>{{ $sub['customer']['name'] ?? 'N/A' }}</td>
                <td>{{ $sub['service']['name'] ?? 'N/A' }}</td>
                <td style="color: #666; font-size: 13px;">{{ $sub['start_date'] }} to {{ $sub['end_date'] }}</td>
                <td>
                    @if($sub['status'] == 'active')
                        <span class="badge badge-active">Active</span>
                    @elseif($sub['status'] == 'trial')
                        <span class="badge" style="background: #fff3cd; color: #856404;">Trial</span>
                    @elseif($sub['status'] == 'isolir')
                        <span class="badge badge-inactive">Isolir</span>
                    @else
                        <span class="badge" style="background: #e2e3e5; color: #383d41;">Dismantle</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('dropdown-{{ $index }}')">⋮</button>
                        
                        <div id="dropdown-{{ $index }}" class="dropdown-menu">
                            <form action="{{ route('subscriptions.activate', $sub['id']) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="dropdown-item" style="color: green;">Activate</button>
                            </form>
                            <form action="{{ route('subscriptions.deactivate', $sub['id']) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="dropdown-item" style="color: orange;">Deactivate</button>
                            </form>
                            <form action="{{ route('subscriptions.trial', $sub['id']) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="dropdown-item" style="color: #856404;">Trial</button>
                            </form>
                            <form action="{{ route('subscriptions.isolir', $sub['id']) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="dropdown-item" style="color: red;">Isolir</button>
                            </form>
                            <form action="{{ route('subscriptions.dismantle', $sub['id']) }}" method="POST" onsubmit="return confirm('Dismantle subscription ini?');">
                                @csrf @method('PATCH')
                                <button type="submit" class="dropdown-item text-danger" style="font-weight: bold;">Dismantle</button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No subscriptions data available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="addDataModal" class="modal">
    <div class="modal-content">
        <h2>Add Subscription</h2>
        <form action="{{ route('subscriptions.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Customer</label>
                <select name="customer_id" class="form-control" required>
                    <option value="">Select Customer</option>
                    @foreach($customers as $c)
                        <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>Service</label>
                <select name="service_id" class="form-control" required>
                    <option value="">Select Service</option>
                    @foreach($services as $s)
                        <option value="{{ $s['id'] }}">{{ $s['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-size: 13px; font-weight: 600;">Start Date</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-size: 13px; font-weight: 600;">End Date</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="active">Active</option>
                    <option value="trial">Trial</option>
                    <option value="isolir">Isolir</option>
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal('addDataModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

@endsection