@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Roles')
<!-- Vendor Styles -->
@section('vendor-style')
  @vite([
  'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss',
  'resources/assets/vendor/libs/animate-css/animate.scss"',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.scss',
  ])
@endsection

<!-- Page Styles -->
{{--@section('page-style')--}}
{{--  @vite([--}}
{{--  //'resources/assets/vendor/scss/pages/wizard-ex-checkout.scss'--}}
{{--  ])--}}
{{--@endsection--}}

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite([
  'resources/assets/vendor/libs/jquery/jquery.js',
  'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
  ])

@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite([
  'resources/assets/js/test.js',
  'resources/assets/js/extended-ui-sweetalert2.js',
  ])

@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <span class="fw-bold">Access</span>
      </li>
      <li class="breadcrumb-item active">Permissions</li>
    </ol>
  </nav>
  @hasrole('root')
  @php
    // get roles, first role
     echo auth()->user()->getRoleNames()[0]
  @endphp
  @endhasrole
  <style>
    body {
      font-family: sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 50px;
      background-color: #f0f2f5;
    }
    .container {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 500px;
      margin-bottom: 30px;
    }
    h1 {
      color: #333;
      margin-bottom: 20px;
      text-align: center;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    input[type="text"] {
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
      width: 100%;
      box-sizing: border-box;
    }
    button {
      padding: 12px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #0056b3;
    }
    #notification-container {
      width: 100%;
      max-width: 500px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .notification-item {
      background-color: #e9f7ef; /* Light green for success */
      border: 1px solid #d4edda;
      color: #155724;
      padding: 15px;
      border-radius: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      animation: fadeIn 0.5s ease-out;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      display: none; /* Hidden by default, will fade in with jQuery */
    }
    .notification-item p {
      margin: 0;
      flex-grow: 1;
      font-size: 15px;
    }
    .notification-item .timestamp {
      font-size: 12px;
      color: #6c757d;
      margin-left: 15px;
    }
    .close-notification {
      background: none;
      border: none;
      font-size: 20px;
      color: #155724;
      cursor: pointer;
      margin-left: 10px;
      padding: 0 5px;
    }
    .close-notification:hover {
      color: #0d3617;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>

  <div class="row">
    <div class="container">
    <div class="col-12">
      <h1>Send Real-time Notification</h1>
      <form id="notification-form" action="{{ route('send-test-message') }}" method="POST">
        @csrf
        <input type="text" id="notification-input" name="message" placeholder="Type your notification message...">
        <button type="button" id="send">Send Notification</button>
      </form>
    </div>

      <div id="notification-container">
        <!-- Notifications will appear here -->
      </div>
    </div>
  </div>


@endsection



