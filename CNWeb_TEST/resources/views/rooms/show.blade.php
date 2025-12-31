@extends('layouts.app')

@section('title', 'Room ' . $room->room_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5><i class="bi bi-door-open"></i> Room Details</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr><th width="30%">ID:</th><td>{{ $room->id }}</td></tr>
                    <tr><th>Room Number:</th><td><span class="badge bg-dark">{{ $room->room_number }}</span></td></tr>
                    <tr><th>Room Type:</th><td>{{ $room->room_type }}</td></tr>
                    <tr><th>Price/Night:</th><td>${{ number_format($room->price_per_night, 2) }}</td></tr>
                    <tr><th>Guest:</th><td>{{ $room->guest->fullname }} ({{ $room->guest->guest_code }})</td></tr>
                    <tr><th>Check In:</th><td>{{ $room->check_in_date }}</td></tr>
                    <tr><th>Check Out:</th><td>{{ $room->check_out_date ?? 'N/A' }}</td></tr>
                    <tr>
                        <th>Status:</th>
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
                    </tr>
                    <tr><th>Created:</th><td>{{ $room->created_at }}</td></tr>
                    <tr><th>Updated:</th><td>{{ $room->updated_at }}</td></tr>
                </table>
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection