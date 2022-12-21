<?php

namespace App\SectionModals\Collections;

use Dotworkers\Security\Enums\Permissions;
use Illuminate\Support\Facades\Gate;
use Xinax\LaravelGettext\Facades\LaravelGettext;

/**
 * Class SectionModalsCollection
 *
 * This class allows to format section modals data
 *
 * @package App\SectionModals\Collections
 * @author  Eborio Linarez
 */
class SectionModalsCollection
{
    /**
     * Format all modals
     *
     * @param array $modals Sliders data
     * @param array $menu Menu data
     */
    public function formatAll($modals, $menu)
    {
        foreach ($modals as $modal) {
            $locale = LaravelGettext::getLocale();
            $url = s3_asset("section-modals/{$modal->image}");
            $file = $modal->image;
            $modal->image = "<img src='$url' class='img-responsive' width='200'>";
            $modal->language =  $modal->language == '*' ? _i('Everybody') : $modal->language;
            $modal->currency_iso =  $modal->currency_iso == '*' ? _i('Everybody') : $modal->currency_iso;
            $modal->one_time = $modal->one_time ? _i('Yes') : _i('No');
            $modal->scroll = $modal->scroll ? _i('Yes') : _i('No');
            $statusClass = $modal->status ? 'teal' : 'lightred';
            $statusText = $modal->status ? _i('Published') : _i('Unpublished');
            $modal->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            foreach ($menu as $item) {
                if ($modal->route == 'core.index') {
                    $modal->route = _i('Home');
                    break;
                } elseif ($modal->route == 'users.panel') {
                    $modal->route = _i('User panel');
                    break;
                } else {
                    if ($item->route == $modal->route) {
                        $modal->route = $item->metas->$locale->name;
                        break;
                    }
                }
            }

            if (Gate::allows('access', Permissions::$manage_modals)) {
                $modal->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('section-modals.edit', [$modal->id]),
                    _i('Edit')
                );
                $modal->actions .= sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('section-modals.delete', [$modal->id, $file]),
                    _i('Delete')
                );
            }
        }
    }

    /**
     * Format details
     *
     * @param object $modal Modal data
     */
    public function formatDetails($modal)
    {
        $url = s3_asset("section-modals/{$modal->image}");
        $modal->file = $modal->image;
        $modal->image = "<img src='$url' class='img-responsive' width='200'>";
        $modal->url = !is_null($modal->url) ? $modal->url : '' ;
    }
}
