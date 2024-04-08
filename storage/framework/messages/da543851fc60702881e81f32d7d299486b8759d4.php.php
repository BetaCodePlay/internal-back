<?php

namespace App\Http\Controllers;

use App\CRM\Collections\EmailTemplatesCollection;
use App\CRM\Enums\EmailTemplatesTypes;
use App\CRM\Mailers\SendEmail;
use App\CRM\Repositories\EmailTemplatesRepo;
use App\CRM\Repositories\EmailTemplateTypesRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Audits\Repositories\AuditsRepo;
use App\Audits\Enums\AuditTypes;
use Dotworkers\Audits\Audits;

/**
 * Class EmailTemplatesController
 *
 * This class allows to manage email templates requests
 *
 * @package App\Http\Controllers
 * @author  Carlos Hurtado
 * @author  Eborio Linarez
 */
class EmailTemplatesController extends Controller
{
    /**
     * EmailTemplatesRepo
     *
     * @var EmailTemplatesRepo
     */
    private $emailTemplatesRepo;

    /**
     * EmailTemplatesCollection
     *
     * @var EmailTemplatesCollection
     */
    private $emailTemplatesCollection;

    /**
     * EmailTemplateTypesRepo
     *
     * @var EmailTemplateTypesRepo
     */
    private $emailTemplateTypesRepo;
     /**
     * @var AuditsRepo
     */
    private $auditsRepo;

    /**
     * EmailTemplatesController constructor
     *
     * @param EmailTemplatesRepo $emailTemplatesRepo
     * @param EmailTemplatesCollection $emailTemplatesCollection
     *  @param EmailTemplateTypesRepo $emailTemplateTypesRepo
     */
    public function __construct(EmailTemplatesRepo $emailTemplatesRepo, EmailTemplatesCollection $emailTemplatesCollection, EmailTemplateTypesRepo $emailTemplateTypesRepo, AuditsRepo $auditsRepo)
    {
        $this->emailTemplatesRepo = $emailTemplatesRepo;
        $this->emailTemplatesCollection = $emailTemplatesCollection;
        $this->emailTemplateTypesRepo = $emailTemplateTypesRepo;
    }

    /**
     * Get all templates
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all()
    {
        try {
            $templates = $this->emailTemplatesRepo->all();
            $this->emailTemplatesCollection->formatAll($templates);
            $data = [
                'templates' => $templates
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get all transactions templates
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allTransactions(Request $request)
    {
        try {
            $emailTemplates = $request->email_templates_type_id;
            if (is_null($emailTemplates)) {
                $type = [EmailTemplatesTypes::$email_template_transactions, EmailTemplatesTypes::$email_template_transactions_approved, EmailTemplatesTypes::$email_template_transactions_rejected];
            } else {
                $type = [$emailTemplates];
            }

            $templates = $this->emailTemplatesRepo->allTransactions($type);
            $this->emailTemplatesCollection->formatAllTransactions($templates);
            $data = [
                'templates' => $templates
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data['email_templates_type_id'] = EmailTemplatesTypes::$email_template_marketing;
        $data['title'] = _i('New template');
        return view('back.crm.email-templates.create', $data);
    }

    /**
     * Show create transaction view
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createTransaction()
    {
        $data['title'] = _i('New template');
        return view('back.notifications.transactions.create', $data);
    }

    /**
     * Delete templates
     *
     * @param int $id Slider ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        try {
            $this->emailTemplatesRepo->delete($id);
            $data = [
                'title' => _i('Template removed'),
                'message' => _i('The template was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Delete templates
     *
     * @param int $id Slider ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteTransaction($id)
    {
        try {
            $this->emailTemplatesRepo->delete($id);
            $data = [
                'title' => _i('Template removed'),
                'message' => _i('The template was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Duplicate templates
     *
     * @param int $id Slider ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function duplicate($id)
    {
        try {
            $emailTemplate = $this->emailTemplatesRepo->find($id);
            $templateData = [
                'title' => $emailTemplate->title,
                'subject' => $emailTemplate->subject,
                'language' => $emailTemplate->language,
                'currency_iso' => $emailTemplate->currency_iso,
                'status' => $emailTemplate->status,
                'content' => $emailTemplate->content,
                'metadata' => $emailTemplate->metadata,
                'html' => $emailTemplate->html,
                'whitelabel_id' => Configurations::getWhitelabel(),
                //'email_templates_type_id' => $emailTemplateType
            ];
            $this->emailTemplatesRepo->store($templateData);

            $data = [
                'title' => _i('Template duplicated'),
                'message' => _i('The template was successfully duplicated'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Duplicate templates
     *
     * @param int $id Slider ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function duplicateTransaction($id)
    {
        try {
            $emailTemplate = $this->emailTemplatesRepo->find($id);
            $templateData = [
                'title' => $emailTemplate->title,
                'subject' => $emailTemplate->subject,
                'language' => $emailTemplate->language,
                'currency_iso' => $emailTemplate->currency_iso,
                'status' => $emailTemplate->status,
                'content' => $emailTemplate->content,
                'metadata' => $emailTemplate->metadata,
                'html' => $emailTemplate->html,
                'whitelabel_id' => Configurations::getWhitelabel(),
                //'email_templates_type_id' => $emailTemplate->email_templates_type_id
            ];
            $this->emailTemplatesRepo->store($templateData);

            $data = [
                'title' => _i('Template duplicated'),
                'message' => _i('The template was successfully duplicated'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show edit view
     *
     * @param int $id Template ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $template = $this->emailTemplatesRepo->find($id);
        $this->emailTemplatesCollection->formatDetails($template);

        if (!is_null($template)) {
            try {
                $data['template'] = $template;
                $data['title'] = _i('Edit template');
                return view('back.crm.email-templates.edit', $data);

            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'slider' => $id]);
                abort(500);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Show edit transaction view
     *
     * @param int $id Template ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function editTransactions($id)
    {
        $template = $this->emailTemplatesRepo->find($id);
        $emailTemplateTypes = $this->emailTemplateTypesRepo->all();
        $this->emailTemplatesCollection->formatDetails($template);

        if (!is_null($template)) {
            try {
                $data['template'] = $template;
                $data['emailTemplateTypes'] = $emailTemplateTypes;
                $data['title'] = _i('Edit template');
                return view('back.notifications.transactions.edit', $data);

            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'slider' => $id]);
                abort(500);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Get images gallery
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function images()
    {
        try {
            $s3Directory = Configurations::getS3Directory();
            $s3Path = "{$s3Directory}/email-templates/";
            $response = [];
            $files = Storage::files($s3Path);

            foreach ($files as $file) {
                $name = str_replace($s3Path, '', $file);
                $filePath = "email-templates/{$name}";
                $response[] = [
                    'name' => $name,
                    'url' => s3_asset($filePath),
                    'size' => Storage::size($file),
                    'thumbnailUrl' => s3_asset($filePath)
                ];
            }

            $data = [
                'files' => $response
            ];
            return response()->json($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show templates list
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data['email_templates_type_id'] = EmailTemplatesTypes::$email_template_marketing;
        $data['title'] = _i('List of templates');
        return view('back.crm.email-templates.index', $data);
    }

    /**
     * Show templates list
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function indexTransaction()
    {
        $data['email_templates_type_id'] = EmailTemplatesTypes::$email_template_transactions;
        $data['title'] = _i('List of templates');
        return view('back.notifications.transactions.index', $data);
    }

    /**
     * Store template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'subject' => 'required',
            'language' => 'required',
            'currency' => 'required'
        ]);

       try {
            $templateData = [
                'title' => $request->title,
                'subject' => $request->subject,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'content' => $request->template,
                'metadata' => $request->metadata,
                'html' => $request->html,
                'whitelabel_id' => Configurations::getWhitelabel(),
                //'email_templates_type_id' => EmailTemplatesTypes::$email_template_marketing
            ];
            $template = $this->emailTemplatesRepo->store($templateData);

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'template_data' => $templateData
            ];

            //Audits::store($user_id, AuditTypes::$email_template_creation, Configurations::getWhitelabel(), $auditData);

            $data = [
                'title' => _i('Template created'),
                'message' => _i('The template was created successfully'),
                'route' => route('email-templates.edit', [$template->id])
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store transaction template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeTransaction(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'subject' => 'required',
            'language' => 'required',
            'currency' => 'required'
        ]);

        try {
            $templateData = [
                'title' => $request->title,
                'subject' => $request->subject,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'content' => $request->template,
                'metadata' => $request->metadata,
                'html' => $request->html,
                'whitelabel_id' => Configurations::getWhitelabel(),
                //'email_templates_type_id' => $request->email_templates_type_id
            ];
            $template = $this->emailTemplatesRepo->store($templateData);

            $data = [
                'title' => _i('Template created'),
                'message' => _i('The template was created successfully'),
                'route' => route('email-templates-transaction.edit', [$template->id])
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function testEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
        ]);

        try{
            $email = $request->email;
            $templateId = $request->template_id;

            $emailTemplate = $this->emailTemplatesRepo->find($templateId);

            Mail::to($email)->send(new \App\CRM\Mailers\SendEmail($emailTemplate));

            $data = [
                'title' => _i('Template email test'),
                'message' => _i('Test mail has been sent'),
                'close' => _i('Close')
            ];

            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'subject' => 'required',
            'language' => 'required',
            'currency' => 'required'
        ]);

        try {
            $id = $request->id;
            $templateData = [
                'title' => $request->title,
                'subject' => $request->subject,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'content' => $request->template,
                'metadata' => $request->metadata,
                'html' => $request->html,
            ];
            $this->emailTemplatesRepo->update($id, $templateData);

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'template_data' => [
                    'id' => $id,
                    'data' => $templateData,
                ],
            ];

            //Audits::store($user_id, AuditTypes::$email_template_modification, Configurations::getWhitelabel(), $auditData);

            $data = [
                'title' => _i('Template updated'),
                'message' => _i('The template was updated successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateTransaction(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'subject' => 'required',
            'language' => 'required',
            'currency' => 'required'
        ]);

        try {
            $id = $request->id;
            $templateData = [
                'title' => $request->title,
                'subject' => $request->subject,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'content' => $request->template,
                'metadata' => $request->metadata,
                'html' => $request->html,
                //'email_templates_type_id' => $request->email_templates_type_id
            ];
            $this->emailTemplatesRepo->update($id, $templateData);

            $data = [
                'title' => _i('Template updated'),
                'message' => _i('The template was updated successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Upload images
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadImages(Request $request)
    {
        try {
            $s3Directory = Configurations::getS3Directory();
            $files = $request->file('files');
            $response = [];

            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $originalName = str_replace(".$extension", '', $file->getClientOriginalName());
                $name = Str::slug($originalName) . time() . '.' . $extension;
                $s3Path = "{$s3Directory}/email-templates/{$name}";
                $filePath = "email-templates/{$name}";
                Storage::put($s3Path, file_get_contents($file->getRealPath()), 'public');

                $response[] = [
                    'deleteType' => 'DELETE',
                    'deleteUrl' => s3_asset($filePath),
                    'name' => $name,
                    'originalName' => $name,
                    'size' => $file->getSize(),
                    'thumbnailUrl' => s3_asset($filePath),
                    'type' => $file->getMimeType(),
                    'url' => s3_asset($filePath)
                ];
            }

            $data = [
                'files' => $response
            ];
            return response()->json($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    public function email($template)
    {
        $templateData = $this->emailTemplatesRepo->find($template);
        Mail::to('eboriolinarez@gmail.com')->send(new SendEmail($templateData));
    }
}
