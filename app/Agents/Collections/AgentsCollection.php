<?php

namespace App\Agents\Collections;

use App\Agents\Repositories\AgentsRepo;
use App\Core\Repositories\TransactionsRepo;
use App\Reports\Collections\ReportsCollection;
use App\Reports\Repositories\ClosuresUsersTotalsRepo;
use App\Users\Repositories\UsersRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Wallet\Wallet;
use Illuminate\Support\Facades\Log;

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
     * Agents tree
     *
     * @param array $agents Agents data
     * @return array
     */
    private function agentsTree($agents)
    {
        $agentsRepo = new AgentsRepo();
        $currency = session('currency');
        $tree = [];

        foreach ($agents as $agent) {
            $children = null;
            $subAgents = $agentsRepo->getAgentsByOwner($agent->user_id, $currency);
            $users = $agentsRepo->getUsersByAgent($agent->id, $currency);

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

            $icon = $agent->master ? 'star' : 'users';
            $treeItem = [
                'id' => $agent->user_id,
                'text' => $agent->username,
                'status' => $agent->status,
                'icon' => "fa fa-{$icon}",
                'li_attr' => [
                    'data_type' => 'agent'
                ]
            ];

            if (!is_null($children)) {
                $treeItem['children'] = $children;
            }
            $tree[] = $treeItem;
        }
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
        $agentsRepo = new AgentsRepo();
        $currency = session('currency');
        $tree = [];
        $agentsChildren = '';
        $usersChildren = '';
        foreach ($agents as $agent) {
            $children = null;
            $subAgents = $agentsRepo->getAgentsByOwner($agent->user_id, $currency);
            $users = $agentsRepo->getUsersByAgent($agent->id, $currency);
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

            if (!empty($children)) {
                $icon = $agent->master ? 'star' : 'users';
                $treeItem = [
                    'id' => $agent->user_id,
                    'text' => $agent->username,
                    'icon' => "fa fa-{$icon}",
                    'li_attr' => [
                        'data_type' => 'agent'
                    ]
                ];

                $treeItem['children'] = $children;
                $tree[] = $treeItem;
            }

        }
        return $tree;
    }

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
                'id' => $agent->id,
                'user_id' => $agent->user_id,
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
     * Get dependency
     *
     * @param object $agent Agent data
     * @param string $currency Currency ISO
     * @return array
     */
    public function dependency($agent, $currency)
    {
        $agentsRepo = new AgentsRepo();
        $usersData = [];
        $agents = $agentsRepo->getAgentsDependency($agent->user_id, $currency);
        $agentsData = [];
        foreach ($agents as $agent) {
            $agentsData[] = $agent->id;
        }
        $users = $agentsRepo->getUsersByAgents($agentsData, $currency);

        foreach ($users as $user) {
            $usersData[] = [
                'id' => $user->id,
                'username' => $user->username
            ];
        }
        return $usersData;
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
        $tree = [
            'id' => $agent->id,
            'text' => $agent->username,
            'status' => $agent->status,
            'icon' => 'fa fa-diamond',
            'type' => 'agent',
            'state' => [
                'opened' => true,
                'selected' => true,
            ],
            'li_attr' => [
                'data_type' => 'agent'
            ]
        ];
        $agentsChildren = $this->agentsTree($agents);
        $usersChildren = $this->usersTree($users);
        $children = array_merge($agentsChildren, $usersChildren);
        $tree['children'] = $children;
        return json_encode($tree);
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
        $tree = [
            'id' => $agent->id,
            'text' => $agent->username,
            'icon' => 'fa fa-diamond',
            'type' => 'agent',
            'state' => [
                'opened' => true,
                'selected' => true,
            ],
            'li_attr' => [
                'data_type' => 'agent'
            ]
        ];
        $agentsChildren = $this->agentsTreeFilter($agents, $status);
        $usersChildren = $this->usersTreeFilter($users, $status);
        $children = array_merge($agentsChildren, $usersChildren);
        $tree['children'] = $children;
        return $tree;
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
        $data = collect();
        $dataSelect = $this->formatSelectAgents($agents, $whitelabel);

        if (isset($dataSelect['agents'])) {
            $dataAgents = $dataSelect['agents'];
            foreach ($dataAgents as $agent) {
                $itemObject = new \stdClass();
                $itemObject->id = $agent['id'];
                $itemObject->username = $agent['username'];
                $itemObject->type = 'agent';
                $data->push($itemObject);
            }
        }
        if ($status) {
            if (isset($dataSelect['users'])) {
                $dataUsers = $dataSelect['users'];
                foreach ($dataUsers as $user) {
                    $itemObject = new \stdClass();
                    $itemObject->id = $user['id'];
                    $itemObject->username = $user['username'];
                    $itemObject->type = 'user';
                    $data->push($itemObject);
                }
            }

            foreach ($users as $user) {
                $itemObject = new \stdClass();
                $itemObject->id = $user->id;
                $itemObject->username = $user->username;
                $itemObject->type = 'user';
                $data->push($itemObject);
            }
        }

        $collection = $data->reject(function ($element) use ($username) {
            return mb_strpos($element->username, $username) === false;
        });
        return $collection->unique('id')->values()->all();
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
        $totalPlayed = 0;
        $totalWon = 0;
        $totalProfit = 0;
        $totalCollect = 0;
        $totalToPay = 0;
        $providersTotalPlayed = [];
        $providersTotalWon = [];
        $providersTotalProfit = [];
        $providerIds = [];
        $providersTitles = null;

        $html = sprintf(
            '<table class="table table-bordered table-sm table-striped table-hover"><thead><tr><th class="text-center">%s</th>',
            _i('Agents / Players')
        );

        foreach ($providers as $provider) {
            $nameTmp = Providers::getName($provider->id);
            //TODO VALIDANDO PROVEEDOR !NULL
            if(!is_null($nameTmp)){
                $providerIds[] = $provider->id;
                $html .= "<th colspan='3' class='text-center'>" . Providers::getName($provider->id) . "</th>";
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
            }else{
                Log::info('provider null',[$provider]);
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
            $auxHTML = '';
            $agentsUsersIds = [];
            $agentTotalPlayed = 0;
            $agentTotalWon = 0;
            $agentTotalProfit = 0;
            $agentTotalCollect = 0;
            $agentTotalToPay = 0;
            $providerPlayed = [];
            $providerWon = [];
            $providerProfit = [];
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
                $financial = $closuresUsersTotalsRepo->getUsersTotalsByIdsAndProvidersGroupedByProvider($whitelabel, $startDate, $endDate, $currency, $agentsUsersIds);

                foreach ($financial as $item) {
                    $agentTotalPlayed += $item->played;
                    $agentTotalWon += $item->won;
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
                $won = isset($providerWon[$provider]) ? $providerWon[$provider]['total'] : 0;
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
                    $percentage = number_format($agent->percentage, 2);
                    $agentTotalCollect = $agentTotalProfit * ($percentage / 100);
                } else {
                    $percentage = '-';
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
            $totalPlayed += $agentTotalPlayed;
            $totalWon += $agentTotalWon;
            $totalProfit += $agentTotalProfit;
            $totalCollect += $agentTotalCollect;
            $totalToPay += $agentTotalToPay;
        }

        $usersIds = [];
        foreach ($users as $user) {
            $usersIds[] = $user->id;
        }
        if (count($usersIds) > 0) {
            $usersTotals = $closuresUsersTotalsRepo->getUsersTotalsByIdsGroupedByProvider($whitelabel, $startDate, $endDate, $currency, $usersIds);

            foreach ($users as $user) {
                $userTotal = [];
                foreach ($usersTotals as $total) {
                    if ($user->id == $total->id) {
                        $userTotal[] = $total;
                    }
                }
                $userTotalPlayed = 0;
                $userTotalWon = 0;
                $userTotalProfit = 0;

                if (count($userTotal) > 0) {
                    $html .= sprintf(
                        '<tr><td>%s <strong>%s</strong></td>',
                        $user->username,
                        _i('(Player)')
                    );

                    foreach ($providers as $provider) {
                        if (!is_null($provider->tickets_table)) {
                            $played = 0;
                            $won = 0;
                            $profit = 0;

                            foreach ($userTotal as $total) {
                                if ($total->provider_id == $provider->id) {
                                    $played += $total->played;
                                    $won += $total->won;
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
                            $userTotalWon += $won;
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
                    $totalWon += $userTotalWon;
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
            $wonProvider = $providersTotalWon[$provider]['total'] ?? 0;
            $profitProvider = $providersTotalProfit[$provider]['total'] ?? 0;
            $html .= sprintf(
                '<td class="text-right"><strong>%s</strong></td>',
                number_format($playedProvider, 2)
            );
            $html .= sprintf(
                '<td class="text-right"><strong>%s</strong></td>',
                number_format($wonProvider, 2)
            );
            $html .= sprintf(
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


    public function financialState_view1($whitelabel, $agents, $users, $currency, $providers, $startDate, $endDate, $endDateOriginal, $today, $providerTypesName)
    {
        $closuresUsersTotalsRepo = new ClosuresUsersTotalsRepo();
        $totalPlayed = 0;
        $totalWon = 0;
        $totalProfit = 0;
        $totalCollect = 0;
        $totalToPay = 0;
        $agentTotalProfit = 0;
        $providersTotalPlayed = [];
        $providersTotalWon = [];
        $providersTotalProfit = [];
        $providersTotalPercentage = [];
        $providersTotalCommissions = [];
        $providerIds = [];
        $providersTitles = null;

        $html = sprintf(
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
            _i('Categories'),
            _i('Bet'),
            _i('Bets'),
            _i('win'),
            _i('Netwin'),
            _i('Commission'),
        );

        if (count($providerTypesName) > 0) {
            $html .= "<tbody>";

            //TODO TOTAL IN AGENTS
                foreach ($agents as $agent) {
                    $agentsUsersIds = [];
                    $dependency = $this->dependency($agent, $currency);

                    foreach ($dependency as $dependencyItem) {
                        $agentsUsersIds[] = $dependencyItem['id'];
                    }

                    if (count($dependency) > 0) {
                        $financial = $closuresUsersTotalsRepo->getUsersTotalsByIdsAndProvidersGroupedByProvider($whitelabel, $startDate, $endDate, $currency, $agentsUsersIds);

                        foreach ($financial as $item) {
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

                            if (isset($providersTotalCommissions[$item->provider_id])) {

                                if (isset($agent->percentage) && $agent->percentage > 0) {
                                    $providersTotalCommissions[$item->provider_id] = [
                                        'total' => $providersTotalCommissions[$item->provider_id]['total'] + $agent->percentage
                                    ];
                                }

                            } else {

                                if (isset($agent->percentage) && $agent->percentage > 0) {
                                    $providersTotalCommissions[$item->provider_id] = [
                                        'total' => $agent->percentage
                                    ];
                                }else{
                                    $providersTotalCommissions[$item->provider_id] = [
                                        'total' => 0
                                    ];
                                }


                            }
                        }
                    }

                }
            //TODO FINISH TOTAL IN AGENTS

            foreach ($providerTypesName as $item => $value) {
                $totalBet = 0;
                $totalBets = 0;
                $totalWin = 0;
                $totalNetWin = 0;
                $totalCommission = 0;

                $htmlProvider = "";
                foreach ($providers as $index => $valor) {
                    $totalProviderBet = 0;
                    $totalProviderBets = 0;
                    $totalProviderWin = 0;
                    $totalProviderNetWin = 0;
                    $totalProviderCommission = 0;

                    if($value->id === $valor->provider_type_id){
                        $nameTmp = Providers::getName($valor->id);

                        if (!is_null($nameTmp)) {
                            $totalProviderBet = isset($providersTotalPlayed[$valor->id])?$providersTotalPlayed[$valor->id]['total']:0;
                            $totalProviderBets = isset($providersTotalWon[$valor->id])?$providersTotalWon[$valor->id]['total']:0;
                            $totalProviderWin = 1;//isset($providersTotalProfit[$valor->id])?$providersTotalProfit[$valor->id]['total']:0;
                            $totalProviderNetWin = isset($providersTotalProfit[$valor->id])?$providersTotalProfit[$valor->id]['total']:0;
                            $totalProviderCommission = isset($providersTotalCommissions[$valor->id])?$providersTotalCommissions[$valor->id]['total']:0;

                            if($totalProviderBet > 0 && $totalProviderBets > 0 && $totalProviderWin> 0 && $totalProviderNetWin> 0 && $totalProviderCommission> 0){
                                $commissionTmp = $totalProviderNetWin * ($totalProviderCommission / 100);
                                $htmlProvider .= "<tr class='table-secondary set_2'>";
                                    $htmlProvider .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $nameTmp . "</td>";
                                    $htmlProvider .= "<td class='text-center'>" . number_format($totalProviderBet, 2) . "</td>";
                                    $htmlProvider .= "<td class='text-center'>" . number_format($totalProviderBets, 2) . "</td>";
                                    $htmlProvider .= "<td class='text-center'>" . number_format($totalProviderWin, 2) . "</td>";
                                    $htmlProvider .= "<td class='text-center'>" . number_format($totalProviderNetWin, 2) . "</td>";
                                    $htmlProvider .= "<td class='text-center'>" . number_format($commissionTmp, 2) . "</td>";
                                $htmlProvider .= "</tr>";
                            }

                        }
                    }


                    //TODO TOTAL PARA CATEGORIES
                    $totalBet = $totalBet + $totalProviderBet;
                    $totalBets = $totalBets + $totalProviderBets;
                    $totalWin = $totalWin + $totalProviderWin;
                    $totalNetWin = $totalNetWin + $totalProviderNetWin;
                    $totalCommission = $totalCommission + ( $totalProviderNetWin * ($totalProviderCommission / 100));
                }

                //TODO TOTAL DEL CATEGORIES (TYPE_PROVIDER)
                $html .= "<tr class='table-primary set_1'>";
                    $html .= "<td>" . $value->name . "</td>";
                    $html .= "<td class='text-center'>" . number_format($totalBet, 2) . "</td>"; //played
                    $html .= "<td class='text-center'>" . number_format($totalBets, 2) . "</td>"; //won
                    $html .= "<td class='text-center'>" . number_format($totalWin, 2) . " por definir</td>";
                    $html .= "<td class='text-center'>" . number_format($totalNetWin, 2) . "</td>"; //profit
                    $html .= "<td class='text-center'>" . number_format($totalCommission, 2) . "</td>"; //% de agente
                $html .= "</tr>" . $htmlProvider;

            }

            $html .= "<tbody></table>";
        }

        return $html;

    }


    public function financialStateRow($whitelabel, $agents, $users, $currency, $providers, $startDate, $endDate)
    {
        $closuresUsersTotalsRepo = new ClosuresUsersTotalsRepo();
        $totalPlayed = 0;
        $totalWon = 0;
        $totalProfit = 0;
        $totalCollect = 0;
        $totalToPay = 0;
        $providersTotalPlayed = [];
        $providersTotalWon = [];
        $providersTotalProfit = [];
        $providerIds = [];
        $providersTitles = null;

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
                $auxHTML = '';
                $agentsUsersIds = [];
                $agentTotalPlayed = 0;
                $agentTotalWon = 0;
                $agentTotalProfit = 0;
                $agentTotalCollect = 0;
                $agentTotalToPay = 0;
                $providerPlayed = [];
                $providerWon = [];
                $providerProfit = [];
                $dependency = $this->dependency($agent, $currency);

                foreach ($dependency as $dependencyItem) {
                    $agentsUsersIds[] = $dependencyItem['id'];
                }

                if (count($dependency) > 0) {
                    $financial = $closuresUsersTotalsRepo->getUsersTotalsByIdsAndProvidersGroupedByProvider($whitelabel, $startDate, $endDate, $currency, $agentsUsersIds);

                    foreach ($financial as $item) {
                        $agentTotalPlayed += $item->played;
                        $agentTotalWon += $item->won;
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
                                'won' => isset($providerWon[$item->provider_id]) ? $providerWon[$item->provider_id]['total'] : 0,
                                'profit' => isset($providerProfit[$item->provider_id]) ? $providerProfit[$item->provider_id]['total'] : 0,
                            ],
                        ];

                    }

                }

//                if ($agentTotalPlayed > 0 || $agentTotalWon > 0) {
//                    $html .= $auxHTML;
//                    if ($agent->percentage > 0) {
//                        $percentage = number_format($agent->percentage, 2);
//                        $agentTotalCollect = $agentTotalProfit * ($percentage / 100);
//                    } else {
//                        $percentage = '-';
//                        $agentTotalCollect = $agentTotalProfit;
//                    }
//                    if ($agentTotalProfit > 0 || $agentTotalCollect > 0) {
//                        $agentTotalToPay = $agentTotalProfit - $agentTotalCollect;
//                    } else {
//                        $agentTotalToPay = $agentTotalProfit - $agentTotalCollect;
//                    }
//                    $html .= sprintf(
//                        '<td class="text-right">%s</td>',
//                        number_format($agentTotalPlayed, 2)
//                    );
//                    $html .= sprintf(
//                        '<td class="text-right">%s</td>',
//                        number_format($agentTotalWon, 2)
//                    );
//                    $html .= sprintf(
//                        '<td class="text-right bg-warning">%s</td>',
//                        number_format($agentTotalProfit, 2)
//                    );
//                    $html .= sprintf(
//                        '<td class="text-right">%s</td>',
//                        $percentage
//                    );
//                    $html .= sprintf(
//                        '<td class="text-right bg-primary">%s</td></tr>',
//                        number_format($agentTotalCollect, 2)
//                    );
//                    $html .= sprintf(
//                        '<td class="text-right bg-success"><strong>%s</strong></td>',
//                        number_format($agentTotalToPay, 2)
//                    );
//                }
                $totalPlayed += $agentTotalPlayed;
                $totalWon += $agentTotalWon;
                $totalProfit += $agentTotalProfit;
                $totalCollect += $agentTotalCollect;
                $totalToPay += $agentTotalToPay;
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
                $html .= "<td class='text-center' data-users='[]' data-agents='" . (isset($arrayAgents[$provider->id]) ? json_encode($arrayAgents[$provider->id]) : json_encode([])) . "'><i class='hs-admin-plus'>+</i></td>
                          </tr>";

            }
            $html .= sprintf(
                '</tbody></table>',
            );
        }
        //TODO => TEST
        return $html;

//        foreach ($providers as $provider) {
//            $providerIds[] = $provider->id;
//            $html .= "<th colspan='3' class='text-center'>" . Providers::getName($provider->id) . "</th>";
//            $providersTitles .= sprintf(
//                '<td class="text-right"><strong>%s</strong></td>',
//                _i('Bet')
//            );
//            $providersTitles .= sprintf(
//                '<td class="text-right"><strong>%s</strong></td>',
//                _i('Bets')
//            );
//            $providersTitles .= sprintf(
//                '<td class="text-right"><strong>%s</strong></td>',
//                _i('Netwin')
//            );
//        }

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
            $auxHTML = '';
            $agentsUsersIds = [];
            $agentTotalPlayed = 0;
            $agentTotalWon = 0;
            $agentTotalProfit = 0;
            $agentTotalCollect = 0;
            $agentTotalToPay = 0;
            $providerPlayed = [];
            $providerWon = [];
            $providerProfit = [];
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
                $financial = $closuresUsersTotalsRepo->getUsersTotalsByIdsAndProvidersGroupedByProvider($whitelabel, $startDate, $endDate, $currency, $agentsUsersIds);

                foreach ($financial as $item) {
                    $agentTotalPlayed += $item->played;
                    $agentTotalWon += $item->won;
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
                $won = isset($providerWon[$provider]) ? $providerWon[$provider]['total'] : 0;
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
                    $percentage = number_format($agent->percentage, 2);
                    $agentTotalCollect = $agentTotalProfit * ($percentage / 100);
                } else {
                    $percentage = '-';
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
            $totalPlayed += $agentTotalPlayed;
            $totalWon += $agentTotalWon;
            $totalProfit += $agentTotalProfit;
            $totalCollect += $agentTotalCollect;
            $totalToPay += $agentTotalToPay;
        }

        $usersIds = [];
        foreach ($users as $user) {
            $usersIds[] = $user->id;
        }
        if (count($usersIds) > 0) {
            $usersTotals = $closuresUsersTotalsRepo->getUsersTotalsByIdsGroupedByProvider($whitelabel, $startDate, $endDate, $currency, $usersIds);

            foreach ($users as $user) {
                $userTotal = [];
                foreach ($usersTotals as $total) {
                    if ($user->id == $total->id) {
                        $userTotal[] = $total;
                    }
                }
                $userTotalPlayed = 0;
                $userTotalWon = 0;
                $userTotalProfit = 0;

                if (count($userTotal) > 0) {
                    $html .= sprintf(
                        '<tr><td>%s <strong>%s</strong></td>',
                        $user->username,
                        _i('(Player)')
                    );

                    foreach ($providers as $provider) {
                        if (!is_null($provider->tickets_table)) {
                            $played = 0;
                            $won = 0;
                            $profit = 0;

                            foreach ($userTotal as $total) {
                                if ($total->provider_id == $provider->id) {
                                    $played += $total->played;
                                    $won += $total->won;
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
                            $userTotalWon += $won;
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
                    $totalWon += $userTotalWon;
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
            $wonProvider = $providersTotalWon[$provider]['total'] ?? 0;
            $profitProvider = $providersTotalProfit[$provider]['total'] ?? 0;
            $html .= sprintf(
                '<td class="text-right"><strong>%s</strong></td>',
                number_format($playedProvider, 2)
            );
            $html .= sprintf(
                '<td class="text-right"><strong>%s</strong></td>',
                number_format($wonProvider, 2)
            );
            $html .= sprintf(
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
    public function financialStateSummary($whitelabel, $agents, $users, $currency, $startDate, $endDate)
    {
        $closuresUsersTotalsRepo = new ClosuresUsersTotalsRepo();
        $totalPlayed = 0;
        $totalWon = 0;
        $totalProfit = 0;
        $totalCollect = 0;

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
            _i('Collect')
        );
        $html .= '</tr></thead><tbody>';

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

        $usersIds = [];
        foreach ($users as $user) {
            $usersIds[] = $user->id;
        }
        if (count($usersIds) > 0) {
            $usersTotals = collect($closuresUsersTotalsRepo->getUsersTotalsByIds($whitelabel, $startDate, $endDate, $currency, $usersIds));

            foreach ($users as $user) {
                $userTotal = $usersTotals->where('id', $user->id)->first();
                $userTotalPlayed = 0;
                $userTotalWon = 0;
                $userTotalProfit = 0;

                if (!is_null($userTotal)) {
                    $html .= sprintf(
                        '<tr><td>%s <strong>%s</strong></td>',
                        $user->username,
                        _i('(Player)')
                    );

                    $played = $userTotal->played;
                    $won = $userTotal->won;
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
                    $userTotalWon += $won;
                    $userTotalProfit += $profit;

                    $html .= '<td class="text-right">-</td>';
                    $html .= '<td class="text-right">-</td></tr>';
                    $totalPlayed += $userTotalPlayed;
                    $totalWon += $userTotalWon;
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
        $transactionsRepo = new TransactionsRepo();
        $totalPlayed = 0;
        $totalWon = 0;
        $totalBonus = 0;
        $totalProfit = 0;
        $totalProfitReal = 0;
        $totalCollect = 0;

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
            $auxHTML = '';
            $agentsUsersIds = [];
            $agentTotalPlayed = 0;
            $agentTotalWon = 0;
            $agentTotalProfit = 0;
            $agentTotalBonus = 0;
            $agentTotalProfitReal = 0;
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
                $bonusTotals = $transactionsRepo->getBonusTotalByUsers($agentsUsersIds, $currency, $startDate, $endDate);

                foreach ($financial as $item) {
                    $agentTotalPlayed += $item->played;
                    $agentTotalWon += $item->won;
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
                    $percentage = number_format($agent->percentage, 2);
                    $agentTotalCollect = $agentTotalProfitReal * ($percentage / 100);

                } else {
                    $percentage = '-';
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
            $totalPlayed += $agentTotalPlayed;
            $totalWon += $agentTotalWon;
            $totalProfit += $agentTotalProfit;
            $totalBonus += $agentTotalBonus;
            $totalProfitReal += $agentTotalProfitReal;
            $totalCollect += $agentTotalCollect;
        }

        $usersIds = [];
        foreach ($users as $user) {
            $usersIds[] = $user->id;
        }

        if (count($usersIds) > 0) {
            $usersTotals = collect($closuresUsersTotalsRepo->getUsersTotalsByIds($whitelabel, $startDate, $endDate, $currency, $usersIds));
            $bonusTotals = collect($transactionsRepo->getBonusTotalByUsers($usersIds, $currency, $startDate, $endDate));

            foreach ($users as $user) {
                $userTotal = $usersTotals->where('id', $user->id)->first();
                $userTotalPlayed = 0;
                $userTotalWon = 0;
                $userTotalProfit = 0;
                $userTotalBonus = 0;
                $userTotalProfitReal = 0;

                if (!is_null($userTotal)) {
                    $html .= sprintf(
                        '<tr><td>%s <strong>%s</strong></td>',
                        $user->username,
                        _i('(Player)')
                    );

                    $played = $userTotal->played;
                    $won = $userTotal->won;
                    $profit = $userTotal->profit;
                    $bonusData = $bonusTotals->where('user_id', $user->id)->first();
                    $bonus = !is_null($bonusData) ? $bonusData->bonus : 0;
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

                    $userTotalPlayed += $played;
                    $userTotalWon += $won;
                    $userTotalProfit += $profit;
                    $userTotalBonus += $bonus;
                    $userTotalProfitReal += $profitReal;

                    $html .= '<td class="text-right">-</td>';
                    $html .= '<td class="text-right">-</td></tr>';
                    $totalPlayed += $userTotalPlayed;
                    $totalWon += $userTotalWon;
                    $totalProfit += $userTotalProfit;
                    $totalBonus += $userTotalBonus;
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
     * Format agent data
     *
     * @param object $user User data
     */
    public function formatAgent($user)
    {
        $statusClass = $user->status ? 'teal' : 'lightred';
        $statusText = $user->status ? _i('Active') : _i('Blocked');
        $words = ['dotpanel.', 'admin.', 'latsoft.'];
        $domain = Configurations::getDomain();
        $user->url = "https://$domain/register?r=$user->referral_code";
        $user->status = sprintf(
            '<a href="javascript:void(0)" id="change-user-status" data-route="%s"><span class="u-label g-bg-%s g-rounded-20 g-px-15">%s</span></a>',
            route('users.change-status', [$user->id, (int)$user->status, 0]),
            $statusClass,
            $statusText
        );

        if (isset($user->master)) {
            $typeClass = $user->master ? 'blue' : 'bluegray';
            $typeText = $user->master ? _i('Master agent') : _i('Cashier');

            if (!$user->master) {
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
            $typeClass = 'bluegray';
            $typeText = _i('User');
            $user->type = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15">%s</span>',
                $typeClass,
                $typeText
            );
        }

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
            $typeText = $user->master ? _i('Master agent') : _i('Cashier');

            if (!$user->master) {
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
            $typeClass = 'bluegray';
            $typeText = _i('User');
            $user->type = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15">%s</span>',
                $typeClass,
                $typeText
            );
        }
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
            $totalBalances += $agent->balance;
            $agent->percentages = $agent->percentage == 0 ? '' : number_format($agent->percentage, 2) . '%';
            $agent->balance = number_format($agent->balance, 2);
            $agent->type = $agent->master ? _i('Master agent') : _i('Cashier');
            $agent->actions = sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#update-percentage" data-agent="%s" data-percentage="%s"><i class="hs-admin-pencil"></i> %s</button>',
                $agent->id,
                $agent->percentage,
                _i('Edit')
            );
        }

        return [
            'agents' => $agents,
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
        $data = [];
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
     * Format agent lock by provider
     *
     * @param array $agents Agents data
     */
    public function formatAgentLockByProvider($agents)
    {
        foreach ($agents as $agent) {
            $agent->agent = $agent->username;
            $agent->provider = $agent->name;
            $agent->date = $agent->created_at->format('d-m-Y H:i:s');
        }
    }

    /**
     * format datas lock
     *
     * @param array $agents Agents data
     * @param array $users Users data
     * @param array $subAgents Subagents data
     * @param string $currency Currency iso
     * @param int $provider Provider id
     * @return false|string
     */
    public function formatDataLock($subAgents, $users, $agent, $currency, $provider)
    {
        $blockUsers = [];
        $dataAngets = $this->formatDataLockSubAngents($subAgents, $currency, $provider);
        $dataUsers = $this->formatDataLockUsers($users, $currency, $provider);

        if (!is_null($agent)) {
            $blockUsers[] = [
                'currency_iso' => $currency,
                'provider_id' => $provider,
                'user_id' => $agent->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        $data = array_merge($dataAngets, $dataUsers, $blockUsers);
        return $data;
    }

    /**
     * format data lock sub-angents
     *
     * @param array $agents Agents data
     * @param string $currency Currency iso
     * @param int $provider Provider id
     * @return false|string
     */
    public function formatDataLockSubAngents($agents, $currency, $provider)
    {
        $agentsRepo = new AgentsRepo();
        $dataAgents = [];
        foreach ($agents as $agent) {
            $dataChildren = null;
            $subAgents = $agentsRepo->getAgentsByOwner($agent->user_id, $currency);
            $users = $agentsRepo->getUsersByAgent($agent->id, $currency);

            if (count($subAgents) > 0) {
                $agentsChildren = $this->formatDataLockSubAngents($subAgents, $currency, $provider);
            }

            if (count($users) > 0) {
                $usersChildren = $this->formatDataLockUsers($users, $currency, $provider);
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
            $dataAgents[] = [
                'currency_iso' => $currency,
                'provider_id' => $provider,
                'user_id' => $agent->user_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            if (!is_null($dataChildren)) {
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
    public function formatDataLockUsers($users, $currency, $provider)
    {
        $dataUsers = [];
        foreach ($users as $user) {
            $dataUsers[] = [
                'currency_iso' => $currency,
                'provider_id' => $provider,
                'user_id' => $user['id'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        return $dataUsers;
    }

    /**
     * Format agents transactions
     *
     * @param array $transactions Transactions data
     */
    public function formatAgentTransactions($transactions)
    {
        foreach ($transactions as $transaction) {
            $timezone = session('timezone');
            $transaction->date = $transaction->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->amount = number_format($transaction->amount, 2);
            $transaction->debit = $transaction->transaction_type_id == TransactionTypes::$debit ? $transaction->amount : '-';
            $transaction->credit = $transaction->transaction_type_id == TransactionTypes::$credit ? $transaction->amount : '-';
            if (isset($transaction->data->balance)) {
                $transaction->balance = number_format($transaction->data->balance, 2);
            } else {
                $transaction->balance = 0;
            }
        }
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
            $transaction->date = $transaction->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->amount = number_format($transaction->amount, 2);
            $transaction->debit = $transaction->transaction_type_id == TransactionTypes::$debit ? $transaction->amount : '-';
            $transaction->credit = $transaction->transaction_type_id == TransactionTypes::$credit ? $transaction->amount : '-';
            if (isset($transaction->data->balance)) {
                $transaction->balance = number_format($transaction->data->balance, 2);
            } else {
                $transaction->balance = 0;
            }
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
        $auxCurrencies = [];
        foreach ($excludedUsers as $excludedUser) {
            $position = array_search($currency, $auxCurrencies);
            if ($position === false) {
                if ($currency == $excludedUser->currency_iso) {
                    array_push($auxCurrencies, $currency);
                    $dataUsers[] = [
                        'currency_iso' => $currency,
                        'provider_id' => $excludedUser->provider_id,
                        'user_id' => $user,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                } else {
                    array_push($auxCurrencies, $currency);
                    $dataUsers[] = [
                        'currency_iso' => $currency,
                        'provider_id' => $excludedUser->provider_id,
                        'user_id' => $user,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }
            }
        }
        return $dataUsers;
    }


    /**
     * Format select agents
     *
     * @param array $agents Agents data
     */
    public function formatSelectAgents($agents, $whitelabel)
    {
        $agentsRepo = new AgentsRepo();
        $currency = session('currency');
        $data = [];
        $dataAgents = [];
        $dataUsers = [];
        foreach ($agents as $agent) {
            $dataChildrenAgents = null;
            $dataChildrenUsers = null;
            $subAgents = $agentsRepo->getSearchAgentsByOwner($currency, $agent->user_id, $whitelabel);
            $users = $agentsRepo->getSearchUsersByAgent($currency, $agent->id, $whitelabel);

            if (count($subAgents) > 0) {
                $agentsChildren = $this->formatSelectAgents($subAgents, $whitelabel);
            }

            if (count($users) > 0) {
                $usersChildren = $this->formatSelectUsers($users);
            }

            if (count($subAgents) > 0) {
                $dataAgents = array_merge($dataAgents, $agentsChildren['agents']);
                $dataUsers = array_merge($dataUsers, $agentsChildren['users']);
            }
            if (count($users) > 0) {
                $dataChildrenUsers = $usersChildren;
                foreach ($dataChildrenUsers as $user) {
                    $dataUsers[] = $user;
                }
            }

            $dataAgents[] = [
                "id" => $agent->user_id,
                "username" => $agent->username,
                'type' => 'agent',
            ];

            $data = [
                'agents' => $dataAgents,
                'users' => $dataUsers,
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
                'id' => $user->id,
                'username' => $user->username,
                'type' => 'user',
            ];
        }
        return $dataUsers;
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
        $generalTotals = [];
        $totalCredit = 0;
        $totalDebit = 0;
        $totalProfit = 0;

        if (count((array)$transactions) > 0) {
            foreach ($transactions['debit'] as $key => $debit) {
                foreach ($transactions['credit'] as $credit) {
                    if ($debit->id == $credit->id) {
                        $totalCredit += $credit->total;
                        $totalDebit += $debit->total;
                        $userProfit = $debit->total - $credit->total;
                        $totalProfit += $userProfit;
                        $transactionsData[] = [
                            'id' => $debit->id,
                            'username' => $debit->username,
                            'debit' => number_format($debit->total, 2),
                            'credit' => number_format($credit->total, 2),
                            'profit' => number_format($userProfit, 2)
                        ];
                        unset($transactions['debit'][$key]);
                    }
                }
            }
            foreach ($transactions['debit'] as $debitItem) {
                $totalDebit += $debitItem->total;
                $totalProfit += $debitItem->total;
                $transactionsData[] = [
                    'id' => $debitItem->id,
                    'username' => $debitItem->username,
                    'debit' => number_format($debitItem->total, 2),
                    'credit' => number_format(0, 2),
                    'profit' => number_format($debitItem->total, 2),
                ];
            }
        }

        $generalTotals['credit'] = number_format($totalCredit, 2);
        $generalTotals['debit'] = number_format($totalDebit, 2);
        $generalTotals['profit'] = number_format($totalProfit, 2);

        return [
            'transactions' => $transactionsData,
            'totals' => $generalTotals
        ];
    }

    /**
     *  Format agent and sub-agents
     * @param array $agents Agents data
     *
     */
    public function formatAgentandSubAgents($agents)
    {
        $dataAgents = [];
        $agentsChildren = $this->formatSubAgents($agents);
        $dataAgents = array_merge($dataAgents, $agentsChildren);

        return $dataAgents;
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
        if (!is_null($agent)) {
            $itemObject = new \stdClass();
            $itemObject->id = $agent['id'];
            $itemObject->username = $agent['username'];
            $data->push($itemObject);
        }

        $dataSelect = $this->formatRelocationSubAgents($agents, $currency, $agentMoveId);
        foreach ($dataSelect as $agentSelect) {
            $itemObject = new \stdClass();
            $itemObject->id = $agentSelect['id'];
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
                $subAgents = $agentsRepo->getAgentsByOwner($agent->user_id, $currency);
                if (count($subAgents) > 0) {
                    $agentsChildren = $this->formatRelocationSubAgents($subAgents, $currency, $agentMoveId);
                }

                if (count($subAgents) > 0) {
                    $dataChildren = $agentsChildren;
                }
                if ($agent->user_id != $agentMoveId || $agent->owner_id != $agentMoveId) {
                    if ($agent->master == true) {
                        $dataAgents[] = [
                            'id' => $agent->user_id,
                            'username' => $agent->username,
                        ];

                        if (!is_null($dataChildren)) {
                            $dataAgents = array_merge($dataAgents, $dataChildren);
                        }
                    }
                }
            }
        }

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
        $currency = session('currency');
        $dataAgents = [];

        foreach ($agents as $agent) {
            $dataChildren = null;
            $subAgents = $agentsRepo->getAgentsByOwner($agent->user_id, $currency);
            if (count($subAgents) > 0) {
                $agentsChildren = $this->formatSubAgents($subAgents);
            }

            if (count($subAgents) > 0) {
                $dataChildren = $agentsChildren;
            }

            $dataAgents[] = [
                'username' => $agent->username,
                'user_id' => $agent->user_id,
            ];
            if (!is_null($dataChildren)) {
                $dataAgents = array_merge($dataAgents, $dataChildren);
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
            if (!empty($currency)) {
                $wallet = Wallet::getByClient($user->id, $currency);
                $user->balance = number_format($wallet->data->wallet->balance, 2);
            } else {
                $user->d = $user->id;
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
        $timezone = session('timezone');
        $ticket->date = $ticket->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
        $ticket->type = $ticket->transaction_type_id == TransactionTypes::$debit ? _i('Credit') : _i('Debit');
        $ticket->username = $ticket->username;
        $ticket->currency_iso = $ticket->currency_iso;
        $ticket->amount = number_format($ticket->amount, 2);
        $ticket->from = $ticket->data->from;
        $ticket->to = $ticket->data->to;
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
            'users' => $users,
            'total_balances' => number_format($totalBalances, 2)
        ];
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
                'id' => $user->id,
                'text' => $user->username,
                'status' => $user->status,
                'icon' => 'fa fa-user',
                'li_attr' => [
                    'data_type' => 'user'
                ]
            ];
        }
        return $children;
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
                    'id' => $user->id,
                    'text' => $user->username,
                    'icon' => 'fa fa-user',
                    'li_attr' => [
                        'data_type' => 'user'
                    ]
                ];
            }
        }
        return $children;
    }
}
