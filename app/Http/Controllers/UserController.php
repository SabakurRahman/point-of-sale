<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreColor;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Validator;
use Auth;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Support\Facades\Hash;

use function PHPSTORM_META\type;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    // public function __construct() {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = FacadesValidator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);

        }
        return $this->createNewToken($token);

    }
    public function admin_register()
    {
        return view('superadmin.admin_register');
    }

    public function admin_edit_page($user_id)
    {
        $user = User::find($user_id);

        return view('superadmin.admin_edit', ['user' => $user]);
    }

    public function admin_details()
    {

        $users = User::where('type','admin')->orderBy('id', 'DESC')->get();

        return view('superadmin.admin_details', ['users' => $users]);

    }


    public function delete_by_superadmin(Request $request)
    {
        $user_id=$request->user_id;
        $user = User::find($user_id);
       $user->status='deleted';
       $user->save();

            if ($user) {
                return redirect()->route('superadmin.dashboard')->with('success', 'User Successfully Deleted');
            } else {
        return response()->json([
            'success' => false,
            'message' => 'User Not Deleted',
            'data' => ''
        ], 404);
    }

    }

    public function admin_details_by_id($id)
    {

        $users = User::find($id);

        $products = Product::where('creator_id', $id)->where('status','active')->orderBy('id', 'DESC')->get();
        $stores = Store::where('user_id', $id)->where('status','active')->orderBy('id', 'DESC')->get();
        $categories = Category::where('creator_id', $id)->where('status','active')->orderBy('id', 'DESC')->get();


        return view('superadmin.admin_details_by_id', ['users' => $users,'products' => $products,'stores' => $stores,'categories' => $categories]);

    }

    public function superadmin_register_admin(Request $request) {
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'type' =>'admin',
            'parent_id' =>0,
            'password' => Hash::make($request->password)
        ]);

        if ($user) {
            return redirect()->route('superadmin.dashboard')->with('success', 'User Successfully Registered');
                } else {
            return response()->json([
                'success' => false,
                'message' => 'User Not Registered',
                'data' => ''
            ], 404);
        }

    }

    public function register(Request $request) {
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'type' =>'admin',
            'status' =>'active',
            'parent_id' =>0,
            'password' => Hash::make($request->password)
        ]);

        if($user){
            return response()->json([
                'success' => true,
                'message' => 'User Successfully Registered',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User Not Register',
                'data' => ''
            ], 404);
        }

    }

public function superadmin_update_admin(Request $request)
{
    $validator = FacadesValidator::make($request->all(), [
        'name' => 'required|max:255',
        'email' => 'required|string|email|max:255',
        'address' => 'required|string',
        'password' => 'nullable|string|min:6',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }
    $user = User::find($request->id);
    if ($user) {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($user) {
            return redirect()->route('superadmin.dashboard')->with('success', 'User Successfully Updated');
                } else {
            return response()->json([
                'success' => false,
                'message' => 'User Not Updated',
                'data' => ''
            ], 404);
        }}
}

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */


     public function logout()
     {
         if (auth()->user()->type === 'employee') {
             auth()->logout();

             if (!auth()->check()) {
                 return response()->json([
                     'success' => true,
                     'message' => 'Employee Successfully Logged Out',
                     'data' => null
                 ], 200);
             } else {
                 return response()->json([
                     'success' => false,
                     'message' => 'Employee Logout Failed',
                     'data' => null
                 ], 404);
             }
         } else {
             auth()->logout();

             if (!auth()->check()) {
                 return response()->json([
                     'success' => true,
                     'message' => 'Admin Successfully Logged Out',
                     'data' => null
                 ], 200);
             } else {
                 return response()->json([
                     'success' => false,
                     'message' => 'Admin Logout Failed',
                     'data' => null
                 ], 404);
             }
         }
     }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        $user=auth()->user();
        if($user->type==='admin'){
            return response()->json([
                'success' => true,
                'message' => 'Admin data',
                'data' => $user
            ], 200);
        }elseif($user->type==='employee'){
            return response()->json([
                'success' => true,
                'message' => 'Employee Data',
                'data' => $user
            ], 200);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'User Not Found',
                'data' => ''
            ], 404);
        }
    }
    public function adminProfile($id) {
        $user=User::where('id',$id)->get();
        if($user){
            return response()->json([
                'success' => true,
                'message' => 'Admin Profile',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User Not Found',
                'data' => ''
            ], 404);
        }
    }
    public function alladmin()
    {
        $users = User::where('type', 'admin')->orderBy('id', 'ASC')->paginate(10);

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No admin found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'All admin data',
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
            'links' => [
                'first_page_url' => $users->url(1),
                'last_page_url' => $users->url($users->lastPage()),
                'next_page_url' => $users->nextPageUrl(),
                'prev_page_url' => $users->previousPageUrl(),
            ],
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $user->name = $request->name ? $request->name : $user->name;
        $user->email = $request->email ? $request->email : $user->email;
        $user->address = $request->address ? $request->address : $user->address;
        $user->password = $request->password ? $request->password : $user->password;

        $user->save();

        if($user){
            return response()->json([
                'success' => true,
                'message' => 'User Updated',
                'user' => $user
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User Not Updated',
                'data' => ''
            ], 404);
        }

    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'store_color' =>$this->getStoreColorByEmployee(auth()->user()),
            'expires_in' => auth()->factory()->getTTL() * 600,
            'user' => auth()->user()
        ]);
    }

    protected function getStoreColorByEmployee($user)
    {
        $store_color = StoreColor::query()->where('store_id', $user->store_id)->first();
        return [
            'primary_color'=> $store_color?->primary_color,
            'secondary_color'=> $store_color?->secondary_color,
            'optional_color'=> $store_color?->optional_color,
        ];
    }
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        if($user){
            return response()->json([
                'success' => true,
                'message' => 'Admin Deleted Successfully',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Admin Not Found',
                'data' => ''
            ], 404);
        }
    }
}
