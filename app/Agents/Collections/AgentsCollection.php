<?php

namespace App\Agents\Collections;

use App\Agents\Repositories\AgentsRepo;
use App\Core\Repositories\GamesRepo;
use App\Core\Repositories\TransactionsRepo;
use App\Reports\Repositories\ClosuresUsersTotals2023Repo;
use App\Reports\Repositories\ClosuresUsersTotalsRepo;
use App\Users\Enums\ActionUser;
use App\Users\Enums\TypeUser;
use App\Users\Repositories\UsersRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Wallet\Wallet;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

/**
 * Class AgentsCollection
 *
 * This class allows to format agents data
 *
 * @package App\Core\Collections
 * @author  Eborio Linarez
 */
class AgentsCollection
{
    /**
     * Get child agents
     *
     * @param object $agentData Agent data
     * @param string $currency Currency ISO
     * @return array
     */
    public function childAgents($agents, $currency)
    {
        $agentsRepo = new AgentsRepo();
        $agentsData = [];

        foreach ($agents as $agent) {
            $agentsData[] = [
                'id'       => $agent->id,
                'user_id'  => $agent->user_id,
                'username' => $agent->username
            ];

            $subAgents = $agentsRepo->getAgentsByOwner($agent->user_id, $currency);

            if (count($subAgents) > 0) {
                foreach ($subAgents as $subAgent) {
                    $subAgentsAgents = $this->childAgents([$subAgent], $currency);
                    foreach ($subAgentsAgents as $subAgentsAgent) {
                        $agentsData[] = $subAgentsAgent;
                    }
                }
            }
        }
        return $agentsData;
    }

    /**
     *  Tree format for users (Children Tree)
     * @param object $agent User data
     * @param int $user User data
     */
    public function childrenTree($agent, $user)
    {
        $currency   = session('currency');
        $whitelabel = Configurations::getWhitelabel();
        $agentsRepo = new AgentsRepo();
        $tree       = [
            'id'       => $agent->id,
            'text'     => $agent->username,
            'status'   => $agent->status,
            'icon'     => 'fa fa-diamond',
            'type'     => 'agent',
            'state'    => [
                'opened'   => true,
                'selected' => true,
            ],
            'li_attr'  => [
                'data_type' => 'agent',
                'class'     => 'init_tree'
            ],
            'children' => $agentsRepo->getChildrenByOwner($user, $currency, $whitelabel)
        ];

        return json_encode($tree);
    }

    /**
     *  Tree format for users (Children Tree Sql)
     * @param int $user User Id
     */
    public function childrenTreeSql($user)
    {
        $agentsRepo = new AgentsRepo();
        return $tree = collect(
            $agentsRepo->getTreeSqlLevels($user, session('currency'), Configurations::getWhitelabel())
        );
    }

    /**
     *  Tree format for users (Children Tree Sql) Format
     * @param int $user User Id
     */
    public function childrenTreeSql_format($user)
    {
        $agentsRepo = new AgentsRepo();
        $tree       = collect(
            $agentsRepo->getTreeSqlLevels($user, session('currency'), Configurations::getWhitelabel())
        );

        return $this->childrenTreeDraw($tree, 0);
    }

    /**
     *  Tree format (Children Tree Draw)
     * @param array $tree Users
     */
    public function childrenTreeDraw($tree, $level, $idOwner = null)
    {
        $arrayTree = [];
        $treeEdit  = $tree;
        $treeAll   = $tree->where('level', $level)->all();
        if (! is_null($idOwner)) {
            $treeAll = $tree->where('level', $level)->where('owner_id', $idOwner)->all();
        }
        foreach ($treeAll as $value) {
            if ($value->level == $level) {
                $icon = $level === 0 ? 'diamond' : ($value->type_user == 1 ? 'star' : ($value->type_user == 2 ? 'users' : 'user'));
                $type = $value->type_user == 5 ? 'user' : 'agent';

                $arrayTreeTmp = [
                    'id'      => $value->id,
                    'text'    => $value->username,
                    'status'  => $value->status,
                    'icon'    => "fa fa-{$icon}",
                    'li_attr' => [
                        'data_type' => $type,
                        'class'     => 'init_' . $type
                    ]
                ];
                if ($level == 0) {
                    $arrayTreeTmp['state'] = [
                        'opened'   => true,
                        'selected' => true,
                    ];
                }
                if (in_array($value->type_user, [TypeUser::$agentMater, TypeUser::$agentCajero])) {
                    $arrayTreeTmp['children'] = $this->childrenTreeDraw($tree, $level + 1, $value->id);
                }
                $arrayTree[] = $arrayTreeTmp;
            }
        }

        return $arrayTree;
    }

    /**
     * Json Format
     * @param $tableDb
     * @param $percentage
     * @return string
     */
    public function closuresByUsername($closures, $total, $percentage, $request)
    {
        $timezone = session('timezone');
        $data     = array();

        $i            = 1;
        $total_played = 0;
        $total_won    = 0;
        $total_bet    = 0;
        $total_profit = 0;
        $rtp          = 0;
        foreach ($closures as $value) {
            $total_played = $total_played + $value->total_played;
            $total_won    = $total_won + $value->total_won;
            $total_bet    = $total_bet + $value->total_bet;
            $total_profit = $total_profit + $value->total_profit;
            //$rtp = $rtp +$value->rtp;

            $data[] = [
                'id'           => $i++,
                'username'     => $value->user_name,
                'total_played' => $value->total_played,
                'total_won'    => $value->total_won,
                'total_bet'    => $value->total_bet,
                'total_profit' => $value->total_profit,
                'rtp'          => $value->rtp . '%'
            ];
        }

        $total_rtp = ($total_won / $total_played) * 100;
        $data[]    = [
            'id'           => 999999999,
            'username'     => _i('Totals'),
            'total_played' => number_format($total_played, 2, '.', '.'),
            'total_won'    => number_format($total_won, 2, '.', '.'),
            'total_bet'    => number_format($total_bet, 2, '.', '.'),
            'total_profit' => number_format($total_profit, 2, '.', '.'),
            'rtp'          => number_format($total_rtp, 2, '.') . '%'
        ];

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $data
        );

        return $json_data;
    }

    /**
     * @param $tableDb
     * @param $percentage
     * @return string
     */
    public function closuresTotalUsername($tableDb, $percentage = null)
    {
        $htmlUsername = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                        </tr>
                    </thead>',
            _i('Users'),
            _i('Played'),
            _i('Win'),
            _i('Bets'),
            _i('Profit'),
            _i('Rtp'),
        );

        if (! empty($tableDb)) {
            $htmlUsername .= "<tbody>";
            $totalPlayed  = 0;
            $totalWon     = 0;
            $totalBet     = 0;
            $totalProfit  = 0;
            foreach ($tableDb as $item => $value) {
                $totalPlayed  += $value->total_played;
                $totalWon     += $value->total_won;
                $totalBet     += $value->total_bet;
                $totalProfit  += $value->total_profit;
                $htmlUsername .= "<tr class='" . $value->id_user . "'>";
                $htmlUsername .= "<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $value->user_name . "</td>";
                //$htmlUsername .= "<td data-type='".$closuresUsersTotalsRepo->dataUser($value->user_id)->type_user."' class='name_".$closuresUsersTotalsRepo->dataUser($value->user_id)->type_user."'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $closuresUsersTotalsRepo->dataUser($value->user_id)->username . "</td>";
                $htmlUsername .= "<td class='text-center'>" . number_format($value->total_played, 2) . "</td>";
                $htmlUsername .= "<td class='text-center'>" . number_format($value->total_won, 2) . "</td>";
                $htmlUsername .= "<td class='text-center'>" . $value->total_bet . "</td>";
                $htmlUsername .= "<td class='text-center'>" . number_format($value->total_profit, 2) . "</td>";
                $htmlUsername .= "<td class='text-center'>" . number_format($value->rtp, 2) . " %</td>";
                $htmlUsername .= "</tr>";
            }
            $htmlUsername .= "<tr><td class='text-center' colspan='6'></td></tr>
                              <tr>
                                  <td class='text-center'><strong>" . _i('Totals') . "</strong></td>
                                  <td class='text-center'><strong>" . number_format($totalPlayed, 2) . "</strong></td>
                                  <td class='text-center'><strong>" . number_format($totalWon, 2) . "</strong></td>
                                  <td class='text-center'><strong>" . $totalBet . "</strong></td>
                                  <td class='text-center'><strong>" . number_format($totalProfit, 2) . "</strong></td>
                                  <td class='text-center'><strong>" . number_format(
                    ($totalWon / $totalPlayed) * 100,
                    2
                ) . "%</strong></td>
                              </tr>
                             </tbody>";

            //$htmlUsername .= "</tbody>";


            if (! is_null($percentage)) {
                $totalComission = $totalProfit * ($percentage / 100);
                $htmlUsername   .= "<tfoot>
                                      <tr>
                                          <td class='text-center' colspan='6'></td>
                                      </tr>
                                      <tr>
                                          <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                          <td class='text-center' colspan='2' style='background-color: #92ff678c;'><strong>" . _i(
                        'Total Comission'
                    ) . "</strong> &nbsp;&nbsp;&nbsp;&nbsp;(" . number_format(($percentage), 2) . "%)</td>
                                      </tr>
                                      <tr>
                                          <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                          <td class='text-center' colspan='2' style='background-color: #92ff678c;'><strong>" . number_format(
                                       ($totalComission),
                                       2
                                   ) . "</strong>  </td>
                                      </tr>
                                      <!--TODO TOTAL A PAGAR-->
                                      <tr>
                                          <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                          <td class='text-center' colspan='2' style='background-color: #ff588373;'><strong>" . _i(
                                       'Total to pay'
                                   ) . " </strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(" . number_format(
                                       (100 - $percentage),
                                       2
                                   ) . "%)</td>
                                      </tr>
                                      <tr>
                                          <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                          <td class='text-center' colspan='2' style='background-color: #ff588373;'><strong>" . number_format(
                                       ($totalProfit - $totalComission),
                                       2
                                   ) . "</strong>  </td>
                                      </tr>
                                    </tfoot>";
            } else {
                $htmlUsername .= "<tfoot>
                                      <tr>
                                          <td class='text-center' colspan='6'></td>
                                      </tr>
                                      <tr>
                                          <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                          <td class='text-center' colspan='2' style='background-color: #92ff678c;'><strong>" . _i(
                        'Total Profit'
                    ) . "</strong></td>
                                      </tr>
                                      <tr>
                                          <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                          <td class='text-center' colspan='2' style='background-color: #92ff678c;'><strong>" . number_format(
                                     ($totalProfit),
                                     2
                                 ) . "</strong>  </td>
                                      </tr>
                                  </tfoot>
                                  ";
            }
        } else {
            $htmlUsername .= "<tbody><tr class='table-secondary'><td class='text-center' colspan='7'>" . _i(
                    'no records'
                ) . "</td></tr></tbody>";
        }
        return $htmlUsername;
    }

    /**
     * Closures Totals By Agent Group Provider
     * @param $tableDb
     * @param $whitelabel
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @param $percentage
     * @return string
     */
    public function closuresTotalsByAgentGroupProvider(
        $tableDb,
        $whitelabel,
        $currency,
        $startDate,
        $endDate,
        $percentage = null
    ) {
        $closureRepo  = new ClosuresUsersTotals2023Repo();
        $htmlProvider = "";
        $totalProfit  = 0;
        $totalCredit  = 0;
        $totalDebit   = 0;
        if (! empty($tableDb)) {
            //TODO STATUS OF PROVIDERS IN PROD
            $arrayProviderTmp = array_map(function ($val) {
                return $val->id;
            }, $closureRepo->getProvidersActiveByCredentials(true, $currency, $whitelabel));

            $providerNull = [];
            foreach ($arrayProviderTmp as $index => $provider) {
                $providerNull[$provider] = [
                    'total_played' => 0,
                    'total_won'    => 0,
                    'total_profit' => 0,
                ];
            }

            $arrayTmp         = [];
            $arrayTmpClosures = [];
            //$transactions = 0;
            foreach ($tableDb as $item => $value) {
                $arrayTmp[$value->user_id] = [
                    'id'        => $value->user_id,
                    'type'      => $value->type_user == 5 ? 'init_user' : 'init_agent',
                    'username'  => $value->username,
                    'providers' => []
                ];

                $providersString = '{' . implode(',', $arrayProviderTmp) . '}';

                if (in_array($value->type_user, [TypeUser::$agentMater, TypeUser::$agentCajero])) {
                    $closures = $closureRepo->getClosureTotalsByWhitelabelAndProvidersWithSon(
                        $whitelabel,
                        $currency,
                        $startDate,
                        $endDate,
                        $value->user_id,
                        $providersString
                    );
                } else {
                    $closures = $closureRepo->getClosureTotalsByWhitelabelAndProvidersAndUser(
                        $whitelabel,
                        $currency,
                        $startDate,
                        $endDate,
                        $value->user_id,
                        $providersString
                    );
                }
                $arrayTmpClosures[$value->user_id] = $closures;

                if (count($closures) > 0) {
                    $providerDB = [];
                    foreach ($closures as $index => $closure) {
                        $providerDB[$closure->id_provider] = [
                            'total_played' => $closure->total_played,
                            'total_won'    => $closure->total_won,
                            'total_profit' => $closure->total_profit,
                        ];
                    }
                    foreach ($arrayProviderTmp as $index => $provider) {
                        if (! isset($providerDB[$provider])) {
                            $providerDB[$provider] = [
                                'total_played' => 0,
                                'total_won'    => 0,
                                'total_profit' => 0,
                            ];
                        }
                    }
                    $arrayTmp[$value->user_id]['providers'] = $providerDB;
                } else {
                    $arrayTmp[$value->user_id]['providers'] = $providerNull;
                }
            }

            $htmlProvider .= "<table class='table table-bordered table-sm table-striped table-hover'><thead><tr><th>" . _i(
                    'Users'
                ) . "</th>";
            foreach ($arrayProviderTmp as $item => $value) {
                $name         = $closureRepo->nameProvider($value);
                $htmlProvider .= "<th  class='text-center' colspan='3'>" . $name . "</th>";
            }
            $htmlProvider .= "</tr></thead>";

            $htmlProvider .= "<tbody><th></th>";
            foreach ($arrayProviderTmp as $item => $value) {
                $htmlProvider .= "<th  class=''>" . _i('total played') . "</th>";
                $htmlProvider .= "<th  class=''>" . _i('Total won') . "</th>";
                $htmlProvider .= "<th  class=''>" . _i('Total Profit') . "</th>";
            }
            $htmlProvider .= "</tr>";

            foreach ($arrayTmp as $item => $value) {
                $htmlProvider .= "<tr>";
                $htmlProvider .= "<td class='" . $value['type'] . "'>" . $value['username'] . "</td>";
                foreach ($value['providers'] as $i => $provider) {
                    $totalProfit  += $provider['total_profit'];
                    $totalDebit   += $provider['total_played'];
                    $totalCredit  += $provider['total_won'];
                    $htmlProvider .= "<td>" . number_format($provider['total_played'], 2) . "</td>";
                    $htmlProvider .= "<td>" . number_format($provider['total_won'], 2) . "</td>";
                    $htmlProvider .= "<td>" . number_format($provider['total_profit'], 2) . "</td>";
                }
                $htmlProvider .= "</tr>";
            }

            //TODO TOTALES
            if (! is_null($percentage)) {
                $totalComission = $totalProfit * ($percentage / 100);
                $htmlProvider   .= "<tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3) . "'><br></td>
                                      <td class='text-center'><br></td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #81d0f6;'><strong>" . _i(
                        'Total Profit'
                    ) . "</strong></td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #81d0f6;'><strong>" . number_format(
                                       ($totalProfit),
                                       2
                                   ) . "</strong>  </td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #92ff678c;'><strong>" . _i(
                                       'Total Comission'
                                   ) . "</strong> &nbsp;(" . number_format(($percentage), 2) . "%)</td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #92ff678c;'><strong>" . number_format(
                                       ($totalComission),
                                       2
                                   ) . "</strong>  </td>
                                  </tr>
                                  <!--TODO TOTAL A PAGAR-->
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #ff588373;'><strong>" . _i(
                                       'Total to pay'
                                   ) . " </strong> &nbsp;(" . number_format((100 - $percentage), 2) . "%)</td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #ff588373;'><strong>" . number_format(
                                       ($totalProfit - $totalComission),
                                       2
                                   ) . "</strong>  </td>
                                  </tr>";
            } else {
                $htmlProvider .= "<tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3) . "'><br></td>
                                      <td class='text-center'><br></td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #81d0f6;'><strong>" . _i(
                        'Total Profit'
                    ) . "</strong></td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #81d0f6;'><strong>" . number_format(
                                     ($totalProfit),
                                     2
                                 ) . "</strong>  </td>
                                  </tr>
                                  ";
            }

            $htmlProvider .= "</tbody>";
        } else {
            $htmlProvider = sprintf(
                '<table class="table table-bordered table-sm table-striped table-hover"><thead>
                    <tr>
                        <th>%s</th>
                        <th colspan="3" class="text-center">%s</th>
                    </tr></thead><tbody><tr><td class="text-center" colspan="4">%s</td></tr></tbody>',
                _i('Agents / Players'),
                _i('Totals'),
                _i('no records')
            );
        }

        return $htmlProvider;
    }

    /**
     * MODO TEST NEW TABLE _HOUR
     * Closures Totals By Agent Group Provider
     * @param $tableDb
     * @param $whitelabel
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @param $percentage
     * @return string
     */
    public function closuresTotalsByAgentGroupProviderHour(
        $tableDb,
        $whitelabel,
        $currency,
        $startDate,
        $endDate,
        $percentage = null
    ) {
        $closureRepo  = new ClosuresUsersTotals2023Repo();
        $htmlProvider = "";
        $totalProfit  = 0;
        $totalCredit  = 0;
        $totalDebit   = 0;
        if (! empty($tableDb)) {
            //TODO STATUS OF PROVIDERS IN PROD
            // disabled status true
            $arrayProviderTmp = array_map(function ($val) {
                return $val->id;
            }, $closureRepo->getProvidersActiveByCredentials(true, $currency, $whitelabel));
//            $arrayProviderTmp[]=171;
//            $arrayProviderTmp[]=166;
//            $arrayProviderTmp[]=5;
            $providerNull = [];
            foreach ($arrayProviderTmp as $index => $provider) {
                $providerNull[$provider] = [
                    'total_played' => 0,
                    'total_won'    => 0,
                    'total_profit' => 0,
                ];
            }

            $arrayTmp         = [];
            $arrayTmpClosures = [];
            foreach ($tableDb as $item => $value) {
                $arrayTmp[$value->user_id] = [
                    'id'        => $value->user_id,
                    'type'      => $value->type_user == 5 ? 'init_user' : 'init_agent',
                    'username'  => $value->username,
                    'providers' => $providerNull
                ];

                $providersString = '{' . implode(',', $arrayProviderTmp) . '}';

                if (in_array($value->type_user, [TypeUser::$agentMater, TypeUser::$agentCajero])) {
                    $closures = $closureRepo->getClosureTotalsByWhitelabelAndProvidersWithSonHourSql(
                        $whitelabel,
                        $currency,
                        $startDate,
                        $endDate,
                        $value->user_id,
                        $providersString
                    );
                } else {
                    $closures = $closureRepo->getClosureTotalsByWhitelabelAndProvidersAndUserHourSql(
                        $whitelabel,
                        $currency,
                        $startDate,
                        $endDate,
                        $value->user_id,
                        $providersString
                    );
                }

                $arrayTmpClosures[$value->user_id] = $closures;

                if (count($closures) > 0) {
                    $providerDB = [];
                    foreach ($closures as $index => $closure) {
                        $providerDB[$closure->id_provider] = [
                            'total_played' => is_null($closure->total_played) ? 0 : $closure->total_played,
                            'total_won'    => is_null($closure->total_won) ? 0 : $closure->total_won,
                            'total_profit' => is_null($closure->total_profit) ? 0 : $closure->total_profit,
                        ];
                    }
                    foreach ($arrayProviderTmp as $index => $provider) {
                        if (! isset($providerDB[$provider])) {
                            $providerDB[$provider] = [
                                'total_played' => '0',
                                'total_won'    => '0',
                                'total_profit' => '0',
                            ];
                        }
                    }

                    $arrayTmp[$value->user_id]['providers'] = $providerDB;
                } else {
                    $arrayTmp[$value->user_id]['providers'] = $providerNull;
                }
            }
            sort($arrayProviderTmp);
            $htmlProvider .= "<table class='table table-bordered table-sm table-striped table-hover'><thead><tr><th>" . _i(
                    'Users'
                ) . "</th>";
            foreach ($arrayProviderTmp as $item => $value) {
                $name         = $closureRepo->nameProvider($value);
                $htmlProvider .= "<th  class='text-center' colspan='3'>" . $name . "</th>";
            }
            $htmlProvider .= "</tr></thead>";

            $htmlProvider .= "<tbody><th></th>";
            foreach ($arrayProviderTmp as $item => $value) {
                $htmlProvider .= "<th  class=''>" . _i('total played') . "</th>";
                $htmlProvider .= "<th  class=''>" . _i('Total won') . "</th>";
                $htmlProvider .= "<th  class=''>" . _i('Total Profit') . "</th>";
            }
            $htmlProvider .= "</tr>";
            //return [$arrayTmp,$arrayProviderTmp];
            foreach ($arrayTmp as $item => $value) {
                $htmlProvider .= "<tr>";
                $htmlProvider .= "<td class='" . $value['type'] . "'>" . $value['username'] . "</td>";

//                sort($value['providers']);
//                foreach ($value['providers'] as $i => $provider) {
//                    $totalProfit += $provider['total_profit'];
//                    $totalDebit += $provider['total_played'];
//                    $totalCredit += $provider['total_won'];
//                    $htmlProvider .= "<td>" . number_format($provider['total_played'], 2) . "</td>";
//                    $htmlProvider .= "<td>" . number_format($provider['total_won'], 2) . "</td>";
//                    $htmlProvider .= "<td>" . number_format($provider['total_profit'], 2) . "</td>";
//                }

                foreach ($arrayProviderTmp as $i => $provider) {
                    $totalProfit  += $value['providers'][$provider]['total_profit'];
                    $totalDebit   += $value['providers'][$provider]['total_played'];
                    $totalCredit  += $value['providers'][$provider]['total_won'];
                    $htmlProvider .= "<td>" . number_format(
                            $value['providers'][$provider]['total_played'],
                            2
                        ) . "</td>";
                    $htmlProvider .= "<td>" . number_format($value['providers'][$provider]['total_won'], 2) . "</td>";
                    $htmlProvider .= "<td>" . number_format(
                            $value['providers'][$provider]['total_profit'],
                            2
                        ) . "</td>";
                }
                $htmlProvider .= "</tr>";
            }

            //return [$arrayTmp,$arrayProviderTmp,$htmlProvider];
            //TODO TOTALES
            if (! is_null($percentage)) {
                $totalComission = $totalProfit * ($percentage / 100);
                $htmlProvider   .= "<tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3) . "'><br></td>
                                      <td class='text-center'><br></td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #81d0f6;'><strong>" . _i(
                        'Total Profit'
                    ) . "</strong></td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #81d0f6;'><strong>" . number_format(
                                       ($totalProfit),
                                       2
                                   ) . "</strong>  </td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #92ff678c;'><strong>" . _i(
                                       'Total Comission'
                                   ) . "</strong> &nbsp;(" . number_format(($percentage), 2) . "%)</td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #92ff678c;'><strong>" . number_format(
                                       ($totalComission),
                                       2
                                   ) . "</strong>  </td>
                                  </tr>
                                  <!--TODO TOTAL A PAGAR-->
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #ff588373;'><strong>" . _i(
                                       'Total to pay'
                                   ) . " </strong> &nbsp;(" . number_format((100 - $percentage), 2) . "%)</td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #ff588373;'><strong>" . number_format(
                                       ($totalProfit - $totalComission),
                                       2
                                   ) . "</strong>  </td>
                                  </tr>";
            } else {
                $htmlProvider .= "<tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3) . "'><br></td>
                                      <td class='text-center'><br></td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #81d0f6;'><strong>" . _i(
                        'Total Profit'
                    ) . "</strong></td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='" . (count($arrayProviderTmp) * 3 - 1) . "' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #81d0f6;'><strong>" . number_format(
                                     ($totalProfit),
                                     2
                                 ) . "</strong>  </td>
                                  </tr>
                                  ";
            }

            $htmlProvider .= "</tbody>";
        } else {
            $htmlProvider = sprintf(
                '<table class="table table-bordered table-sm table-striped table-hover"><thead>
                    <tr>
                        <th>%s</th>
                        <th colspan="3" class="text-center">%s</th>
                    </tr></thead><tbody><tr><td class="text-center" colspan="4">%s</td></tr></tbody>',
                _i('Agents / Players'),
                _i('Totals'),
                _i('no records')
            );
        }

        return $htmlProvider;
    }

    public function hourlyAgentGroupTotals(
        $userSonData,
        $whitelabelId,
        $currency,
        $startDate,
        $endDate,
        $percentage = null
    ) {
        $closureRepo = new ClosuresUsersTotals2023Repo();

        if (! empty($userSonData)) {
            $providerData = array_reduce(
                $closureRepo->getProvidersActiveByCredentials(true, $currency, $whitelabelId),
                function ($carry, $val) {
                    $carry[$val->id] = [
                        'total_played' => 0,
                        'total_won'    => 0,
                        'total_profit' => 0,
                    ];
                    return $carry;
                },
                []
            );
        }
    }


    /**
     * @param $tableDb
     * @param $percentage
     * @return string
     */
    public function closuresTotalsByWhitelabels($tableDb, $percentage = null)
    {
        $htmlProvider = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                        </tr>
                    </thead>',
            _i('Total played'),
            _i('Total won'),
            _i('Total Bets'),
            _i('Total Profit'),
            _i('Total Rtp'),
        );

        if (! empty($tableDb)) {
            $htmlProvider .= "<tbody>";
            foreach ($tableDb as $item => $value) {
                $htmlProvider .= "<tr class=''>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->total_played, 2) . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->total_won, 2) . "</td>";
                $htmlProvider .= "<td class='text-center'>" . $value->total_bet . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->total_profit, 2) . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->rtp, 2) . "%</td>";
                $htmlProvider .= "</tr>";
            }
            if (! is_null($percentage)) {
                $totalComission = $value->total_profit * ($percentage / 100);
                $htmlProvider   .= "<tr><td class='text-center' colspan='5'></td></tr>
                                  <!--TODO TOTAL COMISION-->
                                  <tr>
                                      <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' style='background-color: #92ff678c;'><strong>" . _i(
                        'Total Comission '
                    ) . "</strong> &nbsp;&nbsp;&nbsp;&nbsp;(" . number_format(($percentage), 2) . "%)</td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' style='background-color: #92ff678c;'><strong>" . number_format(
                                       ($totalComission),
                                       2
                                   ) . "</strong></td>
                                  </tr>
                                  <!--TODO TOTAL A PAGAR-->
                                  <tr>
                                      <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' style='background-color: #ff588373;'><strong>" . _i(
                                       'Total to pay'
                                   ) . " </strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(" . number_format(
                                       (100 - $percentage),
                                       2
                                   ) . "%)</td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' style='background-color: #ff588373;'><strong>" . number_format(
                                       ($value->total_profit - $totalComission),
                                       2
                                   ) . "</strong></td>
                                  </tr>";
            }

            $htmlProvider .= "</tbody>";
        } else {
            $htmlProvider .= "<tbody><tr class='table-secondary'><td class='text-center' colspan='5'>" . _i(
                    'no records'
                ) . "</td></tr></tbody>";
        }

        return $htmlProvider;
    }

    /**
     * @param $tableDb
     * @param $percentage
     * @return string
     */
    public function closuresTotalsByWhitelabelsSymple($tableDb, $percentage)
    {
        $htmlProvider = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover">
                    <thead>
                        <tr style="background-color: #517dff!important;color: white">
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                        </tr>
                    </thead>',
            _i('Total played'),
            _i('Total won'),
            _i('Total Profit'),
            _i('Comission') . ' %',
            _i('Total Comission'),
            _i('Total to pay'),
        );

        if (! empty($tableDb)) {
            $htmlProvider .= "<tbody>";
            foreach ($tableDb as $item => $value) {
                $totalComission = $value->total_profit * ($percentage / 100);

                $htmlProvider .= "<tr class='' style=''>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->total_played, 2) . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->total_won, 2) . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->total_profit, 2) . "</td>";
//                $htmlProvider .= "<td class='text-center'>" . $percentage . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($percentage, 2) . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($totalComission, 2) . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format(
                        ($value->total_profit - $totalComission),
                        2
                    ) . "</td>";
                $htmlProvider .= "</tr>";
            }

            $htmlProvider .= "</tbody>";
        } else {
            $htmlProvider .= "<tbody><tr class='table-secondary'><td class='text-center' colspan='5'>" . _i(
                    'no records'
                ) . "</td></tr></tbody>";
        }

        return $htmlProvider;
    }

    /**
     * @param $tableDb
     * @param $percentage
     * @return string
     */
    public function closuresTotalsProvider($tableDb, $percentage = null)
    {
        $htmlProvider = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                        </tr>
                    </thead>',
            _i('Providers'),
            _i('Played'),
            _i('Win'),
            _i('Bets'),
            _i('Profit'),
            _i('Rtp'),
        );

        if (! empty($tableDb)) {
            $htmlProvider .= "<tbody>";
            $totalPlayed  = 0;
            $totalWon     = 0;
            $totalBet     = 0;
            $totalProfit  = 0;
            foreach ($tableDb as $item => $value) {
                $totalPlayed  += $value->total_played;
                $totalWon     += $value->total_won;
                $totalBet     += $value->total_bet;
                $totalProfit  += $value->total_profit;
                $htmlProvider .= "<tr class='" . $value->id_provider . "'>";
                $htmlProvider .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $value->provider_name . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->total_played, 2) . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->total_won, 2) . "</td>";
                $htmlProvider .= "<td class='text-center'>" . $value->total_bet . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->total_profit, 2) . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->rtp, 2) . "%</td>";
                $htmlProvider .= "</tr>";
            }
            $htmlProvider .= "<tr><td class='text-center' colspan='6'></td></tr>
                              <tr>
                                  <td class='text-center'><strong>" . _i('Totals') . "</strong></td>
                                  <td class='text-center'><strong>" . number_format($totalPlayed, 2) . "</strong></td>
                                  <td class='text-center'><strong>" . number_format($totalWon, 2) . "</strong></td>
                                  <td class='text-center'><strong>" . $totalBet . "</strong></td>
                                  <td class='text-center'><strong>" . number_format($totalProfit, 2) . "</strong></td>
                                  <td class='text-center'><strong>" . number_format(
                    ($totalWon / $totalPlayed) * 100,
                    2
                ) . "%</strong></td>
                              </tr>
                              </tbody>";

            if (! is_null($percentage)) {
                $totalComission = $totalProfit * ($percentage / 100);
                $htmlProvider   .= "<tfoot><tr>
                                      <td class='text-center' colspan='6'></td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #92ff678c;'><strong>" . _i(
                        'Total Comission'
                    ) . "</strong> &nbsp;&nbsp;&nbsp;&nbsp;(" . number_format(($percentage), 2) . "%)</td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #92ff678c;'><strong>" . number_format(
                                       ($totalComission),
                                       2
                                   ) . "</strong>  </td>
                                  </tr>
                                  <!--TODO TOTAL A PAGAR-->
                                  <tr>
                                      <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #ff588373;'><strong>" . _i(
                                       'Total to pay'
                                   ) . " </strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(" . number_format(
                                       (100 - $percentage),
                                       2
                                   ) . "%)</td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #ff588373;'><strong>" . number_format(
                                       ($totalProfit - $totalComission),
                                       2
                                   ) . "</strong>  </td>
                                  </tr></tfoot>";
            } else {
                $htmlProvider .= "<tfoot><tr>
                                      <td class='text-center' colspan='6'></td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #92ff678c;'><strong>" . _i(
                        'Total Profit'
                    ) . "</strong></td>
                                  </tr>
                                  <tr>
                                      <td class='text-center' colspan='4' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
                                      <td class='text-center' colspan='2' style='background-color: #92ff678c;'><strong>" . number_format(
                                     ($totalProfit),
                                     2
                                 ) . "</strong>  </td>
                                  </tr></tfoot>";
            }
        } else {
            $htmlProvider .= "<tbody><tr class='table-secondary'><td class='text-center' colspan='6'>" . _i(
                    'no records'
                ) . "</td></tr></tbody>";
        }

        return $htmlProvider;
    }

    /**
     * @param $tableDb
     * @param $percentage
     * @return string
     */
    public function closuresTotalsProviderAndMaker($tableDb, $percentage = null)
    {
        $htmlProvider = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover">',
        );
        if (count($tableDb) > 0) {
            $prov_current = 0;
            $acum         = 0;
            $salPage      = false;
            foreach ($tableDb as $item) {
                if ($item->id_provider != $prov_current) {
                    if ($prov_current != 0) {
                        $htmlProvider .= '<tr>
                                    <td colspan="6"></td>
                                    <td colspan="1"><strong>' . number_format($acum, 2) . '</strong></td>
                                </tr>';
                    }
                    $htmlProvider .= '
                        <thead>
                            ' . ($salPage ? '<tr>
                                <th colspan="7" class="text-center"><br></th>
                            </tr>' : '') . '
                            <tr>
                                <th colspan="7" class="text-center" style="background-color: #' . substr(
                            md5($item->name_provider),
                            1,
                            6
                        ) . ';color: white;font-size: larger;"><strong>' . $item->name_provider . '</strong></th>
                            </tr>
                            <tr>
                                <th colspan="2">' . _i('Maker') . '</th>
                                <th>' . _i('Users') . '</th>
                                <th>' . _i('Total Payed') . '</th>
                                <th>' . _i('Total Won') . '</th>
                                <th>' . _i('Total Bets') . '</th>
                                <th>' . _i('Total Profit') . '</th>
                            </tr>
                        </thead><tbody>';
                    $prov_current = $item->id_provider;
                    $acum         = 0;
                    $salPage      = true;
                }
                $htmlProvider .= '<tr>
                                    <td colspan="2">' . $item->name_maker . '</td>
                                    <td>' . $item->username . '</td>
                                    <td>' . number_format($item->total_played, 2) . '</td>
                                    <td>' . number_format($item->total_won, 2) . '</td>
                                    <td>' . $item->total_bet . '</td>
                                    <td>' . number_format($item->total_profit, 2) . '</td>
                                </tr>';
                $acum         += $item->total_profit;
            }
            if ($prov_current != 0) {
                $htmlProvider .= '<tr>
                                   <td colspan="6"></td>
                                   <td colspan="1"><strong>' . number_format($acum, 2) . '</strong></td>
                                </tr>';
            }
            $htmlProvider .= '</tbody></table>';
        } else {
            $htmlProvider .= "<tbody><tr class='table-secondary'><td class='text-center' colspan='6'>" . _i(
                    'no records'
                ) . "</td></tr></tbody></table>";
        }

        return $htmlProvider;
    }

    /**
     * closuresTotalsProviderAndMakerGlobal
     * @param $tableDb
     * @param $percentage
     * @return string
     */
    public function closuresTotalsProviderAndMakerGlobal($tableDb, $percentage = null)
    {
        $htmlProvider = sprintf(
            '<table id="makers-global" class="table table-bordered table-sm table-striped table-hover">',
        );
        if (count($tableDb) > 0) {
            $prov_current = 0;
            $acum         = 0;
            $salPage      = false;
            foreach ($tableDb as $item) {
                if ($item->id_provider != $prov_current) {
                    if ($prov_current != 0) {
                        $htmlProvider .= '<tr>
                                    <td colspan="6"></td>
                                    <td colspan="1"><strong>' . number_format($acum, 2) . '</strong></td>
                                </tr>';
                    }
                    $htmlProvider .= '
                        <thead>
                            ' . ($salPage ? '<tr>
                                <th colspan="7" class="text-center"><br></th>
                            </tr>' : '') . '
                            <tr>
                                <th colspan="7" class="text-center" style="background-color: #' . substr(
                            md5($item->name_provider),
                            1,
                            6
                        ) . ';color: white;font-size: larger;"><strong>' . $item->name_provider . '</strong></th>
                            </tr>
                            <tr>
                                <th colspan="2">' . _i('Maker') . '</th>
                                <th>' . _i('Total Payed') . '</th>
                                <th>' . _i('Total Won') . '</th>
                                <th>' . _i('Total Bets') . '</th>
                                <th colspan="2">' . _i('Total Profit') . '</th>
                            </tr>
                        </thead><tbody>';
                    $prov_current = $item->id_provider;
                    $acum         = 0;
                    $salPage      = true;
                }
                $htmlProvider .= '<tr>
                                    <td colspan="2">' . $item->name_maker . '</td>
                                    <td>' . number_format($item->total_played, 2) . '</td>
                                    <td>' . number_format($item->total_won, 2) . '</td>
                                    <td>' . $item->total_bet . '</td>
                                    <td colspan="2">' . number_format($item->total_profit, 2) . '</td>
                                </tr>';
                $acum         += $item->total_profit;
            }
            if ($prov_current != 0) {
                $htmlProvider .= '<tr>
                                   <td colspan="5"></td>
                                   <td colspan="2"><strong>' . number_format($acum, 2) . '</strong></td>
                                </tr>';
            }
            $htmlProvider .= '</tbody></table>';
        } else {
            $htmlProvider .= "<tbody><tr class='table-secondary'><td class='text-center' colspan='6'>" . _i(
                    'no records'
                ) . "</td></tr></tbody></table>";
        }

        return $htmlProvider;
    }

    /**
     * Dependency select
     *
     * @param array $agents Agents data
     * @param array $users Users data
     * @param var $username Username
     * @return array
     */
    public function dependencySelect($username, $agents, $users, $whitelabel, $status)
    {
        $data       = collect();
        $dataSelect = $this->formatSelectAgents($agents, $whitelabel);

        if (isset($dataSelect['agents'])) {
            $dataAgents = $dataSelect['agents'];
            foreach ($dataAgents as $agent) {
                $itemObject           = new \stdClass();
                $itemObject->id       = $agent['id'];
                $itemObject->username = $agent['username'];
                $itemObject->type     = 'agent';
                $data->push($itemObject);
            }
        }
        if ($status) {
            if (isset($dataSelect['users'])) {
                $dataUsers = $dataSelect['users'];
                foreach ($dataUsers as $user) {
                    $itemObject           = new \stdClass();
                    $itemObject->id       = $user['id'];
                    $itemObject->username = $user['username'];
                    $itemObject->type     = 'user';
                    $data->push($itemObject);
                }
            }

            foreach ($users as $user) {
                $itemObject           = new \stdClass();
                $itemObject->id       = $user->id;
                $itemObject->username = $user->username;
                $itemObject->type     = 'user';
                $data->push($itemObject);
            }
        }

        $collection = $data->reject(function ($element) use ($username) {
            return mb_strpos($element->username, $username) === false;
        });
        return $collection->unique('id')->values()->all();
    }

    /**
     * Format users select
     *
     * @param array $agents Agents data
     * @param array $users Users data
     * @param var $username Username
     * @return array
     */
    public function formatUsersSelect($users, $rolesRepo)
    {
        $data = collect();

        foreach ($users as $user) {
            $itemObject           = new \stdClass();
            $itemObject->id       = $user->id;
            $itemObject->username = $user->username;
            $itemObject->type     = 'user';
            $itemObject->roles    = $rolesRepo->getRolesUser($user->id);
            $data->push($itemObject);
        }

        return $data->unique('id')->values()->all();
    }

    /**
     * FormatRole
     *
     * @param $usernameOwner
     * @param $user
     * @param $balance
     * @param $percentage
     * @return mixed
     */
    public function formatRole($usernameOwner, $user, $balance, $percentage)
    : mixed {
        $user->statusText  = ActionUser::getName($user->action);
        $user->balanceUser = number_format($balance, 2);
        $user->agentType   = $user->type;
        $user->owner       = $usernameOwner;
        $user->percentage  = $percentage;
        return $user;
    }

    /**
     * Format select agents
     *
     * @param array $agents Agents data
     */
    public function formatSelectAgents($agents, $whitelabel)
    {
        $agentsRepo = new AgentsRepo();
        $currency   = session('currency');
        $data       = [];
        $dataAgents = [];
        $dataUsers  = [];
        foreach ($agents as $agent) {
            $dataChildrenAgents = null;
            $dataChildrenUsers  = null;
            $subAgents          = $agentsRepo->getSearchAgentsByOwner($currency, $agent->user_id, $whitelabel);
            $users              = $agentsRepo->getSearchUsersByAgent($currency, $agent->id, $whitelabel);

            if (count($subAgents) > 0) {
                $agentsChildren = $this->formatSelectAgents($subAgents, $whitelabel);
            }

            if (count($users) > 0) {
                $usersChildren = $this->formatSelectUsers($users);
            }

            if (count($subAgents) > 0) {
                $dataAgents = array_merge($dataAgents, $agentsChildren['agents']);
                $dataUsers  = array_merge($dataUsers, $agentsChildren['users']);
            }
            if (count($users) > 0) {
                $dataChildrenUsers = $usersChildren;
                foreach ($dataChildrenUsers as $user) {
                    $dataUsers[] = $user;
                }
            }

            $dataAgents[] = [
                "id"       => $agent->user_id,
                "username" => $agent->username,
                'type'     => 'agent',
            ];

            $data = [
                'agents' => $dataAgents,
                'users'  => $dataUsers,
            ];
        }
        return $data;
    }

    /**
     * format select users
     *
     * @param array $users Users data
     */
    public function formatSelectUsers($users)
    {
        $dataUsers = [];
        foreach ($users as $user) {
            $dataUsers[] = [
                'id'       => $user->id,
                'username' => $user->username,
                'type'     => 'user',
            ];
        }
        return $dataUsers;
    }

    /**
     * Format dependency tree
     *
     * @param array $agents Agents data
     * @param array $users Users data
     * @return false|string
     */
    public function dependencyTree($agent, $agents, $users)
    {
        $tree             = [
            'id'      => $agent->id,
            'text'    => $agent->username,
            'status'  => $agent->status,
            'icon'    => 'fa fa-diamond',
            'type'    => 'agent',
            'state'   => [
                'opened'   => true,
                'selected' => true,
            ],
            'li_attr' => [
                'data_type' => 'agent',
                'class'     => 'init_tree'
            ]
        ];
        $agentsChildren   = $this->agentsTree($agents);
        $usersChildren    = $this->usersTree($users);
        $children         = array_merge($agentsChildren, $usersChildren);
        $tree['children'] = $children;
        return json_encode($tree);
    }

    /**
     * Agents tree
     *
     * @param array $agents Agents data
     * @return array
     */
    private function agentsTree($agents)
    {
        $agentsRepo = new AgentsRepo();
        $currency   = session('currency');
        $tree       = [];

        foreach ($agents as $agent) {
            $children  = null;
            $subAgents = $agentsRepo->getAgentsByOwner($agent->user_id, $currency);
            $users     = $agentsRepo->getUsersByAgent($agent->id, $currency);

            if (count($subAgents) > 0) {
                $agentsChildren = $this->agentsTree($subAgents);
            }

            if (count($users) > 0) {
                $usersChildren = $this->usersTree($users);
            }

            if (count($subAgents) > 0 && count($users) > 0) {
                $children = array_merge($agentsChildren, $usersChildren);
            } else {
                if (count($subAgents) > 0) {
                    $children = $agentsChildren;
                }

                if (count($users) > 0) {
                    $children = $usersChildren;
                }
            }

            $icon     = $agent->master ? 'star' : 'users';
            $treeItem = [
                'id'      => $agent->user_id,
                'text'    => $agent->username,
                'status'  => $agent->status,
                'icon'    => "fa fa-{$icon}",
                'li_attr' => [
                    'data_type' => 'agent',
                    'class'     => 'init_agent'
                ]
            ];

            if (! is_null($children)) {
                $treeItem['children'] = $children;
            }
            $tree[] = $treeItem;
        }
        return $tree;
    }

    /**
     * Users tree
     *
     * @param array $users Users data
     * @return array
     */
    private function usersTree($users)
    {
        $children = [];

        foreach ($users as $user) {
            $children[] = [
                'id'      => $user->id,
                'text'    => $user->username,
                'status'  => $user->status,
                'icon'    => 'fa fa-user',
                'li_attr' => [
                    'data_type' => 'user',
                    'class'     => 'init_user'
                ]
            ];
        }
        return $children;
    }

    /**
     * Format dependency tree filter
     *
     * @param json $agent agent auth
     * @param array $agents Agents data
     * @param array $users Users data
     * @param bool $status Status
     * @return false|string
     */
    public function dependencyTreeFilter($agent, $agents, $users, $status)
    {
        $tree             = [
            'id'      => $agent->id,
            'text'    => $agent->username,
            'icon'    => 'fa fa-diamond',
            'type'    => 'agent',
            'state'   => [
                'opened'   => true,
                'selected' => true,
            ],
            'li_attr' => [
                'data_type' => 'agent'
            ]
        ];
        $agentsChildren   = $this->agentsTreeFilter($agents, $status);
        $usersChildren    = $this->usersTreeFilter($users, $status);
        $children         = array_merge($agentsChildren, $usersChildren);
        $tree['children'] = $children;
        return $tree;
    }

    /**
     * Agents tree filter
     *
     * @param array $agents Agents data
     * @param bool $status Status
     * @return array
     */
    private function agentsTreeFilter($agents, $status)
    {
        $agentsRepo     = new AgentsRepo();
        $currency       = session('currency');
        $tree           = [];
        $agentsChildren = '';
        $usersChildren  = '';
        foreach ($agents as $agent) {
            $children  = null;
            $subAgents = $agentsRepo->getAgentsByOwner($agent->user_id, $currency);
            $users     = $agentsRepo->getUsersByAgent($agent->id, $currency);
            if (count($subAgents) > 0) {
                $agentsChildren = $this->agentsTreeFilter($subAgents, $status);
            }

            if (count($users) > 0) {
                $usersChildren = $this->usersTreeFilter($users, $status);
            }

            if (count($subAgents) > 0 && count($users) > 0) {
                $children = array_merge($agentsChildren, $usersChildren);
            } else {
                if (count($subAgents) > 0) {
                    $children = $agentsChildren;
                }

                if (count($users) > 0) {
                    $children = $usersChildren;
                }
            }

            if (! empty($children)) {
                $icon     = $agent->master ? 'star' : 'users';
                $treeItem = [
                    'id'      => $agent->user_id,
                    'text'    => $agent->username,
                    'icon'    => "fa fa-{$icon}",
                    'li_attr' => [
                        'data_type' => 'agent'
                    ]
                ];

                $treeItem['children'] = $children;
                $tree[]               = $treeItem;
            }
        }
        return $tree;
    }

    /**
     * Users tree filter
     *
     * @param array $users Users data
     * @param bool $status Status
     * @return array
     */
    private function usersTreeFilter($users, $status)
    {
        $children = [];
        foreach ($users as $user) {
            if ($user->status == $status) {
                $children[] = [
                    'id'      => $user->id,
                    'text'    => $user->username,
                    'icon'    => 'fa fa-user',
                    'li_attr' => [
                        'data_type' => 'user'
                    ]
                ];
            }
        }
        return $children;
    }

    /**
     * Format dependency tree Ids
     *
     * @param array $agents Agents data
     * @param array $users Users data
     * @return false|string
     */
    public function dependencyTreeIds($agent, $agents, $users)
    {
        $tree           = [
            'id' => $agent->id,
        ];
        $agentsChildren = $this->agentsTreeIds($agents);
        $usersChildren  = $this->usersTreeIds($users);
        $children       = array_merge($agentsChildren, $usersChildren);
        $tree           = array_merge($children, $tree);

        return Arr::flatten($tree);
    }

    /**
     * Agents tree Ids
     *
     * @param array $agents Agents data
     * @return array
     */
    private function agentsTreeIds($agents)
    {
        $agentsRepo = new AgentsRepo();
        $currency   = session('currency');
        $tree       = [];

        foreach ($agents as $agent) {
            $children  = null;
            $subAgents = $agentsRepo->getAgentsByOwner($agent->user_id, $currency);
            $users     = $agentsRepo->getUsersByAgent($agent->id, $currency);

            if (count($subAgents) > 0) {
                $agentsChildren = $this->agentsTreeIds($subAgents);
            }

            if (count($users) > 0) {
                $usersChildren = $this->usersTreeIds($users);
            }

            if (count($subAgents) > 0 && count($users) > 0) {
                $children = array_merge($agentsChildren, $usersChildren);
            } else {
                if (count($subAgents) > 0) {
                    $children = $agentsChildren;
                }

                if (count($users) > 0) {
                    $children = $usersChildren;
                }
            }

            $treeItem = [
                'id' => $agent->user_id,
            ];

            if (! is_null($children)) {
                $treeItem[] = $children;
            }
            $tree[] = $treeItem;
        }
        return $tree;
    }

    /**
     * Users tree Ids
     *
     * @param array $users Users data
     * @return array
     */
    private function usersTreeIds($users)
    {
        $children = [];
        foreach ($users as $user) {
            $children[] = [
                'id' => $user->id,
            ];
        }
        return $children;
    }

    /**
     * Financial state
     *
     * @param int $whitelabel Whitelabel ID
     * @param array $agents Agents data
     * @param array $users Users data
     * @param string $currency Currency ISO
     * @param array $providers Providers IDs
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return string
     */
    public function financialState($whitelabel, $agents, $users, $currency, $providers, $startDate, $endDate)
    {
        $closuresUsersTotalsRepo = new ClosuresUsersTotalsRepo();
        $totalPlayed             = 0;
        $totalWon                = 0;
        $totalProfit             = 0;
        $totalCollect            = 0;
        $totalToPay              = 0;
        $providersTotalPlayed    = [];
        $providersTotalWon       = [];
        $providersTotalProfit    = [];
        $providerIds             = [];
        $providersTitles         = null;

        $html = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover"><thead><tr><th class="text-center">%s</th>',
            _i('Agents / Players')
        );

        foreach ($providers as $provider) {
            $nameTmp = Providers::getName($provider->id);
            //TODO VALIDANDO PROVEEDOR !NULL
            if (! is_null($nameTmp)) {
                $providerIds[]   = $provider->id;
                $html            .= "<th colspan='3' class='text-center'>" . Providers::getName(
                        $provider->id
                    ) . "</th>";
                $providersTitles .= sprintf(
                    '<td class="text-right"><strong>%s</strong></td>',
                    _i('Bet')
                );
                $providersTitles .= sprintf(
                    '<td class="text-right"><strong>%s</strong></td>',
                    _i('Bets')
                );
                $providersTitles .= sprintf(
                    '<td class="text-right"><strong>%s</strong></td>',
                    _i('Netwin')
                );
            } else {
                // Log::info('provider null',[$provider]);
            }
        }

        $html .= sprintf(
            '<th colspan="5" class="text-center">%s</th>',
            _i('Totals')
        );
        $html .= '</tr><tr><td></td>';
        $html .= $providersTitles;

        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Bet')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Bets')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Netwin')
        );

        $html .= '<td class="text-right"><strong>%</strong></td>';
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Commission')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('To pay')
        );
        $html .= '</tr></thead><tbody>';

        foreach ($agents as $agent) {
            $auxHTML           = '';
            $agentsUsersIds    = [];
            $agentTotalPlayed  = 0;
            $agentTotalWon     = 0;
            $agentTotalProfit  = 0;
            $agentTotalCollect = 0;
            $agentTotalToPay   = 0;
            $providerPlayed    = [];
            $providerWon       = [];
            $providerProfit    = [];
            $dependency        = $this->dependency($agent, $currency);

            foreach ($dependency as $dependencyItem) {
                $agentsUsersIds[] = $dependencyItem['id'];
            }

            $auxHTML .= sprintf(
                '<tr><td>%s <strong>%s</strong></td>',
                $agent->username,
                _i('(Agent)')
            );

            if (count($dependency) > 0) {
                $financial = $closuresUsersTotalsRepo->getUsersTotalsByIdsAndProvidersGroupedByProvider(
                    $whitelabel,
                    $startDate,
                    $endDate,
                    $currency,
                    $agentsUsersIds
                );

                foreach ($financial as $item) {
                    $agentTotalPlayed += $item->played;
                    $agentTotalWon    += $item->won;
                    $agentTotalProfit += $item->profit;

                    if (isset($providersTotalPlayed[$item->provider_id])) {
                        $providersTotalPlayed[$item->provider_id] = [
                            'total' => $providersTotalPlayed[$item->provider_id]['total'] + $item->played
                        ];
                    } else {
                        $providersTotalPlayed[$item->provider_id] = [
                            'total' => $item->played
                        ];
                    }

                    if (isset($providersTotalWon[$item->provider_id])) {
                        $providersTotalWon[$item->provider_id] = [
                            'total' => $providersTotalWon[$item->provider_id]['total'] + $item->won
                        ];
                    } else {
                        $providersTotalWon[$item->provider_id] = [
                            'total' => $item->won
                        ];
                    }

                    if (isset($providersTotalProfit[$item->provider_id])) {
                        $providersTotalProfit[$item->provider_id] = [
                            'total' => $providersTotalProfit[$item->provider_id]['total'] + $item->profit
                        ];
                    } else {
                        $providersTotalProfit[$item->provider_id] = [
                            'total' => $item->profit
                        ];
                    }

                    if (isset($providerPlayed[$item->provider_id])) {
                        $providerPlayed[$item->provider_id] = [
                            'total' => $providerPlayed[$item->provider_id]['total'] + $item->played
                        ];
                    } else {
                        $providerPlayed[$item->provider_id] = [
                            'total' => $item->played
                        ];
                    }

                    if (isset($providerWon[$item->provider_id])) {
                        $providerWon[$item->provider_id] = [
                            'total' => $providerWon[$item->provider_id]['total'] + $item->won
                        ];
                    } else {
                        $providerWon[$item->provider_id] = [
                            'total' => $item->won
                        ];
                    }

                    if (isset($providerProfit[$item->provider_id])) {
                        $providerProfit[$item->provider_id] = [
                            'total' => $providerProfit[$item->provider_id]['total'] + $item->profit
                        ];
                    } else {
                        $providerProfit[$item->provider_id] = [
                            'total' => $item->profit
                        ];
                    }
                }
            }

            foreach ($providerIds as $provider) {
                $played = isset($providerPlayed[$provider]) ? $providerPlayed[$provider]['total'] : 0;
                $won    = isset($providerWon[$provider]) ? $providerWon[$provider]['total'] : 0;
                $profit = isset($providerProfit[$provider]) ? $providerProfit[$provider]['total'] : 0;

                $auxHTML .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($played, 2)
                );
                $auxHTML .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($won, 2)
                );
                $auxHTML .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($profit, 2)
                );
            }

            if ($agentTotalPlayed > 0 || $agentTotalWon > 0) {
                $html .= $auxHTML;
                if ($agent->percentage > 0) {
                    $percentage        = number_format($agent->percentage, 2);
                    $agentTotalCollect = $agentTotalProfit * ($percentage / 100);
                } else {
                    $percentage        = '-';
                    $agentTotalCollect = $agentTotalProfit;
                }
                if ($agentTotalProfit > 0 || $agentTotalCollect > 0) {
                    $agentTotalToPay = $agentTotalProfit - $agentTotalCollect;
                } else {
                    $agentTotalToPay = $agentTotalProfit - $agentTotalCollect;
                }
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($agentTotalPlayed, 2)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($agentTotalWon, 2)
                );
                $html .= sprintf(
                    '<td class="text-right bg-warning">%s</td>',
                    number_format($agentTotalProfit, 2)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    $percentage
                );
                $html .= sprintf(
                    '<td class="text-right bg-primary">%s</td></tr>',
                    number_format($agentTotalCollect, 2)
                );
                $html .= sprintf(
                    '<td class="text-right bg-success"><strong>%s</strong></td>',
                    number_format($agentTotalToPay, 2)
                );
            }
            $totalPlayed  += $agentTotalPlayed;
            $totalWon     += $agentTotalWon;
            $totalProfit  += $agentTotalProfit;
            $totalCollect += $agentTotalCollect;
            $totalToPay   += $agentTotalToPay;
        }

        $usersIds = [];
        foreach ($users as $user) {
            $usersIds[] = $user->id;
        }
        if (count($usersIds) > 0) {
            $usersTotals = $closuresUsersTotalsRepo->getUsersTotalsByIdsGroupedByProvider(
                $whitelabel,
                $startDate,
                $endDate,
                $currency,
                $usersIds
            );

            foreach ($users as $user) {
                $userTotal = [];
                foreach ($usersTotals as $total) {
                    if ($user->id == $total->id) {
                        $userTotal[] = $total;
                    }
                }
                $userTotalPlayed = 0;
                $userTotalWon    = 0;
                $userTotalProfit = 0;

                if (count($userTotal) > 0) {
                    $html .= sprintf(
                        '<tr><td>%s <strong>%s</strong></td>',
                        $user->username,
                        _i('(Player)')
                    );

                    foreach ($providers as $provider) {
                        if (! is_null($provider->tickets_table)) {
                            $played = 0;
                            $won    = 0;
                            $profit = 0;

                            foreach ($userTotal as $total) {
                                if ($total->provider_id == $provider->id) {
                                    $played += $total->played;
                                    $won    += $total->won;
                                    $profit += $total->profit;
                                    break;
                                }
                            }

                            $html .= sprintf(
                                '<td class="text-right">%s</td>',
                                number_format($played, 2)
                            );
                            $html .= sprintf(
                                '<td class="text-right">%s</td>',
                                number_format($won, 2)
                            );
                            $html .= sprintf(
                                '<td class="text-right">%s</td>',
                                number_format($profit, 2)
                            );

                            $userTotalPlayed += $played;
                            $userTotalWon    += $won;
                            $userTotalProfit += $profit;

                            if (isset($providersTotalPlayed[$provider->id])) {
                                $providersTotalPlayed[$provider->id] = [
                                    'total' => $providersTotalPlayed[$provider->id]['total'] + $played
                                ];
                            } else {
                                $providersTotalPlayed[$provider->id] = [
                                    'total' => $played
                                ];
                            }

                            if (isset($providersTotalWon[$provider->id])) {
                                $providersTotalWon[$provider->id] = [
                                    'total' => $providersTotalWon[$provider->id]['total'] + $won
                                ];
                            } else {
                                $providersTotalWon[$provider->id] = [
                                    'total' => $won
                                ];
                            }

                            if (isset($providersTotalProfit[$provider->id])) {
                                $providersTotalProfit[$provider->id] = [
                                    'total' => $providersTotalProfit[$provider->id]['total'] + $profit
                                ];
                            } else {
                                $providersTotalProfit[$provider->id] = [
                                    'total' => $profit
                                ];
                            }
                        }
                    }

                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($userTotalPlayed, 2)
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($userTotalWon, 2)
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($userTotalProfit, 2)
                    );
                    $html .= '<td class="text-right">-</td>';
                    $html .= '<td class="text-right">-</td></tr>';

                    $totalPlayed += $userTotalPlayed;
                    $totalWon    += $userTotalWon;
                    $totalProfit += $userTotalProfit;
                }
            }
        }

        $html .= sprintf(
            '<tr><td><strong>%s</strong></td>',
            _i('Totals')
        );

        foreach ($providerIds as $provider) {
            $playedProvider = $providersTotalPlayed[$provider]['total'] ?? 0;
            $wonProvider    = $providersTotalWon[$provider]['total'] ?? 0;
            $profitProvider = $providersTotalProfit[$provider]['total'] ?? 0;
            $html           .= sprintf(
                '<td class="text-right"><strong>%s</strong></td>',
                number_format($playedProvider, 2)
            );
            $html           .= sprintf(
                '<td class="text-right"><strong>%s</strong></td>',
                number_format($wonProvider, 2)
            );
            $html           .= sprintf(
                '<td class="text-right"><strong>%s</strong></td>',
                number_format($profitProvider, 2)
            );
        }

        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalPlayed, 2)
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalWon, 2)
        );
        $html .= sprintf(
            '<td class="text-right bg-warning"><strong>%s</strong></td>',
            number_format($totalProfit, 2)
        );
        $html .= '<td class="text-right"><strong>-</strong></td>';

        $html .= sprintf(
            '<td class="text-right bg-primary"><strong>%s</strong></td>',
            number_format($totalCollect, 2)
        );
        $html .= sprintf(
            '<td class="text-right bg-success"><strong>%s</strong></td>',
            number_format($totalToPay, 2)
        );
        $html .= '<td class="text-right"><strong>-</strong></td>';
        $html .= '</tr></tbody></table>';
        return $html;
    }

    /**
     * Get dependency
     *
     * @param object $agent Agent data
     * @param string $currency Currency ISO
     * @return array
     */
    public function dependency($agent, $currency)
    {
        $agentsRepo = new AgentsRepo();
        $usersData  = [];
        $agents     = $agentsRepo->getAgentsDependency($agent->user_id, $currency);
        $agentsData = [];
        foreach ($agents as $agent) {
            $agentsData[] = $agent->id;
        }
        $users = $agentsRepo->getUsersByAgents($agentsData, $currency);

        foreach ($users as $user) {
            $usersData[] = [
                'id'       => $user->id,
                'username' => $user->username
            ];
        }
        return $usersData;
    }

    /**
     * @param $whitelabel
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @param $treeUsers
     * @return string
     */
    public function financialStateProvider($whitelabel, $currency, $startDate, $endDate, $treeUsers)
    {
        $closuresUsersTotalsRepo = new ClosuresUsersTotals2023Repo();
        $providerId              = $closuresUsersTotalsRepo->getClosureByGroupTotals(
            $startDate,
            $endDate,
            $whitelabel,
            $currency,
            $treeUsers,
            'provider_id'
        );

        $htmlProvider = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>

                        </tr>
                    </thead>',
            _i('Provider'),
            _i('Won'),
            _i('Win'),
            _i('Bet'),
            _i('Profit'),
//            _i('Rtp'),
        );

        if (! empty($htmlProvider)) {
            $htmlProvider .= "<tbody>";
            foreach ($providerId as $item => $value) {
                $htmlProvider .= "<tr class=''>";
                $htmlProvider .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $closuresUsersTotalsRepo->nameProvider(
                        $value->provider_id
                    ) . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->total_played, 2) . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->total_won, 2) . "</td>";
                $htmlProvider .= "<td class='text-center'>" . $value->total_bet . "</td>";
                $htmlProvider .= "<td class='text-center'>" . number_format($value->total_profit, 2) . "</td>";
//                $htmlProvider .= "<td class='text-center'>" . number_format($value->total_rtp ,2) . "</td>";
                $htmlProvider .= "</tr>";
            }
            $htmlProvider .= "</tbody>";
        } else {
            $htmlProvider .= "<tbody><tr class='table-secondary'><td class='text-center' colspan='5'>Sin registros</td></tr></tbody>";
        }

        return $htmlProvider;
    }

    /**
     * @param $whitelabel
     * @param $agents
     * @param $users
     * @param $currency
     * @param $providers
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public function financialStateRow($whitelabel, $agents, $users, $currency, $providers, $startDate, $endDate)
    {
        $closuresUsersTotalsRepo = new ClosuresUsersTotalsRepo();
        $totalPlayed             = 0;
        $totalWon                = 0;
        $totalProfit             = 0;
        $totalCollect            = 0;
        $totalToPay              = 0;
        $providersTotalPlayed    = [];
        $providersTotalWon       = [];
        $providersTotalProfit    = [];
        $providerIds             = [];
        $providersTitles         = null;

        $html = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover">
                       <thead>
                            <tr>
                                <th class="text-center">%s</th>
                                <th class="text-center">%s</th>
                                <th class="text-center">%s</th>
                                <th class="text-center">%s</th>
                                <th class="text-center">%s</th>
                                <th class="text-center">%s</th>
                            </tr>
                     </thead>',
            _i('Categories'),
            _i('Bet'),
            _i('Bets'),
            _i('Netwin'),
            _i('Commission'),
            _i('Details'),
        );

        if (count($providers) > 0) {
            $arrayAgents = [];
            foreach ($agents as $index => $agent) {
                $agentsUsersIds    = [];
                $agentTotalPlayed  = 0;
                $agentTotalWon     = 0;
                $agentTotalProfit  = 0;
                $agentTotalCollect = 0;
                $agentTotalToPay   = 0;
                $providerPlayed    = [];
                $providerWon       = [];
                $providerProfit    = [];
                $dependency        = $this->dependency($agent, $currency);

                foreach ($dependency as $dependencyItem) {
                    $agentsUsersIds[] = $dependencyItem['id'];
                }

                if (count($dependency) > 0) {
                    $financial  = $closuresUsersTotalsRepo->getUsersTotalsByIdsAndProvidersGroupedByProvider(
                        $whitelabel,
                        $startDate,
                        $endDate,
                        $currency,
                        $agentsUsersIds
                    );
                    $financial2 = $financial;
                    foreach ($financial as $item) {
                        $agentTotalPlayed += $item->played;
                        $agentTotalWon    += $item->won;
                        $agentTotalProfit += $item->profit;

                        if (isset($providersTotalPlayed[$item->provider_id])) {
                            $providersTotalPlayed[$item->provider_id] = [
                                'total' => $providersTotalPlayed[$item->provider_id]['total'] + $item->played
                            ];
                        } else {
                            $providersTotalPlayed[$item->provider_id] = [
                                'total' => $item->played
                            ];
                        }

                        if (isset($providersTotalWon[$item->provider_id])) {
                            $providersTotalWon[$item->provider_id] = [
                                'total' => $providersTotalWon[$item->provider_id]['total'] + $item->won
                            ];
                        } else {
                            $providersTotalWon[$item->provider_id] = [
                                'total' => $item->won
                            ];
                        }

                        if (isset($providersTotalProfit[$item->provider_id])) {
                            $providersTotalProfit[$item->provider_id] = [
                                'total' => $providersTotalProfit[$item->provider_id]['total'] + $item->profit
                            ];
                        } else {
                            $providersTotalProfit[$item->provider_id] = [
                                'total' => $item->profit
                            ];
                        }

                        if (isset($providerPlayed[$item->provider_id])) {
                            $providerPlayed[$item->provider_id] = [
                                'total' => $providerPlayed[$item->provider_id]['total'] + $item->played
                            ];
                        } else {
                            $providerPlayed[$item->provider_id] = [
                                'total' => $item->played
                            ];
                        }

                        if (isset($providerWon[$item->provider_id])) {
                            $providerWon[$item->provider_id] = [
                                'total' => $providerWon[$item->provider_id]['total'] + $item->won
                            ];
                        } else {
                            $providerWon[$item->provider_id] = [
                                'total' => $item->won
                            ];
                        }

                        if (isset($providerProfit[$item->provider_id])) {
                            $providerProfit[$item->provider_id] = [
                                'total' => $providerProfit[$item->provider_id]['total'] + $item->profit
                            ];
                        } else {
                            $providerProfit[$item->provider_id] = [
                                'total' => $item->profit
                            ];
                        }

                        $arrayAgents[$item->provider_id][] = [
                            'username' => $agent->username,
                            'provider' => [
                                'played' => isset($providerPlayed[$item->provider_id]) ? $providerPlayed[$item->provider_id]['total'] : 0,
                                'won'    => isset($providerWon[$item->provider_id]) ? $providerWon[$item->provider_id]['total'] : 0,
                                'profit' => isset($providerProfit[$item->provider_id]) ? $providerProfit[$item->provider_id]['total'] : 0,
                            ],
                        ];
                    }
                }

                $totalPlayed  += $agentTotalPlayed;
                $totalWon     += $agentTotalWon;
                $totalProfit  += $agentTotalProfit;
                $totalCollect += $agentTotalCollect;
                $totalToPay   += $agentTotalToPay;
            }

            $html .= sprintf(
                '<tbody>',
            );

            foreach ($providers as $provider) {
                $providerIds[] = $provider->id;
                //TODO CATEGORY
                $html .= "<tr>
                          <td class='text-center'>" . Providers::getName($provider->id) . "</td>";
                //TODO BET
                $html .= "<td class='text-center'>" . (isset($providerPlayed[$provider->id]) ? $providerPlayed[$provider->id]['total'] : 0) . "</td>";
                //TODO BETS
                $html .= "<td class='text-center'>" . (isset($providerWon[$provider->id]) ? $providerWon[$provider->id]['total'] : 0) . "</td>";
                //TODO NETWIN
                $html .= "<td class='text-center'>" . (isset($providerProfit[$provider->id]) ? $providerProfit[$provider->id]['total'] : 0) . "</td>";
                //TODO COMMISSION
                $html .= "<td class='text-center'>5% EJEMPLO</td>";
                //TODO DETAILS
                $html .= "<td class='text-center' data-users='[]' data-agents='" . (isset($arrayAgents[$provider->id]) ? json_encode(
                        $arrayAgents[$provider->id]
                    ) : json_encode([])) . "'><i class='hs-admin-plus'>+</i></td>
                          </tr>";
            }
            $html .= sprintf(
                '</tbody></table>',
            );
        }
        //TODO => TEST
        return [
            'html'       => $html,
            'financial2' => $financial2,
        ];
    }

    /**
     * Financial state summary
     *
     * @param int $whitelabel Whitelabel ID
     * @param array $agents Agents data
     * @param array $users Users data
     * @param string $currency Currency ISO
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return string
     */
    public function financialStateSummary($whitelabel, $agents, $users, $currency, $startDate, $endDate, $iAgent = null)
    {
        $closuresUsersTotalsRepo = new ClosuresUsersTotalsRepo();
        $totalPlayed             = 0;
        $totalWon                = 0;
        $totalProfit             = 0;
        $totalCollect            = 0;

        $html = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover"><thead><tr><th class="text-center">%s</th>',
            _i('Agents / Players')
        );

        $html .= sprintf(
            '<th colspan="5" class="text-center">%s</th>',
            _i('Totals')
        );
        $html .= '</tr><tr><td></td>';

        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Credit')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Debit')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Profit')
        );
        $html .= '<td class="text-right"><strong>%</strong></td>';
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Comission')
        );
        $html .= '</tr></thead><tbody>';

        foreach ($agents as $agent) {
            $auxHTML           = '';
            $agentsUsersIds    = [];
            $agentTotalPlayed  = 0;
            $agentTotalWon     = 0;
            $agentTotalProfit  = 0;
            $agentTotalCollect = 0;
            $dependency        = $this->dependency($agent, $currency);

            foreach ($dependency as $dependencyItem) {
                $agentsUsersIds[] = $dependencyItem['id'];
            }

            $auxHTML .= sprintf(
                '<tr class="init_agent"><td>%s <strong>%s</strong></td>',
                $agent->username,
                ''
            //_i('(Agent)')
            );

            if (count($dependency) > 0) {
                $financial = $closuresUsersTotalsRepo->getUsersTotalsByIds(
                    $whitelabel,
                    $startDate,
                    $endDate,
                    $currency,
                    $agentsUsersIds
                );

                foreach ($financial as $item) {
                    $agentTotalPlayed += $item->played;
                    $agentTotalWon    += $item->won;
                    $agentTotalProfit += $item->profit;
                }
            }

            if ($agentTotalPlayed > 0 || $agentTotalWon > 0) {
                $html       .= $auxHTML;
                $percentage = '-';
                if ($agent->percentage > 0) {
                    $percentage = number_format($agent->percentage, 2);
                    //$percentage = $agent->percentage;
                    $agentTotalCollect = $agentTotalProfit * ($agent->percentage / 100);
                } else {
                    $agentTotalCollect = $agentTotalProfit;
                }

                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($agentTotalPlayed, 2)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($agentTotalWon, 2)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($agentTotalProfit, 2)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    ($percentage != '-' ? $percentage . '%' : $percentage)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td></tr>',
                    number_format($agentTotalCollect, 2)
                );
            }
            $totalPlayed  += $agentTotalPlayed;
            $totalWon     += $agentTotalWon;
            $totalProfit  += $agentTotalProfit;
            $totalCollect += $agentTotalCollect;
        }

        $usersIds = [];
        foreach ($users as $user) {
            $usersIds[] = $user->id;
        }
        if (count($usersIds) > 0) {
            $usersTotals = collect(
                $closuresUsersTotalsRepo->getUsersTotalsByIds($whitelabel, $startDate, $endDate, $currency, $usersIds)
            );

            foreach ($users as $user) {
                $userTotal       = $usersTotals->where('id', $user->id)->first();
                $userTotalPlayed = 0;
                $userTotalWon    = 0;
                $userTotalProfit = 0;

                if (! is_null($userTotal)) {
                    $played = $userTotal->played;
                    $won    = $userTotal->won;
                    $profit = $userTotal->profit;

                    $html .= sprintf(
                        '<tr class="init_user"><td>%s <strong>%s</strong></td>',
                        $user->username,
                        ''
                    //_i('(Player)')
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($played, 2)
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($won, 2)
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($profit, 2)
                    );

                    $userTotalPlayed += $played;
                    $userTotalWon    += $won;
                    $userTotalProfit += $profit;

                    $html        .= '<td class="text-right">-</td>';
                    $html        .= '<td class="text-right">-</td></tr>';
                    $totalPlayed += $userTotalPlayed;
                    $totalWon    += $userTotalWon;
                    $totalProfit += $userTotalProfit;
                }
            }
        }

        $html .= sprintf(
            '<tr style="background-color: #ffcc5ead;"><td><strong>%s</strong></td>',
            _i('Totals')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalPlayed, 2)
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalWon, 2)
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalProfit, 2)
        );
        $html .= '<td class="text-right"><strong>-</strong></td>';
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td></tr>',
            '-'
        //number_format($totalCollect, 2)
        );
        $html .= sprintf(
            '<tr><td colspan="6"></td></tr>',
        );

        if (! is_null($iAgent) && isset($iAgent->percentage) && $iAgent->percentage > 0) {
            //TODO COMISSION
            $percentageTotalToPay = $iAgent->percentage;
            $totalToPay           = $totalProfit * ($percentageTotalToPay / 100);

            $html .= sprintf(
                '<tr style="background-color: #92ff678c;"><td colspan="3"></td><td class="text-right"><strong>%s</strong></td>',
                _i('Total Comission')
            );
            $html .= sprintf(
                '<td class="text-right"><strong>%s</strong></td>',
                $percentageTotalToPay . '%'
            );
            $html .= sprintf(
                '<td class="text-right"><strong>%s</strong></td></tr>',
                number_format($totalToPay, 2)
            );

            //TODO TOTAL TO PAY
            $html .= sprintf(
                '<tr style="background-color: #ff588373;"><td colspan="3"></td><td class="text-right"><strong>%s</strong></td>',
                _i('Total to pay')
            );
            $html .= sprintf(
                '<td class="text-right"><strong>%s</strong></td>',
                (100 - $percentageTotalToPay) . '%'
            );
            $html .= sprintf(
                '<td class="text-right"><strong>%s</strong></td></tr>',
                number_format(($totalProfit - $totalToPay), 2)
            );
        }


        $html .= '</tbody></table>';
        return $html;
    }

    /**
     * Financial state summary bonus
     *
     * @param int $whitelabel Whitelabel ID
     * @param array $agents Agents data
     * @param array $users Users data
     * @param string $currency Currency ISO
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return string
     */
    public function financialStateSummaryBonus($whitelabel, $agents, $users, $currency, $startDate, $endDate)
    {
        $closuresUsersTotalsRepo = new ClosuresUsersTotalsRepo();
        $transactionsRepo        = new TransactionsRepo();
        $totalPlayed             = 0;
        $totalWon                = 0;
        $totalBonus              = 0;
        $totalProfit             = 0;
        $totalProfitReal         = 0;
        $totalCollect            = 0;

        $html = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover"><thead><tr><th class="text-center">%s</th>',
            _i('Agents / Players')
        );

        $html .= sprintf(
            '<th colspan="7" class="text-center">%s</th>',
            _i('Totals')
        );
        $html .= '</tr><tr><td></td>';

        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Credit')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Debit')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Total')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Bonus')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Profit')
        );
        $html .= '<td class="text-right"><strong>%</strong></td>';
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Collect')
        );
        $html .= '</tr></thead><tbody>';

        foreach ($agents as $agent) {
            $auxHTML              = '';
            $agentsUsersIds       = [];
            $agentTotalPlayed     = 0;
            $agentTotalWon        = 0;
            $agentTotalProfit     = 0;
            $agentTotalBonus      = 0;
            $agentTotalProfitReal = 0;
            $agentTotalCollect    = 0;
            $dependency           = $this->dependency($agent, $currency);

            foreach ($dependency as $dependencyItem) {
                $agentsUsersIds[] = $dependencyItem['id'];
            }

            $auxHTML .= sprintf(
                '<tr><td>%s <strong>%s</strong></td>',
                $agent->username,
                _i('(Agent)')
            );

            if (count($dependency) > 0) {
                $financial   = $closuresUsersTotalsRepo->getUsersTotalsByIds(
                    $whitelabel,
                    $startDate,
                    $endDate,
                    $currency,
                    $agentsUsersIds
                );
                $bonusTotals = $transactionsRepo->getBonusTotalByUsers(
                    $agentsUsersIds,
                    $currency,
                    $startDate,
                    $endDate
                );

                foreach ($financial as $item) {
                    $agentTotalPlayed += $item->played;
                    $agentTotalWon    += $item->won;
                    $agentTotalProfit += $item->profit;
                }

                foreach ($bonusTotals as $item) {
                    $agentTotalBonus += $item->bonus;
                }
                $agentTotalProfitReal = $agentTotalProfit - $agentTotalBonus;
            }

            if ($agentTotalPlayed > 0 || $agentTotalWon > 0) {
                $html .= $auxHTML;
                if ($agent->percentage > 0) {
                    $percentage        = number_format($agent->percentage, 2);
                    $agentTotalCollect = $agentTotalProfitReal * ($percentage / 100);
                } else {
                    $percentage        = '-';
                    $agentTotalCollect = $agentTotalProfitReal;
                }
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($agentTotalPlayed, 2)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($agentTotalWon, 2)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($agentTotalProfit, 2)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($agentTotalBonus, 2)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($agentTotalProfitReal, 2)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    $percentage
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td></tr>',
                    number_format($agentTotalCollect, 2)
                );
            }
            $totalPlayed     += $agentTotalPlayed;
            $totalWon        += $agentTotalWon;
            $totalProfit     += $agentTotalProfit;
            $totalBonus      += $agentTotalBonus;
            $totalProfitReal += $agentTotalProfitReal;
            $totalCollect    += $agentTotalCollect;
        }

        $usersIds = [];
        foreach ($users as $user) {
            $usersIds[] = $user->id;
        }

        if (count($usersIds) > 0) {
            $usersTotals = collect(
                $closuresUsersTotalsRepo->getUsersTotalsByIds($whitelabel, $startDate, $endDate, $currency, $usersIds)
            );
            $bonusTotals = collect($transactionsRepo->getBonusTotalByUsers($usersIds, $currency, $startDate, $endDate));

            foreach ($users as $user) {
                $userTotal           = $usersTotals->where('id', $user->id)->first();
                $userTotalPlayed     = 0;
                $userTotalWon        = 0;
                $userTotalProfit     = 0;
                $userTotalBonus      = 0;
                $userTotalProfitReal = 0;

                if (! is_null($userTotal)) {
                    $html .= sprintf(
                        '<tr><td>%s <strong>%s</strong></td>',
                        $user->username,
                        _i('(Player)')
                    );

                    $played     = $userTotal->played;
                    $won        = $userTotal->won;
                    $profit     = $userTotal->profit;
                    $bonusData  = $bonusTotals->where('user_id', $user->id)->first();
                    $bonus      = ! is_null($bonusData) ? $bonusData->bonus : 0;
                    $profitReal = $profit - $bonus;

                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($played, 2)
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($won, 2)
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($profit, 2)
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($bonus, 2)
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($profitReal, 2)
                    );

                    $userTotalPlayed     += $played;
                    $userTotalWon        += $won;
                    $userTotalProfit     += $profit;
                    $userTotalBonus      += $bonus;
                    $userTotalProfitReal += $profitReal;

                    $html            .= '<td class="text-right">-</td>';
                    $html            .= '<td class="text-right">-</td></tr>';
                    $totalPlayed     += $userTotalPlayed;
                    $totalWon        += $userTotalWon;
                    $totalProfit     += $userTotalProfit;
                    $totalBonus      += $userTotalBonus;
                    $totalProfitReal += $userTotalProfitReal;
                }
            }
        }

        $html .= sprintf(
            '<tr><td><strong>%s</strong></td>',
            _i('Totals')
        );

        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalPlayed, 2)
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalWon, 2)
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalProfit, 2)
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalBonus, 2)
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalProfitReal, 2)
        );
        $html .= '<td class="text-right"><strong>-</strong></td>';
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalCollect, 2)
        );
        $html .= '</tr></tbody></table>';
        return $html;
    }

    /**
     * @param $whitelabel
     * @param $agents
     * @param $users
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @return string
     */
    public function financialStateSummaryNew($whitelabel, $agents, $users, $currency, $startDate, $endDate)
    {
        $closuresUsersTotals2023Repo = new ClosuresUsersTotals2023Repo();
        $totalPlayed                 = 0;
        $totalWon                    = 0;
        $totalProfit                 = 0;
        $totalCollect                = 0;

        $html = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover"><thead>
                    <tr>
                        <th class="text-center">%s</th>
                        <th colspan="5" class="text-center">%s</th>
                    </tr>',
            _i('Agents / Players'),
            _i('Totals')
        );

        $html .= '<tr>
                    <td></td>';

        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Credit')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Debit')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Profit')
        );
        $html .= '<td class="text-right"><strong>%</strong></td>';
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Collect')
        );
        $html .= '</tr></thead>';

        if (count($agents) < 0) {
            foreach ($agents as $agent) {
                $allUsersByAgent   = $closuresUsersTotals2023Repo->allUsersByAgent($agent->user_id, $currency, true);
                $agentTotalCollect = 0;
                $total_played      = 0;
                $total_won         = 0;
                $total_profit      = 0;
                $percentage        = is_null($agent->percentage) ? 0 : $agent->percentage;
                if (count($allUsersByAgent) > 0) {
                    $totals2023 = $closuresUsersTotals2023Repo->getClosureByGroupTotals(
                        $startDate,
                        $endDate,
                        $whitelabel,
                        $currency,
                        $allUsersByAgent,
                        'currency_iso'
                    );
                    foreach ($totals2023 as $item => $value) {
                        if ($percentage > 0) {
                            $percentage        = number_format($percentage, 2);
                            $agentTotalCollect = $value->total_profit * ($percentage / 100);
                        } else {
                            $agentTotalCollect = $value->total_profit;
                        }

                        $agentTotalCollect = number_format($agentTotalCollect, 2);
                        $total_played      = number_format($value->total_played, 2);
                        $total_won         = number_format($value->total_won, 2);
                        $total_profit      = number_format($value->total_profit, 2);
                        $percentage        = $value->percentage;
                    }
                }
                $html .= sprintf(
                    '<tr><td>%s <strong>%s</strong></td>',
                    $agent->username,
                    _i('(Agent)')
                );

                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    $total_played
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    $total_won,
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    $total_profit
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    $percentage . '%'
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td></tr>',
                    $agentTotalCollect
                );
            }
        }

        //return $html;
        if (count($users) > 0) {
            foreach ($users as $user) {
                $agentTotalCollect = 0;
                $total_played      = 0;
                $total_won         = 0;
                $total_profit      = 0;
                //TODO DEL AGENTE PADRE
                $percentage = 0;

                $totals2023 = $closuresUsersTotals2023Repo->getClosureByGroupTotals(
                    $startDate,
                    $endDate,
                    $whitelabel,
                    $currency,
                    [$user->user_id],
                    'user_id'
                );
                foreach ($totals2023 as $item => $value) {
                    if ($percentage > 0) {
                        $percentage        = number_format($percentage, 2);
                        $agentTotalCollect = $value->total_profit * ($percentage / 100);
                    } else {
                        $agentTotalCollect = $value->total_profit;
                    }

                    $agentTotalCollect = number_format($agentTotalCollect, 2);
                    $total_played      = number_format($value->total_played, 2);
                    $total_won         = number_format($value->total_won, 2);
                    $total_profit      = number_format($value->total_profit, 2);
                }

                $html .= sprintf(
                    '<tr><td>%s <strong>%s</strong></td>',
                    $user->username,
                    _i('(Played)')
                );

                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    $total_played
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    $total_won,
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    $total_profit
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    $percentage . '%'
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td></tr>',
                    $agentTotalCollect
                );
            }
        }

        return $html;
        /*
        foreach ($agents as $agent) {
            $auxHTML = '';
            $agentsUsersIds = [];
            $agentTotalPlayed = 0;
            $agentTotalWon = 0;
            $agentTotalProfit = 0;
            $agentTotalCollect = 0;
            $dependency = $this->dependency($agent, $currency);

            foreach ($dependency as $dependencyItem) {
                $agentsUsersIds[] = $dependencyItem['id'];
            }

            $auxHTML .= sprintf(
                '<tr><td>%s <strong>%s</strong></td>',
                $agent->username,
                _i('(Agent)')
            );

            if (count($dependency) > 0) {
                $financial = $closuresUsersTotalsRepo->getUsersTotalsByIds($whitelabel, $startDate, $endDate, $currency, $agentsUsersIds);

                foreach ($financial as $item) {
                    $agentTotalPlayed += $item->played;
                    $agentTotalWon += $item->won;
                    $agentTotalProfit += $item->profit;
                }
            }

            if ($agentTotalPlayed > 0 || $agentTotalWon > 0) {
                $html .= $auxHTML;
                if ($agent->percentage > 0) {
                    $percentage = number_format($agent->percentage, 2);
                    $agentTotalCollect = $agentTotalProfit * ($percentage / 100);

                } else {
                    $percentage = '-';
                    $agentTotalCollect = $agentTotalProfit;
                }
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($agentTotalPlayed, 2)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($agentTotalWon, 2)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    number_format($agentTotalProfit, 2)
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td>',
                    $percentage
                );
                $html .= sprintf(
                    '<td class="text-right">%s</td></tr>',
                    number_format($agentTotalCollect, 2)
                );
            }
            $totalPlayed += $agentTotalPlayed;
            $totalWon += $agentTotalWon;
            $totalProfit += $agentTotalProfit;
            $totalCollect += $agentTotalCollect;
        }
*/
        $usersIds = [];
        foreach ($users as $user) {
            $usersIds[] = $user->id;
        }
        if (count($usersIds) > 0) {
            $usersTotals = collect(
                $closuresUsersTotalsRepo->getUsersTotalsByIds($whitelabel, $startDate, $endDate, $currency, $usersIds)
            );

            foreach ($users as $user) {
                $userTotal       = $usersTotals->where('id', $user->id)->first();
                $userTotalPlayed = 0;
                $userTotalWon    = 0;
                $userTotalProfit = 0;

                if (! is_null($userTotal)) {
                    $html .= sprintf(
                        '<tr><td>%s <strong>%s</strong></td>',
                        $user->username,
                        _i('(Player)')
                    );

                    $played = $userTotal->played;
                    $won    = $userTotal->won;
                    $profit = $userTotal->profit;

                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($played, 2)
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($won, 2)
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        number_format($profit, 2)
                    );

                    $userTotalPlayed += $played;
                    $userTotalWon    += $won;
                    $userTotalProfit += $profit;

                    $html        .= '<td class="text-right">-</td>';
                    $html        .= '<td class="text-right">-</td></tr>';
                    $totalPlayed += $userTotalPlayed;
                    $totalWon    += $userTotalWon;
                    $totalProfit += $userTotalProfit;
                }
            }
        }

        $html .= sprintf(
            '<tr><td><strong>%s</strong></td>',
            _i('Totals')
        );

        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalPlayed, 2)
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalWon, 2)
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalProfit, 2)
        );
        $html .= '<td class="text-right"><strong>-</strong></td>';
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalCollect, 2)
        );
        $html .= '</tr></tbody></table>';
        return $html;
    }

    /**
     * @param $whitelabel
     * @param $agents
     * @param $users
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @param $iAgent
     * @param $showTypeUserTemp
     * @return string
     */
    public function financialStateSummaryNewTotals(
        $whitelabel,
        $agents,
        $users,
        $currency,
        $startDate,
        $endDate,
        $iAgent,
        $showTypeUserTemp = []
    ) {
        $closuresUsersTotals2023Repo = new ClosuresUsersTotals2023Repo();
        $totalPlayed                 = 0;
        $totalWon                    = 0;
        $totalProfit                 = 0;
        $totalCollect                = 0;

        $html = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover"><thead>
                    <tr>
                        <th class="text-center">%s</th>
                        <th colspan="5" class="text-center">%s</th>
                    </tr>',
            _i('Agents / Players'),
            _i('Totals')
        );

        $html .= '<tr>
                    <td></td>';

        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Credit')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Debit')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Profit')
        );
        $html .= '<td class="text-right"><strong>%</strong></td>';
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Comission')
        );
        $html .= '</tr></thead><tbody>';

        if (count($users) > 0) {
            foreach ($users as $user) {
                $total_played      = 0;
                $total_won         = 0;
                $total_profit      = 0;
                $percentageUser    = '-';
                $agentTotalCollect = 0;

                if ((int)$user->type_user != TypeUser::$player) {
                    $class           = 'init_agent';
                    $allUsersByAgent = $closuresUsersTotals2023Repo->allUsersByAgent($user->user_id, $currency, true);

                    if (count($allUsersByAgent) > 0) {
                        $totals2023 = $closuresUsersTotals2023Repo->getClosureByGroupTotals(
                            $startDate,
                            $endDate,
                            $whitelabel,
                            $currency,
                            $allUsersByAgent,
                            'currency_iso'
                        );

                        foreach ($totals2023 as $item => $value) {
                            if (isset($user->percentage) && ! is_null($user->percentage) && $user->percentage > 0) {
                                $percentageUser    = $user->percentage;
                                $percentageUser    = $percentageUser . '%';
                                $agentTotalCollect = $value->total_profit * ($user->percentage / 100);
                            } else {
                                $agentTotalCollect = $value->total_profit;
                            }
                            //TODO SUM TOTAL
                            $totalPlayed  = $totalPlayed + $value->total_played;
                            $totalWon     = $totalWon + $value->total_won;
                            $totalProfit  = $totalProfit + $value->total_profit;
                            $totalCollect = $totalCollect + $agentTotalCollect;


                            //TODO SHOW ITEM TOTAL
                            $agentTotalCollect = number_format($agentTotalCollect, 2);
                            $total_played      = number_format($value->total_played, 2);
                            $total_won         = number_format($value->total_won, 2);
                            $total_profit      = number_format($value->total_profit, 2);
                        }
                    }
                } else {
                    $class      = 'init_user';
                    $totals2023 = $closuresUsersTotals2023Repo->getClosureByGroupTotals(
                        $startDate,
                        $endDate,
                        $whitelabel,
                        $currency,
                        [$user->user_id],
                        'currency_iso'
                    );
                    foreach ($totals2023 as $item => $value) {
                        //TODO SUM TOTAL
                        $totalPlayed  = $totalPlayed + $value->total_played;
                        $totalWon     = $totalWon + $value->total_won;
                        $totalProfit  = $totalProfit + $value->total_profit;
                        $totalCollect = $totalCollect + $agentTotalCollect;

                        //TODO SHOW ITEM TOTAL
                        $agentTotalCollect = 0;//number_format($agentTotalCollect, 2);
                        $total_played      = number_format($value->total_played, 2);
                        $total_won         = number_format($value->total_won, 2);
                        $total_profit      = number_format($value->total_profit, 2);
                    }
                }

                if (in_array((int)$user->type_user, $showTypeUserTemp)) {
                    $html .= sprintf(
                        '<tr class="' . $class . '"><td><strong>%s</strong></td>',
                        $user->username
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        $total_played
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        $total_won,
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        $total_profit
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td>',
                        $percentageUser
                    );
                    $html .= sprintf(
                        '<td class="text-right">%s</td></tr>',
                        $agentTotalCollect
                    );
                }
            }
        } else {
            $html .= sprintf(
                '<tr><td class="text-center" colspan="6"><strong>%s</strong></td>',
                _i('no records')
            );
        }

        //TODO TITLE TOTAL
        $html .= sprintf(
            '<tr style="background-color: #ffcc5ead;"><td class="text-center"><strong>%s</strong></td>',
            _i('Totals')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalPlayed, 2)
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalWon, 2)
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalProfit, 2)
        );
        $html .= '<td class="text-right"><strong>-</strong></td>';
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($totalCollect, 2)
        );
        $html .= '</tr>';
        $html .= sprintf('<tr><td colspan="6"></td></tr>');

        //TODO TOTAL COMISSION
        $percentageUser = '-';
        if (isset($iAgent->percentage) && ! is_null($iAgent->percentage) && $iAgent->percentage > 0) {
            $percentageUser       = $iAgent->percentage . '%';
            $agentTotalCollectTmp = $totalProfit * (number_format($iAgent->percentage, 2) / 100);
        } else {
            $agentTotalCollectTmp = $totalProfit;
        }

        $html .= '<tr style="background-color: #92ff678c;"><td colspan="3"></td>';
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Total Comission')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            $percentageUser
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($agentTotalCollectTmp, 2)
        );
        $html .= '</tr>';
        //TODO TOTAL TO PAY
        $percentageUser = '-';
        if (isset($iAgent->percentage) && ! is_null($iAgent->percentage) && $iAgent->percentage > 0) {
            $percentageUser         = (100 - $iAgent->percentage);
            $percentageUser         = $percentageUser . '%';
            $agentTotalCollectTmp   = $totalProfit * (number_format($iAgent->percentage, 2) / 100);
            $agentTotalCollectTotal = $totalProfit - $agentTotalCollectTmp;
        } else {
            $agentTotalCollectTotal = $totalProfit;
        }

        $html .= '<tr style="background-color: #ff588373;"><td colspan="3"></td>';
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            _i('Total to pay')
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            $percentageUser
        );
        $html .= sprintf(
            '<td class="text-right"><strong>%s</strong></td>',
            number_format($agentTotalCollectTotal, 2)
        );
        $html .= '</tr>';

        $html .= '</tbody></table>';
        return $html;
    }

    /**
     * @param $whitelabel
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @param $treeUsers
     * @return string
     */
    public function financialStateUsername($whitelabel, $currency, $startDate, $endDate, $treeUsers)
    {
        $closuresUsersTotalsRepo = new ClosuresUsersTotals2023Repo();
        $username                = $closuresUsersTotalsRepo->getClosureByGroupTotals(
            $startDate,
            $endDate,
            $whitelabel,
            $currency,
            $treeUsers,
            'user_id'
        );

        $htmlUsername = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                            <th scope="col" class="text-center">%s</th>
                        </tr>
                    </thead>',
            _i('Users'),
            _i('Played'),
            _i('Win'),
            _i('Bets'),
            _i('Profit'),
            _i('Rtp'),
        );

        if (! empty($username)) {
            $htmlUsername .= "<tbody>";
            foreach ($username as $item => $value) {
                $htmlUsername .= "<tr class=''>";
                $htmlUsername .= "<td data-type='" . $closuresUsersTotalsRepo->dataUser(
                        $value->user_id
                    )->type_user . "' class='name_" . $closuresUsersTotalsRepo->dataUser(
                        $value->user_id
                    )->type_user . "'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $closuresUsersTotalsRepo->dataUser(
                        $value->user_id
                    )->username . "</td>";
                $htmlUsername .= "<td class='text-center'>" . number_format($value->total_played, 2) . "</td>";
                $htmlUsername .= "<td class='text-center'>" . number_format($value->total_won, 2) . "</td>";
                $htmlUsername .= "<td class='text-center'>" . $value->total_bet . "</td>";
                $htmlUsername .= "<td class='text-center'>" . number_format($value->total_profit, 2) . "</td>";
                $htmlUsername .= "<td class='text-center'>" . number_format(
                        ($value->total_won / $value->total_played) * 100,
                        2
                    ) . " %</td>";
                $htmlUsername .= "</tr>";
            }
            $htmlUsername .= "</tbody>";
        } else {
            $htmlUsername .= "<tbody><tr class='table-secondary'><td class='text-center' colspan='7'>" . _i(
                    'no records'
                ) . "</td></tr></tbody>";
        }
        return $htmlUsername;
    }

    /**
     * Format agent data
     *
     * @param object $user User data
     */
    public function formatAgent($user)
    {
        //TODO New route block agent and user, field action and status
        if ((int)$user->action === ActionUser::$changed_password || (int)$user->action === ActionUser::$blocked_branch) {
            $user->status = '<a href="javascript:void(0)"><span class="u-label g-rounded-20 g-px-15" style="background-color: grey !important;">' . ActionUser::getName(
                    $user->action
                ) . '</span></a>';
        } elseif ((int)$user->action === ActionUser::$update_email) {
            $actionTmp = ((int)$user->action === 1 || (int)$user->action === 0) ? ActionUser::$active : ActionUser::$locked_higher;;
            $statusTextTmp  = (int)$user->action === 1
                ? _i('Active')
                : ActionUser::getName(
                    $user->action
                );//_i('Blocked');
            $statusClassTmp = ($actionTmp === 1 || (int)$user->action === 0) ? 'teal' : 'lightred';
            $user->status   = sprintf(
                '<a href="javascript:void(0)" id="change-email-agent" data-route="%s"><span class="u-label g-bg-%s g-rounded-20 g-px-15">%s</span></a>',
                route('users.change-email-agent', [$user->id, $user->action, 0]),
                $statusClassTmp,
                $statusTextTmp
            );
        } else {
            $actionTmp      = ((int)$user->action === 1 || (int)$user->action === 0) && (boolean)$user->status ? ActionUser::$active : ActionUser::$locked_higher;
            $statusTextTmp  = (int)$user->action === 1 && (boolean)$user->status
                ? _i('Active')
                : ActionUser::getName(
                    $user->action
                );//_i('Blocked');
            $statusClassTmp = ($actionTmp === 1 || (int)$user->action === 0) && (boolean)$user->status ? 'teal' : 'lightred';
            $user->status   = sprintf(
                '<a href="javascript:void(0)" id="change-user-status" data-route="%s"><span class="u-label g-bg-%s g-rounded-20 g-px-15">%s</span></a>',
                route(
                    'users.block.status',
                    [
                        $user->id,
                        ((int)$user->action === 1 && (boolean)$user->status ? ActionUser::$locked_higher : ActionUser::$active),
                        0
                    ]
                ),
                $statusClassTmp,
                $statusTextTmp
            );
        }

//        //TODO New route block agent and user, field action and status
//        $actionTmp = ((int)$user->action === 1 || (int)$user->action === 0) && (boolean)$user->status ? ActionUser::$active : ActionUser::$locked_higher;
//        $statusTextTmp = (int)$user->action === 1 && (boolean)$user->status ? _i('Active') : _i('Blocked');
//        $statusClassTmp = ($actionTmp === 1 || (int)$user->action === 0 ) && (boolean)$user->status ? 'teal' : 'lightred';
//        $user->status = sprintf(
//            '<a href="javascript:void(0)" id="change-user-status" data-route="%s"><span class="u-label g-bg-%s g-rounded-20 g-px-15">%s</span></a>',
//            route('users.block.status', [$user->id, ((int)$user->action === 1 && (boolean)$user->status ? ActionUser::$locked_higher : ActionUser::$active), 0]),
//            $statusClassTmp,
//            $statusTextTmp
//        );

        $domain    = Configurations::getDomain();
        $user->url = "https://$domain/register?r=$user->referral_code";

        if (isset($user->master)) {
            $typeClass = $user->master ? 'blue' : 'bluegray';
            $typeText  = $user->master ? _i('Master agent') : _i('Cashier');

            if (! $user->master) {
                $user->typeSet = $typeText;
                $user->type    = sprintf(
                    '<a href="javascript:void(0)" id="change-agent-type" data-route="%s"><span class="u-label g-bg-%s g-rounded-20 g-px-15">%s</span></a>',
                    route('agents.change-agent-type', [$user->agent]),
                    $typeClass,
                    $typeText
                );
            } else {
                $user->typeSet = $typeText;
                $user->type    = sprintf(
                    '<span class="u-label g-bg-%s g-rounded-20 g-px-15">%s</span>',
                    $typeClass,
                    $typeText
                );
            }
        } else {
            $typeClass     = 'bluegray';
            $typeText      = _i('User');
            $user->typeSet = $typeText;
            $user->type    = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15">%s</span>',
                $typeClass,
                $typeText
            );
        }
    }

    /**
     * formatAgentDataMakersTotals
     * @param $totals
     * @return string
     */
    public function formatAgentDataMakersTotals($totals)
    {
        $htmlTotals = sprintf(
            '<table  class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th class="w-th-20">%s</th>
                            <th class="w-th-17-5">' . _i('Total Payed') . '</th>
                            <th class="w-th-20">' . _i('Total Won') . '</th>
                            <th class="w-th-23">' . _i('Total Bets') . '</th>
                            <th>' . _i('Total Profit') . '</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td><strong>%s</strong></td>
                            <td><strong>%s</strong></td>
                            <td><strong>%s</strong></td>
                            <td><strong>%s</strong></td>
                        </tr>
                    </tbody>',
            _i('Totals'),
            $totals[0]->total_played,
            number_format($totals[0]->total_won, 2),
            number_format($totals[0]->total_bet, 2),
            number_format($totals[0]->total_profit, 2),
        );

        return $htmlTotals;
    }

    /**
     * Format agent lock by provider
     *
     * @param array $agents Agents data
     */
    public function formatAgentLockByProvider($agents)
    {
        foreach ($agents as $agent) {
            $agent->agent    = $agent->username;
            $agent->provider = $agent->name;
            $agent->date     = $agent->created_at->format('d-m-Y H:i:s');
        }
    }

    /**
     * Format agents transactions
     *
     * @param array $transactions Transactions data
     */
    public function formatAgentTransactions($transactions)
    {
        $timezone        = session('timezone');
        $newTransactions = collect();
        $totalDebit      = 0;
        $totalCredit     = 0;

        foreach ($transactions as $transaction) {
            $transaction->date   = $transaction->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $amountTmp           = $transaction->amount;
            $transaction->amount = number_format($transaction->amount, 2);
            $transaction->debit  = 0;
            if ($transaction->transaction_type_id == TransactionTypes::$debit) {
                $transaction->debit = $amountTmp;
                $totalDebit         = $totalDebit + $amountTmp;
            }
            $transaction->credit = 0;
            if ($transaction->transaction_type_id == TransactionTypes::$credit) {
                $transaction->credit = $amountTmp;
                $totalCredit         = $totalCredit + $amountTmp;
            }
            if (isset($transaction->data->balance)) {
                $transaction->balance = number_format($transaction->data->balance, 2);
            }
            $newTransactions->push($transaction);
        }

        $totalBalance = $totalCredit - $totalDebit;

        $newTransactions->push([
            'id'                    => null,
            'amount'                => null,
            'transaction_type_id'   => null,
            'created_at'            => null,
            'provider_id'           => null,
            'data'                  => [
                'from'           => null,
                'to'             => null,
                'balance'        => null,
                'transaction_id' => null,
                'second_balance' => null,
            ],
            'transaction_status_id' => null,
            'date'                  => '<strong>' . _i('Totals') . '</strong>',
            'debit'                 => '<strong>' . number_format($totalDebit, 2, ",", ".") . '</strong>',
            'credit'                => '<strong>' . number_format($totalCredit, 2, ",", ".") . '</strong>',
            'balance'               => '<strong>' . number_format($totalBalance, 2, ",", ".") . '</strong>',
        ]);

        return $newTransactions;
    }

    /**
     * Format agents transactions Paginate
     *
     * @param array $transactions Transactions data
     */
    public function formatAgentTransactionsPaginate($transactions, $total, $request)
    {
        $timezone = session('timezone');
        $data     = array();

        foreach ($transactions as $transaction) {
            $amountTmp               = $transaction->amount;
            $transaction->debit      = 0;
            $transaction->credit     = 0;
            $transaction->balance    = number_format(0, 2, '.', '.');
            $transaction->new_amount = 0;

            $from = $transaction->data->from;
            $to   = $transaction->data->to;
            if ($transaction->transaction_type_id == TransactionTypes::$debit) {
                $transaction->debit      = $amountTmp;
                $transaction->new_amount = '<span class="badge badge-pill badge-danger">-' . number_format(
                        $amountTmp,
                        2,
                        '.',
                        '.'
                    ) . '</span>';
            }
            if ($transaction->transaction_type_id == TransactionTypes::$credit) {
                $transaction->credit     = $amountTmp;
                $transaction->new_amount = '<span class="badge badge-pill badge-info">+' . number_format(
                        $amountTmp,
                        2,
                        '.',
                        '.'
                    ) . '</span>';
            }
            if (isset($transaction->data->balance)) {
                $transaction->balance = number_format($transaction->data->balance, 2, '.', '.');
            }

            $credit = $transaction->credit;
            $debit  = $transaction->debit;
            //TODO COMENTADO
//            if($transaction->user_id === Auth::user()->id){
//                $debit = $transaction->credit;
//                $credit = $transaction->debit;
//            }
//            if($transaction->data->from != Auth::user()->username){
//                $credit = $transaction->credit;
//                $debit = $transaction->debit;
//            }

            $debitt     = $debit > 0 ? '-' . number_format($debit, 2, ".", ".") : '0,00';
            $creditt    = $credit > 0 ? '+' . number_format($credit, 2, ".", ".") : '0,00';
            $nameAffect = $transaction->data->from === $transaction->username ? $transaction->data->from : $transaction->data->to;
//            if($from != $nameAffect){
//
//            }
            $data[] = [
                'id'         => null,
                'date'       => $transaction->created_at->setTimezone($timezone)->format('d-m-Y H:i:s'),
                'data'       => [
                    'from' => $from,
                    'to'   => $nameAffect,
                    //'to' => $to,
                ],
                'debit'      => $debitt,
                'credit'     => $creditt,
                'new_amount' => $transaction->new_amount,
                'balance'    => $transaction->balance,
            ];
        }

        return [
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => intval($total),
            'recordsFiltered' => intval($total),
            'data'            => $data,
        ];
    }

    /**
     * Format paginated agent transactions with timezone.
     *
     * Formats the paginated transaction data of an agent with the specified timezone.
     * Each transaction's date, amount, debit, credit, and balance are formatted accordingly.
     * Returns an array containing formatted transaction data along with pagination details.
     *
     * @param LengthAwarePaginator $paginatedResults The paginated transaction data.
     * @param string $timezone The timezone to format the transaction dates.
     * @return array An array containing formatted transaction data and pagination details.
     */
    public function formatAgentTransactionsPaginated($paginatedResults, string $timezone)
    : array {
        $data = $paginatedResults->items();

        foreach ($data as $transaction) {
            $transaction->date       = $transaction->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->debit      = $transaction->transaction_type_id == TransactionTypes::$debit ? $transaction->amount : '-';
            $transaction->credit     = $transaction->transaction_type_id == TransactionTypes::$credit ? $transaction->amount : '-';
            $transaction->balance    = $transaction->balance_final ?? 0;
            $symbol                  = $transaction->transaction_type_id == TransactionTypes::$debit ? '-' : '+';
            $transaction->new_amount = $symbol . formatAmount($transaction->amount);
            $transaction->balance    = formatAmount($transaction->balance);

            $from     = $transaction->data->from ?? null;
            $to       = $transaction->data->to ?? null;
            $nameAffect = $from === $transaction->username ? $from : $to;

            $condition = $transaction->transaction_type_id === 1 ? _i('Descarga') : _i('Carga');
            $nameAffect = $from === $nameAffect ? $condition : $nameAffect;

            $transaction->data->from = $from;
            $transaction->data->to = $nameAffect;
        }

        return [
            'current_page' => $paginatedResults->currentPage(),
            'total'        => $paginatedResults->total(),
            'per_page'     => $paginatedResults->perPage(),
            'data'         => $data
        ];
    }

    /**
     * Format agents transactions report
     *
     * @param array $transactions Transactions data
     */
    public function formatAgentTransactionsReport($transactions)
    {
        $timezone = session('timezone');
        foreach ($transactions as $transaction) {
            $transaction->date   = $transaction->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->amount = number_format($transaction->amount, 2);
            $transaction->debit  = $transaction->transaction_type_id == TransactionTypes::$debit ? $transaction->amount : '-';
            $transaction->credit = $transaction->transaction_type_id == TransactionTypes::$credit ? $transaction->amount : '-';
            if (isset($transaction->data->balance)) {
                $transaction->balance = number_format($transaction->data->balance, 2);
            } else {
                $transaction->balance = 0;
            }
        }
    }

    /**
     * Format Total Credit And Debit
     * @param $credit
     * @param $debit
     * @return string
     */
    public function formatAgentTransactionsTotals($credit, $debit)
    {
        $balance = $credit - $debit;
        //' . _i('Debit') . '
        //' . _i('Credit') . '
        $htmlTotals = sprintf(
            '<table  class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>%s</th>
                            <th class="text-right">Descarga</th>
                            <th class="text-right">Carga</th>
                            <th class="text-right">' . _i('Balance') . '</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td class="text-right"><strong>%s</strong></td>
                            <td class="text-right"><strong>%s</strong></td>
                            <td class="text-right"><strong>%s</strong></td>
                        </tr>
                    </tbody>',
            _i('Totals'),
            number_format($debit, 2),
            number_format($credit, 2),
            number_format($balance, 2),
        );

        return $htmlTotals;
    }

    /**
     *  Format agent and sub-agents
     * @param array $agents Agents data
     *
     */
    public function formatAgentandSubAgents($agents)
    {
        $dataAgents     = [];
        $agentsChildren = $this->formatSubAgents($agents);
        $dataAgents     = array_merge($dataAgents, $agentsChildren);

        return $dataAgents;
    }

    /**
     *  Format sub-agents and agent
     * @param array $agents Agents data
     *
     */
    public function formatSubAgents($agents)
    {
        $agentsRepo = new AgentsRepo();
        $currency   = session('currency');
        $dataAgents = [];

        foreach ($agents as $agent) {
            $dataChildren = null;
            $subAgents    = $agentsRepo->getAgentsByOwner($agent->user_id, $currency);
            if (count($subAgents) > 0) {
                $agentsChildren = $this->formatSubAgents($subAgents);
            }

            if (count($subAgents) > 0) {
                $dataChildren = $agentsChildren;
            }

            $dataAgents[] = [
                'username' => $agent->username,
                'user_id'  => $agent->user_id,
            ];
            if (! is_null($dataChildren)) {
                $dataAgents = array_merge($dataAgents, $dataChildren);
            }
        }

        return $dataAgents;
    }


    /**
     *  Format sub-agents and agents
     *
     * @param object $agentsRepo Repository Agents
     * @param string $currency Currency iso
     * @param array $agents Agents data
     *
     */
    public function formatAgentandSubAgentsNew($agentsRepo, $currency, $agents)
    {
        $dataAgents = [];
        foreach ($agents as $agent) {
            $agentsChildren = [];
            $subAgents      = $agentsRepo->getAgentsChildrenByOwner($agent->user_id, $currency);
            if (count($subAgents) > 0) {
                $agentsChildren = $this->formatAgentandSubAgentsNew($agentsRepo, $currency, $subAgents);
            }

            $dataAgents[] = [
                'username' => $agent->username,
                'user_id'  => $agent->user_id,
            ];

            $dataAgents = array_merge($dataAgents, $agentsChildren);
        }

        return $dataAgents;
    }

    /**
     * Format agents
     *
     * @param array $agents Agents data
     */
    public function formatAgents($agents)
    {
        $totalBalances = 0;

        foreach ($agents as $agent) {
            $totalBalances      += $agent->balance;
            $agent->percentages = $agent->percentage == 0 ? '' : number_format($agent->percentage, 2) . '%';
            $agent->balance     = number_format($agent->balance, 2);
            $agent->type        = $agent->master ? _i('Master agent') : _i('Cashier');
            $agent->actions     = sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#update-percentage" data-agent="%s" data-percentage="%s"><i class="hs-admin-pencil"></i> %s</button>',
                $agent->id,
                $agent->percentage,
                _i('Edit')
            );
        }

        return [
            'agents'         => $agents,
            'total_balances' => number_format($totalBalances, 2)
        ];
    }

    /**
     * Format agents
     *
     * @param array $agents Agents data
     * @param string $currency Currency iso
     */
    public function formatAgentsId($agents, $currency)
    {
        $agentsRepo = new AgentsRepo();
        $data       = [];
        foreach ($agents as $agent) {
            $subAgents = $agentsRepo->getAgentsByOwner($agent->user_id, $currency);

            if (count($subAgents) > 0) {
                $agentsChildren = $this->formatAgentsId($subAgents, $currency);
            }

            if (count($subAgents) > 0) {
                $data = array_merge($data, $agentsChildren);
            }

            $data[] = $agent->user_id;
        }

        return $data;
    }

    /**
     * Format user find data
     *
     * @param object $agent User data
     */
    public function formatUserFind($agent)
    {
        /*\Log::notice(__METHOD__, ['agent' => $agent]);*/
        if ($agent->type_user == 5) {
            $ownerId         = $agent->owner;
            $owner           = $agent->owner_id;
            $agent->owner_id = $ownerId;
            $agent->owner    = $owner;
        }
        return $agent;
    }

    /**
     * Format financial data grouped by users
     *
     * @param array $financial Financial data
     * @return array
     */
    public function formatAgentsTransactionsTotals($transactions)
    {
        $transactionsData = [];
        $generalTotals    = [];
        $totalCredit      = 0;
        $totalDebit       = 0;
        $totalProfit      = 0;

        if (count((array)$transactions) > 0) {
            foreach ($transactions['debit'] as $key => $debit) {
                foreach ($transactions['credit'] as $credit) {
                    if ($debit->id == $credit->id) {
                        $totalCredit        += $credit->total;
                        $totalDebit         += $debit->total;
                        $userProfit         = $debit->total - $credit->total;
                        $totalProfit        += $userProfit;
                        $transactionsData[] = [
                            'id'       => $debit->id,
                            'username' => $debit->username,
                            'debit'    => number_format($debit->total, 2),
                            'credit'   => number_format($credit->total, 2),
                            'profit'   => number_format($userProfit, 2)
                        ];
                        unset($transactions['debit'][$key]);
                    }
                }
            }
            foreach ($transactions['debit'] as $debitItem) {
                $totalDebit         += $debitItem->total;
                $totalProfit        += $debitItem->total;
                $transactionsData[] = [
                    'id'       => $debitItem->id,
                    'username' => $debitItem->username,
                    'debit'    => number_format($debitItem->total, 2),
                    'credit'   => number_format(0, 2),
                    'profit'   => number_format($debitItem->total, 2),
                ];
            }
        }

        $generalTotals['credit'] = number_format($totalCredit, 2);
        $generalTotals['debit']  = number_format($totalDebit, 2);
        $generalTotals['profit'] = number_format($totalProfit, 2);

        return [
            'transactions' => $transactionsData,
            'totals'       => $generalTotals
        ];
    }

    /**
     * Format agent type data
     *
     * @param object $user User data
     */
    public function formatChangeAgentType($user)
    {
        if (isset($user->master)) {
            $typeClass = $user->master ? 'blue' : 'bluegray';
            $typeText  = $user->master ? _i('Master agent') : _i('Cashier');

            if (! $user->master) {
                $user->type = sprintf(
                    '<a href="javascript:void(0)" id="change-agent-type" data-route="%s"><span class="u-label g-bg-%s g-rounded-20 g-px-15">%s</span></a>',
                    route('agents.change-agent-type', [$user->agent]),
                    $typeClass,
                    $typeText
                );
            } else {
                $user->type = sprintf(
                    '<span class="u-label g-bg-%s g-rounded-20 g-px-15">%s</span>',
                    $typeClass,
                    $typeText
                );
            }
        } else {
            $typeClass  = 'bluegray';
            $typeText   = _i('User');
            $user->type = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15">%s</span>',
                $typeClass,
                $typeText
            );
        }
    }

    /**
     * Format agents transactions Paginate
     *
     * @param array $transactions Transactions data
     */
    public function formatClosuresTotalsProviderPaginate($transactions, $total, $percentage, $request)
    {
        $timezone     = session('timezone');
        $data         = array();
        $total_played = 0;
        $total_won    = 0;
        $total_bet    = 0;
        $total_profit = 0;
        $rtp          = 0;
        $i            = 1;
        foreach ($transactions as $transaction) {
            $total_played = $total_played + $transaction->total_played;
            $total_won    = $total_won + $transaction->total_won;
            $total_bet    = $total_bet + $transaction->total_bet;
            $total_profit = $total_profit + $transaction->total_profit;
            $rtp          = $rtp + $transaction->rtp;

            $data[] = [
                'id'     => $i++,
                'name'   => $transaction->provider_name,
                'played' => number_format($transaction->total_played, 2, '.', '.'),
                'won'    => number_format($transaction->total_won, 2, '.', '.'),
                'bet'    => number_format($transaction->total_bet, 0, '.', '.'),
                'profit' => number_format($transaction->total_profit, 2, '.', '.'),
                'rpt'    => number_format($transaction->rtp, 2, '.', '.'),
            ];
        }

        $data[] = [
            'id'     => 999999999,
            'name'   => '<strong>' . _i('Totals') . '</strong>',
            'played' => '<strong>' . number_format($total_played, 2, '.', '.') . '</strong>',
            'won'    => '<strong>' . number_format($total_won, 2, '.', '.') . '</strong>',
            'bet'    => '<strong>' . number_format($total_bet, 0, '.', '.') . '</strong>',
            'profit' => '<strong>' . number_format($total_profit, 2, '.', '.') . '</strong>',
            'rpt'    => '<strong>' . number_format($rtp, 2, '.', '.') . '</strong>',
        ];

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $data
        );

        return $json_data;
    }

    /**
     * format datas lock
     *
     * @param array $agents Agents data
     * @param array $users Users data
     * @param array $subAgents Subagents data
     * @param string $currency Currency iso
     * @param string $category Category name
     * @param string $maker Maker name
     * @return false|string
     */
    public function formatDataLock($lockUsers, $subAgents, $users, $agent, $currency, $category, $maker)
    {
        $blockUsers = [];
        $dataAngets = $this->formatDataLockSubAngents($lockUsers, $subAgents, $currency, $category, $maker);
        $dataUsers  = $this->formatDataLockUsers($lockUsers, $users, $currency, $category, $maker);
        $agentsRepo = new AgentsRepo();
        $gamesRepo  = new GamesRepo();
        $whitelabel = Configurations::getWhitelabel();

        if (! is_null($agent)) {
            $dataMakers[] = $maker;
            if ($lockUsers == 'true') {
                $blockUsers[] = [
                    'currency_iso' => $currency,
                    'makers'       => null,
                    'user_id'      => $agent->id,
                    'category'     => null,
                    'created_at'   => Carbon::now(),
                    'updated_at'   => Carbon::now()
                ];
            } else {
                if (is_null($category)) {
                    $categories = $gamesRepo->getCategoriesByMaker($maker);
                    $categories = array_column($categories->toArray(), 'category');
                } else {
                    $categories[] = $category;
                }

                foreach ($categories as $category) {
                    $excludedAgent = $this->getExcludedAgent(
                        $agentsRepo,
                        $agent->id,
                        $currency,
                        $category,
                        $whitelabel
                    );
                    $makersExclude = isset($excludedAgent->makers) ? json_decode($excludedAgent->makers) : [];
                    $dataMakers    = array_merge($dataMakers, $makersExclude);
                    $listMakers    = array_values(array_filter(array_unique($dataMakers)));
                    $blockUsers[]  = [
                        'currency_iso' => $currency,
                        'makers'       => json_encode($listMakers),
                        'user_id'      => $agent->id,
                        'category'     => $category,
                        'created_at'   => Carbon::now(),
                        'updated_at'   => Carbon::now()
                    ];
                }
            }
        }
        $data = array_merge($dataAngets, $dataUsers, $blockUsers);
        return $data;
    }

    /**
     * format data lock sub-angents
     *
     * @param array $agents Agents data
     * @param string $currency Currency iso
     * @param string $category Category name
     * @param string $maker Maker name
     * @return false|string
     */
    public function formatDataLockSubAngents($lockUsers, $agents, $currency, $category, $maker)
    {
        $agentsRepo = new AgentsRepo();
        $gamesRepo  = new GamesRepo();
        $whitelabel = Configurations::getWhitelabel();
        $dataAgents = [];
        foreach ($agents as $agent) {
            $dataChildren = null;
            $subAgents    = $agentsRepo->getAgentsByOwner($agent->user_id, $currency);
            $users        = $agentsRepo->getUsersByAgent($agent->id, $currency);

            if (count($subAgents) > 0) {
                $agentsChildren = $this->formatDataLockSubAngents($lockUsers, $subAgents, $currency, $category, $maker);
            }

            if (count($users) > 0) {
                $usersChildren = $this->formatDataLockUsers($lockUsers, $users, $currency, $category, $maker);
            }

            if (count($subAgents) > 0 && count($users) > 0) {
                $dataChildren = array_merge($agentsChildren, $usersChildren);
            } else {
                if (count($subAgents) > 0) {
                    $dataChildren = $agentsChildren;
                }
                if (count($users) > 0) {
                    $dataChildren = $usersChildren;
                }
            }
            $dataMakers[] = $maker;
            if ($lockUsers == 'true') {
                $dataAgents[] = [
                    'currency_iso' => $currency,
                    'user_id'      => $agent->user_id,
                    'category'     => null,
                    'makers'       => null,
                    'created_at'   => Carbon::now(),
                    'updated_at'   => Carbon::now()
                ];
            } else {
                if (is_null($category)) {
                    $categories = $gamesRepo->getCategoriesByMaker($maker);
                    $categories = array_column($categories->toArray(), 'category');
                } else {
                    $categories[] = $category;
                }
                foreach ($categories as $category) {
                    $excludedAgent = $this->getExcludedAgent(
                        $agentsRepo,
                        $agent->user_id,
                        $currency,
                        $category,
                        $whitelabel
                    );
                    $makersExclude = isset($excludedAgent->makers) ? json_decode($excludedAgent->makers) : [];
                    $dataMakers    = array_merge($dataMakers, $makersExclude);
                    $listMakers    = array_values(array_filter(array_unique($dataMakers)));
                    $dataAgents[]  = [
                        'currency_iso' => $currency,
                        'user_id'      => $agent->user_id,
                        'category'     => $category,
                        'makers'       => json_encode($listMakers),
                        'created_at'   => Carbon::now(),
                        'updated_at'   => Carbon::now()
                    ];
                }
            }
            if (! is_null($dataChildren)) {
                $dataAgents = array_merge($dataAgents, $dataChildren);
            }
        }
        return $dataAgents;
    }

    /**
     * format data lock users
     *
     * @param array $users Users data
     * @param string $currency Currency iso
     * @param int $provider Provider id
     * @return false|string
     */
    public function formatDataLockUsers($lockUsers, $users, $currency, $category, $maker)
    {
        $dataUsers  = [];
        $whitelabel = Configurations::getWhitelabel();
        $usersRepo  = new UsersRepo();
        $gamesRepo  = new GamesRepo();
        foreach ($users as $user) {
            $dataMakers[] = $maker;
            if ($lockUsers == 'true') {
                $dataUsers[] = [
                    'currency_iso' => $currency,
                    'makers'       => null,
                    'user_id'      => $user['id'],
                    'category'     => null,
                    'created_at'   => Carbon::now(),
                    'updated_at'   => Carbon::now()
                ];
            } else {
                if (is_null($category)) {
                    $categories = $gamesRepo->getCategoriesByMaker($maker);
                    $categories = array_column($categories->toArray(), 'category');
                } else {
                    $categories[] = $category;
                }
                foreach ($categories as $category) {
                    $excludedUsers = $usersRepo->getUserLockByUserAndCategory(
                        $user['id'],
                        $currency,
                        $category,
                        $whitelabel
                    );
                    $makersExclude = isset($excludedUsers->makers) ? json_decode($excludedUsers->makers) : [];
                    $dataMakers    = array_merge($dataMakers, $makersExclude);
                    $listMakers    = array_values(array_filter(array_unique($dataMakers)));
                    $dataUsers[]   = [
                        'currency_iso' => $currency,
                        'makers'       => json_encode($listMakers),
                        'user_id'      => $user['id'],
                        'category'     => $category,
                        'created_at'   => Carbon::now(),
                        'updated_at'   => Carbon::now()
                    ];
                }
            }
        }
        return $dataUsers;
    }

    /**
     * Get Exclude Agent
     */
    private function getExcludedAgent($agentsRepo, $userId, $currency, $category, $whitelabel)
    {
        return $agentsRepo->getAgentLockByUserAndCategory($userId, $currency, $category, $whitelabel);
    }

    /**
     * Format search
     *
     * @param array $users Users data
     */
    public function formatExcludeMakersUser($users)
    {
        $timezone = session('timezone');
        foreach ($users as $user) {
            $makers       = json_decode($user->makers);
            $user->user   = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$user->user_id]),
                $user->user_id
            );
            $user->makers = '';
            foreach ($makers as $maker) {
                if (! is_null($maker)) {
                    $user->makers .= sprintf(
                        '<li>%s</li>',
                        $maker
                    );
                }
            }
            $user->date    = $user->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $user->actions = sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" id="delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route(
                    'agents.reports.exclude-providers-agents.delete',
                    [$user->user_id, $user->category, $user->currency_iso]
                ),
                _i('Delete')
            );
        }
    }

    /**
     * Format excluder providers users
     *
     * @param array $userExclude Excluder users data
     * @param int $user User ID
     */
    public function formatExcluderProvidersUsers($user, $excludedUsers, $currency)
    {
        $dataUsers = [];
        foreach ($excludedUsers as $excludedUser) {
            $dataUsers[] = [
                'currency_iso' => $currency,
                'category'     => $excludedUser->category,
                'makers'       => $excludedUser->makers,
                'user_id'      => $user,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now()
            ];
        }
        return $dataUsers;
    }

    /**
     *  Format relocation agents
     * @param array $agent Agent data
     * @param array $agents Agents data
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency iso
     * @param int $agentMoveId Agent move ID
     *
     */
    public function formatRelocationAgents($agent, $agents, $currency, $agentMoveId)
    {
        $data = collect();
        if (! is_null($agent)) {
            $itemObject           = new \stdClass();
            $itemObject->id       = $agent['id'];
            $itemObject->username = $agent['username'];
            $data->push($itemObject);
        }

        $dataSelect = $this->formatRelocationSubAgents($agents, $currency, $agentMoveId);
        foreach ($dataSelect as $agentSelect) {
            $itemObject           = new \stdClass();
            $itemObject->id       = $agentSelect['id'];
            $itemObject->username = $agentSelect['username'];
            $data->push($itemObject);
        }
        return $data;
    }

    /**
     *  Format relocation sub agents
     *
     * @param array $agents Agents data
     * @param string $currency Currency iso
     * @param int $agentMoveId Agent move ID
     */
    public function formatRelocationSubAgents($agents, $currency, $agentMoveId)
    {
        $agentsRepo = new AgentsRepo();
        $dataAgents = [];

        foreach ($agents as $agent) {
            if ($agent->user_id != $agentMoveId) {
                $dataChildren = null;
                $subAgents    = $agentsRepo->getAgentsByOwner($agent->user_id, $currency);
                if (count($subAgents) > 0) {
                    $agentsChildren = $this->formatRelocationSubAgents($subAgents, $currency, $agentMoveId);
                }

                if (count($subAgents) > 0) {
                    $dataChildren = $agentsChildren;
                }
                if ($agent->user_id != $agentMoveId || $agent->owner_id != $agentMoveId) {
                    if ($agent->master == true) {
                        $dataAgents[] = [
                            'id'       => $agent->user_id,
                            'username' => $agent->username,
                        ];

                        if (! is_null($dataChildren)) {
                            $dataAgents = array_merge($dataAgents, $dataChildren);
                        }
                    }
                }
            }
        }

        return $dataAgents;
    }

    /**
     * Format users
     *
     * @param array $users Users data
     * @param string $currency Currency ISO
     */
    public function formatUsers($users, $currency)
    {
        foreach ($users as $user) {
            if (! empty($currency)) {
                $wallet        = Wallet::getByClient($user->id, $currency);
                $user->balance = number_format($wallet->data->wallet->balance, 2);
            } else {
                $user->d        = $user->id;
                $user->username = $user->username;
            }
        }
    }

    /**
     * Ticket formatter
     *
     * @param object $ticket Ticket data
     */
    public function ticketFormatter($ticket)
    {
        $timezone             = session('timezone');
        $ticket->date         = $ticket->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
        $ticket->type         = $ticket->transaction_type_id == TransactionTypes::$debit ? _i('Credit') : _i('Debit');
        $ticket->username     = $ticket->username;
        $ticket->currency_iso = $ticket->currency_iso;
        $ticket->amount       = number_format($ticket->amount, 2);
        $ticket->from         = $ticket->data->from;
        $ticket->to           = $ticket->data->to;
    }

    /**
     * Format users balances
     *
     * @param array $users Users data
     */
    public function usersBalances($users)
    {
        $totalBalances = 0;

        foreach ($users as $user) {
            $totalBalances += $user->balance;
            $user->balance = number_format($user->balance, 2);
        }

        return [
            'users'          => $users,
            'total_balances' => number_format($totalBalances, 2)
        ];
    }
}
