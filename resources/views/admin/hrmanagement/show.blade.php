@extends('admin.layouts.app')

@section('title', 'View ' . ucfirst($user->role))

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">View {{ ucfirst($user->role) }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.hrmanagement.index', ['role' => $user->role]) }}">HR Management</a></li>
                    <li class="breadcrumb-item" aria-current="page">View {{ ucfirst($user->role) }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('assets/images/logo.gif') }}" class="img-fluid rounded mb-3" style="max-height: 250px;">
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->code }} | {{ ucfirst($user->role) }}</p>
                <span class="badge {{ $user->status == 'active' ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }} fs-6">
                    {{ ucfirst($user->status) }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Detailed Information</h5>
                <a href="{{ route('admin.hrmanagement.edit', $user->id) }}" class="btn btn-primary btn-sm">
                    <i class="ti ti-edit me-1"></i> Edit
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <th width="30%">Code:</th>
                                <td>{{ $user->code }}</td>
                            </tr>
                            <tr>
                                <th width="30%">Name:</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th width="30%">Email:</th>
                                <td>{{ $user->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Mobile:</th>
                                <td>{{ $user->mobile }}</td>
                            </tr>
                            <tr>
                                <th>Joining Date:</th>
                                <td>{{ \Carbon\Carbon::parse($user->joining_date)->format('M d, Y') }}</td>
                            </tr>
                            @if($user->role == 'worker')
                            <tr>
                                <th>Work Skill:</th>
                                <td>{{ $user->skill->name ?? 'N/A' }}</td>
                            </tr>
                            @else
                            <tr>
                                <th>Designation:</th>
                                <td>{{ $user->designation->name ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Working Site:</th>
                                <td>{{ $user->site->site_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{ $user->current_address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>ESI Number:</th>
                                <td>{{ $user->esi_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>PF Number:</th>
                                <td>{{ $user->pf_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Bank Details:</th>
                                <td>{{ $user->bank_account_no ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="fw-bold d-block mb-2">PAN Card</label>
                        @if($user->pancard)
                            <a href="{{ asset('storage/'.$user->pancard) }}" target="_blank" class="btn btn-outline-info btn-sm w-100">
                                <i class="ti ti-file-text me-1"></i> View PAN Card
                            </a>
                        @else
                            <div class="alert alert-light border text-center mb-0">Not Uploaded</div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold d-block mb-2">Aadhar Card</label>
                        @if($user->adhaarcard)
                            <a href="{{ asset('storage/'.$user->adhaarcard) }}" target="_blank" class="btn btn-outline-info btn-sm w-100">
                                <i class="ti ti-id me-1"></i> View Aadhar Card
                            </a>
                        @else
                            <div class="alert alert-light border text-center mb-0">Not Uploaded</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('admin.hrmanagement.index', ['role' => $user->role]) }}" class="btn btn-light border">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection
