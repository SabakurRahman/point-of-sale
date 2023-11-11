<?php

namespace App\Http\Controllers;
use App\Models\StoreColor;
use Illuminate\Validation\Rule;

use App\Models\Store;
use App\Models\User;
use App\Models\UserStore;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Auth as FacadesAuth;


use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $user = FacadesAuth::user();

        $userId =$user->id;
        if ($user->store_id) {
            $store_id=$user->store_id;
            $data = Store::find($store_id);

            return response()->json([
                'success' => true,
                'message' => 'Store List',
                'data' => [$data],

            ], 200);
        }
    else{
        $data = UserStore::join('stores', 'user_stores.store_id', '=', 'stores.id')
        ->where('user_stores.user_id', $userId)
        ->where('status','!=', 'deleted')
        ->select('user_stores.*', 'stores.*')
        ->orderBy('id', 'ASC')
        ->paginate(10);

    if ($data->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Store Not Found',
            'data' => []
        ]);
    }
    return response()->json([
        'success' => true,
        'message' => 'Store List',
        'data' => $data->items(),
        'meta' => [
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'total_pages' => $data->lastPage(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'from' => $data->firstItem(),
            'to' => $data->lastItem()
        ],
        'links' => [
            'first_page_url' => $data->url(1),
            'last_page_url' => $data->url($data->lastPage()),
            'next_page_url' => $data->nextPageUrl(),
            'prev_page_url' => $data->previousPageUrl()
        ]
    ], 200);

    }


    }

    public function store_details()
    {

        $stores = Store::orderBy('id', 'DESC')->get();

        return view('superadmin.store_details', ['stores' => $stores]);

    }


    public function superadmin_edit_store($user_id,$store_id)
    {

        $user = User::find($user_id);
        $store = Store::find($store_id);

        return view('superadmin.store_edit', ['user' => $user,'store' => $store]);
    }
    public function superadmin_edit_store_post(Request $request)
    {

        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $store = Store::find($request->store_id);
        if ($store) {
            $store->user_id = $request->creator_id;
            $store->name = $request->name;
            $store->address = $request->address;
            $store->description = $request->description;
            $store->save();

            if ($store) {
                return redirect()->route('superadmin.dashboard')->with('success', 'Store Successfully Updated');
                    } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Store Not Updated',
                    'data' => ''
                ], 404);
            }}

    }



    public function delete_by_superadmin(Request $request)
    {
        $store_id=$request->store_id;
        $store = Store::find($store_id);
       $store->status='deleted';
       $store->save();

            if ($store) {
                return redirect()->route('superadmin.dashboard')->with('success', 'Store Successfully Deleted');
            } else {
        return response()->json([
            'success' => false,
            'message' => 'Store Not Deleted',
            'data' => ''
        ], 404);
    }

    }

    public function admins_allstore($id)
    {
        $userId = $id;
        $data1 = UserStore::join('stores', 'user_stores.store_id', '=', 'stores.id')
            ->where('user_stores.user_id', $userId)
            ->where('status', 'active')
            ->select('user_stores.*', 'stores.*')
            ->orderBy('id', 'DESC')->paginate(10);
        $data2= Store::where('user_id', $userId)->get();
        $data=[$data1,$data2];

        if($data){
            return response()->json([
                'success' => true,
                'message' => 'Store List',
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Store Not Found',
                'data' => ''
            ]);
        }

    }
    public function allstore()
    {

        $data=Store::where('status','active')->orderBy('id', 'DESC')->paginate(10);


        if($data){
            return response()->json([
                'success' => true,
                'message' => 'All Store List',
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Store Not Found',
                'data' => ''
            ]);
        }

    }

    public function store(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'required|string',
            'phone' => 'required|string'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        if (FacadesAuth::check() && FacadesAuth::user()->type === 'admin') {

            $user_id = FacadesAuth::id();
            if ($request->hasFile('logo')){
                $file = $request->file('logo');
                $extension = $file->getClientOriginalExtension();
                $filename = time().'.'.$extension;
                $file-> move('uploads/logo/', $filename);
                $logo = $filename;
            }
        $store = new Store();
            $store -> name = $request -> name;
            $store -> address = $request -> address;
            $store -> description = $request -> description;
            $store -> phone = $request -> phone;
            $store -> user_id = $user_id;
            $store -> status = 'active';
            $store->vat = $request->vat;
            $store->logo = $logo ?? '';
            $store->save();

        if($store){
            UserStore::create([
                'user_id' => $user_id,
                'store_id' => $store->id,
            ]);

        }
        return response()->json([
            'message' => 'Store Successfully created',
            'user' => $store
        ], 201);

        return Store::create($request->all());

        }
        else {
            abort(403, 'Unauthorized');
        }

    }



    public function addstorepage($user_id= null)
    {
     if ($user_id) {
            $users = User::find($user_id);
            return view('superadmin.store_register_page', ['users' => $users, 'user_id' => $user_id]);
        } else {
            $users = User::get();
            return view('superadmin.store_register_page', ['users' => $users, 'user_id' => null]);
        }


    }

    public function show($id)
    {
       $data=Store::findOrFail($id);
       $store_color = StoreColor::query()->where('store_id', $id)->first();


        if($data){
            $data->primary_color = $store_color?->primary_color;
            $data->secondary_color = $store_color?->secondary_color;
            $data->optional_color = $store_color?->optional_color;
            return response()->json([
                'success' => true,
                'message' => 'Store get successfully',
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Store Not get',
                'data' => ''
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);
        // $store->update($request->all());
        $store -> name = $request -> name;
        $store -> address = $request -> address;
        $store -> description = $request -> description;
        $store -> phone = $request -> phone;
        $store -> status = 'active';
        $store->vat = $request->vat;
        if ($request->hasFile('logo')){
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
            $file-> move('uploads/logo/', $filename);
            $store->logo = $filename;
        }
        $store->save();

        // $store = new Store();
        // $store -> name = $request -> name;
        // $store -> address = $request -> address;
        // $store -> description = $request -> description;
        // $store -> phone = $request -> phone;
        // $store -> user_id = $user_id;
        // $store -> status = 'active';
        // $store->save();

        if ($request->has('primary_color')){
            $store_color = StoreColor::query()->where('store_id', $id)->first();
            if ($store_color){
                $color_data = [
                    'primary_color' => $request->primary_color,
                    'secondary_color' => $request->secondary_color ?? $store_color->secondary_color,
                    'optional_color' => $request->optional_color ?? $store_color->optional_color,
                ];
                $store_color->update($color_data);
            }else{
                $color_data = [
                    'primary_color' => $request->primary_color,
                    'secondary_color' => $request->secondary_color,
                    'optional_color' => $request->optional_color ,
                    'store_id' => $id ,
                ];
                StoreColor::query()->create($color_data);
            }

        }

        if($store){
            return response()->json([
                'success' => true,
                'message' => 'Store Updated successfully',
                'data' => $store
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Store Not Update',
                'data' => ''
            ]);
        }
    }

    public function destroy($id)
    {
       $data= Store::findOrFail($id);
       $data->status = 'deleted';
       $data->save();

        if($data){
            return response()->json([
                'success' => true,
                'message' => 'Store deleted successfully',
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Store Not delete',
                'data' => ''
            ]);
        }

    }
}
