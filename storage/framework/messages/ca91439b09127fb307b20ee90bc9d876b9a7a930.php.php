<?php

namespace App\Core\Repositories;

use App\Core\Entities\SectionImage;

/**
 * Class SectionImagesRepo
 *
 * This class allows to interact with SectionImage entity
 *
 * @package App\Core\Repositories
 * @author  Eborio Linarez
 */
class SectionImagesRepo
{
    /**
     * Find by ID
     *
     * @param int $id Image ID
     * @param int $templateElementType Template element type ID
     * @param string $section Section String
     * @return mixed
     */
    public function find($id)
    {
        return SectionImage::find($id);
    }

    /**
     * Get all section image by element type
     *
     * @param int $templateElementType Template element type ID
     * @return mixed
     */
    public function allByElementType($templateElementType)
    {
        return SectionImage::where('element_type_id', $templateElementType)
            ->whitelabel()
            ->get();
    }

    /**
     * Find by element type
     *
     * @param int $templateElementType Template element type ID
     * @return mixed
     */
    public function findByElementType($templateElementType)
    {
        return SectionImage::where('element_type_id', $templateElementType)
            ->whitelabel()
            ->first();
    }

    /**
     * Find by position
     *
     * @param string $position Image position
     * @param int $templateElementType Template element type ID
     * @return mixed
     */
    public function findByPosition($position, $templateElementType)
    {
        return SectionImage::where('position', $position)
            ->where('element_type_id', $templateElementType)
            ->whitelabel()
            ->first();
    }

    /**
     * Find by position and section
     *
     * @param string $position Image position
     * @param int $templateElementType Template element type ID
     * @param string $section Section String
     * @return mixed
     */
    public function findByPositionAndSection($position, $templateElementType, $section)
    {
        return SectionImage::where('position', $position)
            ->where('element_type_id', $templateElementType)
            ->where('section', $section)
            ->whitelabel()
            ->first();
    }

    /**
     * Get by section
     *
     * @param string $section Section String
     * @return mixed
     */
    public function getBySection($section)
    {
        return SectionImage::where('section', $section)
            ->whitelabel()
            ->get();
    }

    /**
     * Store image
     *
     * @param array $data Section image data
     * @return mixed
     */
    public function store($data)
    {
        return SectionImage::create($data);
    }

    /**
     * Update image
     *
     * @param int $id Image ID
     * @param array $data Image data
     * @return mixed
     */
    public function update($id, $data)
    {
        return SectionImage::where('id', $id)
            ->update($data);
    }

    /**
     * Update image by element type
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $templateElementType Template element type ID
     * @param array $data Image data
     * @return mixed
     */
    public function updateByElementType($whitelabel, $templateElementType, $data)
    {
        return SectionImage::updateOrInsert(
            ['whitelabel_id' => $whitelabel, 'element_type_id' => $templateElementType],
            $data
        );
    }

    /**
     * Update image by section
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $position Image position
     * @param int $templateElementType Template element type ID
     * @param string $section Section String
     * @param array $data Image data
     * @return mixed
     */
    public function updateBySection($whitelabel, $position, $templateElementType, $section, $data)
    {
        return SectionImage::updateOrInsert(
            ['whitelabel_id' => $whitelabel, 'element_type_id' => $templateElementType, 'position' => $position, 'section' => $section],
            $data
        );
    }
}
