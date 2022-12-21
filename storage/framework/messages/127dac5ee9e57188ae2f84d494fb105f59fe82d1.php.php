<?php


namespace App\Core\Repositories;

use App\Core\Entities\LandingPage;

/**
 * Class LandingPagesRepo
 *
 * This class allows to interact with LandingPage entity
 *
 * @package App\Core\Repositories
 * @author  Orlando Bravo
 */
class LandingPagesRepo
{

    /**
     * Get all Landing Page
     *
     * @return mixed
     */
    public function all()
    {
        $image = LandingPage::whitelabel()
            ->get();
        return $image;
    }

    /**
     * Delete Landing Page
     *
     * @param int $id Landing Page ID
     * @return mixed
     */
    public function delete($id)
    {
        $image = LandingPage::where('id', $id)
            ->whitelabel()
            ->first();
        $image->delete();
        return $image;
    }

    /**
     * Find Landing Page
     *
     * @param int $landingPage Landing Page ID
     * @return mixed
     */
    public function find($landingPage)
    {
        $image = LandingPage::where('id', $landingPage)
            ->whitelabel()
            ->first();
        return $image;
    }

    /**
     * Store Landing Page
     *
     * @param array $data Slider data
     * @return mixed
     */
    public function store($data)
    {
        $image = LandingPage::create($data);
        return $image;
    }

    /**
     * Update Landing Page
     *
     * @param int $id Landing Page ID
     * @param array $data Landing Page data
     * @return mixed
     */
    public function update($id, $data)
    {
        $image = LandingPage::find($id);
        $image->fill($data);
        $image->save();
        return $image;
    }

    /**
     * Update and section Landing Page
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $position Landing Pages
     * @param string $currency Currency iso
     * @param string $section Section String
     * @param array $data Image data
     * @return mixed
     */
    public function updateAndSection($whitelabel, $id, $currency, $language, $data)
    {
        $image = LandingPage::updateOrInsert(
            ['whitelabel_id' => $whitelabel, 'id' => $id, 'currency_iso' => $currency, 'language' => $language],
            $data
        );
        return $image;
    }

}
