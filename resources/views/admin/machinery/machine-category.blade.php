@extends('admin.layouts.app')

@section('title', 'Machine Categories')

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
                                <h5 class="m-b-10">Machine Category</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript: void(0)">Machinery & Tools</a></li>
                                <li class="breadcrumb-item" aria-current="page">Machine Category</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="page-header-actions col-md-2 text-end">
                    <button class="btn btn-success btn-sm fw-bold" data-bs-toggle="modal"
                        data-bs-target="#addCategoryModal">
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
                            <h5 class="card-title mb-0">Categories</h5>
                            <div class="d-flex gap-3">
                                <button class="btn btn-outline-warning text-primary btn-sm"
                                    id="exportData">Export</button>
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                        id="bulkActionDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                        disabled>
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
                                            <a class="dropdown-item" href="#" id="bulkActivate">
                                                <i class="ti ti-check me-2"></i>Activate Selected
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" id="bulkDeactivate">
                                                <i class="ti ti-x me-2"></i>Deactivate Selected
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
                                            <th>Category Name</th>
                                            <th>Created Date</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input row-checkbox" type="checkbox"
                                                        value="1" id="check1">
                                                    <label class="form-check-label" for="check1"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">

                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0">Battery Drill</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">Dec 15, 2024</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button class="btn btn-sm btn-light-primary" title="Edit"
                                                        data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                                                        <i class="ti ti-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-light-danger" title="Delete"
                                                        data-bs-toggle="modal" data-bs-target="#deleteCategoryModal">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input row-checkbox" type="checkbox"
                                                        value="2" id="check2">
                                                    <label class="form-check-label" for="check2"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">

                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0">Others</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">Dec 20, 2024</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">Inactive</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button class="btn btn-sm btn-light-primary" title="Edit">
                                                        <i class="ti ti-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-light-danger" title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>

                                    <!-- pagination -->
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-muted">Showing 1 to 10 of 50 entries</div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <button class="btn btn-sm btn-outline-primary"
                                                            disabled>Prev</button>
                                                        <input type="number"
                                                            class="form-control form-control-sm text-center"
                                                            style="width: 60px;" value="1" min="1" max="5">
                                                        <span class="text-muted">/</span>
                                                        <input type="number"
                                                            class="form-control form-control-sm text-center"
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
            <!-- Add Category Modal -->
            <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCategoryModalLabel">Add Machine Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <div class="mb-3">
                                    <label for="categoryName" class="form-label">Category Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control mb-2" id="categoryName"
                                        placeholder="Enter category name">

                                    <label for="status" class="form-label">Status<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="status">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
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

            <!-- Edit Category Modal -->
            <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCategoryModalLabel">Update Machine Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <div class="mb-3">
                                    <label for="categoryName" class="form-label">Category Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control mb-2" id="categoryName"
                                        placeholder="Enter category name">

                                    <label for="status" class="form-label">Status<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="status">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
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

            <!-- Delete Category Modal -->
            <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteCategoryModalLabel">Delete Machine Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this category?</p>
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