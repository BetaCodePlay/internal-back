<?php

namespace Dotworkers\Configurations;

use Carbon\Carbon;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Repositories\CredentialsRepo;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;

/**
 * Class Configurations
 *
 * This class allows to create utils functions
 *
 * @package App\Configurations
 * @author  Eborio Linarez
 * @author  Orlando Bravo
 */
class Utils
{
    public function cleanGmailAddress($email)
    {
        $terms = explode('@', $email);

        if ($terms[1] == 'gmail.com') {
            $email = str_replace('.', '', $email);
        }
        echo $email;
    }

    /**
     * Create response
     *
     * @param string $status Response status
     * @param string $code Status code
     * @param array $data Response data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private static function createResponse($status, $code, $data)
    {
        $response = [
            'status' => $status,
            'code' => $code,
            'data' => $data
        ];
        return response()->json($response, $code);
    }

    /**
     * Create error response
     *
     * @param int $code Error code
     * @param array $data Error data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function errorResponse($code, $data)
    {
        return self::createResponse(Status::$error, $code, $data);
    }

    /**
     * Gets the end of the day of a given date in UTC

     * @param string $date Date
     * @param string $originalFormat Original date format
     * @param string $finalFormat Final date format
     * @param string|null $timezone Timezone to format
     * @return mixed
     */
    public static function endOfDayUtc($date, $originalFormat = 'Y-m-d', $finalFormat = 'Y-m-d H:i:s', $timezone = null)
    {
        $timezone = is_null($timezone) ? session()->get('timezone') : $timezone;
        $date = Carbon::createFromFormat($originalFormat, $date)->endOfDay()->format($finalFormat);
        $date = Carbon::createFromFormat($finalFormat, $date, $timezone)->setTimezone('UTC')->format($finalFormat);
        return $date;
    }

    /**
     * Create failed response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function failedResponse()
    {
        $data = [
            'title' => _i('Error!'),
            'message' => _i('Could not load/save data'),
            'close' => _i('Close')
        ];
        return self::createResponse(Status::$failed, Codes::$failed, $data);
    }

    /**
     * Get client ID by OAuth token
     *
     * @param Illuminate\Http\Request $request
     * @return int
     */
    public static function getClient($request)
    {
        $parser = new Parser(new JoseEncoder());
        $jwt = trim(preg_replace('/^(?:\s+)?Bearer\s/', '', $request->header('authorization')));
        $token = $parser->parse($jwt);
        return $token->claims()->get('aud')[0];
    }

    /**
     * Get credentials
     *
     * @param int $client Client ID
     * @param int $provider Provider ID
     * @param string $currency Currency ISO
     * @return null|mixed
     */
    public static function getCredentials($client, $provider, $currency)
    {
        $credentialsRepo = new CredentialsRepo();
        $credentials = $credentialsRepo->find($client, $provider, $currency);

        if (!is_null($credentials)) {
            $credentialsData = json_decode($credentials->data);
            return $credentialsData;

        } else {
            return null;
        }
    }

    /**
     * Gets the start of the day of a given date in UTC

     * @param string $date Date
     * @param string $originalFormat Original date format
     * @param string $finalFormat Final date format
     * @param string|null $timezone Timezone to format
     * @return mixed
     */
    public static function startOfDayUtc($date, $originalFormat = 'Y-m-d', $finalFormat = 'Y-m-d H:i:s', $timezone = null)
    {
        $timezone = is_null($timezone) ? session()->get('timezone') : $timezone;
        $date = Carbon::createFromFormat($originalFormat, $date)->startOfDay()->format($finalFormat);
        $date = Carbon::createFromFormat($finalFormat, $date, $timezone)->setTimezone('UTC')->format($finalFormat);
        return $date;
    }

    /**
     * Strip accents
     *
     * @param $string String with accents
     * @return string
     */
    public static function stripAccents($string)
    {
        $string = str_replace(
            ['á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'],
            ['a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'],
            $string
        );

        $string = str_replace(
            ['é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'],
            ['e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'],
            $string );

        $string = str_replace(
            ['í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'],
            ['i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'],
            $string );

        $string = str_replace(
            ['ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'],
            ['o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'],
            $string );

        $string = str_replace(
            ['ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'],
            ['u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'],
            $string );

        $string = str_replace(
            ['ñ', 'Ñ', 'ç', 'Ç'],
            ['n', 'N', 'c', 'C'],
            $string
        );
        return $string;
    }

    /**
     * Create success response
     *
     * @param array $data Success data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function successResponse($data)
    {
        return self::createResponse(Status::$ok, Codes::$ok, $data);
    }


    /**
     * Get user IP
     * @return mixed|string
     */
    public static function userIp()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipForwarded = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $ip = explode(',', $ipForwarded)[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}
