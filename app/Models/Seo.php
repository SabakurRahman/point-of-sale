<?php

namespace App\Models;

use App\Http\Requests\StoreSeoRequest;
use App\Managers\Utility;
use App\Managers\ImageUploadManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const SEO_PHOTO_UPLOAD_PATH = 'uploads/seo/';
    public const SEO_PHOTO_WIDTH = 600;
    public const SEO_PHOTO_HEIGHT = 600;


    final public function prepareSeoData($request)
    {
        $data= [
            'title'       => $request->meta_title,
            'keyword'    => $request->meta_keyword,
            'description' => $request->meta_description,
        ];
        if($request->has('og_image'))
        {
            $og_image=(new ImageUploadManager())->file($request->og_image)
                ->name('seo_photo_'.time())
                ->path(self::SEO_PHOTO_UPLOAD_PATH)
                ->height(self::SEO_PHOTO_HEIGHT)
                ->width(self::SEO_PHOTO_WIDTH)
                ->upload();
        }
        $data['og_image'] =  $og_image ?? null;
        return $data;




    }


    public function updateSeoData($request, Seo $seo)
    {
        $seo_data = [
            'title'       =>  $request->meta_title ?? $seo->title ,
            'keyword'     => $request->meta_keyword ?? $seo->keyword,
            'description' => $request->meta_description ?? $seo->description,
        ];
        $og_image = $seo->og_image;

        if ($request->hasFile('og_image'))
        {
                if ($seo->og_image)
                {
                    ImageUploadManager::deletePhoto(self::SEO_PHOTO_UPLOAD_PATH, $seo->og_image);
                }

                $og_image = (new ImageUploadManager)->file($request->og_image)
                    ->name(Utility::prepare_name('meta'.time()))
                    ->path(self::SEO_PHOTO_UPLOAD_PATH)
                    ->height(self::SEO_PHOTO_WIDTH)
                    ->width(self::SEO_PHOTO_HEIGHT)
                    ->upload();

        }
        $seo_data['og_image'] =  $og_image;

        return $seo_data;

    }
    public function seoable()
    {
        return $this->morphTo();
    }

}
