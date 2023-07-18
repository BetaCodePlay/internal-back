<div class="alert alert-warning" id="hide" style="display:none" role="alert">
    @if (isset($mailgun_notifications->$locale->title) && !is_null($mailgun_notifications->$locale->title))
    <h4 class="alert-heading"><i class="fa fa-envelope-o" aria-hidden="true"></i> {!!
        $mailgun_notifications->$locale->title !!}</h4>
    @endif
    @if (isset($mailgun_notifications->$locale->message) && !is_null($mailgun_notifications->$locale->message))
    <p>{!! $mailgun_notifications->$locale->message !!}</p>
    <hr style="margin: 1rem 0">
    @endif
    <p class="mb-0">
        @if (isset($mailgun_notifications->$locale->accepted_button) &&
        !is_null($mailgun_notifications->$locale->accepted_button))
        <a href="javascript:void(0)" data-toggle="modal" data-target="#reset-email-modal" class="text-primary">{!!
            $mailgun_notifications->$locale->accepted_button !!}</a>
        @endif
        @if (isset($mailgun_notifications->$locale->denied_button) &&
        !is_null($mailgun_notifications->$locale->denied_button))
        |
        <a href="#" id="close">{!! $mailgun_notifications->$locale->denied_button !!}</a>
        @endif
    </p>
</div>
<script>
$(document).ready(function(){
$("#close").on( "click", function() {
$('#hide').hide(); //oculto mediante id
});
});
</script>