@extends('admin.layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <!-- Add any page-specific styles here -->
@endpush

@section('content')
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Dashboard</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route(getRoutePrefix() . 'dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Admin</li>
                        <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row g-4">
        @if(\Illuminate\Support\Facades\Auth::guard('web')->check() && isset($assignedSites))
            <div class="col-12 mt-4">
                <div class="card bg-primary text-white shadow-sm border-0" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                    <div class="card-body p-5 text-center">
                        <h2 class="text-white fw-bold mb-3">Welcome Back, Coordinator!</h2>
                        <p class="fs-5 opacity-75">Here are the sites currently assigned to you.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-12 mt-4">
                <h5 class="mb-3 fw-bold text-dark">Your Assigned Sites</h5>
                <div class="row">
                    @forelse($assignedSites as $site)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 border-start border-primary border-4">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">{{ $site->site_name ?? 'N/A' }}</h5>
                                    <p class="card-text text-muted mb-0"><strong>Code:</strong> {{ $site->site_code ?? 'N/A' }}</p>
                                    <p class="card-text text-muted"><strong>Address:</strong> {{ $site->location ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning">No sites have been assigned to you yet.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        @if(!\Illuminate\Support\Facades\Auth::guard('web')->check())
        <!-- HR Summary -->
        <div class="col-12 mt-4">
            <div class="d-flex align-items-center mb-1">
                <div class="bg-primary rounded-circle p-2 me-2" style="width: 10px; height: 10px;"></div>
                <h5 class="mb-0 fw-bold text-dark">Human Resource Overview</h5>
            </div>
        </div>
        
        <div class="col-md-6 col-xl-3">
            <div class="card bg-primary shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-white opacity-75 mb-1 fw-medium">Total Personnel</p>
                            <h2 class="text-white mb-0 fw-bold">{{ $hrCounts['total'] }}</h2>
                        </div>
                        <div class="avtar avtar-l bg-white rounded-3 shadow-sm">
                            <i class="ti ti-users text-primary f-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-m bg-primary text-white rounded-3 flex-shrink-0">
                            <i class="ti ti-user f-20"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-0 small text-uppercase fw-bold ls-1">Workers</p>
                            <h4 class="mb-0 fw-bold">{{ $hrCounts['worker'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-m bg-info text-white rounded-3 flex-shrink-0">
                            <i class="ti ti-user f-20"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-0 small text-uppercase fw-bold ls-1">Supervisors</p>
                            <h4 class="mb-0 fw-bold">{{ $hrCounts['supervisor'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-m bg-success text-white rounded-3 flex-shrink-0">
                            <i class="ti ti-briefcase f-20"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-0 small text-uppercase fw-bold ls-1">Staff / HR</p>
                            <h4 class="mb-0 fw-bold">{{ $hrCounts['staff'] + $hrCounts['hr'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Machinery Summary -->
        <div class="col-12 mt-5">
            <div class="d-flex align-items-center mb-1">
                <div class="bg-warning rounded-circle p-2 me-2" style="width: 10px; height: 10px;"></div>
                <h5 class="mb-0 fw-bold text-dark">Machinery & Tools Status</h5>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100 bg-light-primary bg-opacity-10 border-start border-4 border-primary">
                        <div class="card-body py-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase mb-2 small fw-bold">Total Machinary</h6>
                                <h2 class="fw-bold mb-0 text-primary">{{ $machineryCounts['total'] }}</h2>
                                <div class="mt-1 small text-muted">Registered Assets</div>
                            </div>
                            <div class="avtar avtar-l bg-primary text-white rounded-circle">
                                <i class="ti ti-tools f-24"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100 border-start border-4 border-success">
                        <div class="card-body py-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase mb-2 small fw-bold">Running</h6>
                                <h2 class="fw-bold mb-0 text-success">{{ $machineryCounts['running'] }}</h2>
                                <div class="mt-1 small text-muted">Operational on sites</div>
                            </div>
                            <div class="avtar avtar-l bg-light-success text-success rounded-circle">
                                <i class="ti ti-circle-check f-24"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100 border-start border-4 border-warning">
                        <div class="card-body py-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase mb-2 small fw-bold">Under Repair</h6>
                                <h2 class="fw-bold mb-0 text-warning">{{ $machineryCounts['repair'] }}</h2>
                                <div class="mt-1 small text-muted">Maintenance active</div>
                            </div>
                            <div class="avtar avtar-l bg-light-warning text-warning rounded-circle">
                                <i class="ti ti-settings-automation f-24"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100 border-start border-4 border-danger">
                        <div class="card-body py-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase mb-2 small fw-bold">Damaged</h6>
                                <h2 class="fw-bold mb-0 text-danger">{{ $machineryCounts['damage'] }}</h2>
                                <div class="mt-1 small text-muted">Needs Attention</div>
                            </div>
                            <div class="avtar avtar-l bg-light-danger text-danger rounded-circle">
                                <i class="ti ti-alert-triangle f-24"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100 border-start border-4 border-secondary">
                        <div class="card-body py-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase mb-2 small fw-bold">Missing</h6>
                                <h2 class="fw-bold mb-0 text-secondary">{{ $machineryCounts['missing'] }}</h2>
                                <div class="mt-1 small text-muted">Lost or Stolen</div>
                            </div>
                            <div class="avtar avtar-l bg-light-secondary text-secondary rounded-circle">
                                <i class="ti ti-help f-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="mb-0 fw-bold">Condition Distribution</h6>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div id="machinery-pie-chart" style="min-height: 250px; width: 100%;"></div>
                    <div class="row g-1 w-100 mt-3 text-center">
                        <div class="col-3 border-end px-0">
                            <h6 class="mb-0 fw-bold text-success">{{ round(($machineryCounts['running'] / max($machineryCounts['total'], 1)) * 100) }}%</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Running</small>
                        </div>
                        <div class="col-3 border-end px-0">
                            <h6 class="mb-0 fw-bold text-warning">{{ round(($machineryCounts['repair'] / max($machineryCounts['total'], 1)) * 100) }}%</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Repair</small>
                        </div>
                        <div class="col-3 border-end px-0">
                            <h6 class="mb-0 fw-bold text-danger">{{ round(($machineryCounts['damage'] / max($machineryCounts['total'], 1)) * 100) }}%</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Damage</small>
                        </div>
                        <div class="col-3 px-0">
                            <h6 class="mb-0 fw-bold text-secondary">{{ round(($machineryCounts['missing'] / max($machineryCounts['total'], 1)) * 100) }}%</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Missing</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operations Summary -->
        <div class="col-12 mt-5">
            <div class="d-flex align-items-center mb-1">
                <div class="bg-info rounded-circle p-2 me-2" style="width: 10px; height: 10px;"></div>
                <h5 class="mb-0 fw-bold text-dark">Operational Overview</h5>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-xl bg-light-primary text-primary rounded-circle">
                            <i class="ti ti-building-skyscraper f-30"></i>
                        </div>
                        <div class="ms-4">
                            <h3 class="mb-0 fw-bold">{{ $siteCount }}</h3>
                            <p class="text-muted mb-0">Active Working Sites</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-xl bg-light-success text-success rounded-circle">
                            <i class="ti ti-shopping-cart f-30"></i>
                        </div>
                        <div class="ms-4">
                            <h3 class="mb-0 fw-bold">{{ $materialCounts['purchases'] }}</h3>
                            <p class="text-muted mb-0">Material Purchases</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-xl bg-light-warning text-warning rounded-circle">
                            <i class="ti ti-package f-30"></i>
                        </div>
                        <div class="ms-4">
                            <h3 class="mb-0 fw-bold">{{ $materialCounts['consumes'] }}</h3>
                            <p class="text-muted mb-0">Stock Consumptions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Sections -->
        <div class="col-lg-7 mt-5">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="ti ti-replace me-2"></i>Recent Machinery Transfers</h5>
                <a href="{{ route(getRoutePrefix() . 'machinery.transfer-machinery') }}" class="btn btn-sm btn-light-primary">View All</a>
            </div>
            <div class="card shadow-sm border-0 tbl-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Asset Info</th>
                                    <th>To Site</th>
                                    <th>Date</th>
                                    <th class="pe-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransfers as $transfer)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avtar avtar-s bg-light-primary text-primary me-2">
                                                <i class="ti ti-tools"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $transfer->machinery->name }}</h6>
                                                <small class="text-muted">{{ $transfer->machinery->machine_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $transfer->toSite->site_name }}</span>
                                        <br><small class="text-muted">{{ $transfer->toSite->site_code }}</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($transfer->transfer_date)->format('d M, Y') }}</td>
                                    <td class="pe-4 text-center">
                                        <span class="badge bg-light-success text-success px-3">Completed</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-5 text-muted">No recent transfers recorded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if(!\Illuminate\Support\Facades\Auth::guard('web')->check())
        <div class="col-lg-5 mt-5">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="ti ti-users me-2"></i>New Personnel</h5>
                <a href="{{ route(getRoutePrefix() . 'hrmanagement.index') }}" class="btn btn-sm btn-light-primary">Manage</a>
            </div>
            <div class="card shadow-sm border-0 tbl-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Name</th>
                                    <th>Role</th>
                                    <th class="pe-4">Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers as $user)
                                <tr>
                                    <td class="ps-4">
                                        <h6 class="mb-0 fw-bold">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->code }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light-secondary text-dark text-uppercase small">{{ $user->role }}</span>
                                    </td>
                                    <td class="pe-4">{{ $user->created_at->format('d M, Y') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-5 text-muted">No recent personnel.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Machinery Pie Chart
            var options = {
                chart: {
                    height: 280,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false
                },
                series: [
                    {{ $machineryCounts['running'] }}, 
                    {{ $machineryCounts['repair'] }}, 
                    {{ $machineryCounts['damage'] }},
                    {{ $machineryCounts['missing'] }}
                ],
                labels: ['Running', 'Repairing', 'Damaged', 'Missing'],
                colors: ['#2ca87f', '#e58a00', '#dc2626', '#6c757d'],
                legend: {
                    show: false,
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                name: {
                                    show: true
                                },
                                value: {
                                    show: true
                                }
                            }
                        }
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#machinery-pie-chart"), options);
            chart.render();
        });
    </script>
@endpush