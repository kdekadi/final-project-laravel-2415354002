@extends('layouts.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Services List</h2>
    <button class="btn btn-primary" onclick="openModal('addDataModal')">+ Add Data</button>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Service Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Status</th>
                <th style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $index => $service)
            <tr>
                <td style="font-weight: 600;">{{ $service['name'] }}</td>
                <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $service['description'] }}</td>
                <td>Rp {{ number_format($service['price'], 0, ',', '.') }}</td>
                <td>
                    @if($service['status'])
                        <span class="badge badge-active">Active</span>
                    @else
                        <span class="badge badge-inactive">Inactive</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('dropdown-{{ $index }}')">⋮</button>
                        
                        <div id="dropdown-{{ $index }}" class="dropdown-menu">
                            <a href="#editModal-{{ $service['id'] }}" class="dropdown-menu-link">Edit</a>

                            @if(!$service['status'])
                                <form action="{{ route('services.activate', $service['id']) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="dropdown-item" style="color: green;">Activate</button>
                                </form>
                            @else
                                <form action="{{ route('services.deactivate', $service['id']) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="dropdown-item" style="color: orange;">Deactivate</button>
                                </form>
                            @endif
                            <form action="{{ route('services.destroy', $service['id']) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>

            <div id="editModal-{{ $service['id'] }}" class="pure-css-modal">
                <div class="modal-content" style="text-align: left;">
                    <h2>Edit Service</h2>
                    <form action="{{ route('services.update', $service['id']) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group">
                            <label>Service Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $service['name'] }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" class="form-control" value="{{ $service['price'] }}" required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3" required>{{ $service['description'] }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="active" {{ $service['status'] ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ !$service['status'] ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="modal-actions">
                            <a href="#" class="btn btn-outline" style="display: inline-block; text-align: center; line-height: 20px; text-decoration: none;">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No services data available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="addDataModal" class="modal">
    <div class="modal-content">
        <h2>Add Services</h2>
        <form action="{{ route('services.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter service name" required>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" class="form-control" placeholder="Enter price" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Enter description" required></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
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