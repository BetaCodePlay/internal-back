@extends('back.template')

@section('styles')

@endsection

@section('content')
    <div class="wrapper-title g-pb-30">
        {{ _i('Role and permission management') }}
    </div>

    <div class="page-role">
        <div class="page-top">
            <div class="search-input">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" class="form-control" placeholder="{{ _i('Search') }}">
            </div>
            <button type="button" class="btn btn-theme"><i class="fa-solid fa-plus"></i> {{ _i('Create role') }}</button>
        </div>
        <div class="page-header">
            <div class="page-header-top">
                {{ _i('My profile') }}

                <div class="d-inline-block dropdown">
                    <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                        <li><a class="dropdown-item" href="#">Option 1</a></li>
                        <li><a class="dropdown-item" href="#">Option 2</a></li>
                        <li><a class="dropdown-item" href="#">Option 3</a></li>
                    </ul>
                </div>
            </div>
            <div class="page-header-body">
                <div class="page-data">
                    <div class="data-title">{{ _i('Name') }}</div>
                    <div class="data-text">{{ auth()->user()->username }} <span class="deco-role">Administrator</span></div>
                </div>
                <div class="page-data">
                    <div class="data-title">{{ _i('ID User') }}</div>
                    <div class="data-text text-id">{{ auth()->user()->id }}</div>
                </div>
                <div class="page-data">
                    <div class="data-title">{{ _i('Number of dependent agents') }}</div>
                    <div class="data-text-inline">{{ _i('Master agents') }} <span class="number">00</span></div>
                    <div class="data-text-inline">{{ _i('Support agents') }} <span class="number">00</span></div>
                    <div class="data-text-inline">{{ _i('Players') }} <span class="number">00</span></div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="col">
                <table id="table-roles" class="display nowrap">
                    <thead>
                    <tr>
                        <th>{{ _i('Name') }}</th>
                        <th>{{ _i('Rol') }}</th>
                        <th>{{ _i('ID User') }}</th>
                        <th>{{ _i('Status') }}</th>
                        <th>{{ _i('Label') }}</th>
                        <th>{{ _i('Balance') }}</th>
                        <th>{{ _i('Dependencies') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td>
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">Option 1</a></li>
                                    <li><a class="dropdown-item" href="#">Option 2</a></li>
                                    <li><a class="dropdown-item" href="#">Option 3</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let roles = new Roles();
            roles.initTableRoles();
        });
    </script>
@endsection
