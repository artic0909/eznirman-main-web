@extends('account.layouts.app')

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
                        <li class="breadcrumb-item"><a href="{{ route('account.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Accountant</li>
                        <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- User Metrics -->
        <div class="col-md-3 col-sm-6">
            <div class="card bg-light-primary mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-primary mb-1 fw-bold">Supervisors</p>
                            <h3 class="mb-0 text-primary">{{ $totalSupervisors }}</h3>
                        </div>
                        <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ti ti-users fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card bg-light-secondary mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-secondary mb-1 fw-bold">Staffs</p>
                            <h3 class="mb-0 text-secondary">{{ $totalStaffs }}</h3>
                        </div>
                        <div class="bg-secondary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ti ti-briefcase fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card bg-light-warning mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-warning mb-1 fw-bold">Workers</p>
                            <h3 class="mb-0 text-warning">{{ $totalWorkers }}</h3>
                        </div>
                        <div class="bg-warning text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ti ti-tools fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card bg-light-info mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-info mb-1 fw-bold">HRs</p>
                            <h3 class="mb-0 text-info">{{ $totalHrs }}</h3>
                        </div>
                        <div class="bg-info text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ti ti-id fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial & Transaction Metrics -->
    <div class="row">
        <!-- Filter Card -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Financial Overview</h5>
                    <form action="{{ route('account.dashboard') }}" method="GET" class="d-flex gap-2">
                        <select name="year" class="form-select" style="min-width: 150px;">
                            @for ($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <p class="text-muted mb-1 fw-bold">Total Transactions</p>
                    <h2 class="mb-0">{{ number_format($totalTransactions) }}</h2>
                </div>
            </div>
            
            <div class="card bg-light-success border-success border-start border-4 mb-4">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-success mb-0 fw-bold">Yearly Payments ({{ $year }})</p>
                            <h3 class="mb-0 text-success">₹{{ number_format($totalCredits, 2) }}</h3>
                        </div>
                        <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="ti ti-arrow-down fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-light-danger border-danger border-start border-4 mb-4">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-danger mb-0 fw-bold">Yearly Debits ({{ $year }})</p>
                            <h3 class="mb-0 text-danger">₹{{ number_format($totalDebits, 2) }}</h3>
                        </div>
                        <div class="bg-danger text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="ti ti-arrow-up fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ApexChart -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header border-bottom-0 pb-0">
                    <h5 class="mb-0">Transaction Analytics ({{ $year }})</h5>
                </div>
                <div class="card-body">
                    <div id="financialChart"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->

@endsection

@push('scripts')
<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            series: [{
                name: 'Payments Transfer',
                data: @json($chartCredits)
            }, {
                name: 'Debits from Users',
                data: @json($chartDebits)
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            colors: ['#28a745', '#dc3545'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return "₹" + value.toLocaleString();
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "₹" + val.toLocaleString()
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#financialChart"), options);
        chart.render();
    });
</script>
@endpush
