<?php

namespace App\Http\Controllers;

use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\Codes;
use App\Core\Repositories\GamesRepo;
use App\Core\Repositories\LobbyGamesRepo;
use App\Core\Collections\GamesCollection;
use App\Core\Collections\LobbyGamesCollection;
use App\Whitelabels\Repositories\WhitelabelsRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Components;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use App\Core\Repositories\CredentialsRepo;
use App\Audits\Repositories\AuditsRepo;
use App\Audits\Enums\AuditTypes;
use Dotworkers\Audits\Audits;

/**
 * Class LobbyGamesController
 *
 * This class allows to manage Lobby Games requests
 *
 * @package App\Http\Controllers
 * @author  Derluin Gonzalez
 */
class LobbyGamesController extends Controller
{
    /**
     * WhitelabelsRepo
     *
     * @var WhitelabelsRepo
     */
    private $whitelabelsRepo;

    /**
     * LobbyGamesRepo
     *
     * @var LobbyGamesRepo
     */
    private $lobbyGamesRepo;

    /**
     * LobbyGamesCollection
     *
     * @var LobbyGamesCollection
     */
    private $lobbyGamesCollection;

    /**
     * GamesRepo
     *
     * @var GamesRepo
     */
    private $gamesRepo;

    /**
     * GamesCollection
     *
     * @var GamesCollection
     */
    private $gamesCollection;

     /**
     * @var AuditsRepo
     */
    private $auditsRepo;

    /**
     * CredentialsRepo
     *
     * @var CredentialsRepo
     */
    private $credentialsRepo;

    /**
     * LobbyGamesController constructor
     *
     * @param WhitelabelsRepo $whitelabelsRepo
     * @param LobbyGamesRepo $lobbyGamesRepo
     * @param LobbyGamesCollection $LobbyGamesCollection
     * @param GamesRepo $gamesRepo
     * @param GamesCollection $gamesCollection
     * @param CredentialsRepo $credentialsRepo
     */
    public function __construct(WhitelabelsRepo $whitelabelsRepo, LobbyGamesRepo $lobbyGamesRepo, LobbyGamesCollection $lobbyGamesCollection, GamesRepo $gamesRepo, GamesCollection $gamesCollection, CredentialsRepo $credentialsRepo, AuditsRepo $auditsRepo)
    {
        $this->whitelabelsRepo = $whitelabelsRepo;
        $this->lobbyGamesRepo = $lobbyGamesRepo;
        $this->lobbygamesCollection = $lobbyGamesCollection;
        $this->gamesRepo = $gamesRepo;
        $this->gamesCollection = $gamesCollection;
        $this->credentialsRepo = $credentialsRepo;
    }

    /**
    * Get all Lobby Games
    *
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function all()
    {
        try {

            $games = $this->lobbyGamesRepo->getWhitelabel(Configurations::getWhitelabel());
            $this->lobbygamesCollection->formatAll($games);
            $data = [
                'games' => $games
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Delete Lobby Games
     *
     * @param int $game Games ID
     * @param int $whitelabel whitelabels ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function Delete($game)
    {
        try {

            $game = (int)$game;
            $this->lobbyGamesRepo->delete($game, Configurations::getWhitelabel());
            $data = [
                'title' => _i('Deleted games'),
                'message' => _i('Games data was delete correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Game Lobby Games
     *
     * @param int $provider Provider ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function game(Request $request)
    {
        try {

            $provider= (int) $request->provider;
            $games=[];
            if (!is_null($provider)) {
                $currency = session('currency');
                $games = $this->gamesRepo->getGamesByProvider(Configurations::getWhitelabel(), $currency, $provider);
                $this->gamesCollection->formatGames($games);
            }
            $data = [
                'games' => $games
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get index view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        try {

            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $provider = $this->credentialsRepo->searchByWhitelabel($whitelabel, $currency);
            $data['title'] = _i('Lobby Games');
            $data['providers'] = $provider;
            return view('back.lobby-games.lobby-games', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Store Lobby Games
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'games' => 'required|array|min:1',
        ]);

        $games = $request->games;

            foreach ($games as $game) {
                $usedcredencial =  $this->lobbyGamesRepo->searchByGames($game, Configurations::getWhitelabel());
                if (!is_null($usedcredencial)) {
                    $data = [
                        'title' => _i('Used games'),
                        'message' => _i('The games already exists', $game),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
                $lobbyData = [
                    'whitelabel_id' => Configurations::getWhitelabel(),
                    'game_id' =>  (int) $game
                ];
                $this->lobbyGamesRepo->store($lobbyData);
            }

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'lobby_data' => $lobbyData
            ];

            //Audits::store($user_id, AuditTypes::$lobbys_recommended_creation, Configurations::getWhitelabel(), $auditData);


        $data = [
            'title' => _i('Saved games'),
            'message' => _i('Games data was saved correctly'),
            'close' => _i('Close')
        ];
        return Utils::successResponse($data);
    }
}
