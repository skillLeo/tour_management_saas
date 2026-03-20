@extends(MarketplaceHelper::viewPath('vendor-dashboard.layouts.master'))

@section('content')
    <div class="ps-page__content">
        <div class="ps-page__header">
            <h3>{{ __('Tours') }}</h3>
            <div class="ps-page__actions">
                <a href="{{ route('marketplace.vendor.tours.create') }}" class="btn btn-primary">
                    <i class="icon-plus"></i> {{ __('Add New Tour') }}
                </a>
            </div>
        </div>

        <div class="ps-page__body">
            @if($tours->count() > 0)
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
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteTour({{ $tour->id }})" title="{{ __('Delete') }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $tours->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="ti ti-map-pin" style="font-size: 4rem; color: #dee2e6;"></i>
                    </div>
                    <h5>{{ __('No Tours Found') }}</h5>
                    <p class="text-muted">{{ __('You haven\'t created any tours yet.') }}</p>
                    <a href="{{ route('marketplace.vendor.tours.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> {{ __('Create Your First Tour') }}
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
