<?php

namespace App\Core\Repositories;

use App\Whitelabels\Entities\Whitelabel;

/**
 * Class WhitelabelsGamesRepo
 *
 * This class allows to interact with WhitelabelsGames entity
 *
 * @package App\Core\Repositories
 * @author  Eborio Linarez
 * @author  Jhonattan Bullones
 */
class WhitelabelsGamesRepo
{
    /**
     * Get all whitelabels
     *
     * @return mixed
     */
    public function all()
    {
        return Whitelabel::on('replica')
            ->orderBy('name', 'ASC')
            ->get();
    }

}
