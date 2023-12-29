<div class="modal fade modal-style" id="role-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Create role') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="font-weight-bold">{{ _i('What role do you want to create?') }}</p>
                <p>{{ _i('You will be able to create master and support agents initially, then you can assign players if necessary.') }}</p>
                <div class="form">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>{{ _i('Role type') }} <i class="fa-solid fa-circle-info"></i></label>
                                <select class="form-control">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>{{ _i('Name') }}</label>
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>{{ _i('Dependence on') }} <i class="fa-solid fa-circle-info"></i></label>
                                <select class="form-control">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col"></div>
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
