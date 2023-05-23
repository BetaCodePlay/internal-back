<?php

namespace App\Http\Controllers;

use App\Core\Collections\GamesCollection;
use App\Core\Collections\SectionGamesCollection;
use App\Core\Repositories\GamesRepo;
use App\Core\Repositories\SectionGamesRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\TemplateElementTypes;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class SectionGamesController
 *
 * This class allows to manage games section requests
 *
 * @package App\Http\Controllers
 * @author  Genesis Perez
 */
class SectionGamesController extends Controller
{
    /**
     * SectionGamesRepo
     *
     * @var SectionGamesRepo
     */
    private $sectionGamesRepo;

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
     * SectionGamesCollection
     *
     * @var SectionGamesCollection
     */
    private $sectionGamesCollection;

    /**
     * SectionGamesController constructor
     *
     * @param SectionGamesRepo $sectionGamesRepo
     * @param GamesRepo $gamesRepo
     * @param SectionGamesCollection $sectionGamesCollection
     * @param GamesCollection $gamesCollection
     */
    public function __construct(SectionGamesRepo $sectionGamesRepo, GamesRepo $gamesRepo, GamesCollection $gamesCollection, SectionGamesCollection $sectionGamesCollection)
    {
        $this->sectionGamesRepo = $sectionGamesRepo;
        $this->gamesRepo = $gamesRepo;
        $this->gamesCollection = $gamesCollection;
        $this->sectionGamesCollection = $sectionGamesCollection;
    }

    /**
     * Get all games
     *
     * @param string $section Section String
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all($section = null)
    {
        try {
            $games = $this->sectionGamesRepo->allGameBySection($section);
            $this->sectionGamesCollection->formatAll($games);
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
     * Show create view
     *
     * @param string $section Section String
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($templateElementType, $section = null)
    {
        try {
            $providers = [
                Providers::$caleta_gaming,
                Providers::$lega_jackpot,
                Providers::$dlv,
                Providers::$gamzix,
                Providers::$patagonia,
                Providers::$pg_soft,
                Providers::$ka_gaming,
                Providers::$play_son,
                Providers::$game_art,
                Providers::$spinmatic,
                Providers::$pragmatic_play,
                Providers::$mascot_gaming,
                Providers::$branka_originals,
                Providers::$booongo,
                Providers::$booming_games,
                Providers::$hacksaw_gaming,
                Providers::$booongo_original,
                Providers::$wazdan,
                Providers::$mancala_gaming,
                Providers::$belatra,
                Providers::$triple_cherry_original,
                Providers::$red_rake,
                Providers::$mancala_gaming,
                Providers::$evo_play,
                Providers::$pari_play,
                Providers::$i_soft_bet,
                Providers::$play_n_go,
                Providers::$kalamba,
                Providers::$ocb_slots,
                Providers::$lucky_spins,
                Providers::$wnet_games,
                Providers::$gamzix,
                Providers::$branka
            ];
            $additionals=['popular','new','featured'];
            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $games = $this->gamesRepo->getFunGames($whitelabel, $currency, $providers);
            $this->gamesCollection->formatGames($games);
            $data['template_element_type'] = $templateElementType;
            $data['additionals'] = $additionals;
            $data['games'] = $games;
            $data['section'] = $section;
            $data['title'] = _i('Create games section');
            return view('back.section-games.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }


    /**
     * Delete Games
     *
     * @param int $games Game ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete($games)
    {
        try {

            $this->sectionGamesRepo->delete($games);
            $data = [
                'title' => _i('Deleted Games'),
                'message' => _i('Games data was delete correctly'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }



    /**
     * Show games list
     *
     * @param int $templateElementType Template element type ID
     * @param string $section Section String
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($templateElementType, $section = null)
    {
        try {
            if ($templateElementType == TemplateElementTypes::$home) {
                $home = Configurations::getHome();
                $quantity = $home->$section->games->quantity ?? [];
            }
            $data['template_element_type'] = $templateElementType;
            $data['section'] = $section;
            $data['title'] = _i('List of games');
            return view('back.section-games.index', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Store games
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

        $additional= null;
        if ($request->section == 'section-7') {

            $validationRules['additional_info'] = 'required';
            $additional= $request->additional_info;
        }
        try {
            $whitelabel = Configurations::getWhitelabel();
            $games = $request->games;
            foreach ($games as $game) {
                if ($request->section == 'section-7') {

                    $usedGame = $this->sectionGamesRepo->searchBySectionAndAdditional($game, $whitelabel, $request->section, $request->additional_info);
                }else{
                    $usedGame = $this->sectionGamesRepo->searchByGamesAndSection($game, $whitelabel, $request->section);
                }

                if (!is_null( $usedGame)) {
                    $data = [
                        'title' => _i('Used games'),
                        'message' => _i('The games already exists', $game),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
                $gameData = [
                    'game_id' =>  (int) $game,
                    'additional_info' => $additional,
                    'section' =>  $request->section,
                    'whitelabel_id' =>  $whitelabel
                ];
                $this->sectionGamesRepo->store($gameData);
            }

            $data = [
                'title' => _i('Saved games'),
                'message' => _i('Games data was saved correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }


}
