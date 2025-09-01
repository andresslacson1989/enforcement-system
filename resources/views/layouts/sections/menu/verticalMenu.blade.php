@php
  use App\Helpers\MenuHelper;
  use Illuminate\Support\Facades\Route;
  $configData = Helper::appClasses();

  // Fetch the raw menu data from the JSON file
  // Assuming the JSON file is located in the same place as before
  $menuData = json_decode(file_get_contents(base_path('resources/menu/verticalMenu.json')), true);

  // Filter the menu based on the logged-in user's permissions
  // We're passing the 'menu' array from the JSON file to our helper function
  $dynamicMenu = MenuHelper::getDynamicMenu($menuData['menu']);
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu" @foreach ($configData['menuAttributes'] as $attribute=>
  $value)
  {{ $attribute }}="{{ $value }}"
@endforeach>

@if (!isset($navbarFull))
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link">
      <span class="app-brand-logo demo">@include('_partials.macros')</span>
      <span class="app-brand-text demo menu-text fw-bold ms-3">{{ config('variables.templateName') }}</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
      <i class="icon-base ti tabler-x d-block d-xl-none"></i>
    </a>
  </div>
@endif

<div class="menu-inner-shadow"></div>

<ul class="menu-inner py-1">
  {{-- Loop through the filtered dynamic menu instead of the raw menu --}}
  @foreach ($dynamicMenu as $menu)
    {{-- adding active and open class if child is active --}}

    {{-- menu headers --}}
    @if (isset($menu['menuHeader']))
      <li class="menu-header small">
        <span class="menu-header-text">{{ __($menu['menuHeader']) }}</span>
      </li>
    @else
      {{-- active menu method --}}
      @php
        $activeClass = null;
        $currentRouteName = Route::currentRouteName();

        if ($currentRouteName === $menu['slug']) {
          $activeClass = 'active';
        } elseif (isset($menu['submenu'])) {
          if (gettype($menu['slug']) === 'array') {
            foreach ($menu['slug'] as $slug) {
              if (str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) {
                $activeClass = 'active open';
              }
            }
          } else {
            if (
              str_contains($currentRouteName, $menu['slug']) and
              strpos($currentRouteName, $menu['slug']) === 0
            ) {
              $activeClass = 'active open';
            }
          }
        }
      @endphp

      {{-- main menu --}}
      <li class="menu-item {{ $activeClass }}">
        <a href="{{ isset($menu['url']) ? url($menu['url']) : 'javascript:void(0);' }}"
           class="{{ isset($menu['submenu']) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu['target']) and
        !empty($menu['target'])) target="_blank" @endif>
          @isset($menu['icon'])
            <i class="{{ $menu['icon'] }}"></i>
          @endisset
          <div>{{ isset($menu['name']) ? __($menu['name']) : '' }}</div>
          @isset($menu['badge'])
            <div class="badge bg-{{ $menu['badge'][0] }} rounded-pill ms-auto">{{ $menu['badge'][1] }}</div>
          @endisset
        </a>

        {{-- submenu --}}
        @isset($menu['submenu'])
          @include('layouts.sections.menu.submenu', ['menu' => $menu['submenu']])
        @endisset
      </li>
    @endif
  @endforeach
</ul>

</aside>
