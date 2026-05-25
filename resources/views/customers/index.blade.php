@extends('layouts.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Customers List</h2>
    <button class="btn btn-primary" onclick="openModal('addDataModal')">+ Add Data</button>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $index => $customer)
            <tr>
                <td>{{ $customer['customer_id'] ?? '-' }}</td>
                <td>{{ $customer['name'] }}</td>
                <td>{{ $customer['email'] }}</td>
                <td>
                    @if($customer['status'])
                        <span class="badge badge-active">Active</span>
                    @else
                        <span class="badge badge-inactive">Inactive</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('dropdown-{{ $index }}')">⋮</button>
                        
                        <div id="dropdown-{{ $index }}" class="dropdown-menu">
                            <button onclick="openModal('editModal-{{ $customer['id'] }}')" class="dropdown-menu-link" style="background: none; border: none; width: 100%; text-align: left; padding: 8px 12px; cursor: pointer;">Edit</button>

                            @if(!$customer['status'])
                                <form action="{{ route('customers.activate', $customer['id']) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="dropdown-item" style="color: green;">Activate</button>
                                </form>
                            @else
                                <form action="{{ route('customers.deactivate', $customer['id']) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="dropdown-item" style="color: orange;">Deactivate</button>
                                </form>
                            @endif
                            <form action="{{ route('customers.destroy', $customer['id']) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>

            <div id="editModal-{{ $customer['id'] }}" class="pure-css-modal modal">
                <div class="modal-content" style="text-align: left;">
                    <h2>Edit Customer</h2>
                    <form action="{{ route('customers.update', $customer['id']) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group">
                            <label>Customer ID</label>
                            <input type="text" name="customer_id" class="form-control" value="{{ old('customer_id', $customer['customer_id']) }}" required>
                            @error('customer_id') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Customer Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $customer['name']) }}" required>
                            @error('name') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $customer['email']) }}" required>
                            @error('email') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer['phone'] ?? '') }}" required>
                            @error('phone') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $customer['address'] ?? '') }}" required>
                            @error('address') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="active" {{ $customer['status'] ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ !$customer['status'] ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="modal-actions">
                            <button type="button" class="btn btn-outline" onclick="closeModal('editModal-{{ $customer['id'] }}')">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No customers data available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="addDataModal" class="modal">
    <div class="modal-content">
        <h2>Add Customer</h2>
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Customer ID</label>
                <input type="text" name="customer_id" class="form-control" placeholder="Enter ID" value="{{ old('customer_id') }}" required>
                @error('customer_id') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter name" value="{{ old('name') }}" required>
                @error('name') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}" required>
                @error('email') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" placeholder="Enter phone number" value="{{ old('phone') }}" required>
                @error('phone') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" class="form-control" placeholder="Enter address" value="{{ old('address') }}" required>
                @error('address') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal('addDataModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Otomatis buka kembali modal jika validasi API gagal setelah reload
    @if(session('open_modal'))
        openModal("{{ session('open_modal') }}");
    @endif
</script>

@endsection