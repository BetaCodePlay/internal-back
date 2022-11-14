<div class="modal fade" id="add-segmentations-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Add to segment') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('segments.add-user')}}" method="post" id="segment-user-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="client">{{ _i('Segments') }}</label>
                        <select name="segments" id="segments" class="form-control">
                            <option value="">{{ _i('Select...') }}</option>
                            @foreach ($segments as $segment)
                                <option value="{{ $segment->id }}">
                                    {{ $segment->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user" class="user" value="{{ isset($user) ? $user->id : '' }}">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="add-user" data-loading-text="{{ _i('Please wait...') }}">
                        {{ _i('Add') }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
