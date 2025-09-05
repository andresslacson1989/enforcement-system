@php use App\Models\Detachment;use App\Models\RequirementTransmittalForm;use App\Models\User; @endphp
@extends('layouts/layoutMaster')

@section('title', 'Activity Board')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite([
     'resources/assets/vendor/libs/select2/select2.scss',
     'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
     'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
     'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
     'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    ])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite([
    'resources/assets/vendor/libs/jquery/jquery.js',
     'resources/assets/vendor/libs/select2/select2.js',
     'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
     'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
     'resources/assets/vendor/libs/moment/moment.js',
     'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    ])
@endsection

@section('page-script')
    @vite('resources/assets/js/pages-activity-logs.js')
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Activity Logs</li>
        </ol>
    </nav>
    <hr>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header">
                Filters
            </div>
            <div class="card-body">
                <form id="activity-log-filters">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="search">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search message or user..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="model">Model</label>
                            <select name="model" class="select2 form-control" data-style="default">
                                <option value="">All Models</option>
                                @foreach($filterableModels as $model)
                                    <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>{{ $model }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <button type="button" class="btn btn-secondary ms-2" id="clear-filters-btn">Clear</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="activity-logs-table">
                        <thead class="text-center">
                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Message</th>
                            <th>Model</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('F d, Y H:i') }}</td>
                                <td class="text-center">{{ $log->user->name }}</td>
                                <td class="text-center">
                                    @if($log->action == 'created')
                                        <span class="badge bg-label-success">{{ strtoupper($log->action) }}</span>

                                    @elseif($log->action == 'updated')
                                        <span class="badge bg-label-primary">{{ strtoupper($log->action) }}</span>

                                    @elseif($log->action == 'deleted')
                                        <span class="badge bg-label-danger">{{ strtoupper($log->action) }}</span>

                                    @else
                                        <span class="badge bg-label-info">{{ strtoupper($log->action) }}</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    @php
                                        // Split the message by the first newline character
                                        $messageParts = explode("<br>", $log->message, 2);
                                        $summary = $messageParts[0];
                                        $details = $messageParts[1] ?? null;
                                    @endphp

                                    {{-- Display the summary --}}
                                    {!! $summary !!}

                                    {{-- If there are details, show a button to view them --}}
                                    @if($details)
                                        <div class="row">
                                            <div class="col-12 mt-1 ms-0 ps-0">
                                                <button type="button"
                                                        class="btn btn-info btn-sm ms-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#logDetailsModal"
                                                        data-details="{{ $log->message }}">
                                                    More Details
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    {{ class_basename($log->loggable_type) }}
                                </td>
                            </tr>
                        @empty

                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Add this code once at the bottom of your Blade file --}}
    <div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logDetailsModalLabel">Full Log Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Use a <pre> tag to preserve formatting and line breaks --}}
                    <div id="log-details-content" style="white-space: pre-wrap; word-wrap: break-word;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection
