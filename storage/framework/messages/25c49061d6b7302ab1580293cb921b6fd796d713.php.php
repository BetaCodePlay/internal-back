<?php

namespace App\Core\Collections;

use App\Core\Enums\Languages;
use App\Core\Enums\Pages;
use App\Core\Repositories\PagesRepo;

/**
 * Class PagesCollection
 *
 * This class allows to format pages data
 *
 * @package App\Core\Collections
 * @author  Eborio Linarez
 */
class PagesCollection
{
    /**
     * Format all
     *
     * @param array $pages Pages data
     */
    public function formatAll($pages)
    {
        $pagesRepo = new PagesRepo();
        $timezone = session('timezone');
        foreach ($pages as $page) {
            $whitelabelPage = $pagesRepo->findByWhitelabel($page->id);
            $page->original_title = Pages::getName($page->id);
            $page->title = is_null($whitelabelPage) ? _i('Page not configured') : $whitelabelPage->title;
            if(!is_null($whitelabelPage->updated_at)){
                $page->updated = is_null($whitelabelPage) ? _i('Page not configured') : $whitelabelPage->updated_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            } else {
                $page->updated =  _i('Date not configured');
            }
            $page->language = Languages::getName($page->language);

            $statusClass = (!is_null($whitelabelPage) && $whitelabelPage->status) ? 'teal' : 'lightred';
            $statusText = (!is_null($whitelabelPage) && $whitelabelPage->status) ? _i('Published') : _i('Unpublished');
            $page->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            $page->actions = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                route('pages.edit', [$page->id]),
                _i('Edit')
            );
        }
    }

    /**
     * Format details
     *
     * @param object $page Page data
     */
    public function formatDetails($page)
    {
        $page->title = is_null($page->title) ? Pages::getName($page->id) : $page->title;
    }
}
