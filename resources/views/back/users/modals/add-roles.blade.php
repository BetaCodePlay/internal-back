<!-- <div class="modal fade" id="role-user-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Relate Roles to User') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('security.manage-store-role') }}" method="post" id="role-user-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="roles">{{ _i('Role') }}</label>
                        <select name="roles[]" id="roles" class="form-control" multiple='multiple'>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">
                                {{ $role->description }}
                            </option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user" value="{{ $user->id }}">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="role-user"
                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                        {{ _i('Accept') }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> -->
