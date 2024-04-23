<?php


namespace Dotworkers\Bonus\Collections;

/**
 * Class TournamentRankingCollection
 *
 * This class allows to format tournament ranking data
 *
 * @package Dotworkers\Bonus\Collections
 * @author  Damelys Espinoza
 */
class TournamentRankingCollection
{
    /**
     * Format tournament ranking
     *
     * @param array $users Users data
     * @param array $rankings Users data
     */
    public function formatRanking($users, $rankings)
    {
        foreach ($users as $key => $item) {
            $item->position = $key + 1;

            if (isset($rankings->ranking) && !is_null($rankings->ranking)){
                $rankingsData = $rankings->ranking;

                foreach ($rankingsData as $ranking) {
                    switch ($item->position){

                        case 1:{
                            if($ranking->rank == 0){
                                $item->prize = $ranking->amount;
                            }
                            break;
                        }
                        case 2:{
                            if($ranking->rank == 1){
                                $item->prize = $ranking->amount;
                            }
                            break;
                        }
                        case 3:{
                            if($ranking->rank == 2){
                                $item->prize = $ranking->amount;
                            }
                            break;
                        }
                        case 4:{
                            if($ranking->rank == 3){
                                $item->prize = $ranking->amount;
                            }
                            break;
                        }
                        case 5:{
                            if ($ranking->rank == 4){
                                $item->prize = $ranking->amount;
                            }
                            break;
                        }

                        case 6:{
                            if ($ranking->rank == 5){
                                $item->prize = $ranking->amount;
                            }
                            break;
                        }
                        case 7:{
                            if ($ranking->rank == 6){
                                $item->prize = $ranking->amount;
                            }
                            break;
                        }
                        case 8:{
                            if ($ranking->rank == 7){
                                $item->prize = $ranking->amount;
                            }
                            break;
                        }
                        case 9:{
                            if ($ranking->rank == 8){
                                $item->prize = $ranking->amount;
                            }
                            break;
                        }
                        case 10:{
                            if ($ranking->rank == 9){
                                $item->prize = $ranking->amount;
                            }
                            break;
                        }
                    }
                }
            } else {
                $item->prize = 0;
            }
        }
    }
}