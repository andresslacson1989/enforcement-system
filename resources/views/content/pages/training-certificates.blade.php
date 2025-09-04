@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Training Certificates')

@section('vendor-style')
    @vite([
      'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    ])
@endsection

@section('vendor-script')
    @vite([
    'resources/assets/vendor/libs/jquery/jquery.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    ])
@endsection

@section('page-script')
    @vite([
    'resources/assets/js/pages-training-certificates.js',
    ])
@endsection

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('form-library') }}">Main</a></li>
            <li class="breadcrumb-item active">Training Certificates</li>
        </ol>
    </nav>

    <!-- Summary Stat Cards -->
    <div class="row g-6 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Total Certificates</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2">{{ $total_certificates }}</h3>
                            </div>
                            <small>All recorded trainings</small>
                        </div>
                        <span class="badge bg-label-primary rounded p-2"><i class="ti tabler-certificate tabler-xl"></i></span>
                    </div>
                </div>
            </div>
        </div>
        @foreach($top_tags as $tag)
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>{{ $tag->name }}</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h3 class="mb-0 me-2">{{ $tag->tag_count }}</h3>
                                </div>
                                <small>Certificates</small>
                            </div>
                            <span class="badge bg-label-secondary rounded p-2"><i class="ti tabler-tag tabler-xl"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Main Data Table Card -->
    <div class="card p-4">
        <div class="card-header border-bottom">
            <h5 class="card-title">Training Certificates Directory</h5>
            <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <select id="tag_filter" class="form-select select2 text-capitalize">
                        <option value="">All Tags</option>
                        @foreach($all_tags as $tag)
                            <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 text-end"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table id="training_certificates_table" class="table border-top">
                <thead>
                <tr>
                    <th id="employee_header">Employee</th>
                    <th id="training_name_header">Training Name</th>
                    <th id="training_center_header">Training Center</th>
                    <th id="date_header">Date</th>
                    <th id="tags_header">Tags</th>
                    <th id="actions_header">Certificate</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- View Certificate Modal -->
    <div class="modal fade" id="viewCertificateModal" tabindex="-1" aria-labelledby="viewCertificateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCertificateModalLabel">Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="certificate_image_in_modal" class="img-fluid" alt="Training Certificate">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    @can(config("permit.print certificate.name"))
                        <button type="button" class="btn btn-primary" id="print_certificate_btn">
                            <i class="ti tabler-printer me-2"></i>Print
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
