<div class="alert alert-warning" role="alert">
@if (isset($mailgun_notifications->$locale->message) && !is_null($mailgun_notifications->$locale->message))
    <h4 class="alert-heading"><i class="fa fa-envelope-o" aria-hidden="true"></i> Correo electrónico no verificado</h4>
    <p>{!! $mailgun_notifications->$locale->message !!}</p>
    <hr style="margin: 1rem 0">
    <p class="mb-0">
        <a href="javascript:void(0)" data-toggle="modal" data-target="#reset-email-modal" class="text-primary">Verificar</a>
        |
        <a href="#">Rechazar</a>
    </p>
</div>
@endif

