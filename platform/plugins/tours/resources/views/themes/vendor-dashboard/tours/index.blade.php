@extends('plugins/marketplace::themes.vendor-dashboard.layouts.master')

@section('content')
    <div class="ps-page__content">
        <div class="ps-page__header d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">{{ __('Tours') }}</h3>
            <div class="ps-page__actions">
                <a href="{{ route('marketplace.vendor.tours.create') }}" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    {{ __('Add New Tour') }}
                </a>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="ps-page__filter mb-4">
            <form method="GET" action="{{ route('marketplace.vendor.tours.index') }}" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="form-control" placeholder="{{ __('Search tours...') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>{{ __('Published') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        {{ __('Search') }}
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('marketplace.vendor.tours.index') }}" class="btn btn-outline-secondary">
                        {{ __('Reset') }}
                    </a>
                </div>
            </form>
        </div>

        <div class="ps-page__body">
            @if($tours->count() > 0)
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        {{ __('Showing :from to :to of :total results', [
                            'from' => $tours->firstItem(),
                            'to' => $tours->lastItem(),
                            'total' => $tours->total()
                        ]) }}
                        ({{ $tours->count() }} on this page)
                    </small>
                    <small class="text-muted">
                        Page {{ $tours->currentPage() }} of {{ $tours->lastPage() }}
                    </small>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Image') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Created') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tours as $tour)
                                <tr>
                                    <td>{{ $tour->id }}</td>
                                    <td>
                                        @if($tour->image)
                                            <img src="{{ RvMedia::getImageUrl($tour->image, 'thumb') }}" 
                                                 alt="{{ $tour->name }}" width="50" height="50" class="rounded">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="ti ti-photo text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tour->status->getValue() === 'published' && !empty($tour->slug))
                                            <a href="{{ route('public.tours.detail', $tour->slug) }}" 
                                               target="_blank" 
                                               rel="noopener" 
                                               title="{{ __('View tour on website') }}"
                                               class="text-decoration-none">
                                                <strong class="text-primary">{{ $tour->name }}</strong>
                                                <i class="ti ti-external-link" style="font-size: 12px; opacity: 0.7;"></i>
                                            </a>
                                        @else
                                            <strong>{{ $tour->name }}</strong>
                                            @if($tour->status->getValue() !== 'published')
                                                <span class="badge bg-secondary" style="font-size: 10px; margin-left: 5px;">
                                                    {{ __('Not Published') }}
                                                </span>
                                            @endif
                                        @endif
                                        @if($tour->duration_days > 0 || $tour->duration_nights > 0)
                                            <br>
                                            <small class="text-muted">
                                                @if($tour->duration_days > 0){{ $tour->duration_days }}d @endif
                                                @if($tour->duration_nights > 0){{ $tour->duration_nights }}n @endif
                                            </small>
                                        @endif
                                    </td>
                                    <td>{{ $tour->category->name ?? __('N/A') }}</td>
                                    <td>{{ format_price($tour->price) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $tour->status->getValue() === 'published' ? 'success' : ($tour->status->getValue() === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ $tour->status->label() }}
                                        </span>
                                    </td>
                                    <td>{{ $tour->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('marketplace.vendor.tours.edit', $tour->id) }}" 
                                               class="btn btn-sm btn-outline-primary" title="{{ __('Edit') }}">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteTour({{ $tour->id }})" title="{{ __('Delete') }}">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3,6 5,6 21,6"></polyline>
                                                    <path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="ps-card__footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Total: {{ $tours->total() }} tours
                            </small>
                        </div>
                        <div>
                            @if($tours->hasPages())
                                {!! $tours->withQueryString()->links() !!}
                            @else
                                <small class="text-muted">All tours shown on this page</small>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="ti ti-map-pin" style="font-size: 4rem; color: #dee2e6;"></i>
                    </div>
                    <h5>{{ __('No Tours Found') }}</h5>
                    <p class="text-muted">{{ __('You haven\'t created any tours yet.') }}</p>
                    <a href="{{ route('marketplace.vendor.tours.create') }}" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        {{ __('Create Your First Tour') }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Confirm Delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to delete this tour? This action cannot be undone.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function deleteTour(id) {
    const form = document.getElementById('deleteForm');
    form.action = '{{ route('marketplace.vendor.tours.index') }}/' + id;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush