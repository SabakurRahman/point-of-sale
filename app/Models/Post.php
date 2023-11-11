<?php

namespace App\Models;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\StoreSeoRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class Post extends Model
{
    use HasFactory;
   protected $guarded = [];



    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function postCategory(){
        return $this->belongsTo(PostCategory::class,'post_category_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function createPost($store_id, StorePostRequest $request)
    {



      // $post= null;
        $post = self::query()->create($this->preparePostData($store_id,$request));
      //  dd($post);
        $seoData = $this->prepareSeoData($request); // Prepare SEO data from the request
       // dd($post);
       $post->seo()->create($seoData);

        return $post;


    }


    private function prepareSeoData(Request $request)
    {

        $filename=Str::slug($request->input('slug')).'.webp';
        if ($request->og_image) {
            $file = $request->og_image;
//            $extension = explode('.', $file);
//            $extension = array_pop($extension);
//            $filename = time() . '.' . $extension;
//            $file->move('uploads/seo/', $filename);
            Image::make($file)
                ->save(public_path('uploads/seo/') . $filename, 50, 'webp');



        }


        return [
            'title'  => $request->input('meta_title'),
            'keyword' => $request->input('meta_keyword'),
            'og_image'=> $filename,
            'description'=>$request->input('meta_description')
        ];

    }

    private function preparePostData($store_id, StorePostRequest $request):array
    {
        $filename = null;
        if ($request->cover_photo) {
            $filename=Str::slug($request->input('slug')).'.webp';
            $file = $request->cover_photo;
////            $extension = $file->getClientOriginalExtension();
//            $extension = explode('.', $file);
//            $extension = array_pop($extension);
//            $filename = Str::slug($request->input('slug')) . '.' . $extension;
//            $file->move('uploads/post/', $filename);


            Image::make($file)
                ->save(public_path('uploads/post/') . $filename, 50, 'webp');


        }



      // dd( $filename);

        return [
            'store_id'      => $store_id,
            'user_id'       => Auth::id(),
            'post_category_id'   => $request->input('post_category_id'),
            'title'         => $request->input('title'),
            'title_bn'      => $request->input('title_bn'),
            'slug'          => Str::slug($request->input('slug')),
            'slug_bn'       => $request->input('slug_bn'),
            'description'   => $request->input('description'),
            'description_bn'=> $request->input('description_bn'),
            'cover_photo'   => $filename,
            'read_count'    => 0,
            'status'        => $request->input('status')


        ];
    }

    public function allPost(int $store_id)
    {
        return self::query()->where('store_id', $store_id)->paginate(10);
    }

    /**
     * @param UpdatePostRequest $request
     * @param Post $post
     * @param $store_id
     * @return Post
     */

    public function updatePost(UpdatePostRequest $request,Post $post,$store_id)
    {

      //  dd($post->category_id);
        $postData = [
            'store_id' => $store_id,
            'user_id' => Auth::id(),
            'post_category_id' => $request->input('post_category_id') ?? $post->post_category_id,
            'title' => $request->input('title') ?? $post->title,
            'title_bn' => $request->input('title_bn') ?? $post->title_bn,
            'slug' =>Str::slug($request->input('slug'))  ?? $post->slug,
            'slug_bn' => $request->input('slug_bn') ?? $post->slug_bn,
            'description' => $request->input('description') ?? $post->description,
            'description_bn' => $request->input('description_bn') ?? $post->description_bn,
            'status'        => $request->input('status') ?? $post->status,
            'read_count' => $request->input('read_count') ?? $post->read_count
        ];


       // dd($filename);
        $filename=Str::slug($request->input('slug')).'.webp';
        if ($request->cover_photo) {
            $file = $request->cover_photo;
//            $extension = $file->getClientOriginalExtension();
//            $filename = time() . '.' . $extension;
//            $file->move('uploads/post/', $filename);

            Image::make($file)
                ->save(public_path('uploads/post/') . $filename, 50, 'webp');


        }

        $postData['cover_photo'] = $filename;
        if ($post->cover_photo) {
            Storage::delete('uploads/post/' . $post->cover_photo);
        }


        $post->update($postData);

       if($post?->seo?->og_image)
       {
           Storage::delete('uploads/seo/'.$post?->seo?->og_image);
       }
        $seoData = $this->prepareSeoData($request);
        $post->seo()->update($seoData);

        return $post;

    }


    public function deletePost(Post $post)
    {
        if ($post->seo) {
            $post->seo->delete();
        }
       return $post->delete();
    }

    public function getPostById(Post $post)
    {
        return self::query()->with('seo')->find($post);
    }

    public function getAllBlog($store_id)
    {
        return self::query()->with('seo','postCategory','user')->where('store_id', $store_id)
            ->where('status',1)->paginate(12);

    }

    public function getBlogDetailsById($store_id, $slug)
    {
        return self::query()->with('seo','postCategory','user')
            ->where('store_id', $store_id)
            ->where('slug',$slug)
            ->where('status',1)
            ->first();
    }

    public function deletePostByCategoryId($post_category_id):void
    {
        self::query()->where('post_category_id', $post_category_id)->delete();
    }
}
