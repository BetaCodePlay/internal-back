<?php

use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Components;

if (!function_exists('global_asset')) {

    function global_asset($path)
    {
        $url = env('AWS_S3_URL') . '/' . env('AWS_GLOBAL_BUCKET');
        return "$url/$path";
    }
}

if (!function_exists('s3_asset')) {

    function s3_asset($path, $whitelabel = null)
    {
        if (!is_null($whitelabel)) {
            $configuration = Configurations::getComponentConfiguration($whitelabel, Components::$design);

        } else {
            $configurations = config('whitelabels.configurations');
            $configuration = $configurations[Components::$design - 1]->data;
        }

        if (is_null($configuration->cdn)) {
            $cdn = env('AWS_S3_URL') . '/' . env('AWS_DEFAULT_BUCKET') . '/' . $configuration->s3_directory;

        } else {
            $cdn = $configuration->cdn;
        }
        return "$cdn/$path";
    }
}
