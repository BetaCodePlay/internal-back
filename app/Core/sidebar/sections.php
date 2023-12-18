<?php

use Dotworkers\Configurations\Configurations;

if (! function_exists('generateSections')) {
    function generateSections(): array {
        $sliderSections = [];
        $imageSections = [];
        $sections = Configurations::getHome();

        if (is_object($sections)) {
            $sectionsArray = convertObjectToArray(Configurations::getHome());

            foreach ($sectionsArray as $sectionKey => $section) {
                if (isset($section['slider'])) {
                    $sliderSections[$sectionKey] = json_decode(json_encode([
                        'text' => ucfirst(str_replace('-', ' ', $sectionKey))
                    ]));
                }

                if (isset($section['section_images'])) {
                    $imageSections[$sectionKey] = json_decode(json_encode([
                        'text' => ucfirst(str_replace('-', ' ', $sectionKey)),
                    ]));
                }
            }
        }

        return ['sliderSections' => $sliderSections, 'imageSections' => $imageSections];
    }
}
