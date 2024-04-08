<?php

namespace App\CRM\Repositories;

use App\CRM\Entities\Slider;
use PhpParser\Node\Expr\Array_;

/**
 * Class SlidersRepo
 *
 * This class allows to interact with Slider entity
 *
 * @package App\Core\Repositories
 * @author  Eborio Linarez
 */
class SlidersRepo
{
    /**
     * Get all sliders by element type
     *
     * @param int $templateElementType Template element type ID
     * @return mixed
     */
    public function allByElementType($templateElementType)
    {
        $sliders = Slider::where('element_type_id', $templateElementType)
            ->whitelabel()
            ->get();
        return $sliders;
    }

    /**
     * Get all sliders by element type and section
     *
     * @param int $templateElementType Template element type ID
     * @param string $section Section String
     * @return mixed
     */
    public function allByElementTypeAndSection($templateElementType, $section)
    {
        $sliders = Slider::where('element_type_id', $templateElementType)
            ->where('section', $section)
            ->whitelabel()
            ->get();
        return $sliders;
    }

    /**
     * Get all sliders with routes
     *
     * @return mixed
     */
    public function allWithRoutes()
    {
        $sliders = Slider::whereNotNull('route')
            ->whitelabel()
            ->get();
        return $sliders;
    }

    /**
     * Get all sliders with image
     *
     * @return mixed
     */
    public function allWithImages($image)
    {
        $sliders = Slider::where('image',$image)
            ->whitelabel()
            ->get();
        return $sliders;
    }

    /**
     * Delete slider
     *
     * @param int $id Slider ID
     * @return mixed
     */
    public function delete($id)
    {
        $slider = Slider::where('id', $id)
            ->whitelabel()
            ->first();
        $slider->delete();
        return $slider;
    }

    /**
     * Find slider
     *
     * @param int $id Slider ID
     * @return mixed
     */
    public function find($id)
    {
        $slider = Slider::where('id', $id)
            ->whitelabel()
            ->first();
        return $slider;
    }

    /**
     * Find order and route slider
     *
     * @param int $order Slider ID
     * @param int $templateElementType Template element type ID
     * @param null|string $device Device String
     * @param null|string $language Language String
     * @param null|string $currency Currency String
     * @param null|Boolean $status Status Boolean
     * @return mixed
     */
    public function findOrderAndTemplateElementType($order, $templateElementType, $device = null, $language = null, $currency = null, $status = null)
    {
        $slider = Slider::where('order', $order)
            ->where('element_type_id', $templateElementType)
            ->conditions($device, $language, $currency, $status)
            ->whitelabel()
            ->first();
        return $slider;
    }

    /**
     * Find order and route slider
     *
     * @param int $order Slider ID
     * @param null|string $route Route String
     * @param null|string $device Device String
     * @param null|string $language Language String
     * @param null|string $currency Currency String
     * @param null|Boolean $status Status Boolean
     * @return mixed
     */
    public function findOrderAndRoute($order, $route, $device = null, $language = null, $currency = null, $status = null)
    {
        $slider = Slider::whereNotNull('route')
            ->where('order', $order)
            ->where('route', $route)
            ->conditions($device, $language, $currency, $status)
            ->whitelabel()
            ->first();
        return $slider;
    }

    /**
     * Find order and element type slider
     *
     * @param int $order Slider ID
     * @param int $section Section ID
     * @param null|string $device Device String
     * @param null|string $language Language String
     * @param null|string $currency Currency String
     * @param null|Boolean $status Status Boolean
     * @return mixed
     */
    public function findOrderAndSection($order, $section, $device = null, $language = null, $currency = null, $status = null)
    {
        $slider = Slider::where('section', $section)
            ->where('order', $order)
            ->conditions($device, $language, $currency, $status)
            ->whitelabel()
            ->first();
        return $slider;
    }

    /**
     * Get by section
     *
     * @param int $section Section ID
     * @return mixed
     */
    public function getBySection($section)
    {
        $sliders = Slider::where('section_id', $section)
            ->whitelabelCurrency()
            ->get();
        return $sliders;
    }

    /**
     *  Search by element type and section
     *
     * @param int $templateElementType Template element type ID
     * @param string $section Section String
     * @param null|Array_ $device Device Array
     * @param null|Array_ $language Language Array
     * @param null|Array_ $currency Currency Array
     * @param null|Boolean $status Status Boolean
     * @return mixed
     */
    public function searchByElementTypeAndSection($templateElementType, $section, $device, $language, $currency, $status)
    {
        $sliders = Slider::where('element_type_id', $templateElementType)
            ->where('section', $section)
            ->whitelabel()
            ->multiple($device, $language, $currency, $status)
            ->get();
        return $sliders;
    }

    /**
     * Get all sliders with routes
     * @param null|Array_ $device Device Array
     * @param null|Array_ $language Language Array
     * @param null|Array_ $currency Currency Array
     * @param null|Boolean $status Status Boolean
     * @param null|Array_ $route Route Array_
     * @return mixed
     */
    public function searchByElementTypeAndRoute($device, $language, $currency, $status, $route)
    {
        $sliders = Slider::whereNotNull('route')
            ->whitelabel()
            ->multipleRoute($device, $language, $currency, $status, $route)
            ->get();
        //\Log::info(__METHOD__, ['2.4 $currency' => $sliders ]);
        return $sliders;
    }

    /**
     * Store slider
     *
     * @param array $data Slider data
     * @return mixed
     */
    public function store($data)
    {
        $slider = Slider::create($data);
        return $slider;
    }

    /**
     * Update sliders
     *
     * @param int $id Slider ID
     * @param array $data Slider data
     * @return mixed
     */
    public function update($id, $data)
    {
        $slider = Slider::find($id);
        $slider->fill($data);
        $slider->save();
        return $slider;
    }
}
