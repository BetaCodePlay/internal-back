<?php

namespace Dotworkers\Security\Enums;

/**
 * Class Permissions
 *
 * This class allows to define static permissions
 *
 * @package Dotworkers\Security\Enums
 * @author  Eborio Linarez
 */
class Permissions
{
    /**
     * Whitelabel login
     *
     * @var int
     */
    public static $whitelabel_login = 1;

    /**
     * Dotpanel login
     *
     * @var int
     */
    public static $dotpanel_login = 2;

    /**
     * Users menu
     *
     * @var int
     */
    public static $users_menu = 3;

    /**
     * Advanced users search
     *
     * @var int
     */
    public static $advanced_users_search = 4;

    /**
     * Create users
     *
     * @var int
     */
    public static $create_users = 5;

    /**
     * Agents menu
     *
     * @var int
     */
    public static $agents_menu = 6;

    /**
     * Agents dashboard
     *
     * @var int
     */
    public static $agents_dashboard = 7;

    /**
     * Agents reports menu
     *
     * @var int
     */
    public static $agents_reports_menu = 8;

    /**
     * BetPay menu
     *
     * @var int
     */
    public static $betpay_menu = 9;

    /**
     * Wire transfers menu
     *
     * @var int
     */
    public static $wire_transfers_menu = 10;

    /**
     * Credit wire transfers
     *
     * @var int
     */
    public static $credit_wire_transfers_menu = 11;

    /**
     * Debit wire transfers menu
     *
     * @var int
     */
    public static $debit_wire_transfers_menu = 12;

    /**
     * Process credit
     *
     * @var int
     */
    public static $process_credit = 13;

    /**
     * Process debit
     *
     * @var int
     */
    public static $process_debit = 14;

    /**
     * Sliders menu
     *
     * @var int
     */
    public static $sliders_menu = 15;

    /**
     * Manage sliders
     *
     * @var int
     */
    public static $manage_sliders = 16;

    /**
     * Sliders list
     *
     * @var int
     */
    public static $sliders_list = 17;

    /**
     * Section images menu
     *
     * @var int
     */
    public static $section_images_menu = 18;

    /**
     * Manage section images
     *
     * @var int
     */
    public static $manage_section_images = 19;

    /**
     * Section images list
     *
     * @var int
     */
    public static $section_images_list = 20;

    /**
     * Promotions menu
     *
     * @var int
     */
    public static $promotions_menu = 21;

    /**
     * Manage promotions
     *
     * @var int
     */
    public static $manage_promotions = 22;

    /**
     * Promotions list
     *
     * @var int
     */
    public static $promotions_list = 23;

    /**
     * Pages menu
     *
     * @var int
     */
    public static $pages_menu = 24;

    /**
     * Manage pages
     *
     * @var int
     */
    public static $manage_pages = 25;

    /**
     * Pages list
     *
     * @var int
     */
    public static $pages_list = 26;

    /**
     * Reports menu
     *
     * @var int
     */
    public static $reports_menu = 27;

    /**
     * Products menu
     *
     * @var int
     */
    public static $operations_menu = 28;

    /**
     * Financial reports menu
     *
     * @var int
     */
    public static $financial_reports_menu = 29;

    /**
     * Products reports menu
     *
     * @var int
     */
    public static $products_reports_menu = 30;

    /**
     * Update users data
     *
     * @var int
     */
    public static $update_users_data = 31;

    /**
     * Update users status
     *
     * @var int
     */
    public static $update_users_status = 32;

    /**
     * Reset users password
     *
     * @var int
     */
    public static $reset_users_password = 33;

    /**
     * Manual transactions
     *
     * @var int
     */
    public static $manual_transactions = 34;

    /**
     * Bonus transactions
     *
     * @var int
     */
    public static $bonus_transactions = 35;

    /**
     * Zelle menu
     *
     * @var int
     */
    public static $zelle_menu = 36;

    /**
     * Users search
     *
     * @var int
     */
    public static $users_search = 39;

    /**
     * Agents financial report
     *
     * @var int
     */
    public static $agents_financial_report = 40;

    /**
     * Dashboard
     *
     * @var int
     */
    public static $dashboard = 41;

    /**
     * Manual adjustments
     *
     * @var int
     */
    public static $manual_adjustments = 42;

    /**
     * Products totals
     *
     * @var int
     */
    public static $products_totals = 43;

    /**
     * Users balances
     *
     * @var int
     */
    public static $users_balances = 44;

    /**
     * Users conversion
     *
     * @var int
     */
    public static $users_conversion = 45;

    /**
     * Web registers
     *
     * @var int
     */
    public static $web_registers = 46;

    /**
     * Dotpanel registers
     *
     * @var int
     */
    public static $dotpanel_registers = 47;

    /**
     * Users logins
     *
     * @var int
     */
    public static $users_logins = 48;

    /**
     * Show Wallet id
     *
     * @var int
     */
    public static $show_wallet_id = 49;

    /**
     * Agents transactions
     *
     * @var int
     */
    public static $agents_transactions = 50;

    /**
     * Credit Zelle menu
     *
     * @var int
     */
    public static $credit_zelle_menu = 51;

    /**
     * Mobile payment menu
     *
     * @var int
     */
    public static $mobile_payment_menu = 52;

    /**
     * Mobile payment credit menu
     *
     * @var int
     */
    public static $mobile_payment_credit_menu = 53;

    /**
     * JustPay menu
     *
     * @var int
     */
    public static $just_pay_menu = 55;

    /**
     * JustPay debit menu
     *
     * @var int
     */
    public static $just_pay_debit_menu = 56;

    /**
     * Tawk chat
     *
     * @var int
     */
    public static $tawk_chat = 58;

    /**
     * Cash flow
     *
     * @var int
     */
    public static $agents_cash_flow = 59;

    /**
     * Paypal menu
     *
     * @var int
     */
    public static $paypal_menu = 60;

    /**
     * Credit paypal menu
     *
     * @var int
     */
    public static $credit_paypal_menu = 61;

    /**
     * Debit paypal menu
     *
     * @var int
     */
    public static $debit_paypal_menu= 62;

    /**
     * Skrill menu
     *
     * @var int
     */
    public static $skrill_menu = 65;

    /**
     * Credit skrill menu
     *
     * @var int
     */
    public static $credit_skrill_menu = 66;

    /**
     * Debit skrill menu
     *
     * @var int
     */
    public static $debit_skrill_menu= 67;

    /**
     * Neteller menu
     *
     * @var int
     */
    public static $neteller_menu = 70;

    /**
     * Credit neteller menu
     *
     * @var int
     */
    public static $credit_neteller_menu = 71;

    /**
     * Debit neteller menu
     *
     * @var int
     */
    public static $debit_neteller_menu= 72;

    /**
     * Add agent users
     *
     * @var int
     */
    public static $add_agent_users = 75;

    /**
     * Temp users
     *
     * @var int
     */
    public static $temp_users = 76;

    /**
     * debit zelle menu
     *
     * @var int
     */
    public static $debit_zelle_menu  = 77;

    /**
     * Airtm menu
     *
     * @var int
     */
    public static $airtm_menu = 78;

    /**
     * Credit Airtm menu
     *
     * @var int
     */
    public static $credit_airtm_menu = 79;

    /**
     * Debit Airtm menu
     *
     * @var int
     */
    public static $debit_airtm_menu= 80;

    /**
     * Uphold menu
     *
     * @var int
     */
    public static $uphold_menu = 83;

    /**
     * Credit Uphold menu
     *
     * @var int
     */
    public static $credit_uphold_menu = 84;

    /**
     * Debit Uphold menu
     *
     * @var int
     */
    public static $debit_uphold_menu= 85;

    /**
     * Cryptocurrencies menu
     *
     * @var int
     */
    public static $cryptocurrencies_menu = 88;

    /**
     * Credit Cryptocurrencies menu
     *
     * @var int
     */
    public static $credit_cryptocurrencies_menu = 89;

    /**
     * Debit Cryptocurrencies menu
     *
     * @var int
     */
    public static $debit_cryptocurrencies_menu = 90;

    /**
     * BetPay reports menu
     *
     * @var int
     */
    public static $betpay_reports_menu = 93;

    /**
     * Configurations menu
     *
     * @var int
     */
    public static $configurations_menu = 94;

    /**
     * Manage providers
     *
     * @var int
     */
    public static $manage_providers = 95;

    /**
     * Agents balances
     *
     * @var int
     */
    public static $agents_balances = 96;

    /**
     * Agents users balances
     *
     * @var int
     */
    public static $agents_users_balances = 97;

    /**
     * JustPay admin menu
     *
     * @var int
     */
    public static $just_pay_admin_menu = 98;

    /**
     * Zippy menu
     *
     * @var int
     */
    public static $zippy_menu = 99;

    /**
     * JustPay debit menu
     *
     * @var int
     */
    public static $zippy_debit_menu = 100;

    /**
     * Credentials menu
     *
     * @var int
     */
    public static $credentials_menu = 102;

    /**
     * IQ soft ticket search
     *
     * @var int
     */
    public static $iq_soft_ticket_search = 103;

    /**
     * Manage products limits
     *
     * @var int
     */
    public static $manage_products_limits = 104;

    /**
     * Zippy admin menu
     *
     * @var int
     */
    public static $zippy_admin_menu = 105;

    /**
     * Rewards menu
     *
     * @var int
     */
    public static $rewards_menu = 106;

    /**
     * Rewards list
     *
     * @var int
     */
    public static $rewards_list = 107;

    /**
     * Manage rewards
     *
     * @var int
     */
    public static $manage_rewards = 108;

    /**
     * Actions configurations menu
     *
     * @var int
     */
    public static $actions_configurations_menu = 109;

    /**
     * Actions configurations list
     *
     * @var int
     */
    public static $actions_configurations_list = 110;

    /**
     * Manage actions configurations
     *
     * @var int
     */
    public static $manage_actions_configurations = 111;

    /**
     * User login
     *
     * @var int
     */
    public static $user_login = 112;

    /**
     * Store menu
     *
     * @var int
     */
    public static $store_menu = 113;

    /**
     * Store rewards menu
     *
     * @var int
     */
    public static $store_rewards_menu = 114;

    /**
     * Manage store rewards
     *
     * @var int
     */
    public static $manage_store_rewards = 115;

    /**
     * Store rewards list
     *
     * @var int
     */
    public static $store_rewards_list = 116;

    /**
     * Store actions menu
     *
     * @var int
     */
    public static $store_actions_menu = 117;

    /**
     * Manage store actions
     *
     * @var int
     */
    public static $manage_store_actions = 118;

    /**
     * Store actions list
     *
     * @var int
     */
    public static $store_actions_list = 119;

    /**
     * Exclude users
     *
     * @var int
     */
    public static $exclude_users = 120;

    /**
     * Manage main users
     *
     * @var int
     */
    public static $manage_main_users = 121;

    /**
     * Manage main agents
     *
     * @var int
     */
    public static $manage_main_agents = 122;

    /**
     * Users status
     *
     * @var int
     */
    public static $users_status = 123;

    /**
     * Dotpanel Dotworkers manual
     *
     * @var int
     */
    public static $dotpanel_dotworkers_manual = 124;

    /**
     * Dotpanel general manual
     *
     * @var int
     */
    public static $dotpanel_general_manual = 125;

    /**
     * Dotpanel agents manual
     *
     * @var int
     */
    public static $dotpanel_agents_manual = 126;

    /**
     * IqSoft totals
     *
     * @var int
     */
    public static  $iq_soft_totals = 127;

    /**
     * Users actives
     *
     * @var int
     */
    public static  $users_actives = 128;

    /**
     * Users audits
     *
     * @var int
     */
    public static  $users_audits = 129;

    /**
     * Store categories menu
     *
     * @var int
     */
    public static  $store_categories_menu = 130;

    /**
     * Manage rewards categories
     *
     * @var int
     */
    public static  $manage_rewards_categories = 131;

    /**
     * Notifications menu
     *
     * @var int
     */
    public static $notifications_menu = 132;

    /**
     * Manage notifications
     *
     * @var int
     */
    public static $manage_notifications = 133;

    /**
     * Manage notifications groups
     * @var int
     */
    public static $manage_notifications_groups = 134;

    /**
     * Points transactions
     * @var int
     */
    public static $points_transactions = 135;

    /**
     * Manage role permissions
     * @var int
     */
    public static $manage_role_permissions = 136;

    /**
     * VCreditos
     *
     * @var int
     */
    public static $vcreditos_menu = 137;

    /**
     * VES to USD
     *
     * @var int
     */
    public static $ves_to_usd_menu = 138;

    /**
     * Email Content
     *
     * @var int
     */
    public static $email_configurations_menu = 139;

    /**
     * Create Email Content
     *
     * @var int
     */
    public static $manage_email_configurations = 140;

    /**
     * Whitebales totals
     *
     * @var int
     */
    public static $whitelabels_totals = 141;

    /**
     * Check User Accounts
     *
     * @var int
     */
    public static $check_user_accounts = 142;

    /**
     * Manage Whitelabels Status Menu
     *
     * @var int
     */
    public static $manage_whitelabels_status_menu= 143;

    /**
     * Manage Whitelabels Status
     *
     * @var int
     */
    public static $manage_whitelabels_status= 144;

    /**
     * Payments report
     *
     * @var int
     */
    public static $payments_report = 37;

    /**
     * Manage betpay menu
     *
     * @var int
     */
    public static $manage_betpay_menu = 38;
    
    /**
     * Pay For Fun Menu
     *
     * @var int
     */
    public static $pay_for_fun_menu = 145;
    
    /**
     * Pay For Fun debit menu
     *
     * @var int
     */
    public static $debit_pay_for_fun_menu = 146;

    /**
     * Daily sales
     *
     * @var int
     */
    public static $daily_sales = 147;

    /**
     * Section games menu
     *
     * @var int
     */
    public static $section_games_menu = 148;

    /**
     * Manage section games
     *
     * @var int
     */
    public static $manage_section_games = 149;

    /**
     * Abitab menu
     *
     * @var int
     */
    public static $abitab_menu = 150;

    /**
     * Credit abitab menu
     *
     * @var int
     */
    public static $credit_abitab_menu = 151;

    /**
     * Red pagos menu
     *
     * @var int
     */
    public static $red_pagos_menu = 152;

    /**
     * Credit red pagos menu
     *
     * @var int
     */
    public static $credit_red_pagos_menu = 153;

    /**
     * VCreditos Api menu
     *
     * @var int
     */
    public static $vcreditos_api_menu = 154;

    /**
     * Debit VCreditos Api menu
     *
     * @var int
     */
    public static $debit_vcreditos_api_menu = 155;

    /**
     * Modals menu
     *
     * @var int
     */
    public static $modals_menu = 156;

    /**
     * Manage modals
     *
     * @var int
     */
    public static $manage_modals = 157;

    /**
     * MOdals list
     *
     * @var int
     */
    public static $modals_list = 158;

    /**
     * Pay Retailers
     *
     * @var int
     */
    public static $pay_retailers_menu = 159;

    /**
     * Debit Pay Retailers menu
     *
     * @var int
     */
    public static $debit_pay_retailers_menu = 160;

    /**
     * Manage lobby games menu
     *
     * @var int
     */
    public static $manage_lobby_games_menu = 161;

    /**
     * Montlhy sales
     *
     * @var int
     */
    public static $monthly_sales = 162;

    /**
     * Manage segmentation tool
     *
     * @var int
     */
    public static $manage_segmentation_tool = 163;

    /**
     * Transaction by lot
     *
     * @var int
     */
    public static $transaction_by_lot = 164;

    /**
     * Sales by whitelabels
     *
     * @var int
     */
    public static $sales_by_whitelabels = 165;

    /**
     * Agents credit transactions
     *
     * @var int
     */
    public static $agents_credit_transactions = 166;

    /**
     * Agents debit transactions
     *
     * @var int
     */
    public static $agents_debit_transactions = 167;

    /**
     * Dashboard widget
     *
     * @var int
     */
    public static $dashboard_widgets = 168;

    /**
     * Dashboard report
     *
     * @var int
     */
    public static $dashboard_report = 169;

    /**
     * Exchange rates
     *
     * @var int
     */
    public static $exchange_rates = 170;

    /**
     * Document verification
     *
     * @var int
     */
    public static $document_verification = 171;

    /**
     * Totals
     *
     * @var int
     */
    public static $totals_report = 172;

    /**
     * Deposits
     *
     * @var int
     */
    public static $deposits_report = 173;

    /**
     * Withdrawals
     *
     * @var int
     */
    public static $withdrawals_report = 174;

    /**
     * Manual transactions
     *
     * @var int
     */
    public static $manual_transactions_report = 175;

    /**
     * Bonus transactions
     *
     * @var int
     */
    public static $bonus_transactions_report = 176;

    /**
     * Locked providers
     *
     * @var int
     */
    public static $locked_providers = 177;

    /**
     * Total pago menu
     *
     * @var int
     */
    public static $total_pago_menu = 178;

    /**
     * Credit total pago menu
     *
     * @var int
     */
    public static $credit_total_pago_menu = 179;

    /**
     * Marketing campaigns menu
     *
     * @var int
     */
    public static $marketing_campaigns_menu = 180;

    /**
     * Manage marketing campaigns
     *
     * @var int
     */
    public static $manage_marketing_campaigns = 181;

    /**
     * CRM
     *
     * @var int
     */
    public static $crm= 182;

    /**
     * Email templates menu
     *
     * @var int
     */
    public static $email_templates_menu = 183;

    /**
     * Manage email templates
     *
     * @var int
     */
    public static $manage_email_templates = 184;

    /**
     * Segmentation tool menu
     *
     * @var int
     */
    public static $segmentation_tool_menu = 185;

    /**
     * Altenar ticket search
     *
     * @var int
     */
    public static $altenar_ticket_search = 186;


    /**
     * Whitelabels games menu
     *
     * @var int
     */
    public static $whitelabels_games_menu = 187;

    /**
     * Deposit withdrawal by user
     *
     * @var int
     */
    public static $deposit_withdrawal_by_user = 188;

    /**
     * System bonus menu
     *
     * @var int
     */
    public static $system_bonus_menu = 189;

    /**
     * Campaigns menu
     *
     * @var int
     */
    public static $campaigns_menu = 190;

    /**
     * Manage campaigns
     *
     * @var int
     */
    public static $manage_campaigns = 191;

    /**
     * Users birthdays report
     *
     * @var int
     */
    public static $users_birthdays_report = 192;

    /**
     * Most played by providers
     *
     * @var int
     */
    public static $most_played_by_providers = 193;

    /**
     * Total financial report
     *
     * @var int
     */
    public static $total_financial_report = 194;

    /**
     * Manual transactions agents
     *
     * @var int
     */
    public static $manual_transactions_agents = 195;

    /**
     * Menu dotsuite
     *
     * @var int
     */
    public static $menu_dotsuite = 196;

    /**
     * Manage menu dotsuite
     *
     * @var int
     */
    public static $manage_menu_dotsuite = 197;

    /**
     * Reports dotsuite
     *
     * @var int
     */
    public static $reports_dotsuite = 198;

    /**
     * Report manual adjustments
     *
     * @var int
     */
    public static $report_manual_adjustments = 199;

    /**
     * Referrals menu
     *
     * @var int
     */
    public static $referrals_menu = 200;

    /**
     *  Referrals create
     *
     * @var int
     */
    public static $referral_create = 201;

    /**
     * Report referrals
     *
     * @var int
     */
    public static $report_referrals = 202;

    /**
     * Report auto lock users
     *
     * @var int
     */
    public static $report_auto_lock_users = 203;

    /**
     * Menu campaign reports
     *
     * @var int
     */
    public static $campaign_reports_menu = 204;

    /**
     * Campaign report
     *
     * @var int
     */
    public static $campaign_report = 205;

    /**
     * Campaign report by user
     *
     * @var int
     */
    public static $campaign_user_report = 206;

    /**
     * Charging point menu
     *
     * @var int
     */
    public static $charging_point_menu = 207;

    /**
     * Charging point credit
     *
     * @var int
     */
    public static $credit_charging_point_menu = 208;

    /**
     * Charging point debit
     *
     * @var int
     */
    public static $debit_charging_point_menu = 209;

    /**
     * Binance menu
     *
     * @var int
     */
    public static $binance_menu = 210;

    /**
     * Credit Binance menu
     *
     * @var int
     */
    public static $credit_binance_menu = 211;

    /**
     * Debit Binance menu
     *
     * @var int
     */
    public static $debit_binance_menu = 212;

    /**
     * Bizum menu
     *
     * @var int
     */
    public static $bizum_menu = 213;

    /**
     * Credit bizum menu
     *
     * @var int
     */
    public static $credit_bizum_menu = 214;

    /**
     * Debit bizum menu
     *
     * @var int
     */
    public static $debit_bizum_menu = 215;

    /**
     * Unlock balance
     *
     * @var int
     */
    public static $unlock_balance = 216;

    /**
     * Manual adjustments bonus
     *
     * @var int
     */
    public static $manual_adjustments_bonus = 217;

    /**
     * ProntoPaga menu
     *
     * @var int
     */
    public static $pronto_paga_menu = 218;

    /**
     * Debit ProntoPaga menu
     *
     * @var int
     */
    public static $debit_pronto_paga_menu = 219;

    /**
     * Reports store
     *
     * @var int
     */
    public static $reports_store = 220;

    /**
     * Reports rewards exchange
     *
     * @var int
     */
    public static $reports_rewards_exchange = 221;

    /**
     * Dot Suite credentials menu
     *
     * @var int
     */
    public static $dot_suite_credentials_menu = 222;

    /**
     * Dot Suite credentials create
     *
     * @var int
     */
    public static $dot_suite_credentials_create = 223;

    /**
     * Dot Suite credentials list
     *
     * @var int
     */
    public static $dot_suite_credentials_list = 224;

    /**
     * Dot Suite free spins menu
     *
     * @var int
     */
    public static $dot_suite_free_spins_menu = 225;

    /**
     * Dot Suite free spins caleta gaming menu
     *
     * @var int
     */
    public static $dot_suite_free_spins_caleta_gaming_menu = 226;

    /**
     * Dot Suite free spins caleta gaming create
     *
     * @var int
     */
    public static  $dot_suite_free_spins_caleta_gaming_create = 227;

    /**
     * Dot Suite free spins caleta gaming cancelar
     *
     * @var int
     */
    public static  $dot_suite_free_spins_caleta_gaming_cancel = 228;

    /**
     * Dot Suite free spins evo play menu
     *
     * @var int
     */
    public static  $dot_suite_free_spins_evo_play_menu = 229;

    /**
     * Dot Suite free spins evo play crear
     *
     * @var int
     */
    public static  $dot_suite_free_spins_evo_play_create = 230;

    /**
     * Dot Suite free spins triple cherry menu
     *
     * @var int
     */
    public static  $dot_suite_free_spins_triple_cherry_menu = 231;

    /**
     * Dot Suite free spins triple cherry crear
     *
     * @var int
     */
    public static  $dot_suite_free_spins_triple_cherry_create = 232;

    /**
     * Dot Suite free spins triple cherry cancel
     *
     * @var int
     */
    public static $dot_suite_free_spins_triple_cherry_cancel = 233;

    /**
     * Dot Suite free spins report menu
     *
     * @var int
     */
    public static  $dot_suite_free_spins_reports_menu = 234;

    /**
     * Dot Suite free spins report
     *
     * @var int
     */
    public static $dot_suite_free_spins_report = 235;

    /**
     * Reserve menu
     *
     * @var int
     */
    public static $reserve_menu = 236;

    /**
     * Credit reserve menu
     *
     * @var int
     */
    public static $credit_reserve_menu = 237;

    /**
     * Debit reserve menu
     *
     * @var int
     */
    public static $debit_reserve_menu= 238;

    /**
     * Disable user account
     *
     * @var int
     */
    public static $disable_user_account= 239;

    /**
     * Personal menu
     *
     * @var int
     */
    public static $personal_menu = 240;

    /**
     * Consult personal menu
     *
     * @var int
     */
    public static $consult_personal_menu = 241;

    /**
     * Cancel personal menu
     *
     * @var int
     */
    public static $cancel_personal_menu = 242;

    /**
     * Zampay Menu
     *
     * @var int
     */
    public static $zampay_menu = 243;

    /**
     * Debit Zampay Menu
     *
     * @var int
     */
    public static $debit_zampay_menu = 244;

    /**
     * Manual adjustment whitelabel
     *
     * @var int
     */
    public static $manual_adjustments_whitelabel = 245;

     /**
     * Pay For Fun Go Menu
     *
     * @var int
     */
    public static $pay_for_fun_go_menu = 246;

    /**
     * Debit Pay For Fun Go Menu
     *
     * @var int
     */
    public static $debit_pay_for_fun_go_menu = 247;

    /**
     * Debit Personal Menu
     *
     * @var int
     */
    public static $debit_personal_menu = 248;

    /**
     * Nequi Menu
     *
     * @var int
     */
    public static $nequi_menu = 249;

    /**
     * Credit Nequi Menu
     *
     * @var int
     */
    public static $credit_nequi_menu = 250;

    /**
     * Search Charging Point menu
     *
     * @var int
     */
    public static $charging_point_search_menu = 251;

    /**
     * Whitelabels active providers
     *
     * @var int
     */
    public static $whitelabels_active_providers = 252;

     /**
     * Payku Menu
     *
     * @var int
     */
    public static $payku_menu = 253;

    /**
     * Debit Payku Menu
     *
     * @var int
     */
    public static $debit_payku_menu = 254;

     /**
     * Set limit client payment methods
     *
     * @var int
     */
    public static $set_limits_client_payment_methods = 255;

    /**
     * Security Menu
     *
     * @var int
     */
    public static $security_menu = 256;

    /**
     * Roles permissions Menu
     *
     * @var int
     */
    public static $roles_permissions_menu = 257;

    /**
     * Roles Menu
     *
     * @var int
     */
    public static $roles_menu = 258;

    /**
     * Permissions Menu
     *
     * @var int
     */
    public static $permissions_menu = 259;

    /**
     * Reports store transactions
     *
     * @var int
     */
    public static $reports_store_transactions = 260;

    /**
     * Franchise menu
     *
     * @var int
     */
    public static $franchise_menu = 261;

    /**
     * Add user franchise
     *
     * @var int
     */
    public static $add_user_franchise = 262;
    /**
     * Add whitelabel franchise
     *
     * @var int
     */
    public static $add_whitelabel_franchise = 263;
    /**
     * Create user agent
     *
     * @var int
     */
    public static $create_user_agent = 264;

    /**
     *  Agents financial
     *
     * @var int
     */
    public static $agents_financial = 265;


    /**
     *  Sales by whitelabels by agents
     *
     * @var int
     */
    public static $sales_by_whitelabels_by_agents = 266;

    /**
     *  Sales by providers by agents
     *
     * @var int
     */
    public static $sales_by_providers_by_agents = 267;

    /**
     *  Betpay Activate Payments Methods
     *
     * @var int
     */
    public static $activate_payments_methods = 268;

    /**
     *  Betpay List Payments Methods
     *
     * @var int
     */
    public static $list_payments_methods = 269;

    /**
     * MercadoPago menu
     *
     * @var int
     */
    public static $mercado_pago_menu = 270;

    /**
     * Credit MercadoPago menu
     *
     * @var int
     */
    public static $credit_mercado_pago_menu = 271;

    /**
     * Debit MercadoPago menu
     *
     * @var int
     */
    public static $debit_mercado_pago_menu = 272;

    /**
     * View Update Rol Admin
     *
     * @var int
     */
    public static $update_rol_admin = 273;

    /**
     * Update Password Wolf
     *
     * @var int
     */
    public static $update_password_wolf = 274;

    /**
     * Report financial by provider
     *
     * @var int
     */
    public static $report_financial_by_provider = 275;

    /**
     * Report financial by username
     *
     * @var int
     */
    public static $report_financial_by_username = 276;

     /**
     * Pix menu
     *
     * @var int
     */
    public static $pix_menu = 277;

    /**
     * Pix debit
     *
     * @var int
     */
    public static $debit_pix_menu = 278;

    /**
     * Dashboard assiria
     *
     * @var int
     */
    public static $dashboard_assiria = 279;

    /**
     * Rol assiria
     *
     * @var int
     */
    public static $rol_assiria = 280;

    /**
     * Reports assiria
     *
     * @var int
     */
    public static $reports_assiria = 281;
}
