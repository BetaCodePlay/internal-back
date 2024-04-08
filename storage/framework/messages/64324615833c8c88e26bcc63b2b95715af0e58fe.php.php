<div class="alert alert-warning" id="paraocultar" role="alert">
    <?php if(isset($mailgun_notifications->$locale->title) && !is_null($mailgun_notifications->$locale->title)): ?>
        <h4 class="alert-heading"><i class="fa fa-envelope-o" aria-hidden="true"></i> <?php echo $mailgun_notifications->$locale->title; ?></h4>
    <?php endif; ?>
    <?php if(isset($mailgun_notifications->$locale->message) && !is_null($mailgun_notifications->$locale->message)): ?>
        <p><?php echo $mailgun_notifications->$locale->message; ?></p>
        <hr style="margin: 1rem 0">
    <?php endif; ?>
    <p class="mb-0">
        <?php if(isset($mailgun_notifications->$locale->accepted_button) &&
        !is_null($mailgun_notifications->$locale->accepted_button)): ?>
            <a href="" data-toggle="modal"
               data-target="#reset-email-modal" class="text-primary"><?php echo $mailgun_notifications->$locale->accepted_button; ?></a>
        <?php endif; ?>
        <?php if(isset($mailgun_notifications->$locale->denied_button) &&
        !is_null($mailgun_notifications->$locale->denied_button)): ?>
            |
            <a href="<?php echo e(route('agents.index')); ?>"
               id="oculta"><?php echo $mailgun_notifications->$locale->denied_button; ?></a>
        <?php endif; ?>
    </p>
</div>
