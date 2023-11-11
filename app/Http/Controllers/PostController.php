<?php

namespace App\Http\Controllers;

use App\Http\Resources\AllPostResource;
use App\Http\Resources\BlogDetailsResource;
use App\Http\Resources\BlogListResource;
use App\Http\Resources\PostGetByIdResource;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $store_id)
    {
        //
        $post = (new Post())->allPost($store_id);
        // dd($post);
        return response()->json([
            "success" => true,
            "message" => "All Post",
            "data"    => AllPostResource::collection($post),
            'meta'    => [
                'total'        => $post->total(),
                'per_page'     => $post->perPage(),
                'total_pages'  => $post->lastPage(),
                'current_page' => $post->currentPage(),
                'last_page'    => $post->lastPage(),
                'from'         => $post->firstItem(),
                'to'           => $post->lastItem(),
            ],
            'links'   => [
                'first_page_url' => $post->url(1),
                'last_page_url'  => $post->url($post->lastPage()),
                'next_page_url'  => $post->nextPageUrl(),
                'prev_page_url'  => $post->previousPageUrl(),
            ],
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
    public function store(StorePostRequest $request, $store_id)
    {

        //$post= (new Post())->createPost($store_id,$request);


        try {
            Log::info('POST_DATA', ['data', $request->all()]);
            $post    = (new Post())->createPost($store_id, $request);
            $success = true;
            $message = "Post Created";
            return response()->json([
                'success' => $success,
                'message' => $message,
                'data'    => $post
            ]);
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed' . $throwable->getMessage();
            Log::info('POST_FAILED', ['ERROR', $throwable]);
            return response()->json([
                'success' => $success,
                'message' => $message,
                'data'    => []
            ]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($store_id, Post $post)
    {
        //
        $post->load('seo');
        //   $postById= (new Post())->getPostById($post);
        //dd($postById);
        return response()->json([
            'success' => true,
            'message' => 'Get Post By Id',
            'data'    => new PostGetByIdResource($post),

        ]);


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, $store_id, Post $post)
    {

        $updatePost = null;
        try {
            Log::info('POST_DATA_UPDATED', ['data', $request->all()]);
            $updatePost = (new Post())->updatePost($request, $post, $store_id);
            // dd($updatePost);
            $success = true;
            $message = "Post Updated";
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed' . $throwable->getMessage();

            Log::info('POST_UPDATED_FAILED', ['ERROR', $throwable]);
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $updatePost
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($store_id, Post $post)
    {
        //
        // (new Post())->deletePost($post);

        try {
            (new Post())->deletePost($post);;
            $success = true;
            $message = 'Post Deleted Successfully';
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed! ' . $throwable;
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function getBlogList($store_id)
    {
        $post = (new Post())->getAllBlog($store_id);
        return response()->json([
            "success" => true,
            "message" => "All Post",
            "data"    => count($post) > 0 ? BlogListResource::collection($post) : [],
            'meta'    => [
                'total'        => $post->total(),
                'per_page'     => $post->perPage(),
                'total_pages'  => $post->lastPage(),
                'current_page' => $post->currentPage(),
                'last_page'    => $post->lastPage(),
                'from'         => $post->firstItem(),
                'to'           => $post->lastItem(),
            ],
            'links'   => [
                'first_page_url' => $post->url(1),
                'last_page_url'  => $post->url($post->lastPage()),
                'next_page_url'  => $post->nextPageUrl(),
                'prev_page_url'  => $post->previousPageUrl(),
            ],
        ]);
    }

    public function blogDetails($store_id, $slug)
    {
        //  $blog_details= (new Post())->getBlogDetailsById($store_id,$blog_id);
        // dd($blog_details);

        try {
            $blog_details = (new Post())->getBlogDetailsById($store_id, $slug);
            $success      = true;
            $message      = 'Blog Details';
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed! ' . $throwable;
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => new BlogDetailsResource($blog_details)
        ]);

    }
}
