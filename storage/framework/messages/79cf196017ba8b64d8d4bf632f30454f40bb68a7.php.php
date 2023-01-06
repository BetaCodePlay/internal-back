<?php


namespace App\Http\Controllers;

use App\Core\Repositories\LandingPagesRepo;
use App\Core\Collections\LandingPagesCollection;
use Dotworkers\Configurations\Enums\TemplateElementTypes;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Core\Core;

/**
 * Class LandingPagesController
 *
 * This class allows to manage landing pages requests
 *
 * @package App\Http\Controllers
 * @author  Orlando Bravo
 */
class LandingPagesController extends Controller
{

    /**
     * LandingPagesRepo
     *
     * @var LandingPagesRepo
     */
    private $landingPagesRepo;

    /**
     * LandingPagesCollection
     *
     * @var LandingPagesCollection
     */
    private $landingPagesCollection;

    /**
     * File path
     *
     * @var string
     */
    private $filePath;

    /**
     * LandingPagesController constructor
     *
     * @param LandingPagesRepo $landingPagesRepo
     * @param LandingPagesCollection $landingPagesCollection
     */
    public function __construct(LandingPagesRepo $landingPagesRepo, LandingPagesCollection $landingPagesCollection)
    {
        $this->landingPagesRepo = $landingPagesRepo;
        $this->landingPagesCollection = $landingPagesCollection;
        $s3Directory = Configurations::getS3Directory();
        $this->filePath = "$s3Directory/landing-pages/";
    }

    /**
     * Get all landing pages
     *
     * @param int $templateElementType Template element type ID
     * @param string $section Section String
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all()
    {
        try {
           $landing = $this->landingPagesRepo->all();

            if (!is_null($landing)) {
                $this->landingPagesCollection->formatAll($landing);
                $data = [
                    'images' => $landing
                ];
            }else {
                $data = [
                    'images' => []
                ];
            }
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show create view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        try {
            $data['title'] = _i('Upload new landing pages');
            return view('back.landing-pages.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Delete landing pages
     *
     * @param int $id landing pages ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        try {
            $landing = $this->landingPagesRepo->find($id);
            $pathBackground1 = "{$this->filePath}{$landing->data->positions->{'background-1'}}";
            Storage::delete($pathBackground1);
            $pathBackground2 = "{$this->filePath}{$landing->data->positions->{'background-2'}}";
            Storage::delete($pathBackground2);
            $pathLeft = "{$this->filePath}{$landing->data->positions->{'left-1'}}";
            Storage::delete($pathLeft);
            $pathLogo = "{$this->filePath}{$landing->data->positions->{'logo-1'}}";
            Storage::delete($pathLogo);
            $this->landingPagesRepo->delete($id);
            $data = [
                'title' => _i('Landing pages removed'),
                'message' => _i('The landing pages was successfully removed'),
                'close' => _i('Close')
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
     * @param int $id Slider ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $landing = $this->landingPagesRepo->find($id);
            $this->landingPagesCollection->formatDetails($landing);
            $data['landing'] = $landing;
            $data['title'] = _i('Update landing pages');
            return view('back.landing-pages.edit', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'slider' => $id]);
            abort(500);
        }
    }

    /**
     * Show  landing pages list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = _i('List of Landing Pages');
        return view('back.landing-pages.index', $data);
    }

    /**
     * Store landing pages
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'language' => 'required',
            'currency' => 'required',
            'status'  => 'required',
            'name'  => 'required',
        ]);
        try {
            $background1 = $request->file('background_1');
            $background2 = $request->file('background_2');
            $image = $request->file('image');
            $logo = $request->file('logo');
            $nameBackground1 = Core::storegePut($background1, $this->filePath);
            $nameBackground2 = Core::storegePut($background2, $this->filePath);
            $nameImage = Core::storegePut($image, $this->filePath);
            $nameLogo = Core::storegePut($logo, $this->filePath);
            $startDate = !is_null($request->start_date) ? Utils::startOfDayUtc($request->start_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;
            $endDate = !is_null($request->end_date) ? Utils::endOfDayUtc($request->end_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;

            $landingData = [
                'name' => $request->name,
                'whitelabel_id' => Configurations::getWhitelabel(),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'data' => [
                    'quantity' => 4,
                    'positions' => [
                        'background-1' => $nameBackground1,
                        'background-2' => $nameBackground2,
                        'left-1' =>  $nameImage,
                        'logo-1' =>  $nameLogo,
                    ],
                    'props' => [
                        'subtitle' => $request->subtitle,
                        'button' => [
                            'text' => $request->text,
                            'url' => $request->url,
                        ],
                        'form' => true,
                        'steps' => [
                            'hero' => [],
                            'title' => $request->steps_title,
                            'content' => $request->steps_content,
                        ],
                        'terms' => [
                            'title' => $request->terms_title,
                            'content' => $request->terms_content,
                        ],
                        'additional_info' => [
                            'title' => $request->additional_title,
                            'content' => $request->additional_content,
                        ]
                    ]
                ]
            ];
            $this->landingPagesRepo->store($landingData);
            $data = [
                'title' => _i('Landing pages loaded'),
                'message' => _i('The landing was loaded correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update landing pages
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'data.language' => 'required',
            'data.currency' => 'required',
            'data.status'  => 'required',
            'data.name'  => 'required',
        ]);
        try {
            \Log::info('data', ['request' => $request]);
            $id = $request->data->id;
            $background1 = $request->file('data.background_1');
            $background2 = $request->file('data.background_2');
            $image = $request->file('data.image');
            $logo = $request->file('data.logo');
            $nameImage = $request->data->file;
            $nameBackground1 = $request->data->file_1;
            $nameBackground2 = $request->data->file_2;
            $nameLogo = $request->data->file_3;
            $startDate = !is_null($request->data->start_date) ? Utils::startOfDayUtc($request->data->start_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;
            $endDate = !is_null($request->data->end_date) ? Utils::endOfDayUtc($request->data->end_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;

            if (!is_null($image)) {
                $oldFilePath = "{$this->filePath}{$nameImage}";
                Storage::delete($oldFilePath);
                $nameImage = Core::storegePut($image, $this->filePath);
            }

            if (!is_null($background1)) {
                $oldFilePath = "{$this->filePath}{$nameBackground1}";
                Storage::delete($oldFilePath);
                $nameBackground1 = Core::storegePut($background1, $this->filePath);
            }

            if (!is_null($background2)) {
                $oldFilePath = "{$this->filePath}{$nameBackground2}";
                Storage::delete($oldFilePath);
                $nameBackground2 = Core::storegePut($background2, $this->filePath);
            }

            if (!is_null($logo)) {
                $oldFilePath = "{$this->filePath}{$nameLogo}";
                Storage::delete($oldFilePath);
                $nameLogo = Core::storegePut($logo, $this->filePath);
            }

            $landingData = [
                'name' => $request->data->name,
                'whitelabel_id' => Configurations::getWhitelabel(),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'language' => $request->data->language,
                'currency_iso' => $request->data->currency,
                'status' => $request->data->status,
                'data' => [
                    'quantity' => 4,
                    'positions' => [
                        'background-1' => $nameBackground1,
                        'background-2' => $nameBackground2,
                        'left-1' =>  $nameImage,
                        'logo-1' =>  $nameLogo,
                    ],
                    'props' => [
                        'subtitle' => $request->data->subtitle,
                        'button' => [
                            'text' => $request->data->text,
                            'url' => $request->data->url,
                        ],
                        'form' => true,
                        'steps' => [
                            'hero' => [],
                            'title' => $request->data->steps_title,
                            'content' => $request->steps_content,
                        ],
                        'terms' => [
                            'title' => $request->data->terms_title,
                            'content' => $request->terms_content,
                        ],
                        'additional_info' => [
                            'title' => $request->data->additional_title,
                            'content' => $request->additional_content,
                        ]
                    ]
                ]
            ];

            $this->landingPagesRepo->update($id,$landingData);
            $data = [
                'title' => _i('Landing pages updated'),
                'message' => _i('Landing pages data was updated correctly'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }
}
