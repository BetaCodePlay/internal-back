<?php

namespace App\Http\Controllers;

use App\Core\Collections\SectionImagesCollection;
use App\Core\Enums\Sections;
use Dotworkers\Configurations\Enums\TemplateElementTypes;
use App\Core\Repositories\SectionImagesRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FeaturedImagesController extends Controller
{
    /**
     * SectionImagesRepo
     *
     * @var SectionImagesRepo
     */
    private $sectionImagesRepo;

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
    public function __construct(SectionImagesRepo $sectionImagesRepo, SectionImagesCollection $sectionImagesCollection)
    {
        $this->sectionImagesRepo = $sectionImagesRepo;
        $this->sectionImagesCollection = $sectionImagesCollection;
    }

    /**
     * Get all featured images
     *
     * @param int $templateElementType Template element type ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all($templateElementType)
    {
        try {
            $images =  $this->sectionImagesRepo->allByElementType($templateElementType);
            $view = Configurations::getFeaturedLobby()->view;
            $configuration = Configurations::getTemplateElement($view);
            $positions = $configuration->data->section_images;
            $imagesData = $this->sectionImagesCollection->formatAllFeatured($images, $positions);
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($templateElementType)
    {
        try {
            if ($templateElementType == TemplateElementTypes::$lobby_featured || $templateElementType == TemplateElementTypes::$lobby_recommended
                || $templateElementType == TemplateElementTypes::$lobby_info || $templateElementType == TemplateElementTypes::$lobby_notifications) {
                $view = Configurations::getFeaturedLobby()->view;
                $configuration = Configurations::getTemplateElement($view);
                $image = new \stdClass();
                $image->size = "{$configuration->data->section_images->width}x{$configuration->data->section_images->height}";
                $data['image'] = $image;
            }
            $data['template_element_type'] = $templateElementType;
            $data['title'] = _i('Upload image');
            return view('back.featured.section-images.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show edit view
     *
     * @param int $id Section Images ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $view = Configurations::getFeaturedLobby()->view;
            $configuration = Configurations::getTemplateElement($view);
            $position = $configuration->data->section_images;
            $props = $configuration->data->section_images->props;
            $image = $this->sectionImagesRepo->find($id);
            $imageData = $this->sectionImagesCollection->formatByFeatured($image, $position);
            $data['template_element_type'] = $image->element_type_id;
            $data['image'] = $imageData;
            $data['props'] = $props;
            $data['title'] = _i('Update image');
            return view('back.featured.section-images.edit', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show featured images list
     *
     * @param int $templateElementType Template element type ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($templateElementType)
    {
        \Log::info(__METHOD__, ['$templateElementType' => $templateElementType]);
        $data['template_element_type'] = $templateElementType;
        $data['title'] = _i('List of images');
        return view('back.featured.section-images.index', $data);
    }

    /**
     *  Store featured images
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $templateElementType = $request->template_element_type;
        $this->validate($request, [
            'image' => 'required',
            'title' => 'required'
        ]);

        try {
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
                'section' => null,
                'image' => $name,
                'whitelabel_id' => $whitelabel
            ];

            $this->sectionImagesRepo->store($imageData);

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
     *  Update featured images
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $templateElementType = $request->template_element_type;
        $validationRules['image'] = 'required';
        $validationRules['title'] = 'required';
        $view = Configurations::getFeaturedLobby()->view;
        $configuration = Configurations::getTemplateElement($view);
        $props = $configuration->data->section_images->props;
        if ($props->description) {
            $validationRules['description'] = 'required';
        }

        if ($props->button) {
            $validationRules['button'] = 'required';
        }
        $this->validate($request, $validationRules);

        try {
            $whitelabel = Configurations::getWhitelabel();
            $image = $request->file('image');
            $file = $request->file;
            $s3Directory = Configurations::getS3Directory();
            $id = $request->image_id;
            $filePath = "$s3Directory/section-images/";
            $imageData = [
                'title' => $request->title,
                'button' => $request->button,
                'description' => $request->description,
                'element_type_id' => $templateElementType,
                'position' => null,
                'url' => $request->url,
                'language' => '*',
                'currency_iso' => '*',
                'mobile' => '*',
                'status' => $request->status,
                'section' => null
            ];


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
            }
            $this->sectionImagesRepo->update($id, $imageData);

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
