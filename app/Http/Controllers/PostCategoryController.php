<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCategoryResource;
use App\Models\Post;
use App\Models\PostCategory;
use App\Http\Requests\StorePostCategoryRequest;
use App\Http\Requests\UpdatePostCategoryRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(int $store_id)
    {
        //
       $postCategory = (new PostCategory())->getAllPostCategory($store_id);
      // dd($postCategory);
        return response()->json([
            "success"=>true,
            "message"=>"All Post Category",
            "data"  =>$postCategory
        ]);
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
    public function store(StorePostCategoryRequest $request,$store_id)
    {

        $postcategory =null;
        try {
            Log::info('POST_CATEGORY_DATA', ['data', $request->all()]);
            $postcategory= (new PostCategory())->createPostCategory($store_id,$request);
            $success = true;
            $message="Post Category Created";
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed' . $throwable->getMessage();
            Log::info('POST_CATEGORY_CREATED_FAILED', ['ERROR', $throwable]);
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $postcategory
        ]);


    }

    /**
     * Display the specified resource.
     */
    public function show($store_id,PostCategory $postCategory)
    {
        //
       $postCategoryById = (new PostCategory())->postCategoryById($store_id,$postCategory);
     /// dd($postCategoryById);
        return response()->json([
            "success"=>true,
            "message"=>" Post Category By Id",
            "data"  => new PostCategoryResource($postCategory),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostCategory $postCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostCategoryRequest $request,$store_id, PostCategory $postCategory)
    {
        //

//     $upadtePostCategory = (new PostCategory())->updatePostCategory($request,$store_id,$postCategory);
     //dd($upadtePostCategory);
        try {
            Log::info('POST_CATEGORY_DATA', ['data', $request->all()]);
            $upadtePostCategory = (new PostCategory())->updatePostCategory($request,$store_id,$postCategory);
            $success = true;
            $message="Post Category Updated";
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed' . $throwable->getMessage();
            Log::info('POST_CATEGORY_UPDATED_FAILED', ['ERROR', $throwable]);
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $upadtePostCategory
        ]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($store_id,PostCategory $postCategory)
    {
        //
      //  (new PostCategory())->deleteCategory($store_id,$postCategory);

        try {
            DB::beginTransaction();
            (new Post())->deletePostByCategoryId($postCategory->id);
            (new PostCategory())->deleteCategory($store_id,$postCategory);
            $success = true;
            $message = 'Post Category Deleted Successfully';
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $success = false;
            $message = 'Failed! ' . $throwable;
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}
