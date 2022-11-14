<div class="modal fade" id="add-bonus-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Activate bonus') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('bonus-system.campaigns.users.add')}}" method="post" id="campaigns-user-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="campaigns">{{ _i('Campaigns') }}</label>
                        <select name="campaigns" id="campaigns" class="form-control">
                            <option value="">{{ _i('Select...') }}</option>
                            @if(isset($campaigns))
                                @foreach ($campaigns as $campaign)
                                    <option value="{{ $campaign->id }}">
                                        {{ $campaign->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user" class="user" value="{{ isset($user) ? $user->id : '' }}">
                    <button type="submit" class="btn u-btn-primary u-btn-3d" id="add-user" data-loading-text="{{ _i('Please wait...') }}">
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
