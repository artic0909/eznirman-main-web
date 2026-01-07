@extends('admin.layouts.app')

@section('title', 'Add Machinery & Tools')

@push('styles')
    <!-- Add any page-specific styles here -->
@endpush

@section('content')

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-10">
                    <div class="page-header-title">
                        <h5 class="m-b-10">All Machine</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Machinery & Tools</a></li>
                        <li class="breadcrumb-item" aria-current="page">All Machinery</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="page-header-actions col-md-2 text-end">
            <button class="btn btn-success btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addMachineModal">
                <i class="ti ti-plus me-2"></i><span>Add Data</span>
            </button>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->



    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">All Machinery</h5>
                    <div class="d-flex gap-3">
                        <button class="btn btn-outline-warning text-primary btn-sm" id="exportData">Export</button>
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="bulkActionDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false" disabled>
                                <i class="ti ti-settings me-1"></i>
                                Bulk Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="bulkActionDropdown">
                                <li>
                                    <a class="dropdown-item text-danger" href="#" id="bulkDelete">
                                        <i class="ti ti-trash me-2"></i>Delete Selected
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-success" href="#" id="bulkRunning">
                                        <i class="ti ti-check me-2"></i>Running Selected
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-warning" href="#" id="bulkRepair">
                                        <i class="ti ti-check me-2"></i>Repair Selected
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" id="bulkDamage">
                                        <i class="ti ti-x me-2"></i>Damage Selected
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                            <label class="form-check-label" for="selectAll"></label>
                                        </div>
                                    </th>
                                    <th>Image</th>
                                    <th>Entry Date</th>
                                    <th>Machine Code</th>
                                    <th>Machine Name</th>
                                    <th>Category</th>
                                    <th>Site Code/Name</th>
                                    <th>Condition</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input row-checkbox" type="checkbox" value="1"
                                                id="check1">
                                            <label class="form-check-label" for="check1"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">

                                            <div class="flex-grow-1 ms-3">
                                                <img src="../../assets/images/no-img.jpg" alt="" class="mb-0 rounded"
                                                    width="50">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">Dec 15, 2024</span>
                                    </td>

                                    <td>
                                        <span class="text-muted">CL061</span>
                                    </td>

                                    <td>
                                        <span class="text-muted">INGCO Cordless Drill</span>
                                    </td>

                                    <td>
                                        <span class="text-muted">Battery Drill</span>
                                    </td>

                                    <td>
                                        <p class="text-muted m-0">10116</p>
                                        <p class="text-muted m-0">NMDC</p>
                                    </td>

                                    <td>
                                        <span class="text-success">Running</span> &nbsp;
                                        <button class="btn btn-sm btn-outline-warning text-primary" title="Edit"
                                            data-bs-toggle="modal" data-bs-target="#changeConditionModal">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <div class="m-0">

                                            <div class="d-flex justify-content-center gap-2 mb-2">
                                                <button class="btn btn-sm btn-light-info border" title="View"
                                                    data-bs-toggle="modal" data-bs-target="#viewMachineModal">
                                                    <i class="ti ti-eye"></i>
                                                </button>

                                                <button class="btn btn-sm btn-light-primary border" title="Edit"
                                                    data-bs-toggle="modal" data-bs-target="#editMachineModal">
                                                    <i class="ti ti-pencil"></i>
                                                </button>
                                            </div>

                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-sm btn-light-success border" title="Transfer"
                                                    data-bs-toggle="modal" data-bs-target="#transferMachineModal">
                                                    <i class="ti ti-share"></i>
                                                </button>

                                                <button class="btn btn-sm btn-light-danger border" title="Delete"
                                                    data-bs-toggle="modal" data-bs-target="#deleteCategoryModal">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                            <!-- pagination -->
                            <tfoot>
                                <tr>
                                    <td colspan="12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted">Showing 1 to 10 of 50 entries</div>
                                            <div class="d-flex align-items-center gap-2">
                                                <button class="btn btn-sm btn-outline-primary" disabled>Prev</button>
                                                <input type="number" class="form-control form-control-sm text-center"
                                                    style="width: 60px;" value="1" min="1" max="5">
                                                <span class="text-muted">/</span>
                                                <input type="number" class="form-control form-control-sm text-center"
                                                    style="width: 60px;" value="5" readonly>
                                                <button class="btn btn-sm btn-outline-primary">Next</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->


    <!-- All Modals -->

    <!-- Add Machine Modal -->
    <div class="modal fade" id="addMachineModal" tabindex="-1" aria-labelledby="addMachineModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMachineModalLabel">Add Machine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Entry Date -->
                        <div class="col-md-6 mb-3">
                            <label for="entryDate" class="form-label">Entry Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="entryDate">
                        </div>

                        <!-- Source -->
                        <div class="col-md-6 mb-3">
                            <label for="source" class="form-label">Source<span class="text-danger">*</span></label>
                            <select class="form-select" id="source">
                                <option value="">--Select Source--</option>
                                <option value="purchase" selected>Purchase</option>
                                <option value="rental">Rental</option>
                                <option value="lease">Lease</option>
                            </select>
                        </div>

                        <!-- Site Code -->
                        <div class="col-md-6 mb-3">
                            <label for="siteCode" class="form-label">Site Code<span class="text-danger">*</span></label>
                            <select class="form-select" id="siteCode">
                                <option value="">--Select Site Code--</option>
                                <option value="site1">Site 1 - NMDC</option>
                            </select>
                        </div>

                        <!-- Site Name -->
                        <div class="col-md-6 mb-3">
                            <label for="siteName" class="form-label">Site Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="siteName" placeholder="By default" readonly>
                        </div>

                        <!-- Machine Name -->
                        <div class="col-md-6 mb-3">
                            <label for="machineName" class="form-label">Machine Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="machineName" placeholder="Enter Machine Name">
                        </div>

                        <!-- Category -->
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category<span class="text-danger">*</span></label>
                            <select class="form-select" id="category">
                                <option value="">--Select Category--</option>
                                <option value="excavator">Excavator</option>
                                <option value="bulldozer">Bulldozer</option>
                                <option value="crane">Crane</option>
                            </select>
                        </div>

                        <!-- Machine Code -->
                        <div class="col-md-6 mb-3">
                            <label for="machineCode" class="form-label">Machine Code<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="machineCode" placeholder="Enter Machine Code">
                        </div>

                        <!-- Model Number -->
                        <div class="col-md-6 mb-3">
                            <label for="modelNumber" class="form-label">Model Number<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modelNumber" placeholder="Enter Model Number">
                        </div>

                        <!-- Machine Condition -->
                        <div class="col-md-6 mb-3">
                            <label for="machineCondition" class="form-label">Machine Condition<span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="machineCondition">
                                <option value="">--Select--</option>
                                <option value="running" selected>Running</option>
                                <option value="repair">Repair</option>
                                <option value="damage">Damage</option>
                            </select>
                        </div>

                        <!-- Serial Number -->
                        <div class="col-md-6 mb-3">
                            <label for="serialNumber" class="form-label">Serial Number<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="serialNumber" placeholder="Enter Serial Number">
                        </div>

                        <!-- Warranty Period -->
                        <div class="col-md-6 mb-3">
                            <label for="warrantyPeriod" class="form-label">Warranty Period<span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="warrantyPeriod">
                        </div>

                        <!-- Machine Rate including GST -->
                        <div class="col-md-6 mb-3">
                            <label for="machineRate" class="form-label">Machine Rate including GST<span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="machineRate" placeholder="Enter Rate" step="0.01">
                        </div>

                        <!-- Upload File -->
                        <div class="col-12 mb-3">
                            <label for="uploadFile" class="form-label">Upload File pdf/image</label>
                            <input type="file" class="form-control" id="uploadFile" accept=".pdf,image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Machine Modal -->
    <div class="modal fade" id="editMachineModal" tabindex="-1" aria-labelledby="editMachineModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMachineModalLabel">Update Machine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Entry Date -->
                        <div class="col-md-6 mb-3">
                            <label for="entryDate" class="form-label">Entry Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="entryDate">
                        </div>

                        <!-- Source -->
                        <div class="col-md-6 mb-3">
                            <label for="source" class="form-label">Source<span class="text-danger">*</span></label>
                            <select class="form-select" id="source">
                                <option value="">--Select Source--</option>
                                <option value="purchase" selected>Purchase</option>
                                <option value="rental">Rental</option>
                                <option value="lease">Lease</option>
                            </select>
                        </div>

                        <!-- Site Code -->
                        <div class="col-md-6 mb-3">
                            <label for="siteCode" class="form-label">Site Code<span class="text-danger">*</span></label>
                            <select class="form-select" id="siteCode">
                                <option value="">--Select Site Code--</option>
                                <option value="site1">Site 1 - NMDC</option>
                            </select>
                        </div>

                        <!-- Site Name -->
                        <div class="col-md-6 mb-3">
                            <label for="siteName" class="form-label">Site Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="siteName" placeholder="By default" readonly>
                        </div>

                        <!-- Machine Name -->
                        <div class="col-md-6 mb-3">
                            <label for="machineName" class="form-label">Machine Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="machineName" placeholder="Enter Machine Name">
                        </div>

                        <!-- Category -->
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category<span class="text-danger">*</span></label>
                            <select class="form-select" id="category">
                                <option value="">--Select Category--</option>
                                <option value="excavator">Excavator</option>
                                <option value="bulldozer">Bulldozer</option>
                                <option value="crane">Crane</option>
                            </select>
                        </div>

                        <!-- Machine Code -->
                        <div class="col-md-6 mb-3">
                            <label for="machineCode" class="form-label">Machine Code<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="machineCode" placeholder="Enter Machine Code">
                        </div>

                        <!-- Model Number -->
                        <div class="col-md-6 mb-3">
                            <label for="modelNumber" class="form-label">Model Number<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modelNumber" placeholder="Enter Model Number">
                        </div>

                        <!-- Machine Condition -->
                        <div class="col-md-6 mb-3">
                            <label for="machineCondition" class="form-label">Machine Condition<span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="machineCondition">
                                <option value="">--Select--</option>
                                <option value="running" selected>Running</option>
                                <option value="repair">Repair</option>
                                <option value="damage">Damage</option>
                            </select>
                        </div>

                        <!-- Serial Number -->
                        <div class="col-md-6 mb-3">
                            <label for="serialNumber" class="form-label">Serial Number<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="serialNumber" placeholder="Enter Serial Number">
                        </div>

                        <!-- Warranty Period -->
                        <div class="col-md-6 mb-3">
                            <label for="warrantyPeriod" class="form-label">Warranty Period<span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="warrantyPeriod">
                        </div>

                        <!-- Machine Rate including GST -->
                        <div class="col-md-6 mb-3">
                            <label for="machineRate" class="form-label">Machine Rate including GST<span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="machineRate" placeholder="Enter Rate" step="0.01">
                        </div>

                        <!-- Upload File -->
                        <div class="col-12 mb-3">
                            <label for="uploadFile" class="form-label">Upload File pdf/image</label>
                            <input type="file" class="form-control" id="uploadFile" accept=".pdf,image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Machine Condition Change Modal -->
    <div class="modal fade" id="changeConditionModal" tabindex="-1" aria-labelledby="changeConditionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeConditionModalLabel">Update Machine Condition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <!-- Machine Info -->
                        <div class="mb-3">
                            <label for="machineName" class="form-label">Machine Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control mb-2" id="machineName" placeholder="Enter machine name"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label for="machineCondition" class="form-label">Select Condition<span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="machineCondition">
                                <option value="running" selected>Running</option>
                                <option value="repair">Repair</option>
                                <option value="damage">Damage</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Machine Transfer Modal -->
    <div class="modal fade" id="transferMachineModal" tabindex="-1" aria-labelledby="transferMachineModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transferMachineModalLabel">Transfer Machine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Machine Details Display -->
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Machine Details</h6>
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Machine Name:</small>
                                    <div class="fw-semibold">Excavator CAT 320</div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Machine Code:</small>
                                    <div class="fw-semibold">MCH-001</div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Category:</small>
                                    <div class="fw-semibold">Heavy Equipment</div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Current Condition:</small>
                                    <div class="fw-semibold">
                                        <span class="badge bg-success">Running</span>
                                    </div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Current Site:</small>
                                    <div class="fw-semibold">Site A - Construction</div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Serial Number:</small>
                                    <div class="fw-semibold">SN-123456789</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Information -->
                    <h6 class="mb-3">Transfer Information</h6>

                    <div class="mb-3">
                        <label for="transferDate" class="form-label">Transfer Date<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="transferDate">
                    </div>

                    <div class="mb-3">
                        <label for="transferSiteCode" class="form-label">Site Code<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="transferSiteCode" placeholder="Enter site code">
                    </div>

                    <div class="mb-3">
                        <label for="transferSiteName" class="form-label">Site Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="transferSiteName" placeholder="By default" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Transfer Now</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Category Modal -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel">Delete Machine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this machine?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/dashboard-default.js') }}"></script>
@endpush