<?php

namespace App\Core\Repositories;

use App\Core\Entities\Page;
use Illuminate\Support\Facades\DB;

/**
 * Class PagesRepo
 *
 * This class allows to interact with Page entity
 *
 * @package App\Core\Repositories
 * @author  Eborio Linarez
 */
class PagesRepo
{
    /**
     * Get all pages
     *
     * @return mixed
     */
    public function all()
    {
        $pages = Page::select('id')
            ->get();
        return $pages;
    }

    /**
     * @param $whitelabel
     * @param $language
     * @return mixed
     */
    public function getByWhitelabel($whitelabel, $language)
    {
        $pages = Page::select('pages.id', 'page_whitelabel.language')
            ->join('page_whitelabel', 'page_whitelabel.page_id', '=', 'pages.id')
            ->where('page_whitelabel.whitelabel_id', $whitelabel)
            ->where('page_whitelabel.language', $language)
            ->get();
        return $pages;
    }

    /**
     * Find page by ID
     *
     * @param int $id Page ID
     * @return mixed
     */
    public function find($id)
    {
        $page = Page::select('pages.id')
            ->where('pages.id', $id)
            ->first();
        return $page;
    }

    /**
     * Find page by ID, whitelabel and language
     *
     * @param int $id Page ID
     * @return mixed
     */
    public function findByWhitelabel($id)
    {
        $page = Page::select('pages.id', 'page_whitelabel.title', 'page_whitelabel.status', 'page_whitelabel.content',
            'page_whitelabel.updated_at')
            ->leftJoin('page_whitelabel', 'pages.id', '=', 'page_whitelabel.page_id')
            ->whitelabelLanguage()
            ->where('pages.id', $id)
            ->first();
        return $page;
    }

    /**
     * Update pages
     *
     * @param int $id Page ID
     * @param int $whitelabel Whitelabel ID
     * @param string $language Language ISO
     * @param array $data Page data
     * @return mixed
     */
    public function update($id, $whitelabel, $language, $data)
    {
        DB::table('page_whitelabel')
            ->updateOrInsert(
                ['page_id' => $id, 'whitelabel_id' => $whitelabel, 'language' => $language],
                $data
            );
    }

}
