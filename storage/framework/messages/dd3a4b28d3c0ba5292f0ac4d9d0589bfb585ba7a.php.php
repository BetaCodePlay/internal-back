<?php


namespace App\Core\Collections;


/**
 * Class EmailConfigurationsCollection
 *
 * This class allows to format email configurations data
 *
 * @package App\Core\Collections
 * @author Carlos Hurtado
 */
class EmailConfigurationsCollection
{

    /**
     * Format email types
     *
     * @param array $emailTypes Email types array
     */
    public function formatEmailTypes($emailTypes)
    {
        foreach ($emailTypes as $emailType) {
            $emailType->email = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i>%s</a>',
                route('email-configurations.edit',  $emailType->id),
                _i('Edit')
            );
        }
    }

    /**
     * Format email type
     *
     * @param array $emailTypes Email types array
     */
    public function formatEmailType($emailType)
    {
        $emailType->title = !is_null($emailType->title)? $emailType->title :'';
        $emailType->subtitle = !is_null($emailType->subtitle)? $emailType->subtitle : '';
        $emailType->content = !is_null($emailType->content)? $emailType->content : '';
        $emailType->button = !is_null($emailType->button)? $emailType->button : '';
        $emailType->footer = !is_null($emailType->footer)? $emailType->footer : '';
        $emailType->email_type_id = !is_null($emailType->id)? $emailType->id : $emailType->email_type_id;
    }
}
