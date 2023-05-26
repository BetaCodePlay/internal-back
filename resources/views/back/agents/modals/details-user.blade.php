<div class="modal fade" id="details-user-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Information of the User') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('agents.move-agent') }}" id="move-agent-form" method="post">
                <div class="modal-body">
                    <div class="row row-div">
                        <div class="col-sm-6">
                            <div class="row mb-2">
                                <div class="offset-md-1 col-md-3">
                                    <strong> {{_i('user')}}: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="userSet"></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="offset-md-1 col-md-3">
                                    <strong> {{_i('father')}}: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="fatherSet"></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="offset-md-1 col-md-3">
                                    <strong> {{_i('rol')}}: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="typeSet"></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="offset-md-1 col-md-3">
                                    <strong> {{_i('agents')}}: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="agentsSet"></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="offset-md-1 col-md-3">
                                    <strong> {{_i('players')}}: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="playersSet"></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="offset-md-1 col-md-3">
                                    <strong> {{_i('created')}}: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="createdSet"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row mb-1">
                                <div class="offset-md-1 col-md-3">
                                   <h5><strong> {{_i('Estructura')}}</strong></h5>
                                </div>
                            </div>
                            <div class="col-8 appendTreeFather">
                                <ul>
                                    <li><strong>blinders001</strong>

                                    </li>
                                </ul>
                            </div>

                            {{--                            <div class="row mb-1">--}}
{{--                                <div class="offset-md-1 col-md-3">--}}
{{--                                    <strong> {{_i('father')}}: </strong>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-8">--}}
{{--                                    <span class="fatherSet"></span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="row mb-1">--}}
{{--                                <div class="offset-md-1 col-md-3">--}}
{{--                                    <strong> {{_i('rol')}}: </strong>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-8">--}}
{{--                                    <span class="typeSet"></span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="row mb-1">--}}
{{--                                <div class="offset-md-1 col-md-3">--}}
{{--                                    <strong> {{_i('agents')}}: </strong>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-8">--}}
{{--                                    <span class="agentsSet"></span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="row mb-1">--}}
{{--                                <div class="offset-md-1 col-md-3">--}}
{{--                                    <strong> {{_i('players')}}: </strong>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-8">--}}
{{--                                    <span class="playersSet"></span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="row mb-1">--}}
{{--                                <div class="offset-md-1 col-md-3">--}}
{{--                                    <strong> {{_i('created')}}: </strong>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-8">--}}
{{--                                    <span class="createdSet"></span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
