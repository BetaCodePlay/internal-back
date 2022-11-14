<?php

namespace App\Core\Repositories;

use App\Core\Entities\PageSection;

/**
 * Class PageSectionsRepo
 *
 * This class allows to interact with PageSection entity
 *
 * @package App\Core\Repositories
 * @author  Eborio Linarez
 */
class PageSectionsRepo
{
    /**
     * Get all sections
     *
     * @return mixed
     */
    public function all()
    {
        $sections = PageSection::orderBy('name', 'ASC')
            ->get();
        return $sections;
    }
}
