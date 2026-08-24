@extends('admin.layouts.app')

@section('title', 'Doctors')

@section('content')
<div class="section-heading">
    <h2><i class="fas fa-stethoscope" style="color:#0d6efd;margin-right:8px;"></i>Doctors</h2>
    <button class="btn btn-primary" onclick="openAddDoctorModal()">+ Add Doctor</button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Location</th>
                    <th>Phone</th>
                    <th>License Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->full_name ?? 'N/A' }}</td>
                    <td>{{ $item->specialization ?? 'N/A' }}</td>
                    <td>{{ $item->location ?? 'N/A' }}</td>
                    <td>{{ $item->phone_number ?? 'N/A' }}</td>
                    <td>{{ $item->license_number ?? 'N/A' }}</td>

                    <td>
                        <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;" onclick="editDoctor($item->id)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;color:var(--red);" onclick="deleteDoctor($item->id)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="100%" style="text-align:center;padding:40px;color:#6a7a8a;">
                        <i class="fas fa-stethoscope" style="font-size:40px;display:block;margin-bottom:10px;color:#c8d0d8;"></i>
                        No Doctors found. Click the "Add Doctor" button to create one.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modals')
@include('admin.doctors.modals')
@endsection