<?php

namespace App\Models;

use App\Http\Requests\StorePostCategoryRequest;
use App\Http\Requests\UpdatePostCategoryRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function createPostCategory($store_id, StorePostCategoryRequest $request)
    {
       return self::query()->create($this->preparePostCategoryData($store_id,$request));
    }

    private function preparePostCategoryData($store_id, StorePostCategoryRequest $request)
    {
         return[
             'store_id'      => $store_id,
             'name'          => $request->input('name'),
             'name_bn'       => $request->input('name_bn'),
             'slug'          => $request->input('slug'),
             'slug_bn'       => $request->input('slug_bn'),
             'description'   => $request->input('description'),
             'description_bn'=> $request->input('description_bn'),
             ];
    }

    public function getAllPostCategory(int $store_id)
    {
        return self::query()->where('store_id', $store_id)->get();
    }

    public function updatePostCategory(UpdatePostCategoryRequest $request, $store_id, PostCategory $postCategory)
    {
      $postCategoryData= [
            'store_id'      => $store_id,
            'name'          => $request->input('name') ?? $postCategory->name,
            'name_bn'       => $request->input('name_bn') ?? $postCategory->name_bn,
            'slug'          => $request->input('slug') ?? $postCategory->slug,
            'slug_bn'       => $request->input('slug_bn') ?? $postCategory->slug_bn,
            'description'   => $request->input('description') ?? $postCategory->description,
            'description_bn'=> $request->input('description_bn') ?? $postCategory->description_bn,
        ];

      return  $postCategory->update($postCategoryData);

    }

    public function deleteCategory($store_id,PostCategory $postCategory)
    {
       return $postCategory->delete();
    }

    public function postCategoryById($store_id, PostCategory $postCategory)
    {
        return self::query()->find($postCategory);

    }
}
