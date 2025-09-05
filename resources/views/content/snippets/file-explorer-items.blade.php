@php
    // Helper to map category names to icons and colors
    $category_styles = [
        'licenses' => ['icon' => 'ti-license', 'color' => 'primary'],
        'documents' => ['icon' => 'ti-file-text', 'color' => 'info'],
        'images' => ['icon' => 'ti-photo', 'color' => 'success'],
        'pdfs' => ['icon' => 'ti-file-type-pdf', 'color' => 'danger'],
        'default' => ['icon' => 'ti-file', 'color' => 'secondary'],
    ];
@endphp

@if ($view_mode === 'grid')
    <div class="row g-3">
        @forelse($files as $file)
            @php
                $style = $category_styles[$file->category] ?? $category_styles['default'];
            @endphp
            <div class="col-md-4 col-lg-3 file-card-item"
                 data-file-url="{{ $file->file_url }}"
                 data-file-title="{{ $file->title }}"
                 data-mime-type="{{ $file->mime_type }}"
                 style="cursor: pointer;">
                <div class="card text-center shadow-none border h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="mb-3">
                            @if(Str::startsWith($file->mime_type, 'image/'))
                                <img src="{{ $file->thumbnail_url }}" alt="{{ $file->title }}" class="img-fluid rounded" style="height: 48px; width: auto;">
                            @else
                                <i class="ti {{ $style['icon'] }} ti-lg text-{{ $style['color'] }}" style="font-size: 3rem !important;"></i>
                            @endif
                        </div>
                        <h6 class="card-title fw-semibold">{{ $file->title }}</h6>
                        <p class="card-text text-muted small">{{ $file->filename }}</p>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti tabler-dots-vertical"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ $file->file_url }}" download><i class="ti tabler-download me-2"></i>Download</a></li>
                                <li><a class="dropdown-item text-danger delete-file-btn" href="#" data-file-id="{{ $file->id }}"><i class="ti tabler-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- This empty state is now handled by the main JS logic --}}
        @endforelse
    </div>
@else
    {{-- List View --}}
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Size</th>
                <th>Date Added</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($files as $file)
                @php
                    $style = $category_styles[$file->category] ?? $category_styles['default'];
                @endphp
                <tr class="file-list-item"
                    data-file-url="{{ $file->file_url }}"
                    data-file-title="{{ $file->title }}"
                    data-mime-type="{{ $file->mime_type }}"
                    style="cursor: pointer;">
                    <td>
                        <div class="d-flex align-items-center">
                            @if(Str::startsWith($file->mime_type, 'image/'))
                                <img src="{{ $file->thumbnail_url }}" alt="{{ $file->title }}" class="img-fluid rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <i class="ti {{ $style['icon'] }} ti-lg text-{{ $style['color'] }} me-3"></i>
                            @endif
                            <div>
                                <h6 class="mb-0">{{ $file->title }}</h6>
                                <small class="text-muted">{{ $file->filename }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ number_format($file->size / 1024, 2) }} KB</td>
                    <td>{{ $file->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti tabler-dots-vertical"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ $file->file_url }}" download><i class="ti tabler-download me-2"></i>Download</a></li>
                                <li><a class="dropdown-item text-danger delete-file-btn" href="#" data-file-id="{{ $file->id }}"><i class="ti tabler-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                {{-- This empty state is now handled by the main JS logic --}}
            @endforelse
            </tbody>
        </table>
    </div>
@endif
