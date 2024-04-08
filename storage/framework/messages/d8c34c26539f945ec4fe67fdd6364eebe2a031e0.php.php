<?php

namespace App\Http\Controllers;

use App\Posts\Collections\PostsCollection;
use App\Posts\Repositories\PostsCategoriesRepo;
use App\Posts\Repositories\PostsRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Audits\Repositories\AuditsRepo;
use App\Audits\Enums\AuditTypes;
use Dotworkers\Audits\Audits;

/**
 * Class PostsController
 *
 * This class allows to manage posts requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 */
class PostsController extends Controller
{
    /**
     * PostsRepo
     *
     * @var PostsRepo
     */
    private $postsRepo;

      /**
     * @var AuditsRepo
     */
    private $auditsRepo;

    /**
     * PostsCollection
     *
     * @var PostsCollection
     */
    private $postsCollection;

    /**
     * File path
     *
     * @var string
     */
    private $filePath;

    /**
     * PostsCategoriesRepo
     *
     * @var $postsCategoriesRepo
     */
    private $postsCategoriesRepo;

    /**
     * PostsController constructor
     *
     * @param PostsRepo $postsRepo
     * @param PostsCollection $postsCollection
     */
    public function __construct(PostsRepo $postsRepo, PostsCollection $postsCollection, PostsCategoriesRepo $postsCategoriesRepo, AuditsRepo $auditsRepo)
    {
        $this->postsRepo = $postsRepo;
        $this->postsCollection = $postsCollection;
        $s3Directory = Configurations::getS3Directory();
        $this->filePath = "$s3Directory/posts/";
        $this->postsCategoriesRepo = $postsCategoriesRepo;
    }

    /**
     * Get all posts
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all()
    {
        try {
            $posts = $this->postsRepo->all();
            $this->postsCollection->formatAll($posts);
            $data = [
                'posts' => $posts
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show post view
     *
     * @param int $id Post ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $post = $this->postsRepo->find($id);

        if (!is_null($post)) {
            try {
                $this->postsCollection->formatDetails($post);
                $postCategories = $this->postsCategoriesRepo->all();
                $data['post'] = $post;
                $data['post_categories'] = $postCategories;
                $data['title'] = _i('Update promotion');
                return view('back.posts.edit', $data);

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
            $postCategories = $this->postsCategoriesRepo->all();
            $data['post_categories'] = $postCategories;
            $data['title'] = _i('New promotion');
            return view('back.posts.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Delete post
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
            $this->postsRepo->delete($id);
            $data = [
                'title' => _i('Promotion removed'),
                'message' => _i('The promotion was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id, 'file' => $file]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store post
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
            'language' => 'required',
            'title' => 'required',
            'content' => 'required',
            'currency' => 'required',
            'category' => 'required'
        ]);

        try {
            $image = $request->file('image');
            $mainImage = $request->file('main_image');
            $title = $request->title;
            $slug = Str::slug($title);
            $startDate = !is_null($request->start_date) ? Utils::startOfDayUtc($request->start_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;
            $endDate = !is_null($request->end_date) ? Utils::endOfDayUtc($request->end_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;
            $extension = $image->getClientOriginalExtension();
            $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
            $name = Str::slug($originalName) . time() . '.' . $extension;
            $path = "{$this->filePath}{$name}";
            Storage::put($path, file_get_contents($image->getRealPath()), 'public');
            if (!is_null($mainImage)){
                $mainExtension = $mainImage->getClientOriginalExtension();
                $mainOriginalName = str_replace(".$mainExtension", '', $mainImage->getClientOriginalName());
                $mainName = Str::slug($mainOriginalName) . time() . '.' . $mainExtension;
                $mainPath = "{$this->filePath}{$mainName}";
                Storage::put($mainPath, file_get_contents($mainImage->getRealPath()), 'public');
            } else {
                $mainName = null;
            }

            $postData = [
                'whitelabel_id' => Configurations::getWhitelabel(),
                'image' => $name,
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->input('content'),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'main_image' => $mainName,
                'post_categories_id' => $request->category,
            ];
            $this->postsRepo->store($postData);

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'post_data' => $postData
            ];

            //Audits::store($user_id, AuditTypes::$posts_creation, Configurations::getWhitelabel(), $auditData);

            $data = [
                'title' => _i('Promotion published'),
                'message' => _i('The promotion was published correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show posts list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = _i('List of promotions');
        return view('back.posts.index', $data);
    }

    /**
     * Update post
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
            'title' => 'required',
            'content' => 'required',
            'language' => 'required',
            'currency' => 'required',
            'category' => 'required'
        ]);

        try {
            $id = $request->id;
            $file = $request->file;
            $mainFile = $request->main_file;
            $image = $request->file('image');
            $mainImage = $request->file('main_image');
            $startDate = !is_null($request->start_date) ? Utils::startOfDayUtc($request->start_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;
            $endDate = !is_null($request->end_date) ? Utils::endOfDayUtc($request->end_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;

            $postData = [
                'content' => $request->input('content'),
                'title' => $request->title,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'post_categories_id' => $request->category
            ];

            if (!is_null($image)) {
                $extension = $image->getClientOriginalExtension();
                $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
                $name = Str::slug($originalName) . time() . '.' . $extension;
                $newFilePath = "{$this->filePath}{$name}";
                $oldFilePath = "{$this->filePath}{$file}";
                Storage::put($newFilePath, file_get_contents($image->getRealPath()), 'public');
                Storage::delete($oldFilePath);
                $postData['image'] = $name;
                $file = $name;
            }

            if (!is_null($mainImage)) {
                $mainExtension = $mainImage->getClientOriginalExtension();
                $mainOriginalName = str_replace(".$mainExtension", '', $mainImage->getClientOriginalName());
                $mainName = Str::slug($mainOriginalName) . time() . '.' . $mainExtension;
                $mainNewFilePath = "{$this->filePath}{$mainName}";
                $mainOldFilePath = "{$this->filePath}{$mainFile}";
                Storage::put($mainNewFilePath, file_get_contents($mainImage->getRealPath()), 'public');
                Storage::delete($mainOldFilePath);
                $postData['main_image'] = $mainName;
                $mainFile = $mainName;
            }

            $this->postsRepo->update($id, $postData);

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'post_data' => [
                    'id' => $id,
                    'data' => $postData
                ],
            ];

            //Audits::store($user_id, AuditTypes::$posts_modification, Configurations::getWhitelabel(), $auditData);

            $data = [
                'title' => _i('Promotion updated'),
                'message' => _i('The promotion data was updated correctly'),
                'close' => _i('Close'),
                'file' => $file,
                'main' => $mainFile
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }
}
