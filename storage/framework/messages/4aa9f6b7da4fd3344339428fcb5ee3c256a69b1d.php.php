<?php


namespace App\Core\Collections;

use App\Core\Enums\ImagesPositions;
use App\Core\Repositories\SectionImagesRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Security\Enums\Permissions;
use Illuminate\Support\Facades\Gate;


class LandingPagesCollection
{

    /**
     * Format all Landing Pages
     *
     * @param array $landing Landing Pages
     * @return array
     */
    public function formatAll($landing)
    {
        $timezone = session('timezone');
        $image=[];
        foreach ($landing as $pages) {

            $start = !is_null($pages->start_date) ? $pages->start_date->setTimezone($timezone)->format('d-m-Y') : _i('No starting date');
            $end = !is_null($pages->end_date) ? $pages->end_date->setTimezone($timezone)->format('d-m-Y') : _i('No end date');
            $pages->language =  $pages->language == '*' ? _i('Everybody') : $pages->language;
            $pages->currency_iso =  $pages->currency_iso == '*' ? _i('Everybody') : $pages->currency_iso;
            $pages->dates = "$start <br> $end";

            $statusClass = $pages->status ? 'teal' : 'lightred';
            $statusText = $pages->status ? _i('Published') : _i('Unpublished');
            $pages->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            $pages->actions = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                route('landing-pages.edit', [$pages->id]),
                _i('Edit')
            );
            $pages->actions .= sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('landing-pages.delete', [$pages->id]),
                _i('Delete')
            );
        }
    }

    /**
     * Format details Landing Pages
     *
     * @param array $landing Landing Pages
     * @return array
     */
    public function formatDetails($landing)
    {
        $timezone = session('timezone');
        $urlBackground1 = s3_asset("landing-pages/{$landing->data->positions->{'background-1'}}");
        $urlBackground2 = s3_asset("landing-pages/{$landing->data->positions->{'background-2'}}");
        $urlLeft = s3_asset("landing-pages/{$landing->data->positions->{'left-1'}}");
        $urlLogo = s3_asset("landing-pages/{$landing->data->positions->{'logo-1'}}");
        $landing->file = $landing->data->positions->{'left-1'};
        $landing->file_1 = $landing->data->positions->{'background-1'};
        $landing->file_2 = $landing->data->positions->{'background-2'};
        $landing->file_3 = $landing->data->positions->{'logo-1'};
        $landing->background_1 = "<img src='$urlBackground1' class='img-responsive' width='600'>";
        $landing->background_2 = "<img src='$urlBackground2' class='img-responsive' width='600'>";
        $landing->image = "<img src='$urlLeft' class='img-responsive' width='600'>";
        $landing->logo = "<img src='$urlLogo' class='img-responsive' width='600'>";
        $start = !is_null ($landing->start_date) ? $landing->start_date->setTimezone($timezone)->format('d-m-Y') : null;
        $end = !is_null($landing->start_date) ? $landing->end_date->setTimezone($timezone)->format('d-m-Y') : null;
        $landing->start = $start;
        $landing->end = $end;
    }
}
