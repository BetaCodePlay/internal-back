<?php


namespace App\Posts\Repositories;

use App\Posts\Entities\PostCategory;

/**
 * Class PostsCategoriesRepo
 *
 * This class allows to interact with Post category entity
 *
 * @package App\Posts\Repositories
 * @author  Damelys Espinoza
 */
class PostsCategoriesRepo
{
    /**
     * Get all posts categories
     *
     * @return mixed
     */
    public function all()
    {
        return  PostCategory::get();
    }
}
