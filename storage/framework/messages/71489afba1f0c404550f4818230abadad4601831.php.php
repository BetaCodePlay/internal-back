<?php

namespace App\Http\Controllers;

use App\Core\Collections\EmailConfigurationsCollection;
use App\Core\Repositories\EmailConfigurationsRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;

/**
 * Class EmailTypesController
 *
 * This class allows to manage email types requests
 *
 * @package App\Http\Controllers
 * @author  Carlos Hurtado
 */
class EmailConfigurationsController extends Controller
{
    /**
     * EmailConfigurationsRepo
     *
     * @var EmailConfigurationsRepo
     */
    private $emailConfigurationsRepo;

    /**
     * EmailConfigurationsCollection
     *
     * @var EmailConfigurationsCollection
     */
    private $emailConfigurationsCollection;

    /**
     * UsersController constructor.
     *
     * @param EmailConfigurationsRepo $emailConfigurationsRepo
     * @param EmailConfigurationsCollection $emailConfigurationsCollection
     */
    public function __construct(EmailConfigurationsRepo $emailConfigurationsRepo, EmailConfigurationsCollection $emailConfigurationsCollection)
    {
        $this->emailConfigurationsRepo = $emailConfigurationsRepo;
        $this->emailConfigurationsCollection = $emailConfigurationsCollection;
    }

    /**
     * Get configurations data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function configurationsData()
    {
        try {
            $emailTypes = $this->emailConfigurationsRepo->all();
            $this->emailConfigurationsCollection->formatEmailTypes($emailTypes);
            $data = [
                'email' => $emailTypes
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
     * @param int $id Email configuration ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function edit($id)
    {
        try{
            $whitelabel = Configurations::getWhitelabel();
            $emailTypes = $this->emailConfigurationsRepo->findEmailConfigurations( $whitelabel, $id );

            if (is_null($emailTypes)) {
                $emailTypes = $this->emailConfigurationsRepo->find($id);
            }

            $this->emailConfigurationsCollection->formatEmailType($emailTypes);
            $data['title'] = _i('Update email content');
            $data['email'] = $emailTypes;
            return view('back.email-configurations.edit', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get view email types
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = _i('Email contents');
        return view('back.email-configurations.index', $data);
    }

    /**
     *  Update email types
     *
     * @param Request $request Request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateEmail(Request $request)
    {
        try{
            $emailTypeId = $request->id;
            $whitelabel = Configurations::getWhitelabel();
            $emailData = [
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'content' => $request->input('content'),
                'button' => $request->button,
                'footer' => $request->footer,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'language' => $request->language,
            ];
            $this->emailConfigurationsRepo->updateEmailConfigurations( $whitelabel, $emailTypeId, $emailData);

            $data = [
                'title' => _i('Updated email content'),
                'message' => _i('Email data was saved correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }
}
