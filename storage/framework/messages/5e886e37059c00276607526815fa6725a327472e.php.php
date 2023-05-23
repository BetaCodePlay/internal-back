<?php

namespace App\Core\Enums;

/**
 * Class Pages
 *
 * This class allows to define pages
 *
 * @package App\Core\Enums
 * @author  Eborio Linarez
 */
class Pages
{
    /**
     * About
     *
     * @var int
     */
    public static $about = 1;

    /**
     * Security and Privacy
     *
     * @var int
     */
    public static $security_and_privacy = 2;

    /**
     * General Conditions
     *
     * @var int
     */
    public static $general_conditions = 3;

    /**
     * Return Policy
     *
     * @var int
     */
    public static $return_policy = 4;

    /**
     * FAQ
     *
     * @var int
     */
    public static $faq = 5;

    /**
     * AML Program
     *
     * @var int
     */
    public static $aml_program = 6;

    /**
     * KYC
     *
     * @var int
     */
    public static $kyc = 7;

    /**
     * Sports Regulation
     *
     * @var int
     */
    public static $sports_regulation = 8;

    /**
     * Terms and Conditions
     *
     * @var int
     */
    public static $terms_and_conditions = 9;

    /**
     * Contact Us
     *
     * @var int
     */
    public static $contact_us= 10;

    /**
     * Lincense
     *
     * @var int
     */
    public static $lincense = 11;

    /**
     * Merchant of Reference
     *
     * @var int
     */
    public static $merchant_of_reference = 12;

    /**
     * VAT on Winnings
     *
     * @var int
     */
    public static $vat_on_winnings= 13;

    /**
     * Responsible Gambling
     *
     * @var int
     */
    public static $responsible_gambling = 14;

    /**
     * Terms and Conditions of Use
     *
     * @var int
     */
    public static $terms_and_conditions_of_use = 15;

    /**
     * Treat Customers Fairly
     *
     * @var int
     */
    public static $treat_customers_fairly = 16;

    /**
     * Payments
     *
     * @var int
     */
    public static $payments = 17;

    /**
     * Cookie Policy
     *
     * @var int
     */
    public static $cookie_policy = 18;

    /**
     * Complaints Policy
     *
     * @var int
     */
    public static $complaints_policy= 19;

    /**
     * AML Policy
     *
     * @var int
     */
    public static $aml_policy = 20;

    /**
     * COPPA Compilance
     *
     * @var int
     */
    public static $coppa_compilance = 21;

    /**
     * Copyright Policy
     *
     * @var int
     */
    public static $copyright_policy= 22;

    /**
     * Information Security Policy
     *
     * @var int
     */
    public static $information_security_policy = 23;

    /**
     * Piracy Policy
     *
     * @var int
     */
    public static $piracy_policy = 24;

    /**
     * OFAC Compilanc
     *
     * @var int
     */
    public static $ofac_compilanc= 25;

    /**
     * Play Nice Policy
     *
     * @var int
     */
    public static $play_nice_policy = 26;

    /**
     * Privacy Policy
     *
     * @var int
     */
    public static $privacy_policy = 27;

    /**
     * Lucky Africa
     *
     * @var int
     */
    public static $lucky_africa= 28;

    /**
     * Spin Deluxe
     *
     * @var int
     */
    public static $spin_deluxe = 29;

    /**
     * Responsible Gaming
     *
     * @var int
     */
    public static $responsible_gaming = 30;

    /**
     * Regulations and Terms
     *
     * @var int
     */
    public static $regulations_and_terms = 31;

    /**
     * Tutorials
     *
     * @var int
     */
    public static $tutorials = 32;


    /**
     * Get page name
     *
     * @param int $page Page ID
     * @return array|string|null
     */
    public static function getName($page)
    {
        switch ($page) {
            case self::$about:
            {
                return _i('About');
                break;
            }
            case self::$security_and_privacy:
            {
                return _i('Security and Privacy');
                break;
            }
            case self::$general_conditions:
            {
                return _i('General Conditions');
                break;
            }
            case self::$return_policy:
            {
                return _i('Return Policy');
                break;
            }
            case self::$faq:
            {
                return _i('FAQ');
                break;
            }
            case self::$aml_program:
            {
                return _i('AML PROGRAM');
                break;
            }
            case self::$kyc:
            {
                return _i('KYC');
                break;
            }
            case self::$sports_regulation:
            {
                return _i('Sports Regulation');
                break;
            }
            case self::$terms_and_conditions:
            {
                return _i('Terms and Conditions');
                break;
            }
            case self::$contact_us:
            {
                return _i('Contact Us');
                break;
            }
            case self::$lincense:
            {
                return _i('Lincense');
                break;
            }
            case self::$merchant_of_reference:
            {
                return _i('Merchant of Reference');
                break;
            }
            case self::$vat_on_winnings:
            {
                return _i('VAT on Winnings');
                break;
            }
            case self::$responsible_gambling:
            {
                return _i('Responsible Gambling');
                break;
            }
            case self::$terms_and_conditions_of_use:
            {
                return _i('Terms and Conditions of Use');
                break;
            }
            case self::$treat_customers_fairly:
            {
                return _i('Treat Customers Fairly');
                break;
            }
            case self::$payments:
            {
                return _i('Payments');
                break;
            }
            case self::$cookie_policy:
            {
                return _i('Cookie Policy');
                break;
            }
            case self::$complaints_policy:
            {
                return _i('Complaints Policy');
                break;
            }
            case self::$aml_policy:
            {
                return _i('AML Policy');
                break;
            }
            case self::$coppa_compilance:
            {
                return _i('COPPA Compilance');
                break;
            }
            case self::$copyright_policy:
            {
                return _i('Copyright Policy');
                break;
            }
            case self::$information_security_policy:
            {
                return _i('Information Security Policy');
                break;
            }
            case self::$piracy_policy:
            {
                return _i('Piracy Policy');
                break;
            }
            case self::$ofac_compilanc:
            {
                return _i('OFAC Compilanc');
                break;
            }
            case self::$play_nice_policy:
            {
                return _i('Play Nice Policy');
                break;
            }
            case self::$privacy_policy:
            {
                return _i('Privacy Policy');
                break;
            }
            case self::$lucky_africa:
            {
                return _i('Lucky Africa');
                break;
            }
            case self::$spin_deluxe:
            {
                return _i('Spin Deluxe');
                break;
            }
            case self::$responsible_gaming:
            {
                return _i('Responsible Gaming');
                break;
            }
            case self::$regulations_and_terms:
            {
                return _i('Regulations and Terms');
                break;
            }
            case self::$tutorials:
            {
                return _i('Tutorials');
                break;
            }
            default:
            {
                return _i('Undefined');
                break;
            }
        }
    }
}
