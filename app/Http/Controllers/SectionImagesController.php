<?php

namespace App\Http\Controllers;

use App\Core\Collections\SectionImagesCollection;
use App\Core\Enums\ImagesPositions;
use App\Core\Enums\Sections;
use Carbon\Carbon;
use Dotworkers\Configurations\Enums\TemplateElementTypes;
use App\Core\Repositories\SectionImagesRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Components;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Audits\Repositories\AuditsRepo;
use App\Audits\Enums\AuditTypes;
use Dotworkers\Audits\Audits;

class SectionImagesController extends Controller
{
    /**
     * SectionImagesRepo
     *
     * @var SectionImagesRepo
     */
    private $sectionImagesRepo;
      /**
     * @var AuditsRepo
     */
    private $auditsRepo;

    /**
     * SectionImagesCollection
     *
     * @var SectionImagesCollection
     */
    private $sectionImagesCollection;

    /**
     * File path
     *
     * @var string
     */
    private $filePath;

    /**
     * ImagesController constructor
     *
     * @param SectionImagesRepo $sectionImagesRepo
     * @param SectionImagesCollection $sectionImagesCollection
     */
    public function __construct(SectionImagesRepo $sectionImagesRepo, SectionImagesCollection $sectionImagesCollection,AuditsRepo $auditsRepo)
    {
        $this->sectionImagesRepo = $sectionImagesRepo;
        $this->sectionImagesCollection = $sectionImagesCollection;
    }

    /**
     * Get all sliders
     *
     * @param int $templateElementType Template element type ID
     * @param string $section Section String
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all($templateElementType, $section = null)
    {
        try {
            switch ($templateElementType) {
                case TemplateElementTypes::$home:
                {
                    $configuration = Configurations::getHome();
                    $positions = $configuration->$section->section_images->positions ?? [];
                    break;
                }
                case TemplateElementTypes::$register_form:
                {
                    $register = Configurations::getRegisterView();
                    $configuration = Configurations::getTemplateElement($register);
                    $positions = $configuration->data->section_images->positions;
                    break;
                }
                case TemplateElementTypes::$login_form:
                {
                    $login = Configurations::getLoginView();
                    $configuration = Configurations::getTemplateElement($login);
                    $positions = $configuration->data->section_images->positions;
                    break;
                }
                case TemplateElementTypes::$header:
                {
                    $header = Configurations::getHeader();
                    $configuration = Configurations::getTemplateElement($header);
                    $positions = $configuration->data->section_images->positions;
                    break;
                }
            }
            if (empty($positions))  {
                $imagesData = $this->sectionImagesRepo->getBySection($section);
                $this->sectionImagesCollection->formatAll($imagesData, $configuration->$section->section_images);
            } else {
                $imagesData = $this->sectionImagesCollection->formatAllWithPositions($positions, $templateElementType, $section);
            }
            $data = [
                'images' => $imagesData
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
     * @param int $templateElementType Template element type ID
     * @param string $section Section String
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($templateElementType, $section = null)
    {
        try {
            if ($templateElementType == TemplateElementTypes::$home) {
                $home = Configurations::getHome();
                if (empty($home->$section->section_images->positions)) {
                    $image = new \stdClass();
                    $image->size = "{$home->$section->section_images->width}x{$home->$section->section_images->height}";
                    $data['image'] = $image;
                    $data['front'] = $image;
                }
            }
            $data['template_element_type'] = $templateElementType;
            $data['section'] = $section;
            $data['title'] = _i('Upload image');
            return view('back.section-images.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show edit view
     *
     * @param Request $request
     * @param int $templateElementType Template element type ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $templateElementType)
    {
        $id = $request->id;
        $section = $request->section;
        $position = $request->position;
        try {
            switch ($templateElementType) {
                case TemplateElementTypes::$home:
                {
                    $configuration = Configurations::getHome();
                    break;
                }
                case TemplateElementTypes::$register_form:
                {
                    $register = Configurations::getRegisterView();
                    $configuration = Configurations::getTemplateElement($register);
                    break;
                }
                case TemplateElementTypes::$login_form:
                {
                    $login = Configurations::getLoginView();
                    $configuration = Configurations::getTemplateElement($login);
                    break;
                }
                case TemplateElementTypes::$header:
                {
                    $header = Configurations::getHeader();
                    $configuration = Configurations::getTemplateElement($header);
                    break;
                }
            }

            if (is_null($section)) {
                $positions = $configuration->data->section_images->positions;
                $props = $configuration->data->section_images->props;
            } else {
                if (is_null($position)) {
                    $positions = [];

                } else {
                    $positions = $configuration->$section->section_images->positions;
                }
                $props = $configuration->$section->section_images->props;
            }

            if ($position == ImagesPositions::$logo_light || $position == ImagesPositions::$logo_dark || $position == ImagesPositions::$favicon || $position ==  ImagesPositions::$mobile_light || $position ==  ImagesPositions::$mobile_dark) {
                $image = new \stdClass();
                switch ($position) {
                    case ImagesPositions::$logo_light:
                    {
                        $imageData = Configurations::getLogo($mobile = false);
                        $image->image = $imageData->img_light;
                        break;
                    }
                    case ImagesPositions::$logo_dark:
                    {
                        $imageData = Configurations::getLogo($mobile = false);
                        $image->image = $imageData->img_dark;
                        break;
                    }
                    case ImagesPositions::$mobile_light:
                    {
                        $imageData = Configurations::getLogo($mobile = true);
                        $image->image = $imageData->img_light;
                        break;
                    }
                    case ImagesPositions::$mobile_dark:
                    {
                        $imageData = Configurations::getLogo($mobile = true);
                        $image->image = $imageData->img_dark;
                        break;
                    }
                    case ImagesPositions::$favicon:
                    {
                        $image->image = Configurations::getFavicon();
                        break;
                    }
                }
                $image->status = true;
            } else {
                if (!is_null($id)) {
                    $image = $this->sectionImagesRepo->find($id);

                } else {
                    if (is_null($section)) {
                        $image = $this->sectionImagesRepo->findByPosition($position, $templateElementType);
                    }
                    else {
                        $image = $this->sectionImagesRepo->findByPositionAndSection($position, $templateElementType, $section);
                    }
                }
            }

            $imageData = $this->sectionImagesCollection->formatDetails($image, $position, $positions);
            $data['template_element_type'] = $templateElementType;
            $data['position'] = $position;
            $data['props'] = $props;
            $data['image'] = $imageData;
            $data['front'] = $imageData;
            $data['section'] = $section;
            $data['title'] = _i('Update image');
            return view('back.section-images.edit', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show sliders list
     *
     * @param int $templateElementType Template element type ID
     * @param string $section Section String
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($templateElementType, $section = null)
    {
        if ($templateElementType == TemplateElementTypes::$home) {
            $home = Configurations::getHome();
            $positions = $home->$section->section_images->positions ?? [];
            $data['positions'] = $positions;
        }
        $data['template_element_type'] = $templateElementType;
        $data['section'] = $section;
        $data['title'] = _i('List of images');
        return view('back.section-images.index', $data);
    }

    /**
     *  Store section images
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $templateElementType = $request->template_element_type;
        $section = !is_null($request->section) ? $request->section : null;
        $validationRules['image'] = 'required';
        if ($section != 'section-3') {
            $validationRules['title'] = 'required';
        }
        if (!is_null($request->start_date) && !is_null($request->end_date)) {
            $validationRules['end_date'] = 'required|date|after:start_date';
        }
        $this->validate($request, $validationRules);

        try {
            $timezone = session('timezone');
            $startDate = !is_null($request->start_date) ? Carbon::createFromFormat('d-m-Y h:i a', $request->start_date, $timezone)->setTimezone('UTC') : null;
            $endDate = !is_null($request->end_date) ? Carbon::createFromFormat('d-m-Y h:i a', $request->end_date, $timezone)->setTimezone('UTC') : null;
            $whitelabel = Configurations::getWhitelabel();
            $image = $request->file('image');
            $file = $request->file;
            $s3Directory = Configurations::getS3Directory();
            $filePath = "$s3Directory/section-images/";
            $extension = $image->getClientOriginalExtension();
            $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
            $name = Str::slug($originalName) . time() . '.' . $extension;
            $newFilePath = "{$filePath}{$name}";
            $oldFilePath = "{$filePath}{$file}";
            Storage::put($newFilePath, file_get_contents($image->getRealPath()), 'public');
            Storage::delete($oldFilePath);
            $front = $request->file('front');
            if(!is_null($front)){
                $fileFront = $request->file;
                $filePath = "$s3Directory/section-images/";
                $extensionFront = $front->getClientOriginalExtension();
                $originalNameFront = str_replace(".$extensionFront", '', $front->getClientOriginalName());
                $nameFront = Str::slug($originalNameFront) . time() . '.' . $extensionFront;
                $newFilePath = "{$filePath}{$nameFront}";
                $oldFilePath = "{$filePath}{$fileFront}";
                Storage::put($newFilePath, file_get_contents($front->getRealPath()), 'public');
                Storage::delete($oldFilePath);
            }else{
                $nameFront = null;
            }
            $imageData = [
                'title' => $request->title,
                'button' => $request->button,
                'description' => $request->description,
                'element_type_id' => $templateElementType,
                'url' => $request->url,
                'language' => '*',
                'currency_iso' => '*',
                'mobile' => '*',
                'status' => $request->status,
                'section' => $section,
                'image' => $name,
                'front' => $nameFront,
                'whitelabel_id' => $whitelabel,
                'start_date' => $startDate,
                'end_date' => $endDate
            ];
            $this->sectionImagesRepo->store($imageData);

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'image_data' => $imageData
            ];

            //Audits::store($user_id, AuditTypes::$image_creation, Configurations::getWhitelabel(), $auditData);
            $data = [
                'title' => _i('Image uploaded'),
                'message' => _i('The image data was uploaded correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Update section images
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $templateElementType = $request->template_element_type;
        $section = !is_null($request->section) ? $request->section : null;
        $position = $request->position;
        $user_id = auth()->user()->id;
        switch ($templateElementType) {
            case TemplateElementTypes::$home:
            {
                $configuration = Configurations::getHome();
                break;
            }
            case TemplateElementTypes::$register_form:
            {
                $register = Configurations::getRegisterView();
                $configuration = Configurations::getTemplateElement($register);
                break;
            }
            case TemplateElementTypes::$login_form:
            {
                $login = Configurations::getLoginView();
                $configuration = Configurations::getTemplateElement($login);
                break;
            }
            case TemplateElementTypes::$header:
            {
                $header = Configurations::getHeader();
                $configuration = Configurations::getTemplateElement($header);
                break;
            }
        }

        if (is_null($section)) {
            $props = $configuration->data->section_images->props;
        } else {
            $props = $configuration->$section->section_images->props;
        }


        $validationRules['image'] = 'required';
        if ($position != ImagesPositions::$logo_light && $position != ImagesPositions::$logo_dark && $position != ImagesPositions::$favicon && $position != ImagesPositions::$mobile_light && $position != ImagesPositions::$mobile_dark) {
            if ($section != 'section-3') {
                $validationRules['title'] = 'required';
            }
        }

        if ($props->description) {
            if ($section != 'section-3') {
                $validationRules['description'] = 'required';
            }
        }

        if ($props->button) {
            $validationRules['button'] = 'required';
        }

        if (!is_null($request->start_date) && !is_null($request->end_date)) {
            $validationRules['end_date'] = 'required|date|after:start_date';
        }
        $this->validate($request, $validationRules);

        try {
            $whitelabel = Configurations::getWhitelabel();
            $image = $request->file('image');
            $file = $request->file;
            $s3Directory = Configurations::getS3Directory();
            $timezone = session('timezone');
            $startDate = !is_null($request->start_date) ? Carbon::createFromFormat('d-m-Y h:i a', $request->start_date, $timezone)->setTimezone('UTC') : null;
            $endDate = !is_null($request->end_date) ? Carbon::createFromFormat('d-m-Y h:i a', $request->end_date, $timezone)->setTimezone('UTC') : null;

            if ($position == ImagesPositions::$logo_light || $position == ImagesPositions::$logo_dark || $position == ImagesPositions::$favicon || $position == ImagesPositions::$mobile_light || $position == ImagesPositions::$mobile_dark){
                $filePath = "$s3Directory/commons/";

            } else {
                $filePath = "$s3Directory/section-images/";
                $imageData = [
                    'title' => $request->title,
                    'button' => $request->button,
                    'description' => $request->description,
                    'element_type_id' => $templateElementType,
                    'position' => $position,
                    'url' => $request->url,
                    'language' => '*',
                    'currency_iso' => '*',
                    'mobile' => '*',
                    'status' => $request->status,
                    'section' => $section,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ];
            }

            if (!is_null($image)) {
                $extension = $image->getClientOriginalExtension();
                $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
                $name = Str::slug($originalName) . time() . '.' . $extension;
                $newFilePath = "{$filePath}{$name}";
                $oldFilePath = "{$filePath}{$file}";
                Storage::put($newFilePath, file_get_contents($image->getRealPath()), 'public');
                Storage::delete($oldFilePath);
                $imageData['image'] = $name;
                $file = $name;
                $front = $request->file('front');
                if(!is_null($front)){
                    $fileFront = $request->file;
                    $extensionFront = $front->getClientOriginalExtension();
                    $originalNameFront = str_replace(".$extensionFront", '', $front->getClientOriginalName());
                    $nameFront = Str::slug($originalNameFront) . time() . '.' . $extensionFront;
                    $newFilePath = "{$filePath}{$nameFront}";
                    $oldFilePath = "{$filePath}{$fileFront}";
                    Storage::put($newFilePath, file_get_contents($front->getRealPath()), 'public');
                    Storage::delete($oldFilePath);
                    $imageData['front'] = $nameFront;
                    $fileFront = $nameFront;
                }
            }

            if ($position == ImagesPositions::$logo_light || $position == ImagesPositions::$logo_dark || $position == ImagesPositions::$favicon || $position ==  ImagesPositions::$mobile_light || $position ==  ImagesPositions::$mobile_dark) {
                $configuration = Configurations::getComponentConfiguration($whitelabel, Components::$design);
                switch ($position) {
                    case ImagesPositions::$logo_light:
                    {
                        $configuration->logo->img_light = $name;
                        break;
                    }
                    case ImagesPositions::$logo_dark:
                    {
                        $configuration->logo->img_dark = $name;
                        break;
                    }
                    case ImagesPositions::$mobile_light:
                    {
                        $configuration->logo->img_mobile_light = $name;
                        break;
                    }
                    case ImagesPositions::$mobile_dark:
                    {
                        $configuration->logo->img_mobile_dark = $name;
                        break;
                    }
                    case ImagesPositions::$favicon:
                    {
                        $configuration->favicon = $name;
                        break;
                    }
                }
                Configurations::update($whitelabel, Components::$design, $configuration);

            } else {
                if (!is_null($id)) {
                    $this->sectionImagesRepo->update($id, $imageData);
                    $auditData = [
                        'ip' => Utils::userIp($request),
                        'user_id' => $user_id,
                        'update_method' => 'id',
                        'username' => auth()->user()->username,
                        'image_data' => [
                            'id' => $id,
                            'data' =>$imageData
                        ]
                    ];

                } else {
                    $this->sectionImagesRepo->updateBySection($whitelabel, $position, $templateElementType, $section, $imageData);
                    $auditData = [
                        'ip' => Utils::userIp($request),
                        'user_id' => $user_id,
                        'update_method' => 'section',
                        'username' => auth()->user()->username,
                        'image_data' => [
                            'whitelabel' => $whitelabel,
                            'position' => $position,
                            'template_element_type' => $templateElementType,
                            'section' => $section,
                            'data' =>$imageData
                        ]
                    ];
                }

            }

            //Audits::store($user_id, AuditTypes::$image_modification, Configurations::getWhitelabel(), $auditData);

            $data = [
                'title' => _i('Image updated'),
                'message' => _i('The image data was updated correctly'),
                'close' => _i('Close'),
                'file' => $file
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }
}
