<div class="modal fade modal-edit" id="edit-permission-users">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Edit Permissions to User') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('security.manage-store-permissions-users')}}" method="post" id="permission-user-form">
                <div class="modal-body">
                <div class="row">    
                    <div class="col-md-3">
                                <div class="form-group">
                                <label for="useredit">{{ _i('User') }}</label>
                                    <select class="form-control useredit" id="useredit" name="users[]" multiple disabled>
                                    @foreach ($users as $user)
                                            <option value="{{ $user->id }}" >
                                                {{ $user->username }}
                                            </option>
                                        @endforeach
                                        <input type="hidden" id="hiddenuser" name="users[]" value="" />
                                    </select>
                                </div>
                    </div>
                    <div class="col-md-3">
                                <div class="form-group">
                                    <label for="permissionsedit">{{ _i('Permissions') }}</label>
                                    <select name="permissions[]" id="permissionsedit" class="form-control permissionsedit" multiple>
                                        @foreach ($permissions as $permission)
                                            <option value="{{ $permission->id }}" >
                                                {{ $permission->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="permission-edit-user"
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
</div>
