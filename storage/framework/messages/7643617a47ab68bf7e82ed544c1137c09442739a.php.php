<?php


namespace App\Http\Controllers;


use App\CRM\Collections\MarketingCampaignsCollection;
use App\CRM\Enums\MarketingCampaignsStatus;
use App\CRM\Repositories\EmailTemplatesRepo;
use App\CRM\Repositories\MarketingCampaignsRepo;
use App\CRM\Repositories\SegmentsRepo;
use App\Users\Repositories\UsersRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Xinax\LaravelGettext\Facades\LaravelGettext;
use App\Audits\Repositories\AuditsRepo;
use App\Audits\Enums\AuditTypes;
use Dotworkers\Audits\Audits;

/**
 * Class MarketingCampaignsController
 *
 * This class allows to manage Marketing Campaigns requests
 *
 * @package App\Http\Controllers
 * @author  Damelys Espinoza
 */
class MarketingCampaignsController extends Controller
{
    /**
     * MarketingCampaignsCollection
     *
     * @var MarketingCampaignsCollection
     */
    private $marketingCampaignsCollection;

    /**
     * MarketingCampaignsRepo
     *
     * @var MarketingCampaignsRepo
     */
    private $marketingCampaignsRepo;

    /**
     * SegmentsRepo
     *
     * @var SegmentsRepo
     */
    private $segmentsRepo;

    /**
     * @var AuditsRepo
     */
    private $auditsRepo;

    /**
     * EmailTemplatesRepo
     *
     * @var EmailTemplatesRepo
     */

    private $emailTemplatesRepo;

    /**
     * MarketingCampaignsController constructor.
     *

     * @param MarketingCampaignsRepo $marketingCampaignsRepo
     * @param SegmentsRepo $segmentsRepo
     */
    public function __construct(MarketingCampaignsCollection $marketingCampaignsCollection, MarketingCampaignsRepo $marketingCampaignsRepo, SegmentsRepo  $segmentsRepo, EmailTemplatesRepo $emailTemplatesRepo, AuditsRepo $auditsRepo)
    {
        $this->marketingCampaignsCollection = $marketingCampaignsCollection;
        $this->marketingCampaignsRepo = $marketingCampaignsRepo;
        $this->segmentsRepo = $segmentsRepo;
        $this->emailTemplatesRepo = $emailTemplatesRepo;
    }

    /**
     * Get all marketing campaigns
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all()
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $campaigns = $this->marketingCampaignsRepo->allByWhitelabel($whitelabel);
            $this->marketingCampaignsCollection->formatAll($campaigns);
            $data = [
                'campaigns' => $campaigns
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show campaign view
     *
     * @param int $id Campaign ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $campaign = $this->marketingCampaignsRepo->find($id);

        if (!is_null($campaign)) {
            try {
                $this->marketingCampaignsCollection->formatCampaign($campaign);
                $emailTemplates = $this->emailTemplatesRepo->all();
                $segments = $this->segmentsRepo->allByWhitelabel();
                $data['email_templates'] = $emailTemplates;
                $data['segments'] = $segments;
                $data['campaign'] = $campaign;
                $data['title'] = _i('Update campaign');
                return view('back.crm.marketing-campaigns.edit', $data);

            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'slider' => $id]);
                abort(500);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Show marketing campaign view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        try {
            $emailTemplates = $this->emailTemplatesRepo->all();
            $segments = $this->segmentsRepo->allByWhitelabel();
            $data['email_templates'] = $emailTemplates;
            $data['segments'] = $segments;
            $data['title'] = _i('New campaign');
            return view('back.crm.marketing-campaigns.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }


    /**
     * Delete marketing campaign
     *
     * @param int $id Campaign ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        try {
            $this->marketingCampaignsRepo->delete($id);
            $data = [
                'title' => _i('Campaign removed'),
                'message' => _i('The campaign was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store marketing campaign
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'language' => 'required',
            'title' => 'required',
            'currency' => 'required',
            'segment' => 'required',
            'email_template' => 'required',
            'scheduled_date'  => 'required',
        ]);

        try {
            $timezone = session('timezone');
            $scheduledDate = Carbon::createFromFormat('d-m-Y h:i a', $request->scheduled_date, $timezone)->setTimezone('UTC');
            $campaignData = [
                'whitelabel_id' => Configurations::getWhitelabel(),
                'title' => $request->title,
                'status' => MarketingCampaignsStatus::$pending,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'segment_id' => $request->segment,
                'email_template_id' => $request->email_template,
                'scheduled_date' => $scheduledDate
            ];
            $this->marketingCampaignsRepo->store($campaignData);

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'marketing_campaign_data' => $campaignData
            ];

            //Audits::store($user_id, AuditTypes::$marketing_campaigns_creation, Configurations::getWhitelabel(), $auditData);
            $data = [
                'title' => _i('Campaign created'),
                'message' => _i('The campaign was created successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show marketing campaigns list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = _i('List of campaigns');
        return view('back.crm.marketing-campaigns.index', $data);
    }

    /**
     * Update marketing campaign
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'language' => 'required',
            'title' => 'required',
            'currency' => 'required',
            'segment' => 'required',
            'email_template' => 'required',
        ]);

        try {
            $timezone = session('timezone');
            $scheduledDate = Carbon::createFromFormat('d-m-Y h:i a', $request->scheduled_date, $timezone)->setTimezone('UTC');
            $id = $request->id;
            $campaignData = [
                'title' => $request->title,
                'status' => $request->status,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'segment_id' => $request->segment,
                'email_template_id' => $request->email_template,
                'scheduled_date' => $scheduledDate,
            ];
            $this->marketingCampaignsRepo->update($id, $campaignData);

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'marketing_campaigns_data' => [
                    'id' => $id,
                    'data' => $campaignData,
                ],
            ];

            //Audits::store($user_id, AuditTypes::$marketing_campaigns_modification, Configurations::getWhitelabel(), $auditData);

            $data = [
                'title' => _i('Campaign updated'),
                'message' => _i('The campaign data was updated correctly'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }
}
