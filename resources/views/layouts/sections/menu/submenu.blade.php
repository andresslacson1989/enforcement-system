@php
  use Illuminate\Support\Facades\Route;
@endphp

<ul class="menu-sub">
  @if (isset($menu))
    @foreach ($menu as $submenu)

      {{-- active menu method --}}
      @php
        $activeClass = null;
        // You also have a $configData variable here, but it's not passed to the partial.
        // You should either pass it or define it here if it's needed for the active class logic.
        $active = 'active open'; // Fallback value

        $currentRouteName =  Route::currentRouteName();

        if (isset($submenu['slug']) && $currentRouteName === $submenu['slug']) {
            $activeClass = 'active';
        }
        elseif (isset($submenu['submenu'])) {
          if (gettype($submenu['slug']) === 'array') {
            foreach($submenu['slug'] as $slug){
              if (str_contains($currentRouteName,$slug) and strpos($currentRouteName,$slug) === 0) {
                  $activeClass = $active;
              }
            }
          }
          else{
            if (str_contains($currentRouteName,$submenu['slug']) and strpos($currentRouteName,$submenu['slug']) === 0) {
              $activeClass = $active;
            }
          }
        }
      @endphp

      <li class="menu-item {{$activeClass}}">
        <a href="{{ isset($submenu['url']) ? url($submenu['url']) : 'javascript:void(0)' }}" class="{{ isset($submenu['submenu']) ? 'menu-link menu-toggle' : 'menu-link' }}"
           @if (isset($submenu['target']) and !empty($submenu['target'])) target="_blank" @endif>
          @if (isset($submenu['icon']))
            <i class="{{ $submenu['icon'] }}"></i>
          @endif
          <div>{{ isset($submenu['name']) ? __($submenu['name']) : '' }}</div>
          @isset($submenu['badge'])
            <div class="badge bg-{{ $submenu['badge'][0] }} rounded-pill ms-auto">{{ $submenu['badge'][1] }}</div>
          @endisset
        </a>

        {{-- submenu --}}
        @if (isset($submenu['submenu']))
          @include('layouts.sections.menu.submenu',['menu' => $submenu['submenu']])
        @endif
      </li>
    @endforeach
  @endif
</ul>
