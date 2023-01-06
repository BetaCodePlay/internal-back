<?php

namespace App\Http\Controllers;

use App\Core\Collections\CoreCollection;
use App\SectionModals\Collections\SectionModalsCollection;
use App\SectionModals\Repositories\SectionModalsRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class SectionModalsController
 *
 * This class allows to manage section modals requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 */
class SectionModalsController extends Controller
{
    /**
     * SectionModalsRepo
     *
     * @var SectionModalsRepo
     */
    private $sectionModalsRepo;

    /**
     * SectionModalsCollection
     *
     * @var SectionModalsCollection
     */
    private $sectionModalsCollection;

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
     * PostsController constructor
     *
     * @param SectionModalsRepo $sectionModalsRepo
     * @param SectionModalsCollection $sectionModalsCollection
     * @param CoreCollection $coreCollection
     */
    public function __construct(SectionModalsRepo $sectionModalsRepo, SectionModalsCollection $sectionModalsCollection, CoreCollection $coreCollection)
    {
        $this->sectionModalsRepo = $sectionModalsRepo;
        $this->sectionModalsCollection = $sectionModalsCollection;
        $this->coreCollection = $coreCollection;
        $s3Directory = Configurations::getS3Directory();
        $this->filePath = "$s3Directory/section-modals/";
    }

    /**
     * Get all modals
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all()
    {
        try {
            $menu = Configurations::getMenu();
            $modals = $this->sectionModalsRepo->all();
            $this->sectionModalsCollection->formatAll($modals, $menu);
            $data = [
                'modals' => $modals
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show modal view
     *
     * @param int $id Post ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $modal = $this->sectionModalsRepo->find($id);

        if (!is_null($modal)) {
            try {
                $this->sectionModalsCollection->formatDetails($modal);
                $menu = Configurations::getMenu();
                $data['menu'] = $this->coreCollection->formatWhitelabelMenu($menu);
                $data['modal'] = $modal;
                $data['title'] = _i('Update popup');
                return view('back.section-modals.edit', $data);

            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'slider' => $id]);
                abort(500);
            }
        } else {
            abort(404);
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
            $menu = Configurations::getMenu();
            $data['menu'] = $this->coreCollection->formatWhitelabelMenu($menu);
            $data['title'] = _i('New popup');
            return view('back.section-modals.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Delete modal
     *
     * @param int $id Post ID
     * @param string $file File name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id, $file)
    {
        try {
            $path = "{$this->filePath}{$file}";
            Storage::delete($path);
            $this->sectionModalsRepo->delete($id);
            $data = [
                'title' => _i('Popup removed'),
                'message' => _i('The popup was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id, 'file' => $file]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store modal
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
            'route' => 'required',
            'one_time' => 'required',
            'scroll' => 'required',
            'language' => 'required',
            'currency' => 'required'
        ]);

        try {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
            $name = Str::slug($originalName) . time() . '.' . $extension;
            $path = "{$this->filePath}{$name}";
            Storage::put($path, file_get_contents($image->getRealPath()), 'public');

            $modalData = [
                'image' => $name,
                'route' => $request->route,
                'status' => $request->status,
                'one_time' => $request->one_time,
                'scroll' => $request->scroll,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'url' => $request->url,
                'whitelabel_id' => Configurations::getWhitelabel(),
            ];
            $this->sectionModalsRepo->store($modalData);
            $data = [
                'title' => _i('Popup published'),
                'message' => _i('The popup was published correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show modals list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = _i('List of popups');
        return view('back.section-modals.index', $data);
    }

    /**
     * Update modal
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
            'route' => 'required',
            'one_time' => 'required',
            'scroll' => 'required',
            'language' => 'required',
            'currency' => 'required'
        ]);

        try {
            $id = $request->id;
            $file = $request->file;
            $image = $request->file('image');

            $modalData = [
                'route' => $request->route,
                'status' => $request->status,
                'one_time' => $request->one_time,
                'scroll' => $request->scroll,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'url' => $request->url
            ];

            if (!is_null($image)) {
                $extension = $image->getClientOriginalExtension();
                $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
                $name = Str::slug($originalName) . time() . '.' . $extension;
                $newFilePath = "{$this->filePath}{$name}";
                $oldFilePath = "{$this->filePath}{$file}";
                Storage::put($newFilePath, file_get_contents($image->getRealPath()), 'public');
                Storage::delete($oldFilePath);
                $modalData['image'] = $name;
                $file = $name;
            }

            $this->sectionModalsRepo->update($id, $modalData);
            $data = [
                'title' => _i('Popup updated'),
                'message' => _i('The popup data was updated correctly'),
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
