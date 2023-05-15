<?php

namespace App\CRM\Collections;

use App\Core\Enums\Languages;
use Dotworkers\Security\Enums\Permissions;
use Illuminate\Support\Facades\Gate;
use Xinax\LaravelGettext\Facades\LaravelGettext;

/**
 * Class SlidersCollection
 *
 * This class allows to format sliders data
 *
 * @package App\CRM\Collections
 * @author  Eborio Linarez
 */
class SlidersCollection
{
    /**
     * Format all lobby sliders
     *
     * @param array $sliders Sliders data
     */
    public function formatAll($sliders, $menu)
    {
        $timezone = session('timezone');
        foreach ($sliders as $slider) {
            $locale = LaravelGettext::getLocale();
            $url = s3_asset("sliders/static/{$slider->image}");
            $start = !is_null($slider->start_date) ? $slider->start_date->setTimezone($timezone)->format('d-m-Y h:i a') : _i('No starting date');
            $end = !is_null($slider->end_date) ? $slider->end_date->setTimezone($timezone)->format('d-m-Y h:i a') : _i('No end date');
            $file = $slider->image;
            $slider->image = "<img src='$url' class='img-responsive g-mb-10' width='200'><br>";
            $slider->image .= sprintf(
                '<strong>%s:</strong> %s',
                _i('URL'),
                !is_null($slider->url) ? $slider->url : _i('Without URL')
            );
            $front = $slider->front;
            \Log::info(__METHOD__, ['front' => $front]);
            if (!is_null($front)) {
                $urlFront = s3_asset("sliders/static/{$slider->front}");
                $slider->front = "<img src='$urlFront' class='img-responsive g-mb-10' width='200'><br>";
            }else{
                $slider->front = _i('Not image');
            }
            $slider->front .= sprintf(
                '<strong>%s:</strong> %s',
                _i('URL'),
                !is_null($slider->url) ? $slider->url : _i('Without URL')
            );
            $slider->language = $slider->language == '*' ? _i('All languages') : Languages::getName($slider->language);
            $slider->currency_iso = $slider->currency_iso == '*' ? _i('All currencies') : $slider->currency_iso;
            $slider->mobile = $slider->mobile == '*' ? _i('All devices') : ($slider->mobile == 'true' ? _i('Mobile') : _i('Desktop'));
            $slider->dates = "$start <br> $end";
            $statusClass = $slider->status ? 'teal' : 'lightred';
            $statusText = $slider->status ? _i('Published') : _i('Unpublished');
            $slider->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            foreach ($menu as $item) {
                if ($item->route == $slider->route) {
                    if ($item->route == 'core.index') {
                        $slider->route = _i('Home');

                    } else {
                        $slider->route = $item->metas->$locale->name;
                    }
                    break;
                }
            }

            if (Gate::allows('access', Permissions::$manage_sliders)) {
                $slider->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('sliders.edit', [$slider->id]),
                    _i('Edit')
                );
                $slider->actions .= sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('sliders.delete', [$slider->id, $file, $front]),
                    _i('Delete')
                );
            }
        }
    }

    /**
     * Format details
     *
     * @param $slider
     */
    public function formatDetails($slider)
    {
        $timezone = session('timezone');
        $url = s3_asset("sliders/static/{$slider->image}");
        $urlFront = s3_asset("sliders/static/{$slider->front}");
        $slider->archive = $slider->front;
        $slider->front = "<img src='$urlFront' class='img-responsive' width='600'>";
        $slider->file = $slider->image;
        $slider->image = "<img src='$url' class='img-responsive' width='600'>";
        $start = !is_null($slider->start_date) ? $slider->start_date->setTimezone($timezone)->format('d-m-Y h:i a') : null;
        $end = !is_null($slider->end_date) ? $slider->end_date->setTimezone($timezone)->format('d-m-Y h:i a') : null;
        $slider->start = $start;
        $slider->end = $end;
    }
}
