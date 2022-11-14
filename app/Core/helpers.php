<?php

use Dotworkers\Security\Enums\Permissions;
use Dotworkers\Configurations\Enums\PaymentMethods;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\TemplateElementTypes;

if (!function_exists('menu')) {

    function menu()
    {
        $menu = [
            'Dashboard' => [
                'text' => _i('Dashboard'),
                'level_class' => 'top',
                'route' => 'core.dashboard',
                'params' => [],
                'icon' => 'hs-admin-dashboard',
                'permission' => Permissions::$dashboard,
                'submenu' => []
            ],

            'Users' => [
                'text' => _i('Users'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-user',
                'permission' => Permissions::$users_menu,
                'submenu' => [

                    'Create' => [
                        'text' => _i('Create'),
                        'level_class' => 'second',
                        'route' => 'users.create',
                        'params' => [],
                        'icon' => 'hs-admin-plus',
                        'permission' => Permissions::$create_users,
                        'submenu' => []
                    ],

                    'AdvancedSearch' => [
                        'text' => _i('Advanced search'),
                        'level_class' => 'second',
                        'route' => 'users.advanced-search',
                        'params' => [],
                        'icon' => 'hs-admin-search',
                        'permission' => Permissions::$advanced_users_search,
                        'submenu' => []
                    ],

                    'RegisteredUsers' => [
                        'text' => _i('Registered users'),
                        'level_class' => 'second',
                        'route' => 'reports.users.registered-users',
                        'params' => [],
                        'icon' => 'hs-admin-pencil-alt',
                        'permission' => Permissions::$web_registers,
                        'submenu' => []
                    ],

                    'UsersStatus' => [
                        'text' => _i('Users status'),
                        'level_class' => 'second',
                        'route' => 'users.users-status',
                        'params' => [],
                        'icon' => 'hs-admin-user',
                        'permission' => Permissions::$users_status,
                        'submenu' => []
                    ],

                    'Balances' => [
                        'text' => _i('Users balances'),
                        'level_class' => 'second',
                        'route' => 'reports.users.balances',
                        'params' => [],
                        'icon' => 'hs-admin-money',
                        'permission' => Permissions::$users_balances,
                        'submenu' => []
                    ],

                    'UsersConversion' => [
                        'text' => _i('Users conversion'),
                        'level_class' => 'second',
                        'route' => 'reports.users.users-conversion',
                        'params' => [],
                        'icon' => 'hs-admin-user',
                        'permission' => Permissions::$users_conversion,
                        'submenu' => []
                    ],

                    'Logins' => [
                        'text' => _i('Logins'),
                        'level_class' => 'second',
                        'route' => 'reports.users.total-logins',
                        'params' => [],
                        'icon' => 'hs-admin-back-right',
                        'permission' => Permissions::$users_logins,
                        'submenu' => []
                    ],

                    'TempUsers' => [
                        'text' => _i('Temp users'),
                        'level_class' => 'second',
                        'route' => 'users.temp',
                        'params' => [],
                        'icon' => 'hs-admin-eraser',
                        'permission' => Permissions::$temp_users,
                        'submenu' => []
                    ],

                    'UsersActives' => [
                        'text' => _i('Active users on platforms'),
                        'level_class' => 'second',
                        'route' => 'reports.users.active-users-platforms',
                        'params' => [],
                        'icon' => 'hs-admin-user',
                        'permission' => Permissions::$users_actives,
                        'submenu' => []
                    ],

                    'ExcludeUsers' => [
                        'text' => _i('Exclude users from providers'),
                        'level_class' => 'second',
                        'route' => 'users.exclude-providers-users',
                        'params' => [],
                        'icon' => 'hs-admin-user',
                        'permission' => Permissions::$exclude_users,
                        'submenu' => []
                    ],
                    'DocumentsVerifications' => [
                        'text' => _i('Documents verifications'),
                        'level_class' => 'second',
                        'route' => 'users.documents-verifications',
                        'params' => [],
                        'icon' => 'hs-admin-book',
                        'permission' =>  Permissions::$document_verification,
                        'submenu' => []
                    ],

                    /*'TransactionByLot' => [
                        'text' => _i('Transactions by lot'),
                        'level_class' => 'second',
                        'route' => 'users.transactions-by-lot',
                        'params' => [],
                        'icon' => 'hs-admin-layers-alt',
                        'permission' => Permissions::$transaction_by_lot,
                        'submenu' => []
                    ],*/

                    'UsersBirthdays' => [
                        'text' => _i('Users birthdays'),
                        'level_class' => 'second',
                        'route' => 'reports.users.users-birthdays',
                        'params' => [],
                        'permission' => Permissions::$users_birthdays_report,
                        'icon' => 'hs-admin-calendar',
                        'submenu' => []
                    ],

                  /*'MostPlayedByProviders' => [
                      'text' => _i('Most played user by provider'),
                      'level_class' => 'fourth',
                      'route' => 'reports.most-played-by-providers',
                      'params' => Permissions::$most_played_by_providers,
                      'icon' => 'hs-admin-stats-up',
                      'submenu' => []
                  ],*/

                    'AutoLock' => [
                        'text' => _i('Autolocked users'),
                        'level_class' => 'third',
                        'route' => 'users.autolocked-users',
                        'params' => [],
                        'icon' => 'hs-admin-lock',
                        'permission' => Permissions::$report_auto_lock_users,
                        'submenu' => []
                    ],

                    'Referrals' => [
                        'text' => _i('Referrals'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-user',
                        'permission' => Permissions::$referrals_menu,
                        'submenu' => [

                            'referralAdd' => [
                                'text' => _i('Add'),
                                'level_class' => 'third',
                                'route' => 'referrals.create',
                                'params' => [],
                                'icon' => 'hs-admin-plus',
                                'permission' => Permissions::$referral_create,
                                'submenu' => []
                            ],

                            'ReportReferral' => [
                                'text' => _i('List'),
                                'level_class' => 'third',
                                'route' => 'referrals.index',
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$report_referrals,
                                'submenu' => []
                            ],
                        ]
                    ],
                ]
            ],

            'Agents' => [
                'text' => _i('Agents'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-briefcase',
                'permission' => Permissions::$agents_menu,
                'submenu' => [

                    'AgentsDashboard' => [
                        'text' => _i('Dashboard'),
                        'level_class' => 'second',
                        'route' => 'agents.index',
                        'params' => [],
                        'icon' => 'hs-admin-dashboard',
                        'permission' => Permissions::$agents_dashboard,
                        'submenu' => []
                    ],

                    'AddAgentUsers' => [
                        'text' => _i('Add users'),
                        'level_class' => 'second',
                        'route' => 'agents.add-users',
                        'params' => [],
                        'icon' => 'hs-admin-plus',
                        'permission' => Permissions::$add_agent_users,
                        'submenu' => []
                    ],

                    'AgentsReports' => [
                        'text' => _i('Reports'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-bar-chart',
                        'permission' => Permissions::$agents_reports_menu,
                        'submenu' => [

                            'AgentsFinancialState' => [
                                'text' => _i('Financial state'),
                                'level_class' => 'third',
                                'route' => 'agents.reports.financial-state',
                                'params' => [],
                                'icon' => 'hs-admin-pie-chart',
                                'permission' => Permissions::$agents_financial_report,
                                'submenu' => []
                            ],

                            'AgentsFinancialStateSummary' => [
                                'text' => _i('Financial state - Summary'),
                                'level_class' => 'third',
                                'route' => 'agents.reports.financial-state-summary',
                                'params' => [],
                                'icon' => 'hs-admin-pie-chart',
                                'permission' => Permissions::$total_financial_report,
                                'submenu' => []
                            ],

                            'AgentsFinancialStateSummaryIncludeBonuses' => [
                                'text' => _i('Financial state - Summary (Include bonuses)'),
                                'level_class' => 'third',
                                'route' => 'agents.reports.financial-state-summary-bonus',
                                'params' => [],
                                'icon' => 'hs-admin-pie-chart',
                                'permission' => Permissions::$total_financial_report,
                                'submenu' => []
                            ],

                            'Agents transactions' => [
                                'text' => _i('Agents transactions'),
                                'level_class' => 'third',
                                'route' => 'agents.reports.agents-transactions',
                                'params' => [],
                                'icon' => 'hs-admin-stats-up',
                                'permission' => Permissions::$agents_transactions,
                                'submenu' => []
                            ],

                            'CashFlow' => [
                                'text' => _i('Cash flow'),
                                'level_class' => 'third',
                                'route' => 'agents.reports.cash-flow',
                                'params' => [],
                                'icon' => 'hs-admin-arrows-horizontal',
                                'permission' => Permissions::$agents_cash_flow,
                                'submenu' => []
                            ],

                            'AgentsBalances' => [
                                'text' => _i('Agents balances'),
                                'level_class' => 'third',
                                'route' => 'agents.reports.agents-balances',
                                'params' => [],
                                'icon' => 'hs-admin-layout-list-thumb',
                                'permission' => Permissions::$agents_balances,
                                'submenu' => []
                            ],

                            'UsersBalances' => [
                                'text' => _i('Users balances'),
                                'level_class' => 'third',
                                'route' => 'agents.reports.users-balances',
                                'params' => [],
                                'icon' => 'hs-admin-layout-list-thumb',
                                'permission' => Permissions::$agents_users_balances,
                                'submenu' => []
                            ],

                            'LockedProviders' => [
                                'text' => _i(' Locked providers'),
                                'level_class' => 'third',
                                'route' => 'agents.reports.locked-providers',
                                'params' => [],
                                'icon' => 'hs-admin-lock',
                                'permission' => Permissions::$locked_providers,
                                'submenu' => []
                            ],

//                           'ManualTransactionsAgents' => [
//                                'text' => _i('Manual transactions'),
//                                'level_class' => 'third',
//                                'route' => 'agents.reports.manual-transactions',
//                                'params' => [],
//                                'icon' => 'hs-admin-pie-chart',
//                                'permission' => Permissions::$manual_transactions_agents,
//                                'submenu' => []
//                            ],
                        ]
                    ]
                ]
            ],

            'Financial' => [
                'text' => _i('Financial'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-stats-up',
                'permission' => Permissions::$financial_reports_menu,
                'submenu' => [

                    'PaymentMethodsTotal' => [
                        'text' => _i('Totals by payment method'),
                        'level_class' => 'second',
                        'route' => 'reports.payment-methods.totals',
                        'params' => [],
                        'icon' => 'hs-admin-exchange-vertical',
                        'permission' => Permissions::$payments_report,
                        'submenu' => []
                    ],

                    'Totals' => [
                        'text' => _i('Totals'),
                        'level_class' => 'second',
                        'route' => 'reports.financial.totals',
                        'params' => [],
                        'icon' => 'hs-admin-exchange-vertical',
                        'permission' => Permissions::$totals_report,
                        'submenu' => []
                    ],

                    'Deposits' => [
                        'text' => _i('Deposits'),
                        'level_class' => 'second',
                        'route' => 'reports.financial.deposits',
                        'params' => [],
                        'icon' => 'hs-admin-import',
                        'permission' => Permissions::$deposits_report,
                        'submenu' => []
                    ],

                    'Withdrawals' => [
                        'text' => _i('Withdrawals'),
                        'level_class' => 'second',
                        'route' => 'reports.financial.withdrawals',
                        'params' => [],
                        'icon' => 'hs-admin-export',
                        'permission' => Permissions::$withdrawals_report,
                        'submenu' => []
                    ],

                    'ManualTransactions' => [
                        'text' => _i('Manual transactions'),
                        'level_class' => 'second',
                        'route' => 'reports.financial.manual-transactions',
                        'params' => [],
                        'icon' => 'hs-admin-money',
                        'permission' => Permissions::$manual_transactions_report,
                        'submenu' => []
                    ],

                    'Bonus' => [
                        'text' => _i('Bonus transactions'),
                        'level_class' => 'second',
                        'route' => 'reports.financial.bonus-transactions',
                        'params' => [],
                        'icon' => 'hs-admin-gift',
                        'permission' => Permissions::$bonus_transactions_report,
                        'submenu' => []
                    ],

                    'DailySales' => [
                        'text' => _i('Daily sales'),
                        'level_class' => 'second',
                        'route' => 'reports.financial.daily-sales',
                        'params' => [],
                        'permission' => Permissions::$daily_sales,
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => []
                    ],

                    'MonthlySales' => [
                        'text' => _i('Monthly sales'),
                        'level_class' => 'second',
                        'route' => 'reports.financial.monthly-sales',
                        'params' => [],
                        'permission' => Permissions::$monthly_sales,
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => []
                    ],

                    'SalesByWhitelabels' => [
                        'text' => _i('Sales by whitelabels'),
                        'level_class' => 'second',
                        'route' => 'reports.financial.whitelabels-sales',
                        'params' => [],
                        'permission' => Permissions::$sales_by_whitelabels,
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => []
                    ],

                    'DepositAndWithdrawalByUser' => [
                        'text' => _i('Profit by user'),
                        'level_class' => 'second',
                        'route' => 'reports.financial.profit-by-user',
                        'params' => [],
                        'permission' => Permissions::$deposit_withdrawal_by_user,
                        'icon' => 'hs-admin-arrows-vertical',
                        'submenu' => []
                    ],

                    'ManualAdjustments' => [
                        'text' => _i('Manual adjustments'),
                        'level_class' => 'second',
                        'route' => 'reports.financial.manual-adjustments',
                        'params' => [],
                        'permission' => Permissions::$report_manual_adjustments,
                        'icon' => 'hs-admin-arrows-vertical',
                        'submenu' => []
                    ],

                    'ManualAdjustmentsWhitelabel' => [
                        'text' => _i('Manual adjustments'),
                        'level_class' => 'second',
                        'route' => 'reports.financial.manual-adjustments-users',
                        'params' => [],
                        'permission' => Permissions::$manual_adjustments_whitelabel,
                        'icon' => 'hs-admin-arrows-vertical',
                        'submenu' => []
                    ]
                ]
            ],

            'Operations' => [
                'text' => _i('Operations'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-settings',
                'permission' => Permissions::$operations_menu,
                'submenu' => [

                    'ProductsTotals' => [
                        'text' => _i('Products totals'),
                        'level_class' => 'second',
                        'route' => 'reports.products-totals',
                        'params' => [],
                        'icon' => 'hs-admin-stats-up',
                        'permission' => Permissions::$products_totals,
                        'submenu' => []
                    ],

                    'WhitelabelsTotals' => [
                        'text' => _i('Whitelabels totals'),
                        'level_class' => 'second',
                        'route' => 'reports.whitelabels-totals',
                        'params' => [],
                        'icon' => 'hs-admin-stats-up',
                        'permission' => Permissions::$whitelabels_totals,
                        'submenu' => []
                    ],

                    'ProductsTotalsGeneral' => [
                        'text' => _i('Products totals'),
                        'level_class' => 'second',
                        'route' => 'reports.products-totals-overview',
                        'params' => [],
                        'icon' => 'hs-admin-stats-up',
                        'permission' => Permissions::$whitelabels_totals,
                        'submenu' => []
                    ],
                ]
            ],

            /*'Referrals' => [
                'text' => _i('Referrals'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-user',
                'permission' => Permissions::$referrals_menu,
                'submenu' => [

                    'referralAdd' => [
                        'text' => _i('Add'),
                        'level_class' => 'second',
                        'route' => 'referrals.create',
                        'params' => [],
                        'icon' => 'hs-admin-plus',
                        'permission' => Permissions::$referral_create,
                        'submenu' => []
                    ],

                    'ReportReferral' => [
                        'text' => _i('List'),
                        'level_class' => 'second',
                        'route' => 'referrals.index',
                        'params' => [],
                        'icon' => 'hs-admin-list',
                        'permission' => Permissions::$report_referrals,
                        'submenu' => []
                    ],
                ]
            ],*/

            'BetPay' => [
                'text' => _i('BetPay'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-credit-card',
                'permission' => Permissions::$betpay_menu,
                'submenu' => [

                    'AccountsSearch' => [
                        'text' => _i('Accounts search'),
                        'level_class' => 'second',
                        'route' => 'betpay.accounts.search',
                        'params' => [],
                        'icon' => 'hs-admin-search',
                        'permission' => Permissions::$check_user_accounts,
                        'submenu' => []
                    ],

                    'Abitab' => [
                        'text' => _i('Abitab'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$abitab_menu,
                        'payment_method' => PaymentMethods::$abitab,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.abitab.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_abitab_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'AirTM' => [
                        'text' => _i('AirTM'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$airtm_menu,
                        'payment_method' => PaymentMethods::$airtm,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.airtm.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_airtm_menu,
                                'submenu' => []
                            ],

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.airtm.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_airtm_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Bizum' => [
                        'text' => _i('Bizum'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$bizum_menu,
                        'payment_method' => PaymentMethods::$bizum,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.bizum.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_bizum_menu,
                                'submenu' => []
                            ],

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.bizum.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_bizum_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'ChargingPoint' => [
                        'text' => _i('Charging point'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$charging_point_menu,
                        'payment_method' => PaymentMethods::$charging_point,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.charging-point.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_charging_point_menu,
                                'submenu' => []
                            ],

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.charging-point.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_charging_point_menu,
                                'submenu' => []
                            ],

                            'Search' => [
                                'text' => _i('Debit by ticket'),
                                'level_class' => 'third',
                                'route' => 'betpay.charging-point.search',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$charging_point_search_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'ProntoPaga' => [
                        'text' => _i('Pronto Paga'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$pronto_paga_menu,
                        'payment_method' => PaymentMethods::$pronto_paga,
                        'submenu' => [
                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.pronto-paga.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_pronto_paga_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Payku' => [
                        'text' => _i('PayKu'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$payku_menu,
                        'payment_method' => PaymentMethods::$payku,
                        'submenu' => [
                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.payku.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_payku_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'PayForFunGateway' => [
                        'text' => _i('Pay For Fun Go'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$pay_for_fun_go_menu,
                        'payment_method' => PaymentMethods::$pay_for_fun_go,
                        'submenu' => [
                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.pay-for-fun-gateway.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_pay_for_fun_go_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Binance' => [
                        'text' => _i('Binance'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$binance_menu,
                        'payment_method' => PaymentMethods::$binance,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.binance.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_binance_menu,
                                'submenu' => []
                            ],

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.binance.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_binance_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Cryptocurrencies' => [
                        'text' => _i('Cryptocurrencies'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$cryptocurrencies_menu,
                        'payment_method' => PaymentMethods::$cryptocurrencies,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.cryptocurrencies.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_cryptocurrencies_menu,
                                'submenu' => []
                            ],

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.cryptocurrencies.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$debit_cryptocurrencies_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'ALPS' => [
                        'text' => _i('ALPS'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$just_pay_menu,
                        'payment_method' => PaymentMethods::$just_pay,
                        'submenu' => [

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.justpay.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$just_pay_debit_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Monnet' => [
                        'text' => _i('Monnet'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$just_pay_menu,
                        'payment_method' => PaymentMethods::$monnet,
                        'submenu' => [

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.monnet.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$just_pay_debit_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'MobilePayment' => [
                        'text' => _i('Mobile payment'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$mobile_payment_menu,
                        'payment_method' => PaymentMethods::$mobile_payment,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.mobile-payment.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$mobile_payment_credit_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Neteller' => [
                        'text' => _i('Neteller'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$neteller_menu,
                        'payment_method' => PaymentMethods::$neteller,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.neteller.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_neteller_menu,
                                'submenu' => []
                            ],
                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.neteller.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_neteller_menu,
                                'submenu' => []
                            ],

                        ]
                    ],

                    'Nequi' => [
                        'text' => _i('Nequi'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$nequi_menu,
                        'payment_method' => PaymentMethods::$nequi,
                        'submenu' => [
                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.nequi.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_nequi_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Paypal' => [
                        'text' => _i('PayPal'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$paypal_menu,
                        'payment_method' => PaymentMethods::$paypal,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.paypal.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_paypal_menu,
                                'submenu' => []
                            ],

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.paypal.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_paypal_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'PayForFun' => [
                        'text' => _i('Pay For Fun'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$pay_for_fun_menu,
                        'payment_method' => PaymentMethods::$pay_for_fun,
                        'submenu' => [

                            //                            'Credit' => [
                            //                                'text' => _i('Credit'),
                            //                                'level_class' => 'third',
                            //                                'route' => 'betpay.pay-for-fun.credit',
                            //                                'params' => [],
                            //                                'icon' => 'hs-admin-shift-left',
                            //                                'permission' => Permissions::$credit_paypal_menu,
                            //                                'submenu' => []
                            //                            ],

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.pay-for-fun.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_pay_for_fun_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'PayRetailers' => [
                        'text' => _i('Pay Retailers'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$pay_retailers_menu,
                        'payment_method' => PaymentMethods::$pay_retailers,
                        'submenu' => [

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.pay-retailers.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_pay_retailers_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'RedPagos' => [
                        'text' => _i('RedPagos'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$red_pagos_menu,
                        'payment_method' => PaymentMethods::$red_pagos,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.red-pagos.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_red_pagos_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Skrill' => [
                        'text' => _i('Skrill'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$skrill_menu,
                        'payment_method' => PaymentMethods::$skrill,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.skrill.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_skrill_menu,
                                'submenu' => []
                            ],

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.skrill.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_skrill_menu,
                                'submenu' => []
                            ],

                        ]
                    ],

//                    'TotalPago' => [
//                        'text' => _i('Total pago'),
//                        'level_class' => 'second',
//                        'route' => null,
//                        'params' => [],
//                        'icon' => 'hs-admin-control-shuffle',
//                        'permission' => Permissions::$total_pago_menu,
//                        'payment_method' => PaymentMethods::$total_pago,
//                        'submenu' => [
//
//                            'Credit' => [
//                                'text' => _i('Credit'),
//                                'level_class' => 'third',
//                                'route' => 'betpay.total-pago.credit',
//                                'params' => [],
//                                'icon' => 'hs-admin-shift-left',
//                                'permission' => Permissions::$credit_total_pago_menu,
//                                'submenu' => []
//                            ],
//                        ]
//                    ],

                    'Reserve' => [
                        'text' => _i('Reserve'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$reserve_menu,
                        'payment_method' => PaymentMethods::$reserve,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.reserve.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_reserve_menu,
                                'submenu' => []
                            ],

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.reserve.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_reserve_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Personal' => [
                        'text' => _i('Personal'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$personal_menu,
                        'payment_method' => PaymentMethods::$personal,
                        'submenu' => [
                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.personal.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$debit_personal_menu,
                                'submenu' => []
                            ],
                            'SearchTransaction' => [
                                'text' => _i('Search transaction'),
                                'level_class' => 'third',
                                'route' => 'betpay.personal.search',
                                'params' => [],
                                'icon' => 'hs-admin-search',
                                'permission' => Permissions::$consult_personal_menu,
                                'submenu' => []
                            ],
//                            'CancelTransaction' => [
//                                'text' => _i('Cancel transaction'),
//                                'level_class' => 'third',
//                                'route' => 'betpay.personal.cancel',
//                                'params' => [],
//                                'icon' => 'hs-admin-back-right',
//                                'permission' => Permissions::$cancel_personal_menu,
//                                'submenu' => []
//                            ],
                        ]
                    ],

                    'Uphold' => [
                        'text' => _i('Uphold'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$uphold_menu,
                        'payment_method' => PaymentMethods::$uphold,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.uphold.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_uphold_menu,
                                'submenu' => []
                            ],

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.uphold.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_uphold_menu,
                                'submenu' => []
                            ],

                        ]
                    ],

                    'VCreditos' => [
                        'text' => _i('VCreditos'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$vcreditos_menu,
                        'payment_method' => PaymentMethods::$vcreditos,
                        'submenu' => [

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.vcreditos.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$vcreditos_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'VCreditosApi' => [
                        'text' => _i('VCreditos API'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$vcreditos_api_menu,
                        'payment_method' => PaymentMethods::$vcreditos_api,
                        'submenu' => [

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.vcreditos-api.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_vcreditos_api_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'VesToUsd' => [
                        'text' => _i('VES to USD'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$ves_to_usd_menu,
                        'payment_method' => PaymentMethods::$ves_to_usd,
                        'submenu' => [

                            'Debit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.ves-to-usd.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$ves_to_usd_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'WireTransfers' => [
                        'text' => _i('Wire transfers'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$wire_transfers_menu,
                        'payment_method' => PaymentMethods::$wire_transfers,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.wire-transfers.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_wire_transfers_menu,
                                'submenu' => []
                            ],

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.wire-transfers.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_wire_transfers_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Zampay' => [
                        'text' => _i('Zampay'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$zampay_menu,
                        'payment_method' => PaymentMethods::$zampay,
                        'submenu' => [
                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.zampay.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_zampay_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Zelle' => [
                        'text' => _i('Zelle'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$zelle_menu,
                        'payment_method' => PaymentMethods::$zelle,
                        'submenu' => [

                            'Credit' => [
                                'text' => _i('Credit'),
                                'level_class' => 'third',
                                'route' => 'betpay.zelle.credit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$credit_zelle_menu,
                                'submenu' => []
                            ],

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.zelle.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-right',
                                'permission' => Permissions::$debit_zelle_menu,
                                'submenu' => []
                            ],
                        ]
                    ],

                    /*'Zippy' => [
                        'text' => _i('Zippy'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$zippy_menu,
                        'payment_method' => PaymentMethods::$zippy,
                        'submenu' => [

                            'Debit' => [
                                'text' => _i('Debit'),
                                'level_class' => 'third',
                                'route' => 'betpay.zippy.debit',
                                'params' => [],
                                'icon' => 'hs-admin-shift-left',
                                'permission' => Permissions::$zippy_debit_menu,
                                'submenu' => []
                            ],
                        ]
                    ],*/

                    'BetPayReports' => [
                        'text' => _i('Reports'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-bar-chart',
                        'permission' => Permissions::$betpay_reports_menu,
                        'submenu' => [

                            'Report123Pago' => [
                                'text' => _i('123Pago'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$pago_123,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$pago_123],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportAbitab' => [
                                'text' => _i('Abitab'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$abitab,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$abitab],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportAirTM' => [
                                'text' => _i('AirTM'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$airtm,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$airtm],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$airtm],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportBizum' => [
                                'text' => _i('Bizum'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$bizum,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$bizum],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$bizum],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportChargingPoint' => [
                                'text' => _i('Charging point'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$charging_point,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$charging_point],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$charging_point],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportCryptocurrencies' => [
                                'text' => _i('Cryptocurrencies'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$cryptocurrencies,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$cryptocurrencies],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$cryptocurrencies],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportCoralCommerce' => [
                                'text' => _i('Coral commerce'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$coral_commerce,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$coral_commerce],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportJustPay' => [
                                'text' => _i('ALPS'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$just_pay,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$just_pay],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$just_pay],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportNeteller' => [
                                'text' => _i('Neteller'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$neteller,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$neteller],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$neteller],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportNequi' => [
                                'text' => _i('Nequi'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$nequi,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$nequi],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportPayPal' => [
                                'text' => _i('PayPal'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$zelle_menu,
                                'payment_method' => PaymentMethods::$paypal,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$paypal],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$paypal],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportPayRetailers' => [
                                'text' => _i('Pay Retailers'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$pay_retailers,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$pay_retailers],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$pay_retailers],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportRedPagos' => [
                                'text' => _i('RedPagos'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$red_pagos,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$red_pagos],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportSkrill' => [
                                'text' => _i('Skrill'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$skrill,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$skrill],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$skrill],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportMobilePayment' => [
                                'text' => _i('Mobile payment'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$mobile_payment,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$mobile_payment],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$mobile_payment],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportTotalPago' => [
                                'text' => _i('Total Pago'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$total_pago,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$total_pago],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportUphold' => [
                                'text' => _i('Uphold'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$uphold,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$uphold],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$uphold],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportVCreditosApi' => [
                                'text' => _i('VCreditos Api'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$vcreditos_api,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$vcreditos_api],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$vcreditos_api],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportVesToUsd' => [
                                'text' => _i('VES to USD'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$ves_to_usd,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$ves_to_usd],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportWireTransfers' => [
                                'text' => _i('Wire transfers'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$wire_transfers,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$wire_transfers],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$wire_transfers],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportZelle' => [
                                'text' => _i('Zelle'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$zelle,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$zelle],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$zelle],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportZippy' => [
                                'text' => _i('Zippy'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$zippy,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$zippy],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$zippy],
                                        'icon' => 'hs-admin-shift-right',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportMonnet' => [
                                'text' => _i('Monnet'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$monnet,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$monnet],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$monnet],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportBinance' => [
                                'text' => _i('Binance'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$binance,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$binance],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$binance],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportReserve' => [
                                'text' => _i('Reserve'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$reserve,
                                'submenu' => [

                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$reserve],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$reserve],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportPayku' => [
                                'text' => _i('Payku'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$payku,
                                'submenu' => [
                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$payku],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$payku],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportPersonal' => [
                                'text' => _i('Personal'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$personal,
                                'submenu' => [
                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$personal],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$personal],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportProntoPaga' => [
                                'text' => _i('Pronto Paga'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$pronto_paga,
                                'submenu' => [
                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$pronto_paga],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$pronto_paga],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ReportPayForFunGateway' => [
                                'text' => _i('Pay For Fun Gateway'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$betpay_reports_menu,
                                'payment_method' => PaymentMethods::$pay_for_fun_go,
                                'submenu' => [
                                    'Credit' => [
                                        'text' => _i('Credit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.credit',
                                        'params' => [PaymentMethods::$pay_for_fun_go],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],

                                    'Debit' => [
                                        'text' => _i('Debit'),
                                        'level_class' => 'fourth',
                                        'route' => 'betpay.reports.debit',
                                        'params' => [PaymentMethods::$pay_for_fun_go],
                                        'icon' => 'hs-admin-shift-left',
                                        'permission' => Permissions::$betpay_reports_menu,
                                        'submenu' => []
                                    ],
                                ]
                            ],
                        ]
                    ],
                ]
            ],

            'Altenar' => [
                'text' => _i('Altenar'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-control-shuffle',
                'provider' => Providers::$altenar,
                'permission' => Permissions::$altenar_ticket_search,
                'submenu' => [

                    'Search' => [
                        'text' => _i('Ticket search'),
                        'level_class' => 'second',
                        'route' => 'altenar.ticket',
                        'params' => [],
                        'icon' => 'hs-admin-search',
                        'permission' => Permissions::$altenar_ticket_search,
                        'submenu' => []
                    ],
                ]
            ],

            'IQSoft' => [
                'text' => _i('IQSoft'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-control-shuffle',
                'provider' => Providers::$iq_soft,
                'permission' => Permissions::$iq_soft_ticket_search,
                'submenu' => [

                    'Search' => [
                        'text' => _i('Ticket search'),
                        'level_class' => 'second',
                        'route' => 'iq-soft.ticket',
                        'params' => [],
                        'icon' => 'hs-admin-search',
                        'permission' => Permissions::$iq_soft_ticket_search,
                        'submenu' => []
                    ],
                ]
            ],


            'BonusSystem' => [
                'text' => _i('Bonus system'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-money',
                'permission' => Permissions::$system_bonus_menu,
                'submenu' => [

                    'Campaigns' => [
                        'text' => _i('Campaigns'),
                        'level_class' => 'top',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-menu-alt',
                        'permission' => Permissions::$campaigns_menu,
                        'submenu' => [

                            'New' => [
                                'text' => _i('New'),
                                'level_class' => 'second',
                                'route' => 'bonus-system.campaigns.create',
                                'params' => [],
                                'icon' => 'hs-admin-plus',
                                'permission' => Permissions::$manage_campaigns,
                                'submenu' => []
                            ],

                            'List' => [
                                'text' => _i('List'),
                                'level_class' => 'second',
                                'route' => 'bonus-system.campaigns.index',
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$manage_campaigns,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'CampaignsReports' => [
                        'text' => _i('Reports'),
                        'level_class' => 'top',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-menu-alt',
                        'permission' => Permissions::$campaign_reports_menu,
                        'submenu' => [

                            'CampaignOverview' => [
                                'text' => _i('Campaigns overview'),
                                'level_class' => 'second',
                                'route' => 'bonus-system.reports.campaigns-overview',
                                'params' => [],
                                'icon' => 'hs-admin-back-right',
                                'permission' => Permissions::$campaign_report,
                                'submenu' => []
                            ],

                            'CampaignByUser' => [
                                'text' => _i('Campaigns by user'),
                                'level_class' => 'second',
                                'route' => 'bonus-system.reports.participation-by-users',
                                'params' => [],
                                'icon' => 'hs-admin-user',
                                'permission' => Permissions::$campaign_user_report,
                                'submenu' => []
                            ],
                        ]
                    ],
                ]
            ],

//            'Dotsuite' => [
//                'text' => _i('Dotsuite'),
//                'level_class' => 'top',
//                'route' => null,
//                'params' => [],
//                'icon' => 'hs-admin-bar-chart',
//                //'permission' => Permissions::$menu_dotsuite,
//                'provider' => Providers::$dot_suite,
//                'submenu' => [
//
//                    'FreeSpins' => [
//                      'text' => _i('Free spins'),
//                     'level_class' => 'top',
//                        'route' => null,
//                        'params' => [],
//                        'icon' => 'hs-admin-menu-alt',
//                        //'permission' => Permissions::$dot_suite_free_spins_menu,
//                        'submenu' => [
//
 //                           'FreeSpinsCaletaGaming' => [
//                                'text' => _i('Caleta gaming'),
//                                'level_class' => 'second',
//                                'route' => null,
//                                'params' =>  [],
//                                'icon' => 'hs-admin-control-shuffle',
//                                //'permission' => Permissions::$dot_suite_free_spins_caleta_gaming_menu,
//                                'provider' => Providers::$caleta_gaming,
//                                'submenu' => [
//
//                                    'CaletaGamingNew' => [
//                                        'text' => _i('Assign free spins'),
//                                        'level_class' => 'second',
//                                        'route' => 'dot-suite.free-spins.caleta-gaming.create-slot',
//                                        'params' =>  [Providers::$caleta_gaming],
//                                        'icon' => 'hs-admin-plus',
//                                        //'permission' => Permissions::$dot_suite_free_spins_caleta_gaming_create,
//                                        'submenu' => []
//                                   ],
//
//                                   'CaletaGamingCancel' => [
//                                       'text' => _i('Cancel free spins '),
//                                       'level_class' => 'second',
//                                        'route' => 'dot-suite.free-spins.caleta-gaming.cancel-free-spins',
//                                        'params' =>  [Providers::$caleta_gaming],
 //                                       'icon' => 'hs-admin-list',
//                                        //'permission' => Permissions::$dot_suite_free_spins_caleta_gaming_cancel,
//                                        'submenu' => []
//                                    ],
//
//                                ]
//                            ],
//
//                            'FreeSpinsEvoPlay' => [
//                                'text' => _i('Evo play'),
//                                'level_class' => 'second',
//                                'route' => 'dot-suite.slot.createSlot',
//                                'params' => [Providers::$evo_play],
//                                'icon' => 'hs-admin-control-shuffle',
                                //'permission' => Permissions::$dot_suite_free_spins_evo_play_menu,
 //                               'provider' => Providers::$evo_play,
 //                               'submenu' => [
//
//                                    'SlotEvoPlayFreeSpins' => [
//                                        'text' => _i('Assign free spins'),
//                                        'level_class' => 'second',
//                                        'route' => 'dot-suite.free-spins.evo-play.create-slot',
//                                        'params' => [Providers::$evo_play],
//                                        'icon' => 'hs-admin-plus',
                                        //'permission' => Permissions::$dot_suite_free_spins_evo_play_create,
 //                                       'submenu' => []
 //                                   ],
 //                               ]
 ///                           ],
/*
                            'FreeSpinsTripleCherry' => [
                                'text' => _i('Triple cherry'),
                                'level_class' => 'second',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                //'permission' => Permissions::$dot_suite_free_spins_triple_cherry_menu,
                                'provider' => Providers::$triple_cherry_original,
                                'submenu' => [

                                    'SlotTripleCherryNew' => [
                                        'text' => _i('Assign Free spins'),
                                        'level_class' => 'second',
                                        'route' => 'dot-suite.free-spins.triple-cherry.create',
                                        'params' => [Providers::$triple_cherry_original],
                                        'icon' => 'hs-admin-plus',
                                        //'permission' => Permissions::$dot_suite_free_spins_triple_cherry_create,
                                        'submenu' => []
                                    ],

                                    'TripleCherryCancel' => [
                                        'text' => _i('Cancel free spins'),
                                        'level_class' => 'second',
                                        'route' => 'dot-suite.free-spins.triple-cherry.cancel-free-spins',
                                        'params' => [Providers::$triple_cherry_original],
                                        'icon' => 'hs-admin-list',
                                        //'permission' => Permissions::$dot_suite_free_spins_triple_cherry_cancel,
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'Report' => [
                                'text' => _i('Reports'),
                                'level_class' => 'second',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-bar-chart',
                                //'permission' => Permissions::$dot_suite_free_spins_reports_menu,
                                'submenu' => [

                                    'FreeSpinsReport' => [
                                        'text' => _i('Free spins report'),
                                        'level_class' => 'second',
                                        'route' => 'dot-suite.free-spins.reports.free-spins',
                                        'params' => [],
                                        'icon' => 'hs-admin-list',
                                        //'permission' => Permissions::$dot_suite_free_spins_report,
                                        'submenu' => []
                                    ],
                                ]
                            ],
                        ]
                    ],*/

//                    /*'ManageMenuDotsuite' => [
//                        'text' => _i('Campaigns'),
//                        'level_class' => 'top',
//                        'route' => null,
//                        'params' => [],
//                        'icon' => 'hs-admin-menu-alt',
//                        'permission' => Permissions::$campaigns_menu,
//                        'submenu' => [
//
//                            'New' => [
//                                'text' => _i('New'),
//                                'level_class' => 'second',
//                                'route' => 'bonus-system.campaigns.create',
//                                'params' => [],
//                                'icon' => 'hs-admin-plus',
//                                'permission' => Permissions::$manage_campaigns,
//                                'submenu' => []
//                            ],
//
//                            'List' => [
//                                'text' => _i('List'),
//                                'level_class' => 'second',
//                                'route' => 'bonus-system.campaigns.index',
//                                'params' => [],
//                                'icon' => 'hs-admin-list',
//                                'permission' => Permissions::$manage_campaigns,
//                                'submenu' => []
//                            ],
//                        ]
//                    ],*/
 //               ]
//            ],

            'Sliders' => [
                'text' => _i('Sliders'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-gallery',
                'permission' => Permissions::$sliders_menu,
                'submenu' => [

                    'Lobby' => [
                        'text' => _i('Lobbys'),
                        'level_class' => 'top',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-layout-grid-3',
                        'permission' => Permissions::$sliders_menu,
                        'submenu' => [

                            'New' => [
                                'text' => _i('New'),
                                'level_class' => 'second',
                                'route' => 'sliders.create',
                                'params' => [],
                                'icon' => 'hs-admin-plus',
                                'permission' => Permissions::$manage_sliders,
                                'submenu' => []
                            ],

                            'List' => [
                                'text' => _i('List'),
                                'level_class' => 'second',
                                'route' => 'sliders.index',
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$sliders_list,
                                'submenu' => []
                            ],
                        ]
                    ],
                ]
            ],

            'Images' => [
                'text' => _i('Images (Beta)'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-image',
                'permission' => Permissions::$section_images_menu,
                'submenu' => [

                    'RegisterImages' => [
                        'text' => _i('Register form'),
                        'level_class' => 'second',
                        'route' => 'section-images.index',
                        'params' => [TemplateElementTypes::$register_form],
                        'icon' => 'hs-admin-shift-left',
                        'permission' => Permissions::$section_images_list,
                        'submenu' => []
                    ],

                    'LoginImages' => [
                        'text' => _i('Login form'),
                        'level_class' => 'second',
                        'route' => 'section-images.index',
                        'params' => [TemplateElementTypes::$login_form],
                        'icon' => 'hs-admin-import',
                        'permission' => Permissions::$section_images_list,
                        'submenu' => []
                    ],

                    'LogoFavicon' => [
                        'text' => _i('Logo and favicon'),
                        'level_class' => 'second',
                        'route' => 'section-images.index',
                        'params' => [TemplateElementTypes::$header],
                        'icon' => 'hs-admin-import',
                        'permission' => Permissions::$section_images_list,
                        'submenu' => []
                    ]
                ]
            ],

            /*'Games' => [
                'text' => _i('Games section'),
                'level_class' => 'top',
                'route' => 'section-games.index',
                'params' => [],
                'icon' => 'hs-admin-game',
                'permission' => Permissions::$manage_section_games,
                'submenu' => [
                    'GamesSection' => [
                        'text' => _i('Games section'),
                        'level_class' => 'second',
                        'route' => 'section-games.index',
                        'params' => [TemplateElementTypes::$home],
                        'icon' => 'hs-admin-shift-left',
                        'permission' => Permissions::$manage_section_games,
                        'submenu' => []
                    ]
                ]
            ],*/


            /*'Featured' => [
                'text' => _i('Featured lobby'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-image',
                'permission' => Permissions::$section_images_menu,
                'submenu' => [
                    'UploadImages' => [
                        'text' => _i('Images'),
                        'level_class' => 'second',
                        'route' => 'featured-images.index',
                        'params' => [],
                        'icon' => 'hs-admin-upload',
                        'permission' => Permissions::$manage_section_images,
                        'submenu' => [
                            'Upload' => [
                                'text' => _i('Upload'),
                                'level_class' => 'third',
                                'route' => 'featured-images.create',
                                'params' => [TemplateElementTypes::$lobby_featured],
                                'icon' => 'hs-admin-upload',
                                'permission' => Permissions::$manage_section_images,
                                'submenu' => []
                            ],

                            'List' => [
                                'text' => _i('List'),
                                'level_class' => 'third',
                                'route' => 'featured-images.index',
                                'params' => [TemplateElementTypes::$lobby_featured],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$section_images_list,
                                'submenu' => []
                            ],
                        ]
                    ]
                ]
            ],*/

            'Lobbys' => [
                'text' => _i('Lobbys'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-gallery',
                'permission' => Permissions::$section_images_menu,
                'submenu' => [
                    'LobbyFeatured' => [
                        'text' => _i('Featured lobby'),
                        'level_class' => 'second',
                        'route' => 'featured-images.index',
                        'params' => [],
                        'icon' => 'hs-admin-star',
                        'permission' => Permissions::$manage_section_images,
                        'submenu' => [
                            'FeaturedUpload' => [
                                'text' => _i('Upload'),
                                'level_class' => 'third',
                                'route' => 'featured-images.create',
                                'params' => [TemplateElementTypes::$lobby_featured],
                                'icon' => 'hs-admin-upload',
                                'permission' => Permissions::$manage_section_images,
                                'submenu' => []
                            ],

                            'FeaturedList' => [
                                'text' => _i('List'),
                                'level_class' => 'third',
                                'route' => 'featured-images.index',
                                'params' => [TemplateElementTypes::$lobby_featured],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$section_images_list,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'LobbyReconmended' => [
                        'text' => _i('Recommended'),
                        'level_class' => 'second',
                        'route' => 'featured-images.index',
                        'params' => [],
                        'icon' => 'hs-admin-check-box',
                        'permission' => Permissions::$manage_section_images,
                        'submenu' => [
                            'RecommendedUpload' => [
                                'text' => _i('Upload'),
                                'level_class' => 'third',
                                'route' => 'featured-images.create',
                                'params' => [TemplateElementTypes::$lobby_recommended],
                                'icon' => 'hs-admin-upload',
                                'permission' => Permissions::$manage_section_images,
                                'submenu' => []
                            ],

                            'RecommendedList' => [
                                'text' => _i('List'),
                                'level_class' => 'third',
                                'route' => 'featured-images.index',
                                'params' => [TemplateElementTypes::$lobby_recommended],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$section_images_list,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'LobbyInfo' => [
                        'text' => _i('Info'),
                        'level_class' => 'second',
                        'route' => 'featured-images.index',
                        'params' => [],
                        'icon' => 'hs-admin-info',
                        'permission' => Permissions::$manage_section_images,
                        'submenu' => [
                            'InfoUpload' => [
                                'text' => _i('Upload'),
                                'level_class' => 'third',
                                'route' => 'featured-images.create',
                                'params' => [TemplateElementTypes::$lobby_info],
                                'icon' => 'hs-admin-upload',
                                'permission' => Permissions::$manage_section_images,
                                'submenu' => []
                            ],

                            'InfoList' => [
                                'text' => _i('List'),
                                'level_class' => 'third',
                                'route' => 'featured-images.index',
                                'params' => [TemplateElementTypes::$lobby_info],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$section_images_list,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'LobbyNotifications' => [
                        'text' => _i('Notifications'),
                        'level_class' => 'second',
                        'route' => 'featured-images.index',
                        'params' => [],
                        'icon' => 'hs-admin-bell',
                        'permission' => Permissions::$manage_section_images,
                        'submenu' => [
                            'NotificationsUpload' => [
                                'text' => _i('Upload'),
                                'level_class' => 'third',
                                'route' => 'featured-images.create',
                                'params' => [TemplateElementTypes::$lobby_notifications],
                                'icon' => 'hs-admin-upload',
                                'permission' => Permissions::$manage_section_images,
                                'submenu' => []
                            ],

                            'NotificationsList' => [
                                'text' => _i('List'),
                                'level_class' => 'third',
                                'route' => 'featured-images.index',
                                'params' => [TemplateElementTypes::$lobby_notifications],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$section_images_list,
                                'submenu' => []
                            ],
                        ]
                    ],
                ]
            ],

            'WhitelabelsGames' => [
                'text' => _i('Highlights games'),
                'level_class' => 'top',
                'route' => 'whitelabels-games.index',
                'params' => [],
                'icon' => 'hs-admin-game',
                'permission' => Permissions::$whitelabels_games_menu,
                'submenu' => []
            ],

            'Modals' => [
                'text' => _i('Popups'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-layout-slider-alt',
                'permission' => Permissions::$modals_menu,
                'submenu' => [

                    'New' => [
                        'text' => _i('New'),
                        'level_class' => 'second',
                        'route' => 'section-modals.create',
                        'params' => [],
                        'icon' => 'hs-admin-plus',
                        'permission' => Permissions::$manage_modals,
                        'submenu' => []
                    ],

                    'List' => [
                        'text' => _i('List'),
                        'level_class' => 'second',
                        'route' => 'section-modals.index',
                        'params' => [],
                        'icon' => 'hs-admin-list',
                        'permission' => Permissions::$modals_list,
                        'submenu' => []
                    ],
                ]
            ],

            'Posts' => [
                'text' => _i('Posts'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-files',
                'permission' => Permissions::$promotions_menu,
                'submenu' => [

                    'Upload' => [
                        'text' => _i('New'),
                        'level_class' => 'second',
                        'route' => 'posts.create',
                        'params' => [],
                        'icon' => 'hs-admin-plus',
                        'permission' => Permissions::$manage_promotions,
                        'submenu' => []
                    ],

                    'List' => [
                        'text' => _i('List'),
                        'level_class' => 'second',
                        'route' => 'posts.index',
                        'params' => [],
                        'icon' => 'hs-admin-list',
                        'permission' => Permissions::$promotions_list,
                        'submenu' => []
                    ],
                ]
            ],

            'CRM' => [
                'text' => _i('CRM'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-target',
                'permission' => Permissions::$crm,
                'submenu' => [

                    'SegmentationTool' => [
                        'text' => _i('Segmentation'),
                        'level_class' => 'top',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-layout-list-thumb',
                        'permission' => Permissions::$segmentation_tool_menu,
                        'submenu' => [

                            'New' => [
                                'text' => _i('New'),
                                'level_class' => 'second',
                                'route' => 'segments.create',
                                'params' => [],
                                'icon' => 'hs-admin-plus',
                                'permission' => Permissions::$manage_segmentation_tool,
                                'submenu' => []
                            ],

                            'List' => [
                                'text' => _i('List'),
                                'level_class' => 'second',
                                'route' => 'segments.index',
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$manage_segmentation_tool,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'EmailTemplates' => [
                        'text' => _i('Email templates'),
                        'level_class' => 'top',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-email',
                        'permission' => Permissions::$email_templates_menu,
                        'submenu' => [

                            'New' => [
                                'text' => _i('New'),
                                'level_class' => 'second',
                                'route' => 'email-templates.create',
                                'icon' => 'hs-admin-plus',
                                'permission' => Permissions::$manage_email_templates,
                                'submenu' => []
                            ],

                            'List' => [
                                'text' => _i('List'),
                                'level_class' => 'second',
                                'route' => 'email-templates.index',
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$manage_email_templates,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'MarketingCampaigns' => [
                        'text' => _i('Marketing campaigns'),
                        'level_class' => 'top',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-marker',
                        'permission' => Permissions::$marketing_campaigns_menu,
                        'submenu' => [

                            'New' => [
                                'text' => _i('New'),
                                'level_class' => 'second',
                                'route' => 'marketing-campaigns.create',
                                'params' => [],
                                'icon' => 'hs-admin-plus',
                                'permission' => Permissions::$manage_marketing_campaigns,
                                'submenu' => []
                            ],

                            'List' => [
                                'text' => _i('List'),
                                'level_class' => 'second',
                                'route' => 'marketing-campaigns.index',
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$manage_marketing_campaigns,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Messaging' => [
                        'text' => _i('Messaging'),
                        'level_class' => 'top',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-files',
                        'permission' => Permissions::$notifications_menu,
                        'submenu' => [

                            'Upload' => [
                                'text' => _i('New'),
                                'level_class' => 'second',
                                'route' => 'notifications.create',
                                'params' => [],
                                'icon' => 'hs-admin-plus',
                                'permission' => Permissions::$manage_notifications,
                                'submenu' => []
                            ],

                            'List' => [
                                'text' => _i('List'),
                                'level_class' => 'second',
                                'route' => 'notifications.index',
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$manage_notifications,
                                'submenu' => []
                            ],

                            /*'EmailTemplatesTransactions' => [
                                'text' => _i('Email templates'),
                                'level_class' => 'top',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-email',
                                'permission' => Permissions::$email_templates_menu,
                                'submenu' => [

                                    'New' => [
                                        'text' => _i('New'),
                                        'level_class' => 'second',
                                        'route' => 'email-templates.create',
                                        'icon' => 'hs-admin-plus',
                                        'permission' => Permissions::$manage_email_templates,
                                        'submenu' => []
                                    ],

                                    'List' => [
                                        'text' => _i('List'),
                                        'level_class' => 'second',
                                        'route' => 'email-templates.index',
                                        'icon' => 'hs-admin-list',
                                        'permission' => Permissions::$manage_email_templates,
                                        'submenu' => []
                                    ],
                                ]
                            ],*/

                            //                    'Groups' => [
                            //                        'text' => _i('Groups'),
                            //                        'level_class' => 'top',
                            //                        'route' => null,
                            //                        'params' => [],
                            //                        'icon' => 'hs-admin-files',
                            //                        'permission' => Permissions::$manage_notifications,
                            //                        'submenu' => [
                            //
                            //                            'Upload' => [
                            //                                'text' => _i('New'),
                            //                                'level_class' => 'second',
                            //                                'route' => 'notifications.groups.create',
                            //                                'params' => [],
                            //                                'icon' => 'hs-admin-plus',
                            //                                'permission' => Permissions::$manage_notifications_groups,
                            //                                'submenu' => []
                            //                            ],
                            //
                            //                            'List' => [
                            //                                'text' => _i('List'),
                            //                                'level_class' => 'second',
                            //                                'route' => 'notifications.groups.index',
                            //                                'params' => [],
                            //                                'icon' => 'hs-admin-list',
                            //                                'permission' => Permissions::$manage_notifications_groups,
                            //                                'submenu' => []
                            //                            ],
                            //                        ]
                            //                    ],
                        ]
                    ],
                ]
            ],

            /*
            'ProductsLimits' => [
                'text' => _i('Products limits'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-arrows-horizontal',
                'permission' => Permissions::$manage_products_limits,
                'submenu' => [

                    'CenterHorses' => [
                        'text' => _i('CenterHorses'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$manage_products_limits,
                        'submenu' => [

                            'Create' => [
                                'text' => _i('Create'),
                                'level_class' => 'third',
                                'route' => 'providers-limits.create',
                                'params' => [],
                                'icon' => 'hs-admin-plus',
                                'permission' => Permissions::$manage_promotions,
                                'submenu' => [Providers::$center_horses]
                            ],

                            'List' => [
                                'text' => _i('List'),
                                'level_class' => 'third',
                                'route' => 'providers-limits.index',
                                'params' => [Providers::$center_horses],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$manage_products_limits,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'SportBook' => [
                        'text' => _i('SportBook'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$manage_products_limits,
                        'submenu' => [

                            'Create' => [
                                'text' => _i('Create'),
                                'level_class' => 'third',
                                'route' => 'providers-limits.create',
                                'params' => [],
                                'icon' => 'hs-admin-plus',
                                'permission' => Permissions::$manage_promotions,
                                'submenu' => [Providers::$sportbook]
                            ],

                            'List' => [
                                'text' => _i('List'),
                                'level_class' => 'third',
                                'route' => 'providers-limits.index',
                                'params' => [Providers::$sportbook],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$manage_products_limits,
                                'submenu' => []
                            ],
                        ]
                    ],
                ]
            ],
            */

            'Store' => [
                'text' => _i('Store'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-shopping-cart',
                'permission' => Permissions::$store_menu,
                'submenu' => [

                    'Rewards' => [
                        'text' => _i('Rewards'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-gift',
                        'permission' => Permissions::$store_rewards_menu,
                        'submenu' => [

                            'Create' => [
                                'text' => _i('Create'),
                                'level_class' => 'third',
                                'route' => 'store.rewards.create',
                                'params' => [],
                                'icon' => 'hs-admin-plus',
                                'permission' => Permissions::$manage_store_rewards,
                                'submenu' => []
                            ],

                            'List' => [
                                'text' => _i('List'),
                                'level_class' => 'third',
                                'route' => 'store.rewards.index',
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$store_rewards_list,
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Categories' => [
                        'text' => _i('Categories'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-view-list-alt',
                        'permission' => Permissions::$store_categories_menu,
                        'submenu' => [

                            'Create' => [
                                'text' => _i('Manage'),
                                'level_class' => 'third',
                                'route' => 'store.categories.create',
                                'params' => [],
                                'icon' => 'hs-admin-settings',
                                'permission' => Permissions::$manage_rewards_categories,
                                'submenu' => []
                            ],

                        ]
                    ],


                    'Reports' => [
                        'text' => _i('Reports'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-bar-chart',
                        'permission' => Permissions::$reports_store,
                        'submenu' => [

                            'Create' => [
                                'text' => _i('Redeemed rewards'),
                                'level_class' => 'third',
                                'route' => 'store.reports.redeemed-rewards',
                                'params' => [],
                                'icon' => 'hs-admin-exchange-vertical',
                                'permission' => Permissions::$reports_rewards_exchange,
                                'submenu' => []
                            ],

                        ]
                    ],

                    //                    'Actions' => [
                    //                        'text' => _i('Actions'),
                    //                        'level_class' => 'second',
                    //                        'route' => null,
                    //                        'params' => [],
                    //                        'icon' => 'hs-admin-settings',
                    //                        'permission' => Permissions::$store_actions_menu,
                    //                        'submenu' => [
                    //
                    //                            'Create' => [
                    //                                'text' => _i('Create'),
                    //                                'level_class' => 'third',
                    //                                'route' => 'store.actions.create',
                    //                                'params' => [],
                    //                                'icon' => 'hs-admin-plus',
                    //                                'permission' => Permissions::$manage_store_actions,
                    //                                'submenu' => []
                    //                            ],
                    //
                    //                            'List' => [
                    //                                'text' => _i('List'),
                    //                                'level_class' => 'third',
                    //                                'route' => 'store.actions.index',
                    //                                'params' => [],
                    //                                'icon' => 'hs-admin-list',
                    //                                'permission' => Permissions::$store_actions_list,
                    //                                'submenu' => []
                    //                            ],
                    //                        ]
                    //                    ],
                ]
            ],

            'Pages' => [
                'text' => _i('Pages'),
                'level_class' => 'top',
                'route' => 'pages.index',
                'params' => [],
                'icon' => 'hs-admin-files',
                'permission' => Permissions::$pages_menu,
                'submenu' => []
            ],


            /*'EmailContents' => [
                'text' => _i('Email contents'),
                'level_class' => 'top',
                'route' => 'email-configurations.index',
                'params' => [],
                'icon' => 'hs-admin-email',
                'permission' => Permissions::$email_configurations_menu,
                'submenu' => []
            ],*/

            'JustPay' => [
                'text' => _i('ALPS'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-credit-card',
                'permission' => Permissions::$just_pay_admin_menu,
                'submenu' => [

                    'Credit' => [
                        'text' => _i('Credit'),
                        'level_class' => 'second',
                        'route' => 'reports.just-pay.credit',
                        'params' => [],
                        'icon' => 'hs-admin-shift-left',
                        'permission' => Permissions::$just_pay_admin_menu,
                        'submenu' => []
                    ],

                    'Debit' => [
                        'text' => _i('Debit'),
                        'level_class' => 'second',
                        'route' => 'reports.just-pay.debit',
                        'params' => [],
                        'icon' => 'hs-admin-shift-right',
                        'permission' => Permissions::$just_pay_admin_menu,
                        'submenu' => []
                    ],
                ]
            ],

            /*'Zippy' => [
                'text' => _i('Zippy'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-credit-card',
                'permission' => Permissions::$zippy_admin_menu,
                'submenu' => [

                    'Credit' => [
                        'text' => _i('Credit'),
                        'level_class' => 'second',
                        'route' => 'reports.zippy.credit',
                        'params' => [],
                        'icon' => 'hs-admin-shift-left',
                        'permission' => Permissions::$zippy_admin_menu,
                        'submenu' => []
                    ],

                    'Debit' => [
                        'text' => _i('Debit'),
                        'level_class' => 'second',
                        'route' => 'reports.zippy.debit',
                        'params' => [],
                        'icon' => 'hs-admin-shift-right',
                        'permission' => Permissions::$zippy_admin_menu,
                        'submenu' => []
                    ],
                ]
            ],*/

            'Reports' => [
                'text' => _i('Reports'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-bar-chart',
                'permission' => Permissions::$reports_menu,
                'submenu' => [

                    'Slots' => [
                        'text' => _i('Slots'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'Belatra' => [
                                'text' => _i('Belatra'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$belatra,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$belatra],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$belatra],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$belatra],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$belatra],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'BoomingGames' => [
                                'text' => _i('Booming Games'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$booming_games,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$booming_games],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$booming_games],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$booming_games],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$booming_games],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'Booongo' => [
                                'text' => _i('Booongo'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$booongo,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$booongo],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$booongo],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$booongo],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$booongo],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'BooongoOriginal' => [
                                'text' => _i('Booongo Original'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$booongo_original,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$booongo_original],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$booongo_original],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$booongo_original],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$booongo_original],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'Branka' => [
                                'text' => _i('Branka'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$branka,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$branka],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$branka],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$branka],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$branka],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'BrankaOriginals' => [
                                'text' => _i('Branka Originals'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$branka_originals,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$branka_originals],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$branka_originals],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$branka_originals],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$branka_originals],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'CaletaGaming' => [
                                'text' => _i('Caleta Gaming'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$caleta_gaming,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$caleta_gaming],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$caleta_gaming],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$caleta_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$caleta_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'DLV' => [
                                'text' => _i('DLV'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$dlv,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$dlv],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                   //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$dlv],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$dlv],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$dlv],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'EspressoGames' => [
                                'text' => _i('Espresso Games'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$espresso_games,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$espresso_games],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$espresso_games],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$espresso_games],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$espresso_games],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'EvolutionSlots' => [
                                'text' => _i('Evolution Slots'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$evolution_slots,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$evolution_slots],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

//                                    'Games' => [
//                                        'text' => _i('Games totals'),
//                                        'level_class' => 'fourth',
//                                        'route' => 'reports.games-totals',
//                                        'params' => [Providers::$evolution_slots],
//                                        'icon' => 'hs-admin-game',
//                                        'submenu' => []
//                                    ],

//                                    'MostPlayedGames' => [
//                                        'text' => _i('Most played games'),
//                                        'level_class' => 'fourth',
//                                        'route' => 'reports.most-played-games',
//                                        'params' => [Providers::$evolution_slots],
//                                        'icon' => 'hs-admin-stats-up',
//                                        'submenu' => []
//                                    ],

//                                    'GamesPlayedByUser' => [
//                                        'text' => _i('Games played by user'),
//                                        'level_class' => 'fourth',
//                                        'route' => 'reports.games-played-by-user',
//                                        'params' => [Providers::$evolution_slots],
//                                        'icon' => 'hs-admin-stats-up',
//                                        'submenu' => []
//                                    ],
                                ]
                            ],

                            'EvoPlay' => [
                                'text' => _i('EvoPlay'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$evo_play,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$evo_play],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$evo_play],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    /*'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$evo_play],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$evo_play],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],*/
                                ]
                            ],

                            'FBMGaming' => [
                                'text' => _i('FBM Gaming'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$fbm_gaming,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$fbm_gaming],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$fbm_gaming],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    /*'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$fbm_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$fbm_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],*/
                                ]
                            ],

                            'GameArt' => [
                                'text' => _i('GameArt'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$game_art,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$game_art],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$game_art],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$game_art],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$game_art],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'Gamzix' => [
                                'text' => _i('Gamzix'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$gamzix,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$gamzix],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$gamzix],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$gamzix],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$gamzix],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'HasksawGaming' => [
                                'text' => _i('Hasksaw Gaming'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$hacksaw_gaming,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$hacksaw_gaming],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$hacksaw_gaming],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$hacksaw_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$hacksaw_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'ISoftBet' => [
                                'text' => _i('IsoftBet'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$i_soft_bet,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$i_soft_bet],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$i_soft_bet],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    /*'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$i_soft_bet],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$i_soft_bet],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],*/
                                ]
                            ],

                            'KaGaming' => [
                                'text' => _i('Ka Gaming'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$ka_gaming,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$ka_gaming],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$ka_gaming],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$ka_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$ka_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'Kalamba' => [
                                'text' => _i('Kalamba'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$kalamba,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$kalamba],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$kalamba],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    /*'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$kalamba],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$kalamba],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],*/
                                ]
                            ],

                            'KironInteractive' => [
                                'text' => _i('Kiron Interactive'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$kiron_interactive,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$kiron_interactive],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$kiron_interactive],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$kiron_interactive],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$kiron_interactive],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'LegaJackpot' => [
                                'text' => _i('Lega Jackpot'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$lega_jackpot,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$lega_jackpot],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$lega_jackpot],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$lega_jackpot],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$lega_jackpot],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ],
                            ],

                            'MascotGaming' => [
                                'text' => _i('Mascot Gaming'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$mascot_gaming,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$mascot_gaming],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$mascot_gaming],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$mascot_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$mascot_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'OCBSlots' => [
                                'text' => _i('OCB Slots'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$ocb_slots,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$ocb_slots],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$ocb_slots],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$ocb_slots],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$ocb_slots],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'OneTouch' => [
                                'text' => _i('One Touch'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$one_touch,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$one_touch],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$one_touch],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    /*'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$one_touch],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$one_touch],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],*/
                                ]
                            ],

                            'OrtizGaming' => [
                                'text' => _i('Ortiz Gaming'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$ortiz_gaming,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$ortiz_gaming],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$ortiz_gaming],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                   // ],

                                    /*'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$ortiz_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$ortiz_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],*/
                                ]
                            ],

                            'LuckySpins' => [
                                'text' => _i('Lucky Spins'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$lucky_spins,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$lucky_spins],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$lucky_spins],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$lucky_spins],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$lucky_spins],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'Patagonia' => [
                                'text' => _i('Patagonia'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$patagonia,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$patagonia],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$patagonia],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$patagonia],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$patagonia],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'PGSoft' => [
                                'text' => _i('PGSoft'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$pg_soft,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$pg_soft],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$pg_soft],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$pg_soft],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$pg_soft],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'PlatiPus' => [
                                'text' => _i('Plati Pus'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$platipus,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$platipus],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$platipus],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    /*'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$platipus],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$platipus],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],*/
                                ]
                            ],

                            'PlatipusVG' => [
                                'text' => _i('Plati Pus VG'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$platipus_vg,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$platipus_vg],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$platipus_vg],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    /*'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$platipus_vg],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$platipus_vg],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],*/
                                ]
                            ],

                            'PragmaticPlay' => [
                                'text' => _i('Pragmatic Play'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$pragmatic_play,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$pragmatic_play],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$pragmatic_play],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$pragmatic_play],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$pragmatic_play],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'PlaySon' => [
                                'text' => _i('PlaySon'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$play_son,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$play_son],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$play_son],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$play_son],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$play_son],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'MancalaGaming' => [
                                'text' => _i('Mancala Gaming'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$mancala_gaming,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$mancala_gaming],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$mancala_gaming],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$mancala_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$mancala_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'PariPlay' => [
                                'text' => _i('Pari Play'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$pari_play,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$pari_play],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$pari_play],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    /*'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$pari_play],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$pari_play],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],*/
                                ]
                            ],

                            'PlayNGo' => [
                                'text' => _i('Play N Go'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$play_n_go,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$play_n_go],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$play_n_go],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    /*'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$play_n_go],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$play_n_go],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],*/
                                ]
                            ],

                            'RedRake' => [
                                'text' => _i('Red Rake'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$red_rake,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$red_rake],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$red_rake],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$red_rake],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$red_rake],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'SalsaGaming' => [
                                'text' => _i('Salsa gaming'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$salsa_gaming,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$salsa_gaming],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$salsa_gaming],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    /*'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$salsa_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$salsa_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],*/
                                ]
                            ],

                            'Spinmatic' => [
                                'text' => _i('Spinmatic'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$spinmatic,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$spinmatic],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$spinmatic],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$spinmatic],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$spinmatic],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'TripleCherry' => [
                                'text' => _i('Triple Cherry'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$triple_cherry,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$triple_cherry],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$triple_cherry],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$triple_cherry],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$triple_cherry],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'TripleCherryOriginal' => [
                                'text' => _i('Triple Cherry Original'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$triple_cherry_original,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$triple_cherry_original],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$triple_cherry_original],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$triple_cherry_original],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$triple_cherry_original],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'UrgentGames' => [
                                'text' => _i('Urgent games'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$urgent_games,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$urgent_games],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$urgent_games],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$urgent_games],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$urgent_games],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'Vibra' => [
                                'text' => _i('Vibra'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$vibra,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$vibra],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$vibra],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    /*'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$vibra],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$vibra],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],*/
                                ]
                            ],

                            'Wazdan' => [
                                'text' => _i('Wazdan'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$wazdan,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$wazdan],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$wazdan],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$wazdan],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$wazdan],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'WNetGames' => [
                                'text' => _i('WNet Games'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$wnet_games,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$wnet_games],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$wnet_games],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$wnet_games],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$wnet_games],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'PariPlay' => [
                                'text' => _i('PariPlay'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$pari_play,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$pari_play],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    'Games' => [
                                        'text' => _i('Games totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-totals',
                                        'params' => [Providers::$pari_play],
                                        'icon' => 'hs-admin-game',
                                        'submenu' => []
                                    ],

                                    /*'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$salsa_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],

                                    'GamesPlayedByUser' => [
                                        'text' => _i('Games played by user'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.games-played-by-user',
                                        'params' => [Providers::$salsa_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],*/
                                ]
                            ],
                        ]
                    ],

                    'LiveCasino' => [
                        'text' => _i('Live casino'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'Ezugi' => [
                                'text' => _i('Ezugi'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$ezugi,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$ezugi],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'Evolution' => [
                                'text' => _i('Evolution'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$evolution,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$evolution],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'VivoGaming' => [
                                'text' => _i('VivoGaming'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$vivo_gaming,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$vivo_gaming],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$vivo_gaming],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$vivo_gaming],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'TVBet' => [
                                'text' => _i('TV Bet'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$tv_bet,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$tv_bet],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'PragmaticPlayLiveCasino' => [
                                'text' => _i('Pragmatic Play'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$pragmatic_play_live_casino,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$pragmatic_play_live_casino],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$pragmatic_play_live_casino],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$pragmatic_play_live_casino],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],
                        ]
                    ],

                    'SportBooks' => [
                        'text' => _i('SportBook'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'Alternar' => [
                                'text' => _i('Alternar'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$altenar,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$altenar],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'IQSoftReports' => [
                                'text' => _i('IQSoft'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$iq_soft,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$iq_soft],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    'Tickets' => [
                                        'text' => _i('Tickets'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.iq-soft.tickets',
                                        'params' => [],
                                        'icon' => 'hs-admin-ticket',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'LivePlayer' => [
                                'text' => _i('Live Player'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$live_player,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$live_player],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'SportBook' => [
                                'text' => _i('SportBook'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$sportbook,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$sportbook],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'SW3' => [
                                'text' => _i('SW3'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$sw3,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$sw3],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'vgcsports' => [
                                'text' => _i('Vgcsports'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$veneto_sportbook,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$veneto_sportbook],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],
                                ]
                            ],
                        ]
                    ],

                    'HorseRaces' => [
                        'text' => _i('Horses'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'ElInmejorable' => [
                                'text' => _i('El Inmejorable'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$inmejorable,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$inmejorable],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'CenterHorses' => [
                                'text' => _i('RaceBook'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$center_horses,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$center_horses],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'UniversalSoft' => [
                                'text' => _i('UniversalSoft'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$universal_soft,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$universal_soft],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],
                                ]
                            ],
                        ]
                    ],

                    'Virtual' => [
                        'text' => _i('Virtual'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'GoldenRace' => [
                                'text' => _i('Golden Race'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$golden_race,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$golden_race],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$golden_race],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$golden_race],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'VirtualGeneration' => [
                                'text' => _i('Virtual Generation'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$virtual_generation,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$virtual_generation],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$virtual_generation],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$virtual_generation],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],

                            'Sisvenprol' => [
                                'text' => _i('Sisvenprol'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$sisvenprol,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$sisvenprol],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],

                                    //'Games' => [
                                    //    'text' => _i('Games totals'),
                                    //    'level_class' => 'fourth',
                                    //    'route' => 'reports.games-totals',
                                    //    'params' => [Providers::$sisvenprol],
                                    //    'icon' => 'hs-admin-game',
                                    //    'submenu' => []
                                    //],

                                    'MostPlayedGames' => [
                                        'text' => _i('Most played games'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.most-played-games',
                                        'params' => [Providers::$sisvenprol],
                                        'icon' => 'hs-admin-stats-up',
                                        'submenu' => []
                                    ],
                                ]
                            ],
                        ]
                    ],

                    'Poker' => [
                        'text' => _i('Poker'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'EventBet' => [
                                'text' => _i('EventBet'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$event_bet,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$event_bet],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],
                                ]
                            ],
                        ]
                    ],

                    'DotSuite' => [
                        'text' => _i('DotSuite'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$menu_dotsuite,
                        'submenu' => [

                            'Users' => [
                                'text' => _i('Users totals'),
                                'level_class' => 'third',
                                'route' => 'reports.dotsuite.users-totals',
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'submenu' => [ ]
                            ],
                        ]
                    ],

                    'LiveGames' => [
                        'text' => _i('Live games'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'SBLGames' => [
                                'text' => _i('SBL Games'),
                                'level_class' => 'third',
                                'route' => null,
                                'params' => [],
                                'icon' => 'hs-admin-control-shuffle',
                                'permission' => Permissions::$products_reports_menu,
                                'provider' => Providers::$dot_live,
                                'submenu' => [

                                    'Users' => [
                                        'text' => _i('Users totals'),
                                        'level_class' => 'fourth',
                                        'route' => 'reports.users-totals',
                                        'params' => [Providers::$dot_live],
                                        'icon' => 'hs-admin-user',
                                        'submenu' => []
                                    ],
                                ]
                            ],
                        ]
                    ],

                    /*
                    'HourClosures' => [
                        'text' => _i('Hour closures'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'Users' => [
                                'text' => _i('Profit'),
                                'level_class' => 'third',
                                'route' => 'reports.hour-closure.profit',
                                'params' => [],
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],
                        ]
                    ]
                    */
                ]
            ],

            'IQSoftTotals' => [
                'text' => _i('IQ Soft Totals'),
                'level_class' => 'top',
                'route' => 'iq-soft.totals',
                'params' => [],
                'icon' => 'hs-admin-briefcase',
                'permission' => Permissions::$iq_soft_totals,
                'submenu' => []
            ],

            'ManageWhitelabels' => [
                'text' => _i('Manage whitelabels'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-settings',
                'permission' => Permissions::$manage_whitelabels_status_menu,
                'submenu' => [

                    'ManageWhitelabelsStatus' => [
                        'text' => _i('Whitelabels status'),
                        'level_class' => 'second',
                        'route' => 'whitelabels.status',
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'permission' => Permissions::$manage_whitelabels_status,
                        'submenu' => []
                    ],

                    'WhitelabelsActiveProviders' => [
                        'text' => _i('Active providers'),
                        'level_class' => 'second',
                        'route' => 'reports.whitelabels-active-providers',
                        'params' => [],
                        'icon' => 'hs-admin-stats-up',
                        'permission' => Permissions::$whitelabels_active_providers,
                        'submenu' => []
                    ],

                ]
            ],

            'BetPayClients' => [
                'text' => _i('BetPay Clients'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-settings',
                'permission' => Permissions::$manage_betpay_menu,
                'submenu' => [

                    'ClientBetPay' => [
                        'text' => _i('Create'),
                        'level_class' => 'second',
                        'route' => 'betpay.clients.create',
                        'params' => [],
                        'icon' => 'hs-admin-user',
                        'submenu' => []
                    ],

                    'ListBetPay' => [
                        'text' => _i('List Clients'),
                        'level_class' => 'second',
                        'route' => 'betpay.clients.index',
                        'params' => [],
                        'icon' => 'hs-admin-list',
                        'submenu' => []
                    ],
                    /*
                    'Abitab' => [
                        'text' => _i('Abitab'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$abitab,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$abitab,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limitss.create',
                                'payment_method' => PaymentMethods::$abitab,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$abitab,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$abitab,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$abitab,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$abitab,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],


                        ]
                    ],

                    'AirTM' => [
                        'text' => _i('AirTM'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$airtm,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$airtm,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$airtm,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$airtm,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$airtm,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$airtm,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$airtm,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Cryptocurrencies' => [
                        'text' => _i('Cryptocurrencies'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$cryptocurrencies,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$cryptocurrencies,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$cryptocurrencies,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$cryptocurrencies,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$cryptocurrencies,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$cryptocurrencies,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$cryptocurrencies,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],


                        ]
                    ],

                    'JustPay' => [
                        'text' => _i('JustPay'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$just_pay,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$just_pay,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$just_pay,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$just_pay,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$just_pay,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$just_pay,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$just_pay,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],


                        ]
                    ],

                    'MobilePayment' => [
                        'text' => _i('Mobile payment'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$mobile_payment,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$mobile_payment,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$mobile_payment,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$mobile_payment,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$mobile_payment,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$mobile_payment,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$mobile_payment,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],


                        ]
                    ],

                    'Neteller' => [
                        'text' => _i('Neteller'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$neteller,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$neteller,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$neteller,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$neteller,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$neteller,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$neteller,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$neteller,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                        ]
                    ],

                    'Paypal' => [
                        'text' => _i('PayPal'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$paypal,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$paypal,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$paypal,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$paypal,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$paypal,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$paypal,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$paypal,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],
                        ]
                    ],

                    'PayForFun' => [
                        'text' => _i('Pay For Fun'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$pay_for_fun,
                        'submenu' => [


                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$pay_for_fun,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$pay_for_fun,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$pay_for_fun,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$pay_for_fun,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$pay_for_fun,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$pay_for_fun,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],
                        ]
                    ],

                    'RedPagos' => [
                        'text' => _i('RedPagos'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$red_pagos,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$red_pagos,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$red_pagos,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$red_pagos,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$red_pagos,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$red_pagos,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$red_pagos,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Skrill' => [
                        'text' => _i('Skrill'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$skrill,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$skrill,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$skrill,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$skrill,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$skrill,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$skrill,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$skrill,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                        ]
                    ],

                    'Uphold' => [
                        'text' => _i('Uphold'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$uphold,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$uphold,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$uphold,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$uphold,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$uphold,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$uphold,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$uphold,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                        ]
                    ],

                    'VCreditos' => [
                        'text' => _i('VCreditos'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$vcreditos,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$vcreditos,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$vcreditos,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$vcreditos,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$vcreditos,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$vcreditos,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limitss',
                                'params' => [],
                                'payment_method' => PaymentMethods::$vcreditos,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],
                        ]
                    ],

                    'VesToUsd' => [
                        'text' => _i('VES to USD'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$ves_to_usd,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$ves_to_usd,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$ves_to_usd,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$ves_to_usd,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$ves_to_usd,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$ves_to_usd,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$ves_to_usd,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],
                        ]
                    ],

                    'WireTransfers' => [
                        'text' => _i('Wire transfers'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$wire_transfers,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$wire_transfers,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$wire_transfers,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$wire_transfers,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$wire_transfers,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$wire_transfers,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$wire_transfers,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Zelle' => [
                        'text' => _i('Zelle'),
                        'level_class' => 'second',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'payment_method' => PaymentMethods::$zelle,
                        'submenu' => [

                            'CreateAccounts' => [
                                'text' => _i('Create Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.create',
                                'params' => [],
                                'payment_method' => PaymentMethods::$zelle,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'CreatePaymentBetPay' => [
                                'text' => _i('Create Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.create',
                                'payment_method' => PaymentMethods::$zelle,
                                'params' => [],
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'EditAccounts' => [
                                'text' => _i('Edit Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$zelle,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'EditPaymentLimits' => [
                                'text' => _i('Edit Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits.edit',
                                'params' => [],
                                'payment_method' => PaymentMethods::$zelle,
                                'icon' => 'hs-admin-user',
                                'submenu' => []
                            ],

                            'ListAccounts' => [
                                'text' => _i('List Accounts'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts',
                                'params' => [],
                                'payment_method' => PaymentMethods::$zelle,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],

                            'ListPaymentLimits' => [
                                'text' => _i('List Payment Limits'),
                                'level_class' => 'third',
                                'route' => 'betpay.clients.accounts.payment-limits',
                                'params' => [],
                                'payment_method' => PaymentMethods::$zelle,
                                'icon' => 'hs-admin-list',
                                'submenu' => []
                            ],
                        ]
                    ],*/
                ]
            ],

            /*'ManageLandingPages' => [
                'text' => _i('Manage Landing Pages'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-settings',
                'submenu' => [

                    'CreateLandingPages' => [
                        'text' => _i('Create'),
                        'level_class' => 'second',
                        'route' => 'landing-pages.create',
                        'params' => [],
                        'icon' => 'hs-admin-list',
                        'submenu' => []
                    ],
 /*
                                'GamesSection' => [
                                    'text' => _i('Games'),
                                    'level_class' => 'top',
                                    'route' => null,
                                    'params' => [],
                                    'icon' => 'hs-admin-gallery',
                                    'permission' => Permissions::$section_games_menu,
                                    'submenu' => [

                                        'Lobbygames' => [
                                            'text' => _i('Lobby Games'),
                                            'level_class' => 'top',
                                            'route' => null,
                                            'params' => [],
                                            'icon' => 'hs-admin-settings',
                                            'permission' => Permissions::$manage_lobby_games_menu,
                                            'submenu' => [

                                                'ClientBetPay' => [
                                                    'text' => _i('Create'),
                                                    'level_class' => 'second',
                                                    'route' => 'lobby-games.index',
                                                    'params' => [],
                                                    'icon' => 'hs-admin-user',
                                                    'submenu' => []
                                                ],

                                            ]
                                        ],

                                    ]
                                ],
*/


            /*     'ListLandingPages' => [
                                            'text' => _i('List'),
                                            'level_class' => 'second',
                                            'route' => 'landing-pages.index',
                                            'params' => [],
                                            'icon' => 'hs-admin-list',
                                            'submenu' => []
                                        ],*/

            /*+
                ]
            ],
*/

            /* 'Configurations' => [
                'text' => _i('Configurations'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-settings',
                'permission' => Permissions::$configurations_menu,
                'submenu' => [
                    'ListLandingPages' => [
                         'text' => _i('List'),
                         'level_class' => 'second',
                          'route' => 'landing-pages.index',
                          'params' => [],
                          'icon' => 'hs-admin-list',
                          'submenu' => []
                     ],
                  ]
            ],*/

            'Credentials' => [
                'text' => _i('Credentials'),
                'level_class' => 'second',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-settings',
                'permission' => Permissions::$credentials_menu,
                'submenu' => [

                    'Slots' => [
                        'text' => _i('Slots'),
                        'level_class' => 'third',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'Belatra' => [
                                'text' => _i('Belatra'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$belatra],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'BoomingGames' => [
                                'text' => _i('Booming Games'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$booming_games],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Booongo' => [
                                'text' => _i('Booongo'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$booongo],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'BooongoOriginal' => [
                                'text' => _i('Booongo Original'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$booongo_original],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Branka' => [
                                'text' => _i('Branka'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$branka],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []

                            ],

                            'BrankaOriginals' => [
                                'text' => _i('Branka Originals'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$branka_originals],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'CaletaGaming' => [
                                'text' => _i('Caleta Gaming'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$caleta_gaming],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'DLV' => [
                                'text' => _i('DLV'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$dlv],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'EspressoGames' => [
                                'text' => _i('Espresso Games'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$espresso_games],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'EvolutionSlots' => [
                                'text' => _i('Evolution Slots'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$evolution_slots],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Evoplay' => [
                                'text' => _i('Evoplay'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$evo_play],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'FBMGaming' => [
                                'text' => _i('FBM Gaming'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$fbm_gaming],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'GameArt' => [
                                'text' => _i('GameArt'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$game_art],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Gamzix' => [
                                'text' => _i('Gamzix'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$gamzix],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'GreenTube' => [
                                'text' => _i('GreenTube'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$greentube],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'HasksawGaming' => [
                                'text' => _i('Hasksaw Gaming'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$hacksaw_gaming],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'ISoftBet' => [
                                'text' => _i('ISoftBet'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$i_soft_bet],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'KaGaming' => [
                                'text' => _i('Ka Gaming'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$ka_gaming],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Kalamba' => [
                                'text' => _i('Kalamba'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$kalamba],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'KironInteractive' => [
                                'text' => _i('Kiron Interactive'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$kiron_interactive],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'LegaJackpot' => [
                                'text' => _i('Lega Jackpot'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$lega_jackpot],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Mancala' => [
                                'text' => _i('Mancala'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$mancala_gaming],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []

                            ],

                            'MascotGaming' => [
                                'text' => _i('Mascot Gaming'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$mascot_gaming],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []

                            ],

                            'OCBSlots' => [
                                'text' => _i('OCB Slots'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$ocb_slots],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'OneTouch' => [
                                'text' => _i('One Touch'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$one_touch],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []

                            ],

                            'Patagonia' => [
                                'text' => _i('Patagonia'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$patagonia],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Platipus' => [
                                'text' => _i('Platipus'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$platipus],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Playson' => [
                                'text' => _i('Playson'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$play_son],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'LuckySpins' => [
                                'text' => _i('Lucky Spins'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$lucky_spins],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Ortiz' => [
                                'text' => _i('Ortiz'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$ortiz_gaming],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'PGSoft' => [
                                'text' => _i('PGSoft'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$pg_soft],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'PragmaticPlay' => [
                                'text' => _i('Pragmatic Play'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$pragmatic_play],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'RedRake' => [
                                'text' => _i('Red Rake'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$red_rake],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'SalsaGaming' => [
                                'text' => _i('Salsa Gaming'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$salsa_gaming],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Spinmatic' => [
                                'text' => _i('Spinmatic'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$spinmatic],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'TripleCherry' => [
                                'text' => _i('Triple Cherry'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$triple_cherry],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'TripleCherryOriginal' => [
                                'text' => _i('Triple Cherry Original'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$triple_cherry_original],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'UrgentGames' => [
                                'text' => _i('Urgent Games'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$urgent_games],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Vibra' => [
                                'text' => _i('Vibra'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$vibra],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Wazdan' => [
                                'text' => _i('Wazdan'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$wazdan],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'WNetGames' => [
                                'text' => _i('WNet Games'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$wnet_games],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],
                        ]
                    ],

                    'LiveCasinos' => [
                        'text' => _i('Live Casino'),
                        'level_class' => 'third',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'ColorSpin' => [
                                'text' => _i('Color Spin'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$color_spin],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Evolution' => [
                                'text' => _i('Evolution'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$evolution],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Ezugi' => [
                                'text' => _i('Ezugi'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$ezugi],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'PragmaticPlayLiveCasino' => [
                                'text' => _i('Pragmatic Play'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$pragmatic_play_live_casino],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'TVBet' => [
                                'text' => _i('TV Bet'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$tv_bet],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'VivoGaming' => [
                                'text' => _i('VivoGaming'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$vivo_gaming],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'VLS' => [
                                'text' => _i('VLS'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$vls],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],


                        ]
                    ],

                    'SportBooks' => [
                        'text' => _i('SportBooks'),
                        'level_class' => 'third',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'Altenar' => [
                                'text' => _i('Altenar'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$altenar],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'AndesSportbook' => [
                                'text' => _i('Andes SportBook'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$andes_sportbook],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'IQSoftReports' => [
                                'text' => _i('IQSoft'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$iq_soft],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'LivePlayer' => [
                                'text' => _i('Live Player'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$live_player],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'SportBook' => [
                                'text' => _i('SportBook'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$sportbook],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'SW3' => [
                                'text' => _i('SW3'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$sw3],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'vgcsports' => [
                                'text' => _i('Vgcsports'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$veneto_sportbook],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Digitain' => [
                                'text' => _i('Digitain'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$digitain],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

//                            'Beter' => [
//                                'text' => _i('Beter'),
//                                'level_class' => 'fourth',
//                                'route' => 'configurations.credentials',
//                                'params' => [Providers::$beter],
//                                'icon' => 'hs-admin-settings',
//                                'submenu' => []
//                            ],
                        ]
                    ],

                    'HorseRace' => [
                        'text' => _i('Horses'),
                        'level_class' => 'third',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'CenterHorses' => [
                                'text' => _i('Center Horses'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$center_horses],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'ElInmejorable' => [
                                'text' => _i('El Inmejorable'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$inmejorable],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Universalsoft' => [
                                'text' => _i('Universal Soft'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$universal_soft],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Virtual' => [
                        'text' => _i('Virtual'),
                        'level_class' => 'third',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'GoldenRace' => [
                                'text' => _i('Golden Race'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$golden_race],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Mohio' => [
                                'text' => _i('Mohio'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$mohio],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'Sisven' => [
                                'text' => _i('Sisvenprol'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$sisvenprol],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],

                            'VirtualGeneration' => [
                                'text' => _i('Virtual Generation'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$virtual_generation],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Poker' => [
                        'text' => _i('Poker'),
                        'level_class' => 'third',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'EventBet' => [
                                'text' => _i('EventBet'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$event_bet],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Payments' => [
                        'text' => _i('Payments'),
                        'level_class' => 'third',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'BetPay' => [
                                'text' => _i('BetPay'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$betpay],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],
                        ]
                    ],

                    'Services' => [
                        'text' => _i('Services'),
                        'level_class' => 'third',
                        'route' => null,
                        'params' => [],
                        'icon' => 'hs-admin-control-shuffle',
                        'submenu' => [

                            'Telegram' => [
                                'text' => _i('Telegram'),
                                'level_class' => 'fourth',
                                'route' => 'configurations.credentials',
                                'params' => [Providers::$telegram],
                                'icon' => 'hs-admin-settings',
                                'submenu' => []
                            ],
                        ]
                    ],


                ]
            ],

            'DotSuiteCredentials' => [
                'text' => _i('DotSuite credentials'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-settings',
                'permission' => Permissions::$dot_suite_credentials_menu,
                'submenu' => [

                    'Create' => [
                        'text' => _i('New'),
                        'level_class' => 'second',
                        'route' => 'dot-suite.credentials.create',
                        'params' => [],
                        'icon' => 'hs-admin-plus',
                        'permission' => Permissions::$dot_suite_credentials_create,
                        'submenu' => [],
                    ],

                    'List' => [
                        'text' => _i('List'),
                        'level_class' => 'second',
                        'route' => 'dot-suite.credentials.index',
                        'params' => [],
                        'icon' => 'hs-admin-list',
                        'permission' => Permissions::$dot_suite_credentials_create,
                        'submenu' => [],
                    ],
                ],
            ],

            'ExchangeRates' => [
                'text' => _i('Exchange rates'),
                'level_class' => 'second',
                'route' => 'core.exchange-rates',
                'params' => [],
                'icon' => 'hs-admin-arrows-horizontal',
                'permission' => Permissions::$exchange_rates,
                'submenu' => []
            ],

            /*
                                       'Access' => [
                                            'text' => _i('Access'),
                                            'level_class' => '',
                                            'route' => null,
                                            'params' => [],
                                            'icon' => 'hs-admin-support',
                                            'submenu' => [

                                                'RegistrationLogin' => [
                                                    'text' => _i('Login and Register'),
                                                    'level_class' => '',
                                                    'route' => 'configurations.registration-login.index',
                                                    'params' => [],
                                                    'icon' => 'hs-admin-user',
                                                    'submenu' => []
                                                ],

                                                'Levels' => [
                                                    'text' => _i('Levels'),
                                                    'level_class' => '',
                                                    'route' => 'configurations.levels.index',
                                                    'params' => [],
                                                    'icon' => 'hs-admin-list-ol',
                                                    'submenu' => []
                                                ],

                                                'MainRoute' => [
                                                    'text' => _i('Main Route'),
                                                    'level_class' => '',
                                                    'route' => 'configurations.main-route.index',
                                                    'params' => [],
                                                    'icon' => 'hs-admin-desktop',
                                                    'submenu' => []
                                                ],
                                            ]
                                        ],

                                        'Design' => [
                                            'text' => _i('Design'),
                                            'level_class' => '',
                                            'route' => null,
                                            'params' => [],
                                            'icon' => 'hs-admin-brush',
                                            'submenu' => [

                                                'Template' => [
                                                    'text' => _i('Template'),
                                                    'level_class' => '',
                                                    'route' => 'configurations.template.index',
                                                    'params' => [],
                                                    'icon' => 'hs-admin-bookmark-alt',
                                                    'submenu' => []
                                                ],
                                            ]
                                        ],*/

            //  'Security' => [
            //     'text' => _i('Security'),
            //     'level_class' => 'second',
            //     'route' => null,
            //     'params' => [],
            //     'icon' => 'hs-admin-layout-list-thumb',
            //     'submenu' => [
            //         'RolesPermissions' => [
            //             'text' => _i('Manage role permissions'),
            //             'level_class' => 'third',
            //             'route' => null,
            //             'params' => [],
            //             'icon' => 'hs-admin-control-shuffle',
            //             'submenu' => [
            //                 'AddRolesPermissions' => [
            //                     'text' => _i('Add'),
            //                     'level_class' => 'third',
            //                     'route' => 'security.manage-role-permissions',
            //                     'params' => [],
            //                     'icon' => 'hs-admin-control-shuffle',
            //                     'submenu' => []
            //                 ],
            //                 'ExcludeRolesPermissions' => [
            //                     'text' => _i('Exclude role permissions'),
            //                     'level_class' => 'third',
            //                     'route' => 'security.exclude-role-permissions',
            //                     'params' => [],
            //                     'icon' => 'hs-admin-control-shuffle',
            //                     'submenu' => []
            //                 ],
            //             ]
            //         ],
            //         'Roles' => [
            //             'text' => _i('Roles'),
            //             'level_class' => 'third',
            //             'route' => null,
            //             'params' => [],
            //             'icon' => 'hs-admin-control-shuffle',
            //             'submenu' => [
            //                 'AddRoles' => [
            //                     'text' => _i('Add'),
            //                     'level_class' => 'third',
            //                     'route' => 'security.role-users',
            //                     'params' => [],
            //                     'icon' => 'hs-admin-control-shuffle',
            //                     'submenu' => []
            //                 ],
            //                 'ExcludeRoleUser' => [
            //                     'text' => _i('Exclude users roles'),
            //                     'level_class' => 'third',
            //                     'route' => 'security.exclude-roles-users',
            //                     'params' => [],
            //                     'icon' => 'hs-admin-control-shuffle',
            //                     'submenu' => []
            //                 ],
            //             ]
            //         ],
            //         'Permissions' => [
            //             'text' => _i('Permissions'),
            //             'level_class' => 'third',
            //             'route' => 'security.manage-permissions-view',
            //             'params' => [],
            //             'icon' => 'hs-admin-control-shuffle',
            //             'submenu' => [
            //                 'Permissions' => [
            //                     'text' => _i('Add'),
            //                     'level_class' => 'third',
            //                     'route' => 'security.manage-permissions-view',
            //                     'params' => [],
            //                     'icon' => 'hs-admin-control-shuffle',
            //                     'submenu' => []
            //                 ],
            //                 'ExcludePermissionsUser' => [
            //                     'text' => _i('Exclude users permissions'),
            //                     'level_class' => 'third',
            //                     'route' => 'security.exclude-permissions-users',
            //                     'params' => [],
            //                     'icon' => 'hs-admin-control-shuffle',
            //                     'submenu' => []
            //                 ],
            //             ]
            //         ],
            //     ]
            // ],
/*
            'DotSuitev2' => [
                'text' => _i('DotSuite'),
                'level_class' => 'top',
                'route' => null,
                'params' => [],
                'icon' => 'hs-admin-user',
                'submenu' => [
                    'CreateLobby' => [
                        'text' => _i('Create Lobby Dotsuite'),
                        'level_class' => 'second',
                        'route' => 'dot-suite.lobby-games.create',
                        'params' => [],
                        'icon' => 'hs-admin-plus',
                        'submenu' => []
                    ]
                ],
            ],
*/


            'ProvidersList' => [
                'text' => _i('Providers list'),
                'level_class' => 'second',
                'route' => 'configurations.providers.index',
                'params' => [],
                'icon' => 'hs-admin-layout-list-thumb',
                'permission' => Permissions::$manage_providers,
                'submenu' => []
            ],

            'MainAgents' => [
                'text' => _i('Main agents'),
                'level_class' => 'second',
                'route' => 'agents.main-agents',
                'params' => [],
                'icon' => 'hs-admin-briefcase',
                'permission' => Permissions::$manage_main_agents,
                'submenu' => []
            ],

            'MainUsers' => [
                'text' => _i('Main users'),
                'level_class' => 'second',
                'route' => 'users.main-users',
                'params' => [],
                'icon' => 'hs-admin-user',
                'permission' => Permissions::$manage_main_users,
                'submenu' => []
            ],

            'DotworkersManual' => [
                'text' => _i('Manual'),
                'level_class' => 'top',
                'url' => 'https://drive.google.com/file/d/1uiGiRBKbAgiCaYM09bd1M7QR6uqr0wla/view?usp=sharing',
                'params' => [],
                'icon' => 'hs-admin-briefcase',
                'permission' => Permissions::$dotpanel_dotworkers_manual,
                'submenu' => []
            ],
        ];

        return json_decode(json_encode($menu));
    }
}
