<?php

namespace App\Http\Controllers;

use App\Core\Collections\PagesCollection;
use App\Core\Enums\Pages;
use App\Core\Repositories\PagesRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Xinax\LaravelGettext\Facades\LaravelGettext;
use App\Audits\Repositories\AuditsRepo;
use App\Audits\Enums\AuditTypes;
use Dotworkers\Audits\Audits;

/**
 * Class PagesController
 *
 * This class allows to manage pages requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 */
class PagesController extends Controller
{

    /**
     * @var AuditsRepo
     */
    private $auditsRepo;

    /**
     * PagesRepo
     *
     * @var PagesRepo
     */
    private $pagesRepo;

    /**
     * PagesCollection
     *
     * @var PagesCollection
     */
    private $pagesCollection;

    /**
     * PagesController constructor
     *
     * @param PagesRepo $pagesRepo
     * @param PagesCollection $pagesCollection
     */
    public function __construct(PagesRepo $pagesRepo, PagesCollection $pagesCollection, AuditsRepo $auditsRepo)
    {
        $this->pagesRepo = $pagesRepo;
        $this->pagesCollection = $pagesCollection;
    }

    /**
     * Get all pages
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all()
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $language = LaravelGettext::getLocale();
            $pages = $this->pagesRepo->getByWhitelabel($whitelabel, $language);
            $this->pagesCollection->formatAll($pages);
            $data = [
                'pages' => $pages
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show edit view
     *
     * @param int $id Page ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $page = $this->pagesRepo->findByWhitelabel($id);

            if (is_null($page)) {
                $page = $this->pagesRepo->find($id);
            }

            $this->pagesCollection->formatDetails($page);
            $data['page'] = $page;
            $data['title'] = _i('Update page') . ' ' . $page->title;
            return view('back.pages.edit', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'user' => $id]);
            abort(500);
        }
    }

    /**
     * Show pages list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = _i('Pages list');
        return view('back.pages.index', $data);
    }

    /**
     * Update pages
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required'
        ]);

        try {
            $id = $request->id;
            $whitelabel = Configurations::getWhitelabel();
            $language = LaravelGettext::getLocale();

            $pageData = [
                'title' => $request->title,
                'content' => $request->input('content'),
                'status' => $request->status,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            $this->pagesRepo->update($id, $whitelabel, $language, $pageData);

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'page_data' =>[
                    'id' => $id,
                    'whitelabel' => $whitelabel,
                    'language' => $language,
                    'data' => $pageData
                ] 
            ];

            //Audits::store($user_id, AuditTypes::$pages, Configurations::getWhitelabel(), $auditData);


            $data = [
                'title' => _i('Page updated'),
                'message' => _i('The page data was updated correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }
}
