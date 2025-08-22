@extends('layouts/layoutMaster')

@section('title', 'Activity Board')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/jkanban/jkanban.scss', 'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/quill/typography.scss',
  'resources/assets/vendor/libs/quill/katex.scss', 'resources/assets/vendor/libs/quill/editor.scss'])
@endsection

@section('page-style')
  @vite('resources/assets/vendor/scss/pages/app-kanban.scss')
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/jkanban/jkanban.js',
  'resources/assets/vendor/libs/quill/katex.js', 'resources/assets/vendor/libs/quill/quill.js'])
@endsection

@section('page-script')
  @vite('resources/assets/js/app-kanban.js')
@endsection

@section('content')
  <div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Application Activity Logs</h1>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
      <div class="card-header">
        Filters
      </div>
      <div class="card-body">
        <form method="GET" action="{{ route('activity-logs') }}">
          <div class="row">
            <div class="col-md-3">
              <label for="search">Search</label>
              <input type="text" name="search" class="form-control" placeholder="Search message or user..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
              <label for="model">Model</label>
              <select name="model" class="form-control">
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
              <a href="{{ route('activity-logs') }}" class="btn btn-secondary ms-2">Clear</a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Logs Table -->
    <div class="card shadow mb-4">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th>Date</th>
              <th>User</th>
              <th>Action</th>
              <th>Message</th>
            </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
              <tr>
                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td>{{ $log->user->name }}</td>
                <td class="text-nowrap">
                  <span class="badge bg-info">{{ $log->action }}</span> on
                  <span class="badge bg-secondary">{{ class_basename($log->loggable_type) }} #{{ $log->loggable_id }}</span>
                </td>
                <td>{!! $log->message !!}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center">No logs found.</td>
              </tr>
            @endforelse
            </tbody>
          </table>
        </div>
        <!-- Pagination -->
        <div class="row mt-4">
          <div class="col">
            {{ $logs->appends(request()->query())->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

