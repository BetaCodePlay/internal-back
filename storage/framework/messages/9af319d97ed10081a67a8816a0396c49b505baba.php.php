<?php

namespace App\CRM\Collections;

use App\Core\Enums\Languages;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Security\Enums\Permissions;
use Illuminate\Support\Facades\Gate;

/**
 * Class EmailTemplatesCollection
 *
 * This class allows to format email templates data
 *
 * @package App\CRM\Collections
 * @author  Carlos Hurtado
 */
class EmailTemplatesCollection
{
    /**
     * Format all templates
     *
     * @param array $templates Templates data
     */
    public function formatAll($templates)
    {
        $whitelabel = Configurations::getWhitelabel();
        foreach ($templates as $template) {
            $template->language =  $template->language == '*' ? _i('All languages') : Languages::getName($template->language);
            $template->currency_iso =  $template->currency_iso == '*' ? _i('All currencies') : $template->currency_iso;

            $statusClass = $template->status ? 'teal' : 'lightred';
            $statusText = $template->status ? _i('Active') : _i('Inactive');
            $template->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            if (Gate::allows('access', Permissions::$manage_email_templates)) {
                $template->actions = sprintf(
                    '<a type="button" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2 duplicate" data-route="%s" data-route-edit="%s"><i class="hs-admin-files"></i> %s</a>',
                    route('email-templates.duplicate', [$template->id]),
                    route('email-templates.edit', [$template->id]),
                    _i('Duplicate')
                );
                $template->actions .= sprintf(
                    '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#test-mail-modal" data-template="%s"><i class="hs-admin-email"></i> %s</button>',
                    $template->id,
                    _i('Test')
                );
                $template->actions .= sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('email-templates.edit', [$template->id]),
                    _i('Edit')
                );
                $template->actions .= sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('email-templates.delete', [$template->id]),
                    _i('Delete')
                );
            }
        }
    }

    /**
     * Format all templates
     *
     * @param array $templates Templates data
     */
    public function formatAllTransactions($templates)
    {
        foreach ($templates as $template) {
            $template->language =  $template->language == '*' ? _i('All languages') : Languages::getName($template->language);
            $template->currency_iso =  $template->currency_iso == '*' ? _i('All currencies') : $template->currency_iso;

            $statusClass = $template->status ? 'teal' : 'lightred';
            $statusText = $template->status ? _i('Active') : _i('Inactive');
            $template->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            if (Gate::allows('access', Permissions::$manage_email_templates)) {
                $template->actions = sprintf(
                    '<a type="button" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2 duplicate" data-route="%s" data-route-edit="%s"><i class="hs-admin-files"></i> %s</a>',
                    route('email-templates-transaction.duplicate', [$template->id]),
                    route('email-templates-transaction.edit', [$template->id]),
                    _i('Duplicate')
                );
                $template->actions .= sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('email-templates-transaction.edit', [$template->id]),
                    _i('Edit')
                );
                $template->actions .= sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('email-templates-transaction.delete', [$template->id]),
                    _i('Delete')
                );
            }
        }
    }

    /**
     * Format details
     *
     * @param object $template Template data
     */
    public function formatDetails($template)
    {
        $template->content = json_decode($template->content);
        $template->metadata = json_decode($template->metadata);
    }
}
