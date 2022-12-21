<?php

namespace App\Posts\Repositories;

use App\Posts\Entities\Post;

/**
 * Class PostsRepo
 *
 * This class allows to interact with Post entity
 *
 * @package App\Posts\Repositories
 * @author  Eborio Linarez
 */
class PostsRepo
{

    /**
     * Get all posts
     *
     * @return mixed
     */
    public function all()
    {
        $posts = Post::whitelabel()
            ->get();
        return $posts;
    }

    /**
     * Delete post
     *
     * @param int $id Post ID
     * @return mixed
     */
    public function delete($id)
    {
        $post = Post::where('id', $id)
            ->whitelabel()
            ->delete();
        return $post;
    }

    /**
     * Find post
     *
     * @param int $id Post ID
     * @return mixed
     */
    public function find($id)
    {
        $post = Post::where('id', $id)
            ->whitelabel()
            ->first();
        return $post;
    }

    /**
     * Store post
     *
     * @param array $data Post data
     * @return mixed
     */
    public function store($data)
    {
        $post = Post::create($data);
        return $post;
    }

    /**
     * Update posts
     *
     * @param int $id Post ID
     * @param array $data Post data
     * @return mixed
     */
    public function update($id, $data)
    {
        $post = Post::find($id);
        $post->fill($data);
        $post->save();
        return $post;
    }
}
