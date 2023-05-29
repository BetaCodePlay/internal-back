<?php

namespace App\Http\Controllers;

use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\Codes;
use App\Core\Repositories\GamesRepo;
use App\Core\Collections\CoreCollection;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class LobbyGamesController
 *
 * This class allows to manage Lobby Games requests
 *
 * @package App\Http\Controllers
 * @author  Genesis Perez
 */
class LobbyGamesController extends Controller
{
    /**
     * CoreCollection
     *
     * @var CoreCollection
     */
    private $coreCollection;

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
     * @param CoreCollection $coreCollection
     * @param CredentialsRepo $credentialsRepo
     */
    public function __construct(CoreCollection $coreCollection, WhitelabelsRepo $whitelabelsRepo, LobbyGamesRepo $lobbyGamesRepo, LobbyGamesCollection $lobbyGamesCollection, GamesRepo $gamesRepo, GamesCollection $gamesCollection, CredentialsRepo $credentialsRepo, AuditsRepo $auditsRepo)
    {
        $this->whitelabelsRepo = $whitelabelsRepo;
        $this->lobbyGamesRepo = $lobbyGamesRepo;
        $this->lobbyGamesCollection = $lobbyGamesCollection;
        $this->coreCollection = $coreCollection;
        $this->gamesRepo = $gamesRepo;
        $this->gamesCollection = $gamesCollection;
        $this->credentialsRepo = $credentialsRepo;
    }

    /**
     * Get all games
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allGames(Request $request)
    {
        try {
            if (!is_null($request->providerr)) {
                $provider = $request->provider;
            }
            if (!is_null($request->route)) {
                $route = $request->route;
            }
            if (!is_null($request->games)) {
                $game = $request->games;
            }
            $provider = $request->provider;
            $route = $request->route;
            $game = $request->games;
            $order = $request->order;
            $image = $request->image;
            $items = Configurations::getMenu();
            $category = 1;
            $whitelabel = Configurations::getWhitelabel();
            $games = $this->lobbyGamesRepo->getGamesWhitelabel($whitelabel, $category, $provider, $route, $order, $game, $image);
            $this->lobbyGamesCollection->formatAll($games, $items, $order, $request->image);
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
     * Show view create lobby games
     *
     */
    public function createLobbyGames()
    {
        try {
            $route = Configurations::getMenu();
            $data['route'] = $this->coreCollection->formatWhitelabelMenu($route);
            $image = new \stdClass();
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $provider = $this->credentialsRepo->searchByWhitelabel($whitelabel, $currency);
            $games = $this->lobbyGamesRepo->searchGamesByWhitelabel($whitelabel);
            $products = $this->gamesRepo->getProducts();
            $makers = $this->gamesRepo->getMakers();
            $data['image'] = $image;
            $data['makers'] = $makers;
            $data['products'] = $products;
            $data['providers'] = $provider;
            $data['games'] = $games;
            $data['title'] = _i('Create lobby');
            return view('back.lobby-games.games.create', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Delete Lobby Games
     *
     * @param int $game Games ID
     * @param int $whitelabel whitelabels ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteGames($game)
    {
        try {
            $gameId = $game;
            $whitelabel = Configurations::getWhitelabel();
            $this->lobbyGamesRepo->delete($gameId, $whitelabel);
            $data = [
                'title' => _i('Deleted game'),
                'message' => _i('The game was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }


    /**
     * Show edit view
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editLobbyGames($id)
    {
        $image = $this->lobbyGamesRepo->findById($id);
        if (!is_null($image)) {
            try {
                $route = Configurations::getMenu();
                $imageData = $this->lobbyGamesCollection->formatByImage($image);
                $data['title'] = _i('Edit games');
                $data['route'] = $this->coreCollection->formatWhitelabelMenu($route);
                $data['image'] = $imageData;
                return view('back.lobby-games.games.edit', $data);

            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'slider' => $id]);
                abort(500);
            }
        } else {
            abort(404);
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
            $games = [];
            if (!is_null($provider)) {
                $currency = session('currency');
                $whitelabel = Configurations::getWhitelabel();
                $games = $this->gamesRepo->getGames($whitelabel, $currency, $provider);
                $this->lobbyGamesCollection->formatDotsuiteGames($games);
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
     * Game by category
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function gameByCategoryAndMaker(Request $request)
    {
        try {
            $provider = $request->provider;
            $category = $request->category;
            $maker = $request->maker;
            $product = $request->product;
            $games = [];
            if (!is_null($category)) {
                $currency = session('currency');
                $whitelabel = Configurations::getWhitelabel();
                $games = $this->gamesRepo->getGamesByCategoryAndMaker($whitelabel, $currency, $provider, $category, $maker, $product);
                $this->lobbyGamesCollection->formatDotsuiteGames($games);
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
    public function storeGames(Request $request)
    {
        $personalize = !is_null($request->personalize) ? $request->personalize : null;
        $route = !is_null($request->route) ? $request->route : null;
        $this->validate($request, [
            'maker' => 'required',
        ]);
        if($personalize) {
            $this->validate($request, [
                'games' => 'required|array|min:1',
            ]);
        }
        try {
            $whitelabel = Configurations::getWhitelabel();

            if($personalize) {
                $games = $request->games;
                $image = $request->file('image');
                if(!is_null($image)){
                    $extension = $image->getClientOriginalExtension();
                    $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
                    $file = $request->file;
                    $s3Directory = Configurations::getS3Directory();
                    $filePath = "$s3Directory/lobby/";
                    $name = Str::slug($originalName) . time() . mt_rand(1, 100) . '.' . $extension;
                    $newFilePath = "{$filePath}{$name}";
                    Storage::put($newFilePath, file_get_contents($image->getRealPath()), 'public');
                }else{
                    $name = null;
                }
                foreach ($games as $game) {
                    $game = (integer)$game;
                    $whitelabelGame = $this->lobbyGamesRepo->searchByDotsuiteGames($game, $whitelabel);
                    if (!is_null($whitelabelGame)) {
                        if ($route = $request->route) {
                            $usedRoute = $this->lobbyGamesRepo->findRoute($route);
                            if ($route !== $whitelabelGame->route){
                                if ($usedRoute) {
                                    $data = [
                                        'title' => _i('Used Games'),
                                        'message' => _i('The game data is used in other route', $usedRoute),
                                        'close' => _i('Close')
                                    ];
                                    return Utils::errorResponse(Codes::$forbidden, $data);
                                }
                            } else {
                                $whitelabelGameData = [
                                    'order' => $request->order,
                                    'data' => []
                                ];
                                $this->lobbyGamesRepo->update($whitelabelGame->game_id, $request->route, $whitelabelGameData);

                                $data = [
                                    'title' => _i('Updated game'),
                                    'message' => _i('The game was updated successfully'),
                                    'close' => _i('Close'),
                                    'route' => route('games.create')
                                ];
                                return Utils::successResponse($data);
                            }
                        }
                    }
                    $whitelabelGameData = [
                        'whitelabel_id' => Configurations::getWhitelabel(),
                        'order' => $request->order,
                        'route' => $request->route,
                        'image' => $name,
                        'game_id' => $game,
                        'data' => []
                    ];
                    $this->lobbyGamesRepo->store($whitelabelGameData);
                }
            }else{
                $provider = (string) $request->provider;
                $maker = $request->maker;
                $category = $request->category;
                $product = $request->product_id;
                $games = $this->gamesRepo->getDotSuiteGamesByProviderAndMakerAndCategoryAndProduct($provider, $category, $maker, $product);
                foreach ($games as $game) {
                    $whitelabelGame = $this->lobbyGamesRepo->searchByDotsuiteGames($game->id, $whitelabel);
                    if (!is_null($whitelabelGame)) {
                        if ($route = $request->route) {
                            $usedRoute = $this->lobbyGamesRepo->findRoute($route);
                            if ($route !== $whitelabelGame->route){
                                if ($usedRoute) {
                                    $data = [
                                        'title' => _i('Used Games'),
                                        'message' => _i('The game data is used in other route', $usedRoute),
                                        'close' => _i('Close')
                                    ];
                                    return Utils::errorResponse(Codes::$forbidden, $data);
                                }
                            } else {
                                $whitelabelGameData = [
                                    'order' => $request->order,
                                    'data' => []
                                ];
                                $this->lobbyGamesRepo->update($whitelabelGame->game_id, $request->route,  $whitelabelGameData);
                                $data = [
                                    'title' => _i('Updated game'),
                                    'message' => _i('The game was updated successfully'),
                                    'close' => _i('Close'),
                                    'route' => route('games.create')
                                ];
                                return Utils::successResponse($data);
                            }
                        }
                    }
                    else{
                        $whitelabelGameData = [
                            'whitelabel_id' => Configurations::getWhitelabel(),
                            'order' => $request->order,
                            'route' => $request->route,
                            'game_id' => $game->id,
                            'data' => []
                        ];
                        $lobbygames=$this->lobbyGamesRepo->store($whitelabelGameData);
                    }
                }
            }
            $data = [
                'title' => _i('Saved game'),
                'message' => _i('The game was saved successfully'),
                'close' => _i('Close'),
                'route' => route('games.create')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }


    /**
     * Update games lobby
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updategames(Request $request)
    {

        try {
            $id = $request->id;
            $file = $request->file;
            $image = $request->file('image');
            $s3Directory = Configurations::getS3Directory();
            $filePath = "$s3Directory/lobby/";

            $whitelabelGameData = [
                'name' => $request->name,
                'order' => $request->order,
                'route' => $request->route
            ];

            if (!is_null($image)) {
                $extension = $image->getClientOriginalExtension();
                $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
                $name = Str::slug($originalName) . time() . '.' . $extension;
                $newFilePath = "{$filePath}{$name}";
                $oldFilePath = "{$filePath}{$file}";
                Storage::put($newFilePath, file_get_contents($image->getRealPath()), 'public');
                Storage::delete($oldFilePath);
                $whitelabelGameData['image'] = $name;
                $file = $name;
            }
            $this->lobbyGamesRepo->updateImage($id, $whitelabelGameData);
            $data = [
                'title' => _i('Saved data game'),
                'message' => _i('The data game was successfully updated'),
                'close' => _i('Close'),
                'file' => $file
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }
}
