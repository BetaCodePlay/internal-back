<?php

namespace App\Http\Controllers;

use App\CRM\Collections\SlidersCollection;
use App\CRM\Repositories\SlidersRepo;
use App\Core\Collections\CoreCollection;
use App\Core\Core;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Boolean;
use App\Audits\Repositories\AuditsRepo;
use App\Audits\Enums\AuditTypes;
use Dotworkers\Audits\Audits;

/**
 * Class SlidersController
 *
 * This class allows to manage sliders requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 * @author Genesis Perez
 */
class SlidersController extends Controller
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
     * CoreCollection
     *
     * @var CoreCollection
     */
    private $coreCollection;

    /**
     * File path
     *
     * @var string
     */
    private $filePath;


    /**
     * @var AuditsRepo
     */
    private $auditsRepo;

    /**
     * SlidersController constructor
     *
     * @param SlidersRepo $slidersRepo
     * @param SlidersCollection $slidersCollection
     * @param CoreCollection $coreCollection
     * @param AuditsRepo $auditsRepo
     */
    public function __construct(SlidersRepo $slidersRepo, SlidersCollection $slidersCollection, CoreCollection $coreCollection, AuditsRepo $auditsRepo)
    {
        $this->slidersRepo = $slidersRepo;
        $this->slidersCollection = $slidersCollection;
        $this->coreCollection = $coreCollection;
        $s3Directory = Configurations::getS3Directory();
        $this->filePath = "$s3Directory/sliders/static/";
    }

    /**
     * Get all sliders
     *
     * @param Request $request
     * @param null|int $templateElementType Template element type ID
     * @param null|string $section Section String
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all(Request $request, $templateElementType = null, $section = null)
    {
        try {
            $device = $request->device;
            $language = $request->language;
            $status = $request->status;
            $currency = $request->currency;
            $route = $request->routes;

            if (!is_null($templateElementType)) {
                if (empty($device) && empty($language) && empty($currency) && empty($status)) {
                    $sliders = $this->slidersRepo->allByElementTypeAndSection($templateElementType, $section);
                } else {
                    $device = explode(',', $device);
                    $device = array_diff($device, array("", 0, null));
                    $language = explode(',', $language);
                    $language = array_diff($language, array("", 0, null));
                    $currency = explode(',', $currency);
                    $currency = array_diff($currency, array("", 0, null));
                    $sliders = $this->slidersRepo->searchByElementTypeAndSection($templateElementType, $section, $device, $language, $currency, $status);
                }

            } else {
                if (empty($device) && empty($language) && empty($currency) && empty($status) && empty($route)) {
                    $sliders = $this->slidersRepo->allWithRoutes();
                } else {
                    $device = explode(',', $device);
                    $device = array_diff($device, array("", 0, null));
                    $language = explode(',', $language);
                    $language = array_diff($language, array("", 0, null));
                    $currency = explode(',', $currency);
                    $currency = array_diff($currency, array("", 0, null));
                    $route = explode(',', $route);
                    $route = array_diff($route, array("", 0, null));
                    $sliders = $this->slidersRepo->searchByElementTypeAndRoute($device, $language, $currency, $status, $route);
                }

            }
            $menu = Configurations::getMenu();
            $this->slidersCollection->formatAll($sliders, $menu);
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
     * @param null|int $templateElementType Template element type ID
     * @param null|string $section Section String
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($templateElementType = null, $section = null)
    {
        try {
            if (is_null($templateElementType)) {
                $menu = Configurations::getMenu();
                $data['menu'] = $this->coreCollection->formatWhitelabelMenu($menu);
                $sliders = $this->slidersRepo->allWithRoutes();
            } else {
                $sliders = $this->slidersRepo->allByElementTypeAndSection($templateElementType, $section);
            }
            $count = count($sliders);
            $data['template_element_type'] = $templateElementType;
            $data['section'] = $section;
            $data['order'] = $count;
            $data['title'] = _i('Upload new slider');
            return view('back.sliders.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Delete sliders
     *
     * @param int $id Slider ID
     * @param string $file File name
     * @param string $front File name
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
        if (!is_null($slider)) {
            try {

                if (!is_null($slider->route)) {
                    $menu = Configurations::getMenu();
                    $data['menu'] = $this->coreCollection->formatWhitelabelMenu($menu);
                    $sliders = $this->slidersRepo->allWithRoutes();
                } elseif (!is_null($slider->section)) {
                    $sliders = $this->slidersRepo->allByElementTypeAndSection($slider->element_type_id, $slider->section);
                } else {
                    $sliders = [];
                }
                $count = count($sliders);
                $this->slidersCollection->formatDetails($slider);
                $data['slider'] = $slider;
                $data['order'] = $count;
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
     * Show sliders list
     *
     * @param null|int $templateElementType Template element type ID
     * @param null|string $section Section String
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($templateElementType = null, $section = null)
    {
        if (is_null($templateElementType)) {
            $menu = Configurations::getMenu();
            $data['menu'] = $this->coreCollection->formatWhitelabelMenu($menu);
        }
        $data['template_element_type'] = $templateElementType;
        $data['section'] = $section;
        $data['title'] = _i('List of sliders');
        return view('back.sliders.index', $data);
    }

    /**
     * Store slider
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        \Log::notice(__METHOD__, ['request' => $request->all()]);
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
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
            $front = $request->file('front');
            if(!is_null($front)){
                $extensionFront = $front->getClientOriginalExtension();
                $originalNameFront = str_replace(".$extensionFront", '', $front->getClientOriginalName());
            }
            $timezone = session('timezone');
            $startDate = !is_null($request->start_date) ? Carbon::createFromFormat('d-m-Y h:i a', $request->start_date, $timezone)->setTimezone('UTC') : null;
            $endDate = !is_null($request->end_date) ? Carbon::createFromFormat('d-m-Y h:i a', $request->end_date, $timezone)->setTimezone('UTC') : null;
            $section = !is_null($request->section) ? $request->section : null;
            $routes = !is_null($request->route) ? $request->route : null;
            if (!is_null($request->order)) {
                $order = $request->order;
                if ($order > 0) {
                    if (!is_null($routes)) {
                        foreach ($routes as $route) {
                            $usedOrder = $this->slidersRepo->findOrderAndRoute($order, $route, $request->device, $request->language, $request->currency, $request->status);
                            if (!is_null($usedOrder)) {
                                $data = [
                                    'title' => _i('Used order'),
                                    'message' => _i('The order data already exists', $order),
                                    'close' => _i('Close')
                                ];
                                return Utils::errorResponse(Codes::$forbidden, $data);
                            }
                        }
                    } elseif (!is_null($section)) {
                        $usedOrder = $this->slidersRepo->findOrderAndSection($order, $section, $request->device, $request->language, $request->currency, $request->status);
                        if (!is_null($usedOrder)) {
                            $data = [
                                'title' => _i('Used order'),
                                'message' => _i('The order data already exists', $order),
                                'close' => _i('Close')
                            ];
                            return Utils::errorResponse(Codes::$forbidden, $data);
                        }
                    }
                }
            } else {
                $order = 0;
            }

            $sliderData = [
                'whitelabel_id' => Configurations::getWhitelabel(),
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

            if (!is_null($routes)) {
                foreach ($routes as $route) {
                    $name = Str::slug($originalName) . time() . mt_rand(1, 100) . '.' . $extension;
                    $path = "{$this->filePath}{$name}";
                    Storage::put($path, file_get_contents($image->getRealPath()), 'public');
                    $sliderData['image'] = $name;
                    $sliderData['route'] = $route;
                    if(!is_null($front)){
                        $nameFront = Str::slug($originalNameFront) . time() . mt_rand(1, 100) . '.' . $extensionFront;
                        $path = "{$this->filePath}{$nameFront}";
                        Storage::put($path, file_get_contents($front->getRealPath()), 'public');
                        $sliderData['front'] = $nameFront;
                        $sliderData['route'] = $route;
                    }else{
                        $sliderData['front'] = null;
                    }
                    $this->slidersRepo->store($sliderData);
                }
            } else {
                $name = Str::slug($originalName) . time() . mt_rand(1, 100) . '.' . $extension;
                $path = "{$this->filePath}{$name}";
                Storage::put($path, file_get_contents($image->getRealPath()), 'public');
                $sliderData['image'] = $name;
                if(!is_null($front)){
                    $nameFront = Str::slug($originalNameFront) . time() . mt_rand(1, 100) . '.' . $extensionFront;
                    $path = "{$this->filePath}{$nameFront}";
                    Storage::put($path, file_get_contents($front->getRealPath()), 'public');
                    $sliderData['front'] = $nameFront;
                }
                $this->slidersRepo->store($sliderData);
            }
            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'slider_data' => $sliderData
            ];

            //Audits::store($user_id, AuditTypes::$slider_creation, Configurations::getWhitelabel(), $auditData);

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
     * Update sliders
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
            $fileFront = $request->file;
            $image = $request->file('image');
            /*$slider = $this->slidersRepo->find($id);*/
            $timezone = session('timezone');
            $startDate = !is_null($request->start_date) ? Carbon::createFromFormat('d-m-Y h:i a', $request->start_date, $timezone)->setTimezone('UTC') : null;
            $endDate = !is_null($request->end_date) ? Carbon::createFromFormat('d-m-Y h:i a', $request->end_date, $timezone)->setTimezone('UTC') : null;
            $section = !is_null($request->section) ? $request->section : null;
            $route = !is_null($request->route) ? $request->route : null;
            if (!is_null($request->order)) {
                $order = $request->order;
                $slider = $this->slidersRepo->find($id);
                if ($order !== $slider->order) {
                    if ($order > 0) {
                        if (!is_null($route)) {
                            $usedOrder = $this->slidersRepo->findOrderAndRoute($order, $route, $request->device, $request->language, $request->currency, $request->status);
                            if (!is_null($usedOrder)) {
                                $data = [
                                    'title' => _i('Used order'),
                                    'message' => _i('The order data already exists', $order),
                                    'close' => _i('Close')
                                ];
                                return Utils::errorResponse(Codes::$forbidden, $data);
                            }
                        } elseif (!is_null($section)) {
                            $usedOrder = $this->slidersRepo->findOrderAndSection($order, $section, $request->device, $request->language, $request->currency, $request->status);
                            if (!is_null($usedOrder)) {
                                $data = [
                                    'title' => _i('Used order'),
                                    'message' => _i('The order data already exists', $order),
                                    'close' => _i('Close')
                                ];
                                return Utils::errorResponse(Codes::$forbidden, $data);
                            }
                        } else {
                            $order = 0;
                        }
                    }
                } else {
                    if ($order > 0) {
                        if (!is_null($route)) {
                            $usedOrder = $this->slidersRepo->findOrderAndRoute($order, $route, $request->device, $request->language, $request->currency, $request->status);
                            if (!is_null($usedOrder)) {
                                if ($usedOrder->id !== $id) {
                                    $data = [
                                        'title' => _i('Used order'),
                                        'message' => _i('The order data already exists', $order),
                                        'close' => _i('Close')
                                    ];
                                    return Utils::errorResponse(Codes::$forbidden, $data);
                                }
                            }
                        } elseif (!is_null($section)) {
                            $usedOrder = $this->slidersRepo->findOrderAndSection($order, $section, $request->device, $request->language, $request->currency, $request->status);
                            if (!is_null($usedOrder)) {
                                if ($usedOrder->id !== $id) {
                                    $data = [
                                        'title' => _i('Used order'),
                                        'message' => _i('The order data already exists', $order),
                                        'close' => _i('Close')
                                    ];
                                    return Utils::errorResponse(Codes::$forbidden, $data);
                                }
                            }
                        } else {
                            $order = 0;
                        }
                    }
                }
            } else {
                $order = 0;
            }

            $sliderData = [
                'url' => $request->url,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'order' => $order,
                'status' => $request->status,
                'element_type_id' => $request->template_element_type,
                'mobile' => $request->device,
                'section' => $section,
                'route' => $route
            ];

            if (!is_null($image)) {
                $extension = $image->getClientOriginalExtension();
                $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
                $name = Str::slug($originalName) . time() . mt_rand(1, 100) . '.' . $extension;
                $newFilePath = "{$this->filePath}{$name}";
                $oldFilePath = "{$this->filePath}{$file}";
                Storage::put($newFilePath, file_get_contents($image->getRealPath()), 'public');
                Storage::delete($oldFilePath);
                $sliderData['image'] = $name;
                $file = $name;
                $front = $request->file('front');
                if(!is_null($front)) {
                    $extensionFront = $front->getClientOriginalExtension();
                    $originalNameFront = str_replace(".$extensionFront", '', $front->getClientOriginalName());
                    $nameFront = Str::slug($originalNameFront) . time() . mt_rand(1, 100) . '.' . $extensionFront;
                    $newFilePath = "{$this->filePath}{$nameFront}";
                    $oldFilePath = "{$this->filePath}{$fileFront}";
                    Storage::put($newFilePath, file_get_contents($front->getRealPath()), 'public');
                    Storage::delete($oldFilePath);
                    $sliderData['front'] = $nameFront;
                }
            }
            $this->slidersRepo->update($id, $sliderData);

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'slider_data' => $sliderData
            ];

            //Audits::store($user_id, AuditTypes::$slider_modification, Configurations::getWhitelabel(), $auditData);

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
