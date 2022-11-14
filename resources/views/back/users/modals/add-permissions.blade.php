<!-- <div class="modal fade" id="permission-user-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Relate Permissions to User') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('security.manage-store-permissions') }}" method="post" id="permission-user-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="permissions">{{ _i('Permission') }}</label>
                        <select name="permissions[]" id="permissions" class="form-control" multiple='multiple'>
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->id }}">
                                {{ $permission->description }}
                            </option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user" value="{{ $user->id }}">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="permission-user"
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
