<?php

namespace App\SectionModals\Repositories;

use App\SectionModals\Entities\SectionModal;

/**
 * Class SectionModalsRepo
 *
 * This class allows to interact with SectionModal entity
 *
 * @package App\SectionModals\Repositories
 * @author  Eborio Linarez
 */
class SectionModalsRepo
{
    /**
     * Get all modals
     *
     * @return mixed
     */
    public function all()
    {
        $modals = SectionModal::whitelabel()
            ->get();
        return $modals;
    }

    /**
     * Delete modal
     *
     * @param int $id Section modal ID
     * @return mixed
     */
    public function delete($id)
    {
        $modal = SectionModal::where('id', $id)
            ->whitelabel()
            ->delete();
        return $modal;
    }

    /**
     * Find modal
     *
     * @param int $id Section modal ID
     * @return mixed
     */
    public function find($id)
    {
        $modal = SectionModal::where('id', $id)
            ->whitelabel()
            ->first();
        return $modal;
    }

    /**
     * Store modal
     *
     * @param array $data Section modal data
     * @return mixed
     */
    public function store($data)
    {
        $modal = SectionModal::create($data);
        return $modal;
    }

    /**
     * Update section modals
     *
     * @param int $id SectionModal ID
     * @param array $data SectionModal data
     * @return mixed
     */
    public function update($id, $data)
    {
        $modal = SectionModal::find($id);
        $modal->fill($data);
        $modal->save();
        return $modal;
    }
}
