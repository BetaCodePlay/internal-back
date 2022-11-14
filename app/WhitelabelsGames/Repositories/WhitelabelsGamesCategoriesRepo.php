<?php

namespace App\WhitelabelsGames\Repositories;

use App\WhitelabelsGames\Entities\WhitelabelsGamesCategories;
/**
 * Class WhitelabelsGamesCategoriesRepo
 *
 * This class allows to interact with WhitelabelsGamesCategories entity
 *
 * @package App\WhitelabelsGames\Repositories
 * @author  Carlos Hurtado
 */
class WhitelabelsGamesCategoriesRepo
{

    public function all()
    {
        $data = WhitelabelsGamesCategories::select('id', 'translations')->get();
        return  $data;
    }
}
