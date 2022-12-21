<?php

namespace App\CRM\Repositories;

use App\Core\Enums\Status;
use App\CRM\Entities\Segment;

/**
 * Class SegmentsRepo
 *
 * This class allows to interact with Segment entity
 *
 * @package App\CRM\Repositories
 * @author  Damelys Espinoza
 * @author  Carlos Hurtado
 */
class SegmentsRepo
{
    /**
     *  Get all segments
     *
     * @return mixed
     */
    public function all()
    {
        return Segment::whitelabel()->get();
    }

    /**
     * Get all segments by whitelabel
     *
     * @return mixed
     */
    public function allByWhitelabel()
    {
        return Segment::select('id', 'name', 'description', 'data', 'filter')
            ->whitelabel()
            ->get();
    }

    /**
     * Get all segments by whitelabel and active
     *
     * @return mixed
     */
    public function allByWhitelabelAndActive()
    {
        return Segment::select('id', 'name', 'description', 'data', 'filter', 'status')
            ->where('status', Status::$active)
            ->whitelabel()
            ->get();
    }

    /**
     * Get by IDs
     *
     * @param array $ids Segments IDs
     * @return mixed
     */
    public function getByIDs($ids)
    {
        return Segment::whereIn('id', $ids)
            ->get();
    }

    /**
     * Find by id
     *
     * @param int $id Segment ID
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function find($id, $whitelabel)
    {
        return Segment::where('id', $id)
            ->where('whitelabel_id', $whitelabel)
            ->first();
    }

    /**
     * Find by id and status
     *
     * @param int $id Segment ID
     * @param int $whitelabel Whitelabel ID
     * @param bool $status Status
     * @return mixed
     */
    public function findByIdAndStatus(int $id, int $whitelabel, bool $status)
    {
        return Segment::where('id', $id)
            ->where('whitelabel_id', $whitelabel)
            ->where('status', $status)
            ->first();
    }

    /**
     * Delete segment
     *
     * @param int $id Segment ID
     * @return mixed
     */
    public function delete($id)
    {
        $segment = Segment::where('id', $id)
            ->whitelabel()
            ->first();
        $segment->delete();
        return $segment;
    }


    /**
     * Get by segment ID
     * @param int $segmentId Segment ID
     * @return mixed
     */
    public function getBySegmentId($segmentId)
    {
        return Segment::select('id', 'data')
            ->where('id', $segmentId)
            ->whitelabel()
            ->first();
    }

    /**
     * Store segments
     *
     * @param array $data Segments data
     * @return mixed
     */
    public function store($data)
    {
        return Segment::create($data);
    }

    /**
     * Update segment
     *
     * @param int $id Segment ID
     * @param array $data Data
     * @return mixed
     */
    public function update($id, $data)
    {
        $Segment = Segment::find($id);
        $Segment->fill($data);
        $Segment->save();
        return $Segment;
    }
}
