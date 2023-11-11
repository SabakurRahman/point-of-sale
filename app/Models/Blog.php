<?php

namespace App\Models;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Managers\ImageUploadManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Blog extends Model
{
    use HasFactory;
    protected $guarded = [];

    public const active = 1;
    public const inactive = 2;
    public const status = [
        self::active => 'active',
        self::inactive => 'inactive',
    ];

    public  const  BLOG_PHOTO_UPLOAD_PATH = 'uploads/blog_photo/';
    public  const  BLOG_PHOTO_WIDTH = 600;
    public  const  BLOG_PHOTO_HEIGHT = 600;






    public function createBlog(StoreBlogRequest $request)
    {
        $blog=self::query()->create($this->preparedBlog($request));
        $seoData =(new Seo)->prepareSeoData($request);
        $blog->seos()->create($seoData);
        return $blog;

    }

    private function preparedBlog(StoreBlogRequest $request)
    {
        $data= [
            'title'       => $request->title,
            'description' => $request->description,
            'slug'        => $request->slug,
            'user_id'     => Auth::id(),
            'status'      => $request->status ?? self::active,
        ];
        if($request->has('photo'))
        {
            $photo=(new ImageUploadManager())->file($request->photo)
                ->name('blog_photo_'.time())
                ->path(self::BLOG_PHOTO_UPLOAD_PATH)
                ->height(self::BLOG_PHOTO_HEIGHT)
                ->width(self::BLOG_PHOTO_WIDTH)
                ->upload();
        }
        $data['photo'] =  $photo ?? null;
        return $data;
    }


    public function updateBlog(UpdateBlogRequest $request, Blog $blog)
    {
        $blog->update($this->updateBlogDta($request, $blog));
        $seoData =(new Seo)->updateSeoData($request, $blog->seos);
        $seo=$blog->seos();
        $seo->update($seoData);
        return $blog;
    }

    private function updateBlogDta(UpdateBlogRequest $request, Blog $blog)
    {
      $data= [
            'title'       => $request->title ?? $blog->title,
            'description' => $request->description ??  $blog->description,
            'slug'        => $request->slug ??  $blog->slug,
            'user_id'     => Auth::id(),
            'status'      => $request->status ?? $blog->status,
        ];

        $photo = $blog->photo;
        if ($request->hasFile('photo'))
        {
            if ($blog->photo)
            {

                ImageUploadManager::deletePhoto(self::BLOG_PHOTO_UPLOAD_PATH, $blog->photo);
            }

            $photo = (new ImageUploadManager())->file($request->photo)
                ->name('blog_photo_'.time())
                ->path(self::BLOG_PHOTO_UPLOAD_PATH)
                ->height(self::BLOG_PHOTO_HEIGHT)
                ->width(self::BLOG_PHOTO_WIDTH)
                ->upload();

        }
        $data['photo'] =  $photo;

        return $data;
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seos()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }


}
