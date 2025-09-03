@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
  $configData = Helper::appClasses();
@endphp
@isset($configData['layout'])
  @auth
    <script>
      window.current_user_id = {{ auth()->id() }};
    </script>
  @endauth
  {{-- Custom Tooltip Styling --}}
  <style>
    .custom-tooltip {
      position: absolute;
      background-color: #333;
      color: #fff;
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 12px;
      z-index: 1080; /* High z-index to appear on top of everything */
      display: none; /* Initially hidden */
    }
  </style>
  @include(
      $configData['layout'] === 'horizontal'
          ? 'layouts.horizontalLayout'
          : ($configData['layout'] === 'blank'
              ? 'layouts.blankLayout'
              : ($configData['layout'] === 'front'
                  ? 'layouts.layoutFront'
                  : 'layouts.contentNavbarLayout')))
@endisset
