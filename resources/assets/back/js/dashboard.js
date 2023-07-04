const axios = require('axios');
const {swalSuccessWithButton, swalError} = require("../../commons/js/core");

class Dashboard {
    // Constructor
    // constructor() {
    //     this.newUsers();
    //     this.completedProfiles();
    //     this.incompleteProfiles();
    //     this.pendingWithdrawals();
    //     this.resetEmail();
    //     this.todayDeposits();
    //     this.totalUsers();
    //     this.todayWithdrawals();
    //     this.totalUsersConnected();
    // }

    // Completed profiles
    completedProfiles() {
        let route = $('#completed-profiles').data('route');
        axios.get(route)
            .then(function (response) {
                $('#completed-profiles').text(response.data.data.users);
            });
    }

    // Incomplete profiles
    incompleteProfiles() {
        let route = $('#incomplete-profiles').data('route');
        axios.get(route)
            .then(function (response) {
                $('#incomplete-profiles').text(response.data.data.users);
            });
    }

    // Get new users
    newUsers() {
        let route = $('#new-users').data('route');
        axios.get(route)
            .then(function (response) {
                $('#new-users').text(response.data.data.users);
            });
    }

    // Get today withdrawals
    pendingWithdrawals() {
        let route = $('#pending-withdrawals').data('route');
        axios.get(route)
            .then(function (response) {
                $('#pending-withdrawals').text(response.data.data.count);
            });
    }

    // Get reset email
    resetEmail() {

        console.log('resetEmail')
        $(document).ready(function () {
            // if (document.getElementById("reset-email-modal") !== null) {
            //     console.log('resetEmail->reset-email-modal')
                $('#reset-email-modal').modal({backdrop: 'static', keyboard: false});
            //}

        });
        // if (document.getElementById("reset-email") !== null) {
        //     console.log('resetEmail->reset-email')

            let $button = $('#reset-email');
            let $form = $('#reset-email-form');

            $button.click(function () {
                $button.button('loading');

                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    data: $form.serialize()

                }).done(function (json) {
                    $('#reset-email-modal').modal('hide');
                    swalSuccessWithButton(json);
                    $form.trigger('reset');

                }).fail(function (json) {
                    swalError(json);

                }).always(function () {
                    $button.button('reset');
                });
            });
        //}

    }

    // Get today deposits
    todayDeposits() {
        let route = $('#today-deposits').data('route');
        axios.get(route)
            .then(function (response) {
                $('#today-deposits').text(response.data.data.total);
            });
    }

    // Get today withdrawals
    todayWithdrawals() {
        let route = $('#today-withdrawals').data('route');
        axios.get(route)
            .then(function (response) {
                $('#today-withdrawals').text(response.data.data.total);
            });
    }

    // Get total users
    totalUsers() {
        let route = $('#total-users').data('route');
        axios.get(route)
            .then(function (response) {
                $('#total-users').text(response.data.data.users);
            });
    }

    // Get account login by device
    totalUsersConnected() {
        let route = $('#connect-by-desktop').data('route');
        axios.get(route)
            .then(function (response) {
                $('#connect-by-desktop').text(response.data.data.desktop);
                $('#connect-by-mobile').text(response.data.data.mobile);
            });
    }
}

window.Dashboard = Dashboard;
