<?php


namespace App\Posts\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PostCategory
 *
 * Class to define the post categories table attributes
 *
 * @package App\Posts\Entities
 * @author  Damelys Espinoza
 */
class PostCategory extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'post_categories';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'translations', 'created_at', 'updated_at'];
}
