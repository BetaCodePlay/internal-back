<?php


namespace App\CRM\Commands;

use App\Core\Enums\Status;
use App\CRM\Enums\MarketingCampaignsStatus;
use App\CRM\Repositories\EmailTemplatesRepo;
use App\CRM\Repositories\MarketingCampaignsRepo;
use App\CRM\Repositories\SegmentsRepo;
use App\Users\Repositories\UsersRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendEmailTemplate
 *
 *
 * @package App\CRM\Commands
 */
class SendEmailTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails';

    /**
     * @param MarketingCampaignsRepo $marketingCampaignsRepo
     * @param EmailTemplatesRepo $emailTemplatesRepo
     * @param UsersRepo $usersRepo
     */
    public function handle(MarketingCampaignsRepo $marketingCampaignsRepo, EmailTemplatesRepo $emailTemplatesRepo, UsersRepo $usersRepo, SegmentsRepo $segmentsRepo)
    {
        $today = Carbon::now();
        $campaigns = $marketingCampaignsRepo->all();

        if (count($campaigns) > 0) {
            foreach ($campaigns as $campaign) {
                Configurations::setEmail($campaign->whitelabel_id);
                $segmentData = $segmentsRepo->findByIdAndStatus($campaign->segment_id, $campaign->whitelabel_id, Status::$active);
                if(!is_null($segmentData)){
                    if ($today >= $campaign->scheduled_date) {
                        $emailTemplate = $emailTemplatesRepo->find($campaign->email_template_id);
                        if (!is_null($emailTemplate)) {
                            $users = $usersRepo->getByIDs($segmentData->data);

                            foreach ($users as $user) {
                                $email = $user->email;
                                $this->email($emailTemplate, $email);
                                $data = [
                                    'status' => MarketingCampaignsStatus::$sent
                                ];
                                $marketingCampaignsRepo->update($campaign->id, $data);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Email
     *
     * @param object $template Email template data
     * @param $email
     */
    public function email($template, $email)
    {
        Mail::to($email)->send(new \App\CRM\Mailers\SendEmail($template));
    }
}
