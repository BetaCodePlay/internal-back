<?php

namespace App\Http\Controllers;


use App\Core\Repositories\CredentialsRepo;
use App\Core\Collections\GamesCollection;
use App\Core\Repositories\GamesRepo;
use App\WhitelabelsGames\Collections\WhitelabelsGamesCollection;
use App\WhitelabelsGames\Repositories\WhitelabelGamesRepo;
use App\WhitelabelsGames\Repositories\WhitelabelsGamesCategoriesRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use App\Audits\Repositories\AuditsRepo;
use App\Audits\Enums\AuditTypes;
use Dotworkers\Audits\Audits;


/**
 * Class WhitelabelsGamesControllers
 *
 * This class allows to manage core requests
 *
 * @package App\Http\Controllers
 * @author  Carlos Hurtado
 */
class WhitelabelsGamesControllers extends Controller
{
    /**
     * CredentialsRepo
     *
     * @var CredentialsRepo
     */
    private $credentialsRepo;

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
     * WhitelabelGamesRepo
     *
     * @var WhitelabelGamesRepo
     */
    private $whitelabelGamesRepo;

    /**
     * WhitelabelsGamesCategoriesRepo
     *
     * @var WhitelabelsGamesCategoriesRepo
     */
    private $whitelabelsGamesCategoriesRepo;

    /**
     * WhitelabelsGamesCollection
     *
     * @var WhitelabelsGamesCollection
     */
    private $whitelabelsGamesCollection;

    /**
     * @var AuditsRepo
     */
    private $auditsRepo;

    /**
     * WhitelabelsGamesControllers constructor.
     *
     * @param CredentialsRepo $credentialsRepo
     * @param GamesRepo $gamesRepo
     * @param GamesCollection $gamesCollection
     * @param WhitelabelGamesRepo $whitelabelGamesRepo
     * @param WhitelabelsGamesCategoriesRepo $whitelabelsGamesCategoriesRepo
     * @param WhitelabelsGamesCollection $whitelabelsGamesCollection
     */
    public function __construct(CredentialsRepo $credentialsRepo,  GamesRepo $gamesRepo, GamesCollection $gamesCollection, WhitelabelGamesRepo $whitelabelGamesRepo, WhitelabelsGamesCategoriesRepo $whitelabelsGamesCategoriesRepo, WhitelabelsGamesCollection $whitelabelsGamesCollection, AuditsRepo $auditsRepo)
    {
        $this->credentialsRepo = $credentialsRepo;
        $this->gamesRepo = $gamesRepo;
        $this->gamesCollection = $gamesCollection;
        $this->whitelabelGamesRepo = $whitelabelGamesRepo;
        $this->whitelabelsGamesCategoriesRepo = $whitelabelsGamesCategoriesRepo;
        $this->whitelabelsGamesCollection = $whitelabelsGamesCollection;
    }

    /**
     * Get all whitelabels Games
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all(Request $request)
    {
        try {
            $provider = $request->provider;
            $category = $request->category;
            $whitelabel = Configurations::getWhitelabel();
            $games = $this->whitelabelGamesRepo->getGamesWhitelabel($whitelabel, $provider, $category);
            $this->whitelabelsGamesCollection->formatAll($games);
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
     * @param int $category Category ID
     * @param int $whitelabel whitelabels ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($game, $category)
    {
        try {
            $gameId = $game;
            $whitelabel = Configurations::getWhitelabel();
            $this->whitelabelGamesRepo->delete($gameId, $category, $whitelabel);
            $data = [
                'title' => _i('Deleted game'),
                'message' => _i('The game was successfully removed from the category'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get the games in view
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $provider = $this->credentialsRepo->searchByWhitelabel($whitelabel, $currency);
            $gamesCategories =  $this->whitelabelsGamesCategoriesRepo->all();
            $this->whitelabelsGamesCollection->formatCategories($gamesCategories);
            $data['title'] = _i('Highlights games');
            $data['providers'] = $provider;
            $data['games_categories'] = $gamesCategories;
            return view('back.whitelabels-games.index', $data);
        } catch (\Exception $e) {
            \Log::error(__METHOD__, ['exception' => $e]);
            abort(500);
        }
    }

    /**
     * Provider game
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function game(Request $request)
    {
        try {
            $provider = $request->change_provider;
            $devices = $request->devices;
            $games = [];
            if (!is_null($provider)) {
                $currency = session('currency');
                $whitelabel = Configurations::getWhitelabel();
                $games = $this->gamesRepo->getGamesByProvider($whitelabel, $currency, $provider, $devices);
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
            'game_category' => 'required',
        ]);

        try {
            $games = $request->games;
            $whitelabelGameCategoryId = $request->game_category;
            $whitelabel = Configurations::getWhitelabel();
            foreach ($games as $game) {
                $whitelabelGame =  $this->whitelabelGamesRepo->searchByGames($game, $whitelabelGameCategoryId, $whitelabel);
                if (!is_null($whitelabelGame)) {
                    $data = [
                        'title' => _i('Used games'),
                        'message' => _i('The games already exists', $game),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
                $whitelabelGameData = [
                    'whitelabel_id' => Configurations::getWhitelabel(),
                    'game_id' =>  (int) $game,
                    'whitelabel_game_category_id' => (int) $whitelabelGameCategoryId
                ];
                $this->whitelabelGamesRepo->store($whitelabelGameData);
            }

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'games_data' => $whitelabelGameData
            ];

            //Audits::store($user_id, AuditTypes::$featured_games, Configurations::getWhitelabel(), $auditData);

            $data = [
                'title' => _i('Saved game'),
                'message' => _i('The game was assigned to the category selected successfully'),
                'close' => _i('Close'),
                'route' => route('whitelabels-games.index')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }
}
