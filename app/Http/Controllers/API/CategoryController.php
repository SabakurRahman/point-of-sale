<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryListBackendResouce;
use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request, $store_id)
    {
        $user       = Auth::user();
        $paginate = $request->input('per_page') ?? 10;
        $query = Category::query()->where('store_id', $store_id)
            ->with(['created_by', 'serviceCategory'])
            ->where('store_id',$store_id);

        $searchTerm = $request->input('search');
        $searchType = $request->input('type');

        if ($searchTerm && $searchType) {
            switch ($searchType) {
                case 'name':
                    $query->where('name', 'like', "%$searchTerm%");
                    break;
                case 'creator_id':
                    $query->where('creator_id', 'like', "%$searchTerm%");
                    break;
                case 'status':
                    $query->where('status', $searchTerm);
                    break;
            }
        }


        if ($request->input('sort_by') && $request->input('sort_direction')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_direction'));
        } else {
            $query->orderByDesc('id');
        }

        $category = $query->paginate($paginate);
        $total    = Category::where('store_id',$store_id)->count();

        if ($category->isEmpty()) {
            return response()->json([
                'data'  => [
                    'categories'=> CategoryListBackendResouce::collection($category),
                    'total' => $total
                            ],
                'meta'  => [
                    'current_page' => 0,
                    'last_page'    => 0,
                    'per_page'     => 10,
                    'total'        => 0,
                ],
                'links' => [
                    'first_page_url' => null,
                    'last_page_url'  => null,
                    'next_page_url'  => null,
                    'prev_page_url'  => null,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product List',
            'data'    => [
              'categories'=> CategoryListBackendResouce::collection($category),
                'total' => $total

            ],
            'meta'    => [
                'total'        => $category->total(),
                'per_page'     => $category->perPage(),
                'total_pages'  => $category->lastPage(),
                'current_page' => $category->currentPage(),
                'last_page'    => $category->lastPage(),
                'from'         => $category->firstItem(),
                'to'           => $category->lastItem(),
            ],
            'links'   => [
                'first_page_url' => $category->url(1),
                'last_page_url'  => $category->url($category->lastPage()),
                'next_page_url'  => $category->nextPageUrl(),
                'prev_page_url'  => $category->previousPageUrl(),
            ],
        ], 200);


    }


    public function addcategorypage($user_id = null, $store_id = null)
    {

        if ($user_id && $store_id) {
            $users = User::find($user_id);
            $store = Store::find($store_id);
            return view('superadmin.category_register_page', ['store' => $store, 'users' => $users, 'store_id' => $store_id, 'user_id' => $user_id]);
        } elseif ($user_id) {
            $users  = User::find($user_id);
            $stores = Store::where('user_id', $user_id)->get();
            return view('superadmin.category_register_page', ['stores' => $stores, 'users' => $users, 'store_id' => null, 'user_id' => $user_id]);
        } else {
            $users  = User::get();
            $stores = Store::get();
            return view('superadmin.category_register_page', ['stores' => $stores, 'users' => $users, 'store_id' => null, 'user_id' => null]);
        }


    }


    public function superadmin_edit_Category($cat_id)
    {

        $category = Category::find($cat_id);

        return view('superadmin.category_edit', ['category' => $category]);
    }

    public function superadmin_edit_Category_post(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',

        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $cat = Category::find($request->cat_id);
        if ($cat) {
            $cat->name = $request->name;

            $cat->save();

            if ($cat) {
                return redirect()->route('superadmin.dashboard')->with('success', 'Category Successfully Updated');
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Category Not Updated',
                    'data'    => ''
                ], 404);
            }
        }

    }


    public function delete_by_superadmin(Request $request)
    {
        $category_id      = $request->category_id;
        $category         = Category::find($category_id);
        $category->status = 'deleted';
        $category->save();

        if ($category) {
            return redirect()->route('superadmin.dashboard')->with('success', 'Category Successfully Deleted');
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Category Not Deleted',
                'data'    => ''
            ], 404);
        }

    }


    public function addcategory(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'store_id' => 'required',
            'name'     => [
                'required',
                Rule::unique('categories')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                }),
            ],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }


        $category             = new Category();
        $category->name       = $request->name;
        $category->store_id   = $request->store_id;
        $category->creator_id = $request->creator_id;
        $category->status     = 'active';
        $category->save();
        if ($category) {
            return redirect()->route('detail_admin', $request->creator_id)->with('success', 'Category Successfully Registered');
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Category Not Registered',
                'data'    => ''
            ], 404);
        }


    }

    public function store_all_categories(Request $request, $admin_id, $store_id)
    {

        $user = Auth::user();

        if (($user->type == 'superadmin')) {
            $categories = Category::
            where('creator_id', $admin_id)
                ->where('store_id', $store_id)
                ->where('status', 'active')->
                orderBy('id', 'DESC')->paginate(10);
            if ($request->search) {
                $categories = Category::where('name', 'LIKE', "%{$request -> search}%")->where('status', 'active')->paginate(10);
            }

            if ($request->sort) {
                $categories = Category::orderBy('name', $request->sort)
                    ->where('status', 'active')->paginate(10);
            }

            if ($categories->count() > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category List',
                    'data'    => $categories->items(),
                    'meta'    => [
                        'current_page' => $categories->currentPage(),
                        'last_page'    => $categories->lastPage(),
                        'per_page'     => $categories->perPage(),
                        'total'        => $categories->total(),
                    ],
                    'links'   => [
                        'first_page_url' => $categories->url(1),
                        'last_page_url'  => $categories->url($categories->lastPage()),
                        'next_page_url'  => $categories->nextPageUrl(),
                        'prev_page_url'  => $categories->previousPageUrl(),
                    ],
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Category Not Found',
                    'data'    => []
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to see the category list',
                'data'    => []
            ], 401);
        }
    }

    public function admin_all_categories(Request $request, $admin_id)
    {

        $user = Auth::user();
        // $store = Store::where('id',$id)->first();
        // dd($store);

        if (($user->type == 'superadmin')) {
            $categories = Category::where('creator_id', $admin_id)
                ->where('status', 'active')->orderBy('id', 'DESC')->paginate(10);
            if ($request->search) {
                $categories = Category::where('name', 'LIKE', "%{$request -> search}%")->where('status', 'active')->paginate(10);
            }
            if ($request->sort) {
                $categories = Category::orderBy('name', $request->sort)->where('status', 'active')->paginate(10);
            }
            if ($categories) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category List',
                    'data'    => $categories
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Category Not Found',
                    'data'    => ''
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to see the category list',
                'data'    => ''
            ], 401);
        }

    }

    public function allcategories(Request $request)
    {

        $user = Auth::user();
        // $store = Store::where('id',$id)->first();
        // dd($store);

        if ($user->type == 'superadmin') {
            $categories = Category::where('status', 'active')->
            orderBy('id', 'DESC')->paginate(10);
            if ($request->search) {
                $categories = Category::where('name', 'LIKE', "%{$request -> search}%")->where('status', 'active')->paginate(10);
            }
            if ($request->sort) {
                $categories = Category::orderBy('name', $request->sort)->where('status', 'active')->paginate(10);
            }
            if ($categories) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category List',
                    'data'    => $categories
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Category Not Found',
                    'data'    => ''
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to see the category list',
                'data'    => ''
            ], 401);
        }


    }


    public function create()
    {
        //
    }


    public function store(Request $request, $store_id)
    {
        // Only admin and employee of this store can create the category
        $user = Auth::user();
        // $store = Store::where('id',$id)->get();

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data'    => null
            ], 400);
        } else {

            $category       = new Category();
            $category->name = $request->name;


            if ($request->hasFile('image')) {
                $file      = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename  = time() . '.' . $extension;
                $file->move('uploads/category/', $filename);

                $category->image = $filename;
            }

            $category->parent_id           = 0;
            $category->creator_id          = auth()->user()->id;
            $category->store_id            = $store_id;
            $category->status              = 'active';
            $category->service_category_id = $request->service_category_id;
            $category->save();
        }
        if ($category) {
            return response()->json([
                'success'   => true,
                'message'   => 'Category Created',
                'data'      => $category,
                //add image url and check if image is null return null
                'image_url' => $category->image ? asset('uploads/category/' . $category->image) : null

            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Category Not Created',
                'data'    => null
            ]);
        }

    }

    public function add_categorie_for_admin(Request $request, $admin_id, $store_id)
    {
        // Only admin and employee of this store can create the category
        $user = Auth::user();
        // $store = Store::where('id',$id)->get();

        if ($user->type == 'superadmin') {
            $validator = Validator::make($request->all(), [
                'name'  => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                    'data'    => null
                ], 400);
            } else {

                $category       = new Category();
                $category->name = $request->name;

                if ($request->hasFile('image')) {
                    $file      = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename  = time() . '.' . $extension;
                    $file->move('uploads/category/', $filename);

                    $category->image = $filename;
                }

                $category->parent_id  = 0;
                $category->creator_id = $admin_id;
                $category->store_id   = $store_id;

                $category->save();
            }
            if ($category) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category Created',
                    'data'    => $category
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Category Not Created',
                    'data'    => null
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to create the category',
                'data'    => null
            ], 401);
        }
    }


    public function sub_store(Request $request, $id, $c_id)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data'    => ''
            ], 400);
        } else {
            $category = Category::where('name', $request->name)->first();
            if ($category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category Already Exists',
                    'data'    => ''
                ], 400);
            }
        }
        $category       = new Category();
        $category->name = $request->name;
        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename  = time() . '.' . $extension;
            $file->move('uploads/category/', $filename);
            $category->image = $filename;
        }

        $category->parent_id  = $c_id;
        $category->creator_id = auth()->user()->id;
        $category->store_id   = $id;


        $category->save();
        if ($category) {
            return response()->json([
                'success' => true,
                'message' => 'Sub Category Created',
                'data'    => $category

            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sub Category Not Created',
                'data'    => ''
            ]);
        }

    }


    public function show($store_id, $category_id)
    {

        //only admin and employee of this store can show the category
        $user = Auth::user();
        //  $store = Store::where('id',$id)->first();


        $category = Category::where('store_id', $store_id)->find($category_id);
        if ($category) {
            return response()->json([
                'success' => true,
                'message' => 'Category Detail',
                'data'    => $category
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Category Not Found',
                'data'    => ''
            ]);
        }


    }

    public function sub_show($id, $s_id, $ss_id)
    {
        $category = Category::
        where('store_id', $id)
            ->where('parent_id', $s_id)
            ->find($ss_id);
        if ($category) {
            return response()->json([
                'success' => true,
                'message' => 'Category Detail',
                'data'    => $category
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Category Not Found',
                'data'    => ''
            ]);
        }
    }


    public function edit($id)
    {

    }


    public function update(Request $request, $cat_id)
    {
        $category                      = Category::findOrFail($cat_id);
        $category->name                = $request->name ? $request->name : $category->name;
        $category->service_category_id = $request->service_category_id ? $request->service_category_id : $category->service_category_id;


        $category->save();


        if ($category) {
            return response()->json([
                'success' => true,
                'message' => 'category Updated successfully',
                'data'    => $category
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'category Not Update',
                'data'    => ''
            ]);
        }
    }


    public function destroy($cat_id)
    {
        $category = Category::find($cat_id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category Not Found',
                'data'    => ''
            ]);
        }

        // Delete the old image file


        // Perform the deletion
        $category->status = 'deleted';
        $category->save();


        return response()->json([
            'success' => true,
            'message' => 'Category Deleted',
            'data'    => $category
        ], 201);

    }

    public function category_list($store_id)
    {
        return Category::query()
            ->where('status', 'active')
            ->where('store_id', $store_id)
            ->select('id', 'name', 'status')
            ->get();
    }

}
