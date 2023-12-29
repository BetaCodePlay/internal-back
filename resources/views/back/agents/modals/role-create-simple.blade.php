<div class="modal fade modal-style" id="role-create-simple">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Create role') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><span class="font-weight-bold text-form">{{ _i('What role do you want to create?') }}</span> <span class="username-form">Antonella93</span></p>
                <p>{{ _i('You will be able to create master and support agents initially, then you can assign players if necessary.') }}</p>
                <div class="form">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label>{{ _i('Role type') }} <i class="fa-solid fa-circle-info"></i></label>
                                <select class="form-control">
                                    <option>{{ _i('Master') }}</option>
                                    <option>{{ _i('Support') }}</option>
                                    <option>{{ _i('Players') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label>{{ _i('Name') }}</label>
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-transparent" data-dismiss="modal">
                    {{ _i('Cancel creation') }}
                </button>
                <button type="button" class="btn btn-theme">
                    {{ _i('Ready! Create role') }}
                </button>
            </div>
        </div>
    </div>
</div>
