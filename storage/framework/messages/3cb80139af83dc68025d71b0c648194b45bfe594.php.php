<?php

namespace App\Posts\Collections;

use App\Core\Enums\Languages;
use Dotworkers\Security\Enums\Permissions;
use Illuminate\Support\Facades\Gate;

/**
 * Class PostsCollection
 *
 * This class allows to format posts data
 *
 * @package App\Posts\Collections
 * @author  Eborio Linarez
 */
class PostsCollection
{
    /**
     * Format all sliders
     *
     * @param array $posts Sliders data
     */
    public function formatAll($posts)
    {
        $timezone = session('timezone');
        foreach ($posts as $post) {
            $url = s3_asset("posts/{$post->image}");
            $start = !is_null($post->start_date) ? $post->start_date->setTimezone($timezone)->format('d-m-Y') : _i('No starting date');
            $end = !is_null($post->end_date) ? $post->end_date->setTimezone($timezone)->format('d-m-Y') : _i('No end date');
            $file = $post->image;
            $post->image = "<img src='$url' class='img-responsive' width='200'>";
            $post->title = !is_null($post->title) ? $post->title : _i('Without title');
            $post->language =  $post->language == '*' ? _i('Everybody') : Languages::getName($post->language);
            $post->currency_iso =  $post->currency_iso == '*' ? _i('Everybody') : $post->currency_iso;
            $post->dates = "$start <br> $end";

            $statusClass = $post->status ? 'teal' : 'lightred';
            $statusText = $post->status ? _i('Published') : _i('Unpublished');
            $post->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            if (Gate::allows('access', Permissions::$manage_sliders)) {
                $post->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('posts.edit', [$post->id]),
                    _i('Edit')
                );
                $post->actions .= sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('posts.delete', [$post->id, $file]),
                    _i('Delete')
                );
            }
        }
    }

    /**
     * Format details
     *
     * @param object $post Post data
     */
    public function formatDetails($post)
    {
        $timezone = session('timezone');
        $url = s3_asset("posts/{$post->image}");
        $post->file = $post->image;
        $post->image = "<img src='$url' class='img-responsive' width='200'>";
        if (!is_null($post->main_image)){
            $urlMain = s3_asset("posts/{$post->main_image}");
            $post->main_file = $post->main_image;
            $post->main_image = "<img src='$urlMain' class='img-responsive' width='200'>";
        }
        $start = !is_null($post->start_date) ? $post->start_date->setTimezone($timezone)->format('d-m-Y') : null;
        $end = !is_null($post->end_date) ? $post->end_date->setTimezone($timezone)->format('d-m-Y') : null;
        $post->start = $start;
        $post->end = $end;
    }
}
