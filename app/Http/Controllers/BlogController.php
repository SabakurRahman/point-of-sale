<?php

namespace App\Http\Controllers;

use App\Managers\CommonResponseManager;
use App\Managers\ImageUploadManager;
use App\Models\Blog;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Seo;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    private Blog $blog;
    private CommonResponseManager $commonResponse;

    public function __construct()
    {
        $this->commonResponse = new CommonResponseManager();
        $this->blog           = new Blog();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $this->commonResponse->success = true;
            $this->commonResponse->data = $this->blog->all();
            $this->commonResponse->message = 'Blog Fetched Successfully';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
        catch ( \Throwable $e){
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {
        try {
            $blog = $this->blog->createBlog($request);
            $this->commonResponse->success = true;
            $this->commonResponse->data = $blog;
            $this->commonResponse->message = 'Blog Created Successfully';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
        catch ( \Throwable $e){
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        try {
            $this->commonResponse->success = true;
            $this->commonResponse->data = $blog;
            $this->commonResponse->message = 'Blog Fetched Successfully';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
        catch ( \Throwable $e){
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        //
        try {
            $blog = $this->blog->updateBlog($request,$blog);
            $this->commonResponse->success = true;
            $this->commonResponse->data = $blog;
            $this->commonResponse->message = 'Blog Updated Successfully';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
        catch ( \Throwable $e){
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        //
        try {
            DB::beginTransaction();

            if ($blog->photo) {
                ImageUploadManager::deletePhoto(Blog::BLOG_PHOTO_UPLOAD_PATH, $blog->photo);
            }
            $seo = $blog->seos;
            if ($seo->og_image) {
              ImageUploadManager::deletePhoto(Seo::SEO_PHOTO_UPLOAD_PATH, $seo->og_image);
            }

            $blog->seos()->delete();
            $blog->delete();

            DB::commit();

            $this->commonResponse->success = true;
            $this->commonResponse->message = 'Blog Deleted Successfully';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
    }
}
