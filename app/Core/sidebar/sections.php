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

if (! function_exists('lobbySections')) {
    function lobbySections(): array
    {
        $lobbySections = isset(Configurations::getCasinoLobby()->home) ? Configurations::getCasinoLobby()->home: [];
        $lobby = [];

        if (is_object($lobbySections)) {
            foreach ($lobbySections as $sectionKey => $section) {
                if (isset($section->section_images)) {
                    $lobby[$sectionKey] = json_decode(json_encode([
                         'text' => ucfirst(str_replace('-', ' ', $sectionKey))
                     ]));
                }

                if (isset($section->slider)) {
                    $lobby[$sectionKey] = json_decode(json_encode([
                        'text' => ucfirst(str_replace('-', ' ', $sectionKey)),
                    ]));
                }
            }
        }

        return ['lobby' => $lobby];
    }
}
