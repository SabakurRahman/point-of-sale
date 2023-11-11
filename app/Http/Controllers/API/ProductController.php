<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceByCategoryResource;
use App\Http\Resources\ServiceCategoryResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\ServiceCategory;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{

    public function show($store_id)
    {

        $query = Product::join('categories', 'categories.id', '=', 'products.category_id')
            ->where('products.store_id', $store_id)
            ->where('products.status', 'active')
            ->where('categories.status', 'active')
            ->select('products.*', 'categories.name as category_name')
            ->orderBy('products.id', 'desc');

        $products = $query->paginate(10);

        if ($products->isEmpty()) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'current_page' => 0,
                    'last_page' => 0,
                    'per_page' => 10,
                    'total' => 0,
                ],
                'links' => [
                    'first_page_url' => null,
                    'last_page_url' => null,
                    'next_page_url' => null,
                    'prev_page_url' => null,
                ],
            ]);
        }

        $formattedProducts = $products->getCollection()->map(function ($product) {
            $formattedExpiryDate = Carbon::parse($product->expiry_date)->format('D, M d, Y');
            $product->expiry_date = $formattedExpiryDate;
            return $product;
        });

        return response()->json([
            'success' => true,
            'message' => 'Product And Service List',
            'data' => $formattedProducts,
            'meta' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'total_pages' => $products->lastPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
            'links' => [
                'first_page_url' => $products->url(1),
                'last_page_url' => $products->url($products->lastPage()),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
        ], 200);
    }

    public function productshow(Request $request, $store_id)
    {

        $query = Product::join('categories', 'categories.id', '=', 'products.category_id')
            ->where('products.store_id', $store_id)
            ->where('products.status', 'active')
            ->where('categories.status', 'active')
//            ->whereNull('variant')
            ->select('products.*', 'categories.name as category_name');
        //->orderBy('products.id', 'desc');
        if ($request->has('type') && $request->type == 'pos') {
            $query->where('quantity', '>', 0);
        }

        if (!empty($request->variant)) {
            $query->where('variant', $request->variant);
        }
        if (!empty($request->search)) {
            $query->where('products.name', 'LIKE', '%' . $request->search . '%');
        }
        if ($request->has('column') && $request->has('sort')) {
            if ($request->column == 'category_id') {
                $query->orderBy('categories.name', $request->sort);
            } else {
                $query->orderBy('products.' . $request->column, $request->sort);
            }

        }
        $paginate = $request->pagination ?? 10;
        $products = $query->paginate($paginate);
        $product_count = Product::where('products.store_id', $store_id)
            ->where('products.status', 'active')->count();

        if ($products->isEmpty()) {
            return response()->json([
                'data' =>[
                    'all_product' => [],
                    'total' => $product_count,
                ],
                'meta' => [
                    'current_page' => 0,
                    'last_page' => 0,
                    'per_page' => 10,
                    'total' => 0,
                ],
                'links' => [
                    'first_page_url' => null,
                    'last_page_url' => null,
                    'next_page_url' => null,
                    'prev_page_url' => null,
                ],
            ]);
        }
        $store = Store::query()->findOrFail($store_id);
        $vat = 0;
        if ($store && !empty($store->vat)) {
            $vat = $store->vat;
        }
        $formattedProducts = $products->getCollection()->map(function ($product) use ($vat) {
            $formattedExpiryDate = Carbon::parse($product->expiry_date)->format('D, M d, Y');
            $product->expiry_date = $formattedExpiryDate;
            $product->price += (($product->price * $vat) / 100);
            $product->created_time = Carbon::parse($product->created_at)->format('D, M d, Y');
            $product->image = !empty($product->image) ? $product->image : 'default.webp';
            return $product;
        });

        return response()->json([
            'success' => true,
            'message' => 'Product List',
            'data' =>[
                  'all_product'=>$formattedProducts,
                   'total' => $product_count,
            ],
            'meta' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'total_pages' => $products->lastPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
            'links' => [
                'first_page_url' => $products->url(1),
                'last_page_url' => $products->url($products->lastPage()),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
        ], 200);
    }

    public function serviceshow($store_id)
    {

        $query = Product::join('categories', 'categories.id', '=', 'products.category_id')
            ->where('products.store_id', $store_id)
            ->where('products.status', 'active')
            ->where('categories.status', 'active')
            ->whereNotNull('variant')
            ->select('products.*', 'categories.name as category_name')
            ->orderBy('products.id', 'desc');

        $products = $query->paginate(10);

        if ($products->isEmpty()) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'current_page' => 0,
                    'last_page' => 0,
                    'per_page' => 10,
                    'total' => 0,
                ],
                'links' => [
                    'first_page_url' => null,
                    'last_page_url' => null,
                    'next_page_url' => null,
                    'prev_page_url' => null,
                ],
            ]);
        }

        $formattedProducts = $products->getCollection()->map(function ($product) {
            $formattedExpiryDate = Carbon::parse($product->expiry_date)->format('D, M d, Y');
            $product->expiry_date = $formattedExpiryDate;
            return $product;
        });

        return response()->json([
            'success' => true,
            'message' => 'Service List',
            'data' => $formattedProducts,
            'meta' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'total_pages' => $products->lastPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
            'links' => [
                'first_page_url' => $products->url(1),
                'last_page_url' => $products->url($products->lastPage()),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
        ], 200);
    }


    public function delete_by_superadmin(Request $request)
    {
        $product_id = $request->product_id;
        $product_id = Product::find($product_id);
        $product_id->status = 'deleted';
        $product_id->save();

        if ($product_id) {
            return redirect()->route('superadmin.dashboard')->with('success', 'Product Successfully Deleted');
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Deleted',
                'data' => ''
            ], 404);
        }

    }


    public function product_details()
    {

        $products = Product::orderBy('id', 'DESC')->get();


        return view('superadmin.product_details', ['products' => $products]);

    }

    public function add_product($user_id = null, $store_id = null, $category_id = null)
    {
        if ($user_id && $store_id && $category_id) {
            $users = User::find($user_id);
            $store = Store::find($store_id);
            $category = Category::find($category_id);

            return view('superadmin.add_product', [
                'store' => $store,
                'category' => $category,
                'users' => $users,
                'store_id' => $store_id,
                'user_id' => $user_id,
                'category_id' => $category_id
            ]);
        } elseif ($user_id && $store_id) {
            $users = User::find($user_id);
            $store = Store::find($store_id);
            $category = Category::where('store_id', $store_id)->get();

            return view('superadmin.add_product', ['store' => $store, 'users' => $users, 'category' => $category, 'store_id' => $store_id, 'user_id' => $user_id, 'category_id' => null]);
        } elseif ($user_id) {
            $users = User::find($user_id);
            $store = Store::where('user_id', $user_id)->get();
            $category = Category::where('creator_id', $user_id)->get();
            return view('superadmin.add_product', ['store' => $store, 'category' => $category, 'users' => $users, 'store_id' => null, 'category_id' => null, 'user_id' => $user_id]);
        } else {
            $users = User::get();
            $store = Store::get();
            $category = Category::get();
            return view('superadmin.add_product', ['store' => $store, 'category' => $category, 'users' => $users, 'store_id' => null, 'category_id' => null, 'user_id' => null]);
        }


    }


    public function superadmin_edit_product($product_id)
    {

        $product = Product::find($product_id);

        return view('superadmin.product_edit', ['product' => $product]);
    }

    public function superadmin_edit_product_post(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
//                'buying_price' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'description' => 'required',
//                'expiry_date' => 'required',

        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $product_id = Product::find($request->product_id);
        if ($product_id) {
            $product_id->name = $request->name;
            $product_id->buying_price = $request->buying_price;
            $product_id->price = $request->price;
            $product_id->quantity = $request->quantity;
            $product_id->description = $request->description;
            $product_id->expiry_date = $request->expiry_date;

            $product_id->save();

            if ($product_id) {
                return redirect()->route('superadmin.dashboard')->with('success', 'Product Successfully Updated');
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product Not Updated',
                    'data' => ''
                ], 404);
            }
        }

    }


    public function category_all_service($store_id, $cat_id)
    {

        $query = Product::where('store_id', $store_id)
            ->where('category_id', $cat_id)
            ->where('status', 'active')
            ->whereNotNull('variant');
        $products = $query->paginate(10);
        if ($products->isEmpty()) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'current_page' => 0,
                    'last_page' => 0,
                    'per_page' => 10,
                    'total' => 0,
                ],
                'links' => [
                    'first_page_url' => null,
                    'last_page_url' => null,
                    'next_page_url' => null,
                    'prev_page_url' => null,
                ],
            ]);
        }
        $formattedProducts = $products->getCollection()->map(function ($product) {
            $formattedExpiryDate = Carbon::parse($product->expiry_date)->format('D, M d, Y');
            $product->expiry_date = $formattedExpiryDate;
            return $product;
        });

        return response()->json([
            'success' => true,
            'message' => 'Service List',
            'data' => $formattedProducts,
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
            'links' => [
                'first_page_url' => $products->url(1),
                'last_page_url' => $products->url($products->lastPage()),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
        ], 200);
    }

    public function category_all_product_and_service($store_id, $cat_id)
    {

        $query = Product::where('store_id', $store_id)
            ->where('category_id', $cat_id)
            ->where('status', 'active');
        $products = $query->paginate(10);
        if ($products->isEmpty()) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'current_page' => 0,
                    'last_page' => 0,
                    'per_page' => 10,
                    'total' => 0,
                ],
                'links' => [
                    'first_page_url' => null,
                    'last_page_url' => null,
                    'next_page_url' => null,
                    'prev_page_url' => null,
                ],
            ]);
        }
        $formattedProducts = $products->getCollection()->map(function ($product) {
            $formattedExpiryDate = Carbon::parse($product->expiry_date)->format('D, M d, Y');
            $product->expiry_date = $formattedExpiryDate;
            return $product;
        });

        return response()->json([
            'success' => true,
            'message' => 'Product And Service List',
            'data' => $formattedProducts,
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
            'links' => [
                'first_page_url' => $products->url(1),
                'last_page_url' => $products->url($products->lastPage()),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
        ], 200);
    }

    public function category_all_product($store_id, $cat_id)
    {
        $query = Product::where([
            ['store_id', $store_id],
            ['category_id', $cat_id],
            ['status', 'active']
        ])->whereNull('variant');

        $products = $query->paginate(10);
        if ($products->isEmpty()) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'current_page' => 0,
                    'last_page' => 0,
                    'per_page' => 10,
                    'total' => 0,
                ],
                'links' => [
                    'first_page_url' => null,
                    'last_page_url' => null,
                    'next_page_url' => null,
                    'prev_page_url' => null,
                ],
            ]);
        }
        $formattedProducts = $products->getCollection()->map(function ($product) {
            $formattedExpiryDate = Carbon::parse($product->expiry_date)->format('D, M d, Y');
            $product->expiry_date = $formattedExpiryDate;
            return $product;
        });

        return response()->json([
            'success' => true,
            'message' => 'Product List',
            'data' => $formattedProducts,
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
            'links' => [
                'first_page_url' => $products->url(1),
                'last_page_url' => $products->url($products->lastPage()),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
        ], 200);
    }


    public function store_index(Request $request, $id)
    {
        // $products = Product::orderBy('id', 'desc')->paginate(10);

        $products = Product::where('store_id', $id)->orderBy('id', 'DESC')->paginate(10);

        if ($request->search) {
            $products = Product::where('name', 'LIKE', "%{$request -> search}%")->paginate(10);
        }
        if ($request->sort) {
            $products = Product::orderBy('name', $request->sort)->paginate(10);
        }
        if ($products) {
            $formattedProducts = $products->getCollection()->map(function ($product) {
                $formattedExpiryDate = Carbon::parse($product->expiry_date)->format('D, M d, Y');
                $product->expiry_date = $formattedExpiryDate;
                return $product;
            });
            return response()->json([
                'success' => true,
                'message' => 'Product List',
                'data' => $formattedProducts,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
                'data' => ''
            ]);
        }
    }

    public function store_product_show($store_id, $product_id)
    {

        // if (($user->type == 'admin' || $user->type == 'employee') && $user->userStores->pluck('store_id')->contains($store_id)) {
        $product = Product::query()->where('id', $product_id)->with('product_features')->where('store_id', $store_id)->first();
        if ($product) {
            $formattedExpiryDate = Carbon::parse($product->expiry_date)->format('D, M d, Y');
            $product->expiry_date = $formattedExpiryDate;

            return response()->json([
                'success' => true,
                'message' => 'Product Details',
                'data' => $product,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
                'data' => ''
            ]);
        }
    }

    public function category_index(Request $request, $id)
    {
        // $products = Product::orderBy('id', 'desc')->paginate(10);

        $products = Product::where('category_id', $id)->orderBy('id', 'DESC')->paginate(10);

        if ($request->search) {
            $products = Product::where('name', 'LIKE', "%{$request -> search}%")->paginate(10);
        }
        if ($request->sort) {
            $products = Product::orderBy('name', $request->sort)->paginate(10);
        }
        if ($products) {
            $formattedProducts = $products->getCollection()->map(function ($product) {
                $formattedExpiryDate = Carbon::parse($product->expiry_date)->format('D, M d, Y');
                $product->expiry_date = $formattedExpiryDate;
                return $product;
            });
            return response()->json([
                'success' => true,
                'message' => 'Product List',
                'data' => $formattedProducts
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
                'data' => ''
            ]);
        }
    }


    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
//            'description' => 'string|nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|integer',
            'store_id' => 'required|integer',
            'variant' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->all(),
                'data' => ''
            ], 400);
        } else {
            $product = new Product();
            $product->name = $request->name;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->description = $request->description;
            $product->variant = $request->variant;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/product/', $filename);
                $product->image = $filename;
            }
            $product->category_id = $id;
            $product->store_id = 0;


            if ($product->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product Created',
                    'data' => $product
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product Not Created',
                    'data' => ''
                ], 400);
            }

        }


    }


    public function product_store_post2(Request $request, $storeId)
    {
        // Only admin and employee of the store can create a product

        $catgoryId = $request->input('category_id');
        $user = Auth::user();

        // if (($user->type == 'admin' || $user->type == 'employee') && $user->userStores->pluck('store_id')->contains($storeId)) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
//                'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->toArray(),
                'data' => ''
            ], 400)->toArray();
        } else {
            $product = new Product();
            $product->name = $request->name;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->stock = $request->quantity;
            $product->description = $request->description;
            $product->status = 'active';

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/product/', $filename);
                $product->image = $filename;
            }
            $product->category_id = $catgoryId;
            $product->sub_category_id = 0;
            $product->store_id = $storeId;
            $product->creator_id = auth()->id();

            if ($product->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product Created',
                    'data' => $product,
                    'image' => $product->image ? url('uploads/product/' . $product->image) : null,
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product Not Created',
                    'data' => ''
                ], 400);
            }
        }
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You are not authorized to create a product for this store',
        //         'data' => ''
        //     ], 401);
        // }
    }


    public function product_store_show(Request $request, $id)
    {
        $cat_id = $request->input('cat_id');
        $user = Auth::user();
        $product = Product::where('id', $id)->where('category_id', $cat_id)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
                'data' => ''
            ]);
        }

        $formattedProducts = $product->getCollection()->map(function ($product) {
            $formattedExpiryDate = Carbon::parse($product->expiry_date)->format('D, M d, Y');
            $product->expiry_date = $formattedExpiryDate;
            return $product;
        });
        return response()->json([
            'success' => true,
            'message' => 'Product Details',
            'data' => $formattedProducts
        ], 200);

    }


    public function product_store_post(Request $request, $storeId)
    {
        try {
            DB::beginTransaction();
            $catgoryId = $request->input('category_id');
            $rules = [
                'name' => 'required|string',
                'price' => 'required|integer',
            ];
            $variant = $request->variant;
            if ($variant == 2) {
                $rules = array_merge($rules, ['duration' => 'required']);
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                throw ValidationException::withMessages(['message' => $validator->messages()]);
            }

            $product = new Product();
            $product->name = $request->name;
            $product->duration = $request->duration;
            $product->buying_price = $request->buying_price;
            $product->price = $request->price;
            $product->expiry_date = $request->expiry_date;
            $product->quantity = $request->quantity;
            $product->stock = $request->quantity;
            $product->variant = $request->variant;
            $product->description = $request->description ?? '';
            $product->status = 'active';
            $product->is_show_on_web = $request->is_show_on_web ? 1 : 0;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/product/', $filename);
                $product->image = $filename;
            }
            $product->category_id = $catgoryId;
            $product->sub_category_id = 0;
            $product->store_id = $storeId;
            $product->creator_id = auth()->id();

            if ($request->variant == 2) {
                $expiryDate = Carbon::parse($product->expiry_date);
                $product->days_left = $expiryDate->diffInDays(Carbon::now());
            }
            $product->save();
            (new ProductFeature())->storeProductFeature($product, $request);
            DB::commit();
            $success = true;
            $message = 'Product Added successfully';
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::info('PRODUCT_STORE_FAILED', ['data' => $request->all(), 'error' => $throwable]);
            $success = false;
            $message = 'Failed! ' . $throwable->getMessage();
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }


    public function product_store_post_superadmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('categories')->where(function ($query) use ($request) {
                    return $query->where('creator_id', $request->user_id);
                }),
            ],
//                'price' => 'required|integer',
//                'buying_price' => 'required|integer',
            'expiry_date' => 'required|date',
            'quantity' => 'required|integer',
//                'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->toArray(),
                'data' => ''
            ], 400);
        } else {

            $product = new Product();
            $product->name = $request->name;
            $product->buying_price = $request->buying_price;
            $product->price = $request->price;
            $product->expiry_date = $request->expiry_date;
            $product->quantity = $request->quantity;
            $product->stock = $request->quantity;
            $product->description = $request->description;
            $product->category_id = $request->category_id;
            $product->store_id = $request->store_id;
            $product->creator_id = $request->creator_id;
            $product->status = 'active';

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/product/', $filename);
                $product->image = $filename;
            }
            $product->sub_category_id = 0;

            if ($product->save()) {
                return redirect()->route('superadmin.dashboard')->with('success', 'Product Successfully Registered');
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product Not Created',
                    'data' => ''
                ], 400);
            }
        }
    }


    public function update(Request $request, int $product_id)
    {
        try {
            DB:: beginTransaction();
            $product = Product::query()->find($product_id);
            if ($product) {
                $rules = [
                    'name' => 'string',
                    'price' => 'integer',
                    'quantity' => 'integer',
                    'category_id' => 'required|numeric',
                    'description' => 'nullable|string',
                ];
                if ($request->variant == 2) {
                    $rules = array_merge($rules, ['duration' => 'required']);
                }
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $success = false;
                    $message = $validator->messages()->first();
                    throw ValidationException::withMessages(['message' => $message]);
                }
                $currentImage = $product->image;
                $product->name = $request->name ? $request->name : $product->name;
                $product->duration = $request->duration ?? $product->duration;
                $product->variant = $request->variant ? $request->variant : $product->variant;
                $product->price = $request->price ? $request->price : $product->price;
                $product->category_id = $request->category_id ? $request->category_id : $product->category_id;
                $product->description = $request->description ? $request->description : $product->description;
                $product->quantity = $request->quantity ? $request->quantity : $product->quantity;
                $product->is_show_on_web = $request->is_show_on_web ? 1 : 0;
                if (!$product->variant) {
                    $product->buying_price = $request->buying_price ?? $product->buying_price;
                    $product->buying_price = $request->buying_price ? $request->buying_price : $product->buying_price;
                    $product->expiry_date = $request->expiry_date ? $request->expiry_date : $product->expiry_date;
                    $product->quantity = $request->quantity ? $request->quantity : $product->quantity;
                    $product->stock = $request->quantity ? $request->quantity : $product->stock;
                }
                if ($request->image) {
                    // Delete the old image
                    if (!empty($currentImage)) {
                        $oldImagePath = public_path('uploads/product/' . $currentImage);
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    // Upload the new image
                    $file = $request->image;
                    if (is_string($file)){
                       // $extension= explode(";", explode("/", $file)[1])[0];
                        $folderPath = "uploads/product/";
                        $base64Image = explode(";base64,", $request->image);
                        $explodeImage = explode("image/", $base64Image[0]);
                        $imageType = $explodeImage[1];
                        $image_base64 = base64_decode($base64Image[1]);
                        $filename = time() . '.' . $imageType;
                        $product->image = $filename;
                        $filename = $folderPath . $filename;
                        file_put_contents($filename, $image_base64);
                    }else{
                        $extension = $file->getClientOriginalExtension();
                        $filename = time() . '.' . $extension;
                        $file->move('uploads/product/', $filename);
                        $product->image = $filename;
                    }

                }
                if ($product->variant == 1 && !empty($product->expiry_date)) {
                    $product->days_left = Carbon::parse($product->expiry_date)->diffInDays(Carbon::now());
                }

                $product->save();
                //update product feature
                (new ProductFeature())->updateProductFeature($product, $request);
                DB::commit();
                $success = true;
                $message = 'Product Update successfully';
            } else {
                $success = false;
                $message = 'Product Not Found';
            }
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::info('PRODUCT UPDATE FAIL', ['data' => $request->all(), 'error' => $throwable]);
            $success = false;
            $message = 'Failed! ' . $throwable->getMessage();
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy($product_id)
    {
        $product = Product::find($product_id);
        $product->status = 'deleted';
        $product->save();

        if ($product->variant) {
            return response()->json([
                'success' => true,
                'message' => 'Service Deleted',
            ], 200);
        } elseif (!$product->variant) {
            return response()->json([
                'success' => true,
                'message' => 'Product Deleted',
                'data' => ''
            ], 200);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Deleted',
                'data' => ''
            ], 400);
        }

    }

    public function getServiceByCategoryId($category_id)
    {

        $getService = (new Product())->getServiceByCategory($category_id);
        //dd($getService);
        $formattedData = ServiceByCategoryResource::collection($getService);
        return response()->json([
            'success' => true,
            'message' => 'Get Service By Category',
            'data' => $formattedData
        ], 200);
    }


    public function getServiceProduct(Request $request, $store_id, $category_id)
    {

        $service_product = ServiceByCategoryResource::collection((new Product())->getServiceProduct($request, $store_id, $category_id));

        return response()->json([
            'success' => true,
            'message' => 'Get Service By Category',
            'data' => $service_product
        ], 200);
    }


    public function getServiceProductByService_type($store_id)
    {
        $service_category = ServiceCategory::query()
            ->orderBy('id')->where('store_id', $store_id)
            ->with(['categories', 'categories.web_products','categories.web_products.product_features'])
            ->get();
        return ServiceCategoryResource::collection($service_category);
    }


}
