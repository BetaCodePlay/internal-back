<?php

namespace App\Core\Collections;

use App\Core\Enums\ImagesPositions;
use App\Core\Repositories\SectionImagesRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Security\Enums\Permissions;
use Illuminate\Support\Facades\Gate;

/**
 * Class SectionImagesCollection
 *
 * This class allows to format section images data
 *
 * @package App\Core\Collections
 * @author  Eborio Linarez
 */
class SectionImagesCollection
{
    /**
     * Format all section images
     *
     * @param array $images Images data
     * @param object $configuration Section configuration
     */
    public function formatAll($images, $configuration)
    {
        foreach ($images as $image) {
            $size = "{$configuration->width}x{$configuration->height}";
            $width = $configuration->width < '250' ? $configuration->width : '250';
            $url = s3_asset("section-images/{$image->image}");
            $image->url = !is_null($image->url) ? $image->url : _i('Without URL');
            $statusClass = $image->status ? 'teal' : 'lightred';
            $statusText = $image->status ? _i('Published') : _i('Unpublished');
            $image->image = "<img src='$url' class='img-responsive' width='$width'>";
            if(!is_null($image->front)){
            $urlFront = s3_asset("section-images/{$image->front}");
            $image->front = "<img src='$urlFront' class='img-responsive' width='$width'>";
            }else{
                $image->front = _i('Without front image');
            }
            $image->position = _i('Does not apply to this image');
            $image->size = $size;
            $image->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            if (Gate::allows('access', Permissions::$manage_section_images)) {
                $id = isset($image->id) ? $image->id : null;
                $image->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('section-images.edit', [$image->element_type_id]) . "?id={$id}&section={$image->section}",
                    _i('Edit')
                );
            } else {
                $image->actions = '';
            }
        }
    }

    /**
     * Format all By Element section images
     *
     * @param array $images Images data
     * @param object $configuration Section configuration
     */
    public function formatAllByElementType($images, $configuration)
    {
        foreach ($images as $image) {
            $size = "{$configuration->width}x{$configuration->height}";
            $width = $configuration->width < '250' ? $configuration->width : '250';
            $url = s3_asset("section-images/{$image->image}");
            $image->url = !is_null($image->url) ? $image->url : _i('Without URL');
            $statusClass = $image->status ? 'teal' : 'lightred';
            $statusText = $image->status ? _i('Published') : _i('Unpublished');
            $image->image = "<img src='$url' class='img-responsive' width='$width'>";
            $image->position = _i('Does not apply to this image');
            $image->size = $size;

            $image->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            if (Gate::allows('access', Permissions::$manage_section_images)) {
                $id = isset($image->id) ? $image->id : null;
                $image->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('featured-images.edit', [$image->element_type_id]) . "?id={$id}",
                    _i('Edit')
                );
            } else {
                $image->actions = '';
            }
        }
        return $images;
    }

    /**
     * Format all By Element section images
     *
     * @param array $images Images data
     * @param object $configuration Section configuration
     */
    public function formatAllFeatured($images, $configuration)
    {
        foreach ($images as $image) {
            $size = "{$configuration->width}x{$configuration->height}";
            $width = $configuration->width < '250' ? $configuration->width : '250';
            $url = s3_asset("section-images/{$image->image}");
            $image->url = !is_null($image->url) ? $image->url : _i('Without URL');
            $statusClass = $image->status ? 'teal' : 'lightred';
            $statusText = $image->status ? _i('Published') : _i('Unpublished');
            $image->image = "<img src='$url' class='img-responsive' width='$width'>";
            $image->position = _i('Does not apply to this image');
            $image->size = $size;

            $image->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            if (Gate::allows('access', Permissions::$manage_section_images)) {
                $image->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('featured-images.edit', [$image->id]) ,
                    _i('Edit')
                );
            } else {
                $image->actions = '';
            }
        }
        return $images;
    }

    /**
     * Format all section images with positions
     *
     * @param array $positions Images positions
     * @param int $templateElementType Template element type ID
     * @return array
     */
    public function formatAllWithPositions($positions, $templateElementType, $section = null)
    {
        $sectionImagesRepo = new SectionImagesRepo();
        $imagesData = [];
        foreach ($positions as $key => $size) {
            \Log::info(__METHOD__, ['key' => $key]);
            switch ($key) {
                case ImagesPositions::$logo_light:
                {
                    $image = Configurations::getLogo($mobile = false);
                    break;
                }
                case ImagesPositions::$logo_dark:
                {
                    $image = Configurations::getLogo($mobile = false);
                    break;
                }
                case ImagesPositions::$mobile_light:
                {
                    $image = Configurations::getLogo($mobile = true);
                    break;
                }
                case ImagesPositions::$mobile_dark:
                {
                    $image = Configurations::getLogo($mobile = true);
                    break;
                }
                case ImagesPositions::$favicon:
                {
                    $image = Configurations::getFavicon();
                    break;
                }
                default:
                {
                    $image = $sectionImagesRepo->findByPositionAndSection($key, $templateElementType, $section);
                }
            }

            $sizes = explode('x', $size);
            $width = $sizes[0] < '250' ? $sizes[0] : '250';
            if (!is_null($image)) {
                switch ($key) {
                    case ImagesPositions::$logo_light:
                    {
                        $url = $image->img_light;
                        $image->status = true;
                        $image->url = _i('Does not apply to this image');
                        break;
                    }
                    case ImagesPositions::$logo_dark:
                    {
                        $url = $image->img_dark;
                        $image->status = true;
                        $image->url = _i('Does not apply to this image');
                        break;
                    }
                    case ImagesPositions::$mobile_light:
                    {
                        $url = $image->img_light;
                        $image->status = true;
                        $image->url = _i('Does not apply to this image');
                        break;
                    }
                    case ImagesPositions::$mobile_dark:
                    {
                        $url = $image->img_dark;
                        $image->status = true;
                        $image->url = _i('Does not apply to this image');
                        break;
                    }
                    case ImagesPositions::$favicon:
                    {
                        $favicon = $image;
                        $url = $favicon;
                        $image = new \stdClass();
                        $image->status = true;
                        $image->url = _i('Does not apply to this image');
                        break;
                    }
                    default:
                    {
                        $url = s3_asset("section-images/{$image->image}");
                    }
                }

                $image->url = !is_null($image->url) ? $image->url : _i('Without URL');
                $statusClass = $image->status ? 'teal' : 'lightred';
                $statusText = $image->status ? _i('Published') : _i('Unpublished');

                $image->status = sprintf(
                    '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                    $statusClass,
                    $statusText
                );
            } else {
                $image = new \stdClass();
                $image->url = _i('Not configured');
                $url = "https://via.placeholder.com/$size";
                $image->status = sprintf(
                    '<span class="u-label g-bg-lightred g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                    _i('Not configured')
                );
            }

            $image->image = "<img src='$url' class='img-responsive' width='$width'>";
            if(!is_null($image->front)){
                $urlFront = s3_asset("section-images/{$image->front}");
                $image->front = "<img src='$urlFront' class='img-responsive' width='$width'>";
            }else{
                $image->front = _i('Without front image');
            }
            $image->position = ImagesPositions::get($key);
            $image->size = $size;

            if (Gate::allows('access', Permissions::$manage_section_images)) {
                $id = isset($image->id) ? $image->id : null;
                $image->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('section-images.edit', [$templateElementType]) . "?id={$id}&section=$section&position=$key",
                    _i('Edit')
                );
            } else {
                $image->actions = '';
            }
            $imagesData[] = $image;
        }
        return $imagesData;
    }

    /**
     * Format by element type
     *
     * @param array $image Image data
     * @param object $configuration Section configuration
     */
    public function formatByElementType($image, $configuration)
    {
        $imageSize = null;
        $width = $configuration->width < '250' ? $configuration->width : '250';
        $imageSize= "{$configuration->width}x{$configuration->height}";
        if (!is_null($image)) {
            $url = s3_asset("section-images/{$image->image}");
            $image->file = $image->image;
            $image->image = "<img src='$url' class='img-responsive' width='$width'>";
        } else {
            $image = new \stdClass();
            $url = "https://via.placeholder.com/$imageSize";
            $image->image = "<img src='$url' class='img-responsive' width='$width'>";
            $image->title = null;
            $image->button = null;
            $image->description = null;
            $image->url = null;
            $image->status = null;
            $image->file = null;
        }
        $image->size = $imageSize;

        return $image;
    }

    /**
     * Format by featured
     *
     * @param array $image Image data
     * @param object $configuration Section configuration
     */
    public function formatByFeatured($image, $configuration)
    {
        $imageSize = null;
        $width = $configuration->width < '250' ? $configuration->width : '250';
        $imageSize= "{$configuration->width}x{$configuration->height}";
        if (!is_null($image)) {
            $url = s3_asset("section-images/{$image->image}");
            $image->file = $image->image;
            $image->image = "<img src='$url' class='img-responsive' width='$width'>";
        } else {
            $image = new \stdClass();
            $url = "https://via.placeholder.com/$imageSize";
            $image->image = "<img src='$url' class='img-responsive' width='$width'>";
            $image->title = null;
            $image->button = null;
            $image->description = null;
            $image->url = null;
            $image->status = null;
            $image->file = null;
        }
        $image->size = $imageSize;
        return $image;
    }

    /**
     * Format details
     *
     * @param array $image Image data
     * @param string $position Image position
     * @param array $positions Images positions
     * @return object
     */
    public function formatDetails($image, $position, $positions)
    {
        $imageSize = null;
        $sectionImagesRepo = new SectionImagesRepo();
        foreach ($positions as $key => $size) {
            if ($key == $position) {
                $imageSize = $size;
                continue;
            }
        }

        $sizes = explode('x', $imageSize);
        $width = $sizes[0] < '250' ? $sizes[0] : '250';
        if (!is_null($image)) {
            if ($position == ImagesPositions::$logo_light || $position == ImagesPositions::$logo_dark || $position == ImagesPositions::$favicon || $position == ImagesPositions::$mobile_light || $position == ImagesPositions::$mobile_dark) {
                $image->file = $image->image;
                $image->image = "<img src='$image->image' class='img-responsive' width='$width'>";
            } else {
                $url = s3_asset("section-images/{$image->image}");
                $image->file = $image->image;
                $image->image = "<img src='$url' class='img-responsive' width='$width'>";
                if(!is_null($image->front)){
                    $urlFront = s3_asset("section-images/{$image->front}");
                    $image->file = $image->front;
                    $image->front = "<img src='$urlFront' class='img-responsive' width='$width'>";
                }else{
                    $image->front = null;
                }
            }
        } else {
            $image = new \stdClass();
            $url = "https://via.placeholder.com/$imageSize";
            $image->image = "<img src='$url' class='img-responsive' width='$width'>";
            $image->title = null;
            $image->button = null;
            $image->description = null;
            $image->url = null;
            $image->status = null;
            $image->file = null;
        }
        $image->size = $imageSize;

        return $image;
    }
}
