@extends('layouts.app')

@section('title', 'Rooms')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-door-open"></i> Rooms</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="bi bi-plus-lg"></i> Add Room
    </button>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Room #</th>
                    <th>Type</th>
                    <th>Price/Night</th>
                    <th>Guest</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $room)
                    <tr>
                        <td>{{ $room->id }}</td>
                        <td><span class="badge bg-dark">{{ $room->room_number }}</span></td>
                        <td>{{ $room->room_type }}</td>
                        <td>${{ number_format($room->price_per_night, 2) }}</td>
                        <td>{{ $room->guest->guest_name }}</td>
                        <td>{{ $room->check_in_date }}</td>
                        <td>{{ $room->check_out_date ?? 'N/A' }}</td>
                        <td>
                            @php
                                $statusColor = match($room->status) {
                                    'Available' => 'success',
                                    'Occupied' => 'danger',
                                    'Maintenance' => 'warning',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }}">{{ $room->status }}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                    data-bs-target="#editModal{{ $room->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $room->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $room->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('rooms.update', $room) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title"><i class="bi bi-pencil"></i> Edit Room</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Guest *</label>
                                            <select name="guest_id" class="form-select" required>
                                                @foreach($guests as $guest)
                                                    <option value="{{ $guest->id }}" 
                                                        {{ $room->guest_id == $guest->id ? 'selected' : '' }}>
                                                        {{ $guest->guest_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Room Number *</label>
                                                <input type="text" name="room_number" class="form-control" 
                                                       value="{{ $room->room_number }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Room Type *</label>
                                                <select name="room_type" class="form-select" required>
                                                    @foreach(['Single', 'Double', 'Suite'] as $type)
                                                        <option value="{{ $type }}" 
                                                            {{ $room->room_type == $type ? 'selected' : '' }}>
                                                            {{ $type }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Price per Night *</label>
                                            <input type="number" step="0.01" name="price_per_night" 
                                                   class="form-control" value="{{ $room->price_per_night }}" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Check In *</label>
                                                <input type="date" name="check_in_date" class="form-control" 
                                                       value="{{ $room->check_in_date }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Check Out</label>
                                                <input type="date" name="check_out_date" class="form-control" 
                                                       value="{{ $room->check_out_date }}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status *</label>
                                            <select name="status" class="form-select" required>
                                                @foreach(['Available', 'Occupied', 'Maintenance'] as $status)
                                                    <option value="{{ $status }}" 
                                                        {{ $room->status == $status ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-warning">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal{{ $room->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('rooms.destroy', $room) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title"><i class="bi bi-trash"></i> Delete Room</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete room <strong>{{ $room->room_number }}</strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No rooms found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $rooms->links() }}
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-lg"></i> Add New Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Guest *</label>
                        <select name="guest_id" class="form-select" required>
                            <option value="">-- Select Guest --</option>
                            @foreach($guests as $guest)
                                <option value="{{ $guest->id }}">
                                    {{ $guest->guest_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room Number *</label>
                            <input type="text" name="room_number" class="form-control" 
                                   placeholder="e.g., Room-101" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room Type *</label>
                            <select name="room_type" class="form-select" required>
                                <option value="Single">Single</option>
                                <option value="Double">Double</option>
                                <option value="Suite">Suite</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price per Night *</label>
                        <input type="number" step="0.01" name="price_per_night" 
                               class="form-control" placeholder="e.g., 99.99" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Check In *</label>
                            <input type="date" name="check_in_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Check Out</label>
                            <input type="date" name="check_out_date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="Available">Available</option>
                            <option value="Occupied">Occupied</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection