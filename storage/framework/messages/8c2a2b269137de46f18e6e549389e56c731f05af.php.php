<?php


namespace App\Http\Controllers;

use App\Core\Collections\SlidersCollection;
use App\Core\Repositories\SlidersRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class SlidersController
 *
 * This class allows to manage sliders requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 * @author  Genesis Perez
 */
class FeaturedSlidersController extends Controller
{
    /**
     * SlidersRepo
     *
     * @var SlidersRepo
     */
    private $slidersRepo;

    /**
     * SlidersCollection
     *
     * @var SlidersCollection
     */
    private $slidersCollection;

    /**
     * File path
     *
     * @var string
     */
    private $filePath;

    /**
     * SlidersController constructor
     *
     * @param SlidersRepo $slidersRepo
     * @param SlidersCollection $slidersCollection
     */
    public function __construct(SlidersRepo $slidersRepo, SlidersCollection $slidersCollection)
    {
        $this->slidersRepo = $slidersRepo;
        $this->slidersCollection = $slidersCollection;
        $s3Directory = Configurations::getS3Directory();
        $this->filePath = "$s3Directory/sliders/static/";
    }

    /**
     * Get all featured sliders
     *
     * @param int $templateElementType Template element type ID
     * @param string $section Section String
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all($templateElementType)
    {
        try {
            $sliders = $this->slidersRepo->allByElementType($templateElementType);
            $this->slidersCollection->formatAll($sliders);
            $data = [
                'sliders' => $sliders
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
            $sliders = $this->slidersRepo->allByElementType($templateElementType);
            $count = count($sliders);
            $data['order'] = $count;
            $data['template_element_type'] = $templateElementType;
            $data['section'] = $section;
            $data['title'] = _i('Upload new slider');
            return view('back.sliders.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Delete featured sliders
     *
     * @param int $id Slider ID
     * @param string $file File name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id, $file)
    {
        try {
            $path = "{$this->filePath}{$file}";
            Storage::delete($path);
            $this->slidersRepo->delete($id);
            $data = [
                'title' => _i('Slider removed'),
                'message' => _i('The slider was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id, 'file' => $file]);
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
        $slider = $this->slidersRepo->find($id);
        $sliders = [];
        if (!is_null($slider)) {
            try {
                $sliders = $this->slidersRepo->allByElementType($slider->element_type_id);
                $count = count($sliders);
                $data['order'] = $count;
                $this->slidersCollection->formatDetails($slider);
                $data['slider'] = $slider;
                $data['title'] = _i('Update slider');
                return view('back.sliders.edit', $data);

            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'slider' => $id]);
                abort(500);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Show featured sliders list
     *
     * @param int $templateElementType Template element type ID
     * @param string $section Section String
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($templateElementType, $section = null)
    {
        $data['template_element_type'] = $templateElementType;
        $data['section'] = $section;
        $data['title'] = _i('List of sliders');
        return view('back.sliders.index', $data);
    }

    /**
     * Store featured slider
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'image' => 'required',
            'device' => 'required',
            'language' => 'required',
            'currency' => 'required'
        ];
        if (!is_null($request->order)) {
            $rules['order'] = 'required|numeric|min:1|digits_between:1,10';
        }
        $this->validate($request, $rules);
        try {
            $image = $request->file('image');
            $startDate = !is_null($request->start_date) ? Utils::startOfDayUtc($request->start_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;
            $endDate = !is_null($request->end_date) ? Utils::endOfDayUtc($request->end_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;
            $extension = $image->getClientOriginalExtension();
            $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
            $name = Str::slug($originalName) . time() . '.' . $extension;
            $path = "{$this->filePath}{$name}";
            Storage::put($path, file_get_contents($image->getRealPath()), 'public');
            $section = !is_null($request->section) ? $request->section : null;
            if(!is_null($request->order)){
                $order= $request->order;
                if(!is_null( $request->template_element_type)) {
                    $usedOrder = $this->slidersRepo->findOrderAndTemplateElementType($order,  $request->template_element_type);
                    if (!is_null($usedOrder)) {
                        $data = [
                            'title' => _i('Used order'),
                            'message' => _i('The order data already exists', $usedOrder),
                            'close' => _i('Close')
                        ];
                        return Utils::errorResponse(Codes::$forbidden, $data);
                    }
                }else{

                }
            }else{
                $order= 0;
            }
            $sliderData = [
                'whitelabel_id' => Configurations::getWhitelabel(),
                'image' => $name,
                'url' => $request->url,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'order' => $order,
                'element_type_id' => $request->template_element_type,
                'mobile' => $request->device,
                'section' => $section
            ];
            $this->slidersRepo->store($sliderData);
            $data = [
                'title' => _i('Slider loaded'),
                'message' => _i('The slider was loaded correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update featured sliders
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $rules = [
            'image' => 'required',
            'device' => 'required',
            'language' => 'required',
            'currency' => 'required'
        ];
        if (!is_null($request->order)) {
            $rules['order'] = 'required|numeric|min:0|digits_between:0,10';
        }
        $this->validate($request, $rules);
        try {
            $id = $request->id;
            $file = $request->file;
            $image = $request->file('image');
            $startDate = !is_null($request->start_date) ? Utils::startOfDayUtc($request->start_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;
            $endDate = !is_null($request->end_date) ? Utils::endOfDayUtc($request->end_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;
            $section = !is_null($request->section) ? $request->section : null;

            if(!is_null($request->order)){
                $order= $request->order;
                $slider = $this->slidersRepo->find($id);
                if($order !== $slider->order){
                        $usedOrder = $this->slidersRepo->findOrderAndTemplateElementType($order, $slider->element_type_id);
                        if (!is_null($usedOrder)) {
                            $data = [
                                'title' => _i('Used order'),
                                'message' => _i('The order data already exists', $usedOrder),
                                'close' => _i('Close')
                            ];
                            return Utils::errorResponse(Codes::$forbidden, $data);
                        }
                }else{

                }
            }else{
                $order= 0;
            }
            $sliderData = [
                'url' => $request->url,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'element_type_id' => $request->template_element_type,
                'mobile' => $request->device,
                'section' => $section,
                'order' => $order,
            ];

            if (!is_null($image)) {
                $extension = $image->getClientOriginalExtension();
                $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
                $name = Str::slug($originalName) . time() . '.' . $extension;
                $newFilePath = "{$this->filePath}{$name}";
                $oldFilePath = "{$this->filePath}{$file}";
                Storage::put($newFilePath, file_get_contents($image->getRealPath()), 'public');
                Storage::delete($oldFilePath);
                $sliderData['image'] = $name;
                $file = $name;
            }

            $this->slidersRepo->update($id, $sliderData);
            $data = [
                'title' => _i('Slider updated'),
                'message' => _i('The slider data was updated correctly'),
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
