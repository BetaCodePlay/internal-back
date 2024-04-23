<?php

use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Components;

if (!function_exists('global_asset')) {

    function global_asset($path)
    {
        $s3URL = str_replace('https://', '', env('AWS_S3_URL'));
        $url = 'https://' . env('AWS_GLOBAL_BUCKET') . '.' . $s3URL;
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
            $s3URL = str_replace('https://', '', env('AWS_S3_URL'));
            $cdn = 'https://' . env('AWS_DEFAULT_BUCKET') . '.' . $s3URL . '/' . $configuration->s3_directory;

        } else {
            $cdn = $configuration->cdn;
        }
        return "$cdn/$path";
    }
}
