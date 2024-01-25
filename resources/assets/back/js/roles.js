import {} from "../../commons/js/core";
import {refreshRandomPassword} from "./commons";

class Roles {
    static globalusername;
    static globaltypeid;
    static globalrolid;
    static globaltable;

    initTableRoles() {
        let $table = $('#table-roles');
        let $route = $table.data('route');

        Roles.globaltable = $table.DataTable({
            ajax: $route,
            processing: true,
            serverSide: true,
            /*columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }],*/
            fixedHeader: true,
            responsive: true,
            columnDefs: [
                { targets: [0, 1], visible: true},
                { targets: '_all', visible: false }
            ],
            fnCreatedRow: function (nRow, aData, iDataIndex) {
                let buttons = $('#user-buttons');
                let modalLockTarget = '[data-target="#role-lock"]';
                let modalResetPasswordTarget = '[data-target="#role-password-reset"]';
                let modalBalanceTarget = '[data-target="#role-balance"]';
                let modalCreateTarget = '[data-target="#role-create"]';

                buttons.find('[data-toggle="modal"]').attr('data-userid', aData[2]).attr('data-username', aData[0]).attr('data-rol', aData[1][1]);
                buttons.find('.btn-href').attr('href', '/agents/role/' + aData[0]);
                buttons.find(modalLockTarget).attr('data-value', aData[3][1]).html(aData[3][1] ? $(modalLockTarget).data('lock') : $(modalLockTarget).data('unlock')).attr('data-type', aData[3][2]);

                if (aData[3][1]) {
                    buttons.find(modalResetPasswordTarget).parent().removeClass('d-none');
                    buttons.find(modalBalanceTarget).parent().removeClass('d-none');
                    buttons.find(modalCreateTarget).parent().removeClass('d-none');
                    buttons.find(modalLockTarget).parent().removeClass('united');
                } else {
                    buttons.find(modalResetPasswordTarget).parent().addClass('d-none');
                    buttons.find(modalBalanceTarget).parent().addClass('d-none');
                    buttons.find(modalCreateTarget).parent().addClass('d-none');
                    buttons.find(modalLockTarget).parent().addClass('united');
                }

                $('td:eq(0)', nRow).html('<span class="btn-tr-details"><i class="fa-regular fa-eye"></i></span> ' + aData[0]);
                $('td:eq(1)', nRow).html('<span class="deco-rol">' + aData[1][0] + '</span>');
                $('td:eq(3)', nRow).html('<i class="fa-solid i-status fa-circle ' + (aData[3][1] ? 'green' : 'red') + '"></i> ' + aData[3][0]);
                $('td:eq(4)', nRow).html('$' + aData[4]);
                $('td:eq(5)', nRow).attr('data-id', aData[2]).addClass('text-right').html(buttons.html());
            },
            initComplete: function () {

            },
        });

        $('.table-load').addClass('table-complete');
        $('.page-role .loading-style').hide();

        $(document).on('click', '.currentDataRole', function () {
            let $username = $(this).data('username');
            let $userid = $(this).data('userid');
            let $rol = $(this).data('rol');

            $('.username-form').html($username);
            Roles.globalusername = $username;
            Roles.globaluserid = $userid;
            Roles.globalrolid = $rol;
        });

        $(document).on('click', '.dtr-control', function (){
            let $this = $(this).parent();
            let $rol = $this.find('td').eq(1).html();
            let $status = $this.find('td').eq(3).html();
            let $balance = $this.find('td').eq(4).html();

            $this.next().find('[data-dt-column="3"]').html($status);
        });

    }

    userResetPassword() {
        let button = '.resetUserPassword';

        $(document).on('click', button, function () {
            let $this = $(this);
            let route = $(button).data('route');
            let $password = $('#password-role-reset');
            let $data = {
                userId: Roles.globaluserid,
                newPassword: $password.val(),
            }

            if ($password.val().length >= 8) {
                $this.button('loading');

                $.ajax({
                    url: route,
                    method: 'post',
                    data: $data
                }).done(function (json) {
                    Roles.globaltable.ajax.reload();
                    Toastr.notifyToastr(json.data.title, json.data.message, 'success');
                    $('#role-password-reset').modal('hide');
                    $password.val('');
                }).fail(function (json) {
                    Roles.errorResponse(json);
                }).always(function () {
                    $this.button('reset');
                });
            } else {
                Toastr.notifyToastr('Error', $password.attr('placeholder'), 'error');
            }
        });

        $(document).on('click', '.btn-reset-password-head', function (){
            Roles.globaluserid = $(this).data('id');
        })
    }

    userLock() {
        let $button = '.lockUser';
        let $targetModal = '[data-target="#role-lock"]';
        let $modal = $('#role-lock');
        let $globalLock;
        let $globalType;
        let $title;

        $(document).on('click', $button, function () {
            let $this = $(this);
            let $route = $('#userLockType').val();
            let $descriptionLock = $('#userReasonLock select').val();
            let $descriptionUnlock = $('#userReasonUnlock select').val();
            let $data = {
                userId: Roles.globaluserid,
                descriptionLock: $descriptionLock,
                descriptionUnlock: $descriptionUnlock,
                lockType: $globalLock
            }

            if ($globalType !== 8 && !$globalLock) {
                $route = $('#lockTypeThis').val();
            }

            $.ajax({
                url: $route,
                method: 'post',
                data: $data
            }).done(function (json) {
                Roles.globaltable.ajax.reload();
                Toastr.notifyToastr(json.data.title, json.data.message, 'success');
                $modal.modal('hide');
            }).fail(function (json) {
                Roles.errorResponse(json);
            }).always(function () {
                $this.button('reset');
            });
        });

        $(document).on('click', $targetModal, function () {
            let $this = $(this);
            let $type = $this.data('type');
            let $buttonCancel = $modal.find('.modal-footer [data-dismiss="modal"]');
            let $buttonSuccess = $modal.find('.lockUser');
            let $cancel;
            let $success;
            let $typeAll = $('#lockTypeAll');
            $globalLock = $this.data('value');

            $globalType = $type;
            $typeAll.show();

            if ($globalLock) {
                $title = $this.data('lock');
                $cancel = $buttonCancel.data('lock');
                $success = $buttonSuccess.data('lock');
                $('#userReasonUnlock').hide();
                $('#userReasonLock').show();
            } else {
                if ($type !== 8) {
                    $typeAll.hide();
                }

                $title = $this.data('unlock');
                $cancel = $buttonCancel.data('unlock');
                $success = $buttonSuccess.data('unlock');
                $('#userReasonLock').hide();
                $('#userReasonUnlock').show();
            }

            $modal.find('.modal-title').html($title);
            $buttonCancel.html($cancel);
            $buttonSuccess.html($success);
        });
    }

    userBalance() {
        let button = '.balanceUser';

        function getUserInformation() {
            return new Promise((resolve, reject) => {
                let apiUrl = $(button).data('route-find');
                let data = {
                    id: Roles.globaluserid,
                    type: 'user',
                };
                /*let apiUrl = `https://dev-back.bestcasinos.lat/agents/find?id=${userId}&type=user`;*/

                $.ajax({
                    url: apiUrl,
                    method: "get",
                    dataType: "json",
                    data: data,
                    success: function (res) {
                        let {data} = res;
                        let {wallet} = data;
                        resolve(wallet);
                    },
                    error: function (error) {
                        console.error("Error obtaining user information:", error);
                        reject(error);
                    }
                });
            });
        }

        function sendAjax(route, data) {
            $(button).button('loading');

            $.ajax({
                url: route,
                method: 'post',
                data: data
            }).done(function (json) {
                let { authBalance, authUserId, balance, balanceBonus } = json.data;

                $('.balance').text(balance);
                $('.balance_bonus').text(balanceBonus);

                let amountRefreshTxt = `amount-refresh-${authUserId}`;
                let amountRefreshClass = `.amount-refresh-${authUserId}`;

                document.getElementsByClassName(amountRefreshTxt).innerHTML = authBalance;
                $(amountRefreshClass).text(authBalance);

                Roles.globaltable.ajax.reload();
                Toastr.notifyToastr(json.data.title, json.data.message, 'success');
                $('#role-balance').modal('hide');
                $('#userBalanceAmountGet').val('');
                $('#userBalanceAmount').val('');
            }).fail(function (json) {
                let data = json.responseJSON;

                if (data.code === 403) {
                    Toastr.notifyToastr(data.data.title, data.data.message, 'error');
                } else {
                    Roles.errorResponse(json);
                }
            }).always(function () {
                $(button).button('reset');
            });
        }

        $(document).on('click', button, function () {
            let $this = $(this);
            let route = $this.data('route');
            let $balance = $this.data('balance');
            const deposit = 1;
            const withdrawal = 2;
            let userId = Roles.globaluserid;
            const getTypeUser = (typeUser) => (
                typeUser === 1 || typeUser === 2 ? 'agent' :
                    typeUser === 5 ? 'user' :
                        null
            );

            let type = getTypeUser(Roles.globalrolid);

            let $data = {
                wallet: '',
                user: userId,
                type: type,
                amount: $('#userBalanceAmount').val(),
                transaction_type: ($balance) ? deposit : withdrawal
            };

            if (type === 'user') {
                getUserInformation()
                    .then(walletId => {
                        $data.wallet = walletId;
                        sendAjax(route, $data, button);
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
            } else {
                sendAjax(route, $data, button);
            }
        });

        Roles.inputMoney('#userBalanceAmountGet', '#userBalanceAmount');
    }

    userCreate() {
        let $button = '.createUser';
        let $globalType;
        randomPassword(10);

        $(document).on('click', '[data-target="#role-create"]', function () {
            randomPassword(10);
            if ($('[data-target="#role-create"]').val() === 'true') {
                $('#createRolDependence').val('').trigger('change');
            } else {
                $('#createRolDependence').val(Roles.globaluserid).trigger('change');
            }
        });

        $(document).on('input', '#createRolPercentage', function () {
            let $this = $(this);
            let $max = $this.data('max');
            $this.val($this.val().replace(/[^0-9]/g, ''));

            if ($this.val() > $max) {
                $this.val($max)
            }
        });

        $(document).on('change', '#createRolType', function () {
            $globalType = $(this).val();

            if ($globalType === '') {
                $('.d-agent').addClass('d-none')
            } else {
                $('.d-agent').removeClass('d-none')
            }
        });

        $(document).on('click', $button, function () {
            let $this = $(this);
            let $route;
            let $data = {
                username: $('#createRolUsername').val(),
                master: $('#createRolType').val(),
                percentage: $('#createRolPercentage').val(),
                password: $('#createRolPassword').val(),
                dependence: $('#createRolDependence').val()
            };

            if ($('#createRolType').length > 0) {
                if ($globalType === '') {
                    $route = $this.data('route-player');
                } else {
                    $route = $this.data('route-agent');
                }
            } else {
                $route = $this.data('route-player');
            }

            $this.button('loading');

            $.ajax({
                url: $route,
                method: 'post',
                data: $data
            }).done(function (json) {
                Roles.globaltable.ajax.reload();
                Toastr.notifyToastr(json.data.title, json.data.message, 'success');
                $('#role-create').modal('hide');
                $('#createRolUsername').val('');
                $('#createRolPercentage').val('');
            }).fail(function (json) {
                let data = json.responseJSON;

                if (data.code === 403) {
                    Toastr.notifyToastr(data.data.title, data.data.message, 'error');
                } else {
                    Roles.errorResponse(json);
                }
            }).always(function () {
                $this.button('reset');
            });
        });

        $(document).on('click', '#createRoPasswordRefresh', function () {
            randomPassword(10);
        })

        function randomPassword(length) {
            const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            const numbers = '1234567890';
            let lettersLength = Math.trunc(length * 0.7);
            let numbersLength = Math.trunc(length * 0.3);
            lettersLength += (length - (lettersLength + numbersLength));
            let password = [];

            for (let i = 0; i < lettersLength; i++) {
                let number = Math.floor(Math.random() * letters.length);
                password.push(letters.substring(number, number + 1));
            }

            for (let i = 0; i < numbersLength; i++) {
                let number = Math.floor(Math.random() * numbers.length);
                password.push(numbers.substring(number, number + 1));
            }
            password = password.sort(() => {
                return Math.random() - 0.5
            }).join('');
            $('#createRolPassword').val(password);
        }
    }

    static errorResponse(json) {
        let array = Object.values(json.responseJSON.errors);
        let title = json.responseJSON.message;

        $.each(array, function (index, value) {
            Toastr.notifyToastr(title, value, 'error');
        })
    }

    static inputMoney($input, $post) {
        function formatMoney(number, places, symbol, thousand, decimal) {
            places = !isNaN(places = Math.abs(places)) ? places : 2;
            symbol = symbol !== undefined ? symbol : "";
            thousand = thousand || ",";
            decimal = decimal || ".";
            var negative = number < 0 ? "-" : "",
                i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
                j = (j = i.length) > 3 ? j % 3 : 0;
            return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand);
        }

        $(document).on('input', $input, function () {
            let $val = $($input).val();
            if ($val === '') {
                $($input).val('')
            }
            let $amount = parseInt($val.replace(/[^0-9]/g, ''));

            $($input).val(formatMoney($amount));
            $($post).val($amount);
        });
    }
}

window.Roles = Roles;
