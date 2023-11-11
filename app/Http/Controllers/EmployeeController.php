<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Validator;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

use Illuminate\Support\Facades\Hash;
class EmployeeController extends Controller
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

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request,$store_id) {
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string',
            'phone' => 'numeric',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $parent_id = FacadesAuth::id();
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->type =  $request->type;
        $user->status = 'active';
        $user->parent_id = $parent_id;
        $user->store_id = $store_id;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->user_stores()->attach([$store_id]);
        if($user){
            return response()->json([
                'success' => true,
                'message' => 'Employee Successfully registered',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Registration fail',
                'data' => ''
            ], 404);
        }

    }
    public function employee_register(Request $request,$id) {
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $parent_id = $id;
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'address'   => $request->address,
            'type'      => $request->type,
            'parent_id' =>$parent_id,
            'password'  => Hash::make($request->password)
        ]);

        if($user){
            return response()->json([
                'success' => true,
                'message' => 'Employee Successfully registered',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'data' => ''
            ], 404);
        }

    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */



    public function userProfile() {
        return response()->json(auth()->user());
    }

    public function update(Request $request, string $id)
    {

        $user = User::find($id);
        $user->name = $request->name ? $request->name : $user->name;
        $user->email = $request->email ? $request->email : $user->email;
        $user->address = $request->address ? $request->address : $user->address;
        $user->phone = $request->phone ? $request->phone : $user->phone;
        $user->type = $request->type ?? $user->type;
        $user->password = $request->password ? $request->password : $user->password;

        $user->save();

        if($user){
            return response()->json([
                'success' => true,
                'message' => 'Employee Updated Successfully',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Employee Not Updated',
                'data' => ''
            ], 404);
        }

    }

    public function destroy($employee_id)
    {
        $employee_id = User::find($employee_id);
        if (!$employee_id) {
            return response()->json([
                'success' => false,
                'message' => 'Employee Not Found',
                'data' => ''
            ]);
        }

        // Delete the old image file


        // Perform the deletion
        $employee_id->status='deleted';
        $employee_id->save();


        return response()->json([
            'success' => true,
            'message' => 'Employee Deleted',
            'data' => $employee_id
        ], 201);

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
            'expires_in' => auth()->factory()->getTTL() * 600,
            'user' => auth()->user()
        ]);
    }


    public function allemployee() {
        $user=User::query()->orderBy('id', 'ASC')->paginate(10);
        if($user){
            return response()->json([
                'success' => true,
                'message' => 'All employee data',
                'data' => $user,
                'meta' => [
                    'total' => $user->total(),
                    'current_page' => $user->currentPage(),
                    'per_page' => $user->perPage(),
                    'last_page' => $user->lastPage(),
                    'from' => $user->firstItem(),
                    'to' => $user->lastItem(),
                ],
                'links' => [
                    'prev' => $user->previousPageUrl(),
                    'next' => $user->nextPageUrl(),
                    'all' => $user->url(1),
                ]
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => ' Not Found',
                'data' => ''
            ], 404);
        }
    }
    public function admins_allemployee($id) {

        $user=User::where('parent_id',$id)->orderBy('id', 'ASC')->paginate(10);
        if($user){
            return response()->json([
                'success' => true,
                'message' => 'All employee data',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => ' Not Found',
                'data' => ''
            ], 404);
        }
    }
    public function my_all_employee() {
        $id=auth()->id();
        $user=User::where('parent_id',$id)->where('status','active')->orderBy('id', 'DESC');
        $user = $user->paginate(10);

            if ($user->isEmpty()) {
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

            return response()->json([
                'success' => true,
                'message' => 'Employee List',
                'data' => $user->items(),
                'meta' => [
                    'total' => $user->total(),
                    'per_page' => $user->perPage(),
                    'total_pages' => $user->lastPage(),
                    'current_page' => $user->currentPage(),
                    'last_page' => $user->lastPage(),
                    'from' => $user->firstItem(),
                    'to' => $user->lastItem(),
                ],
                'links' => [
                    'first_page_url' => $user->url(1),
                    'last_page_url' => $user->url($user->lastPage()),
                    'next_page_url' => $user->nextPageUrl(),
                    'prev_page_url' => $user->previousPageUrl(),
                ],
            ], 200);
    }
    public function employee_profile($id) {
        $user=User::where('id',$id)
        ->where('status','active')->first();
        if($user){
            return response()->json([
                'success' => true,
                'message' => 'Employee Profile',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => ' Not Found',
                'data' => ''
            ], 404);
        }
    }

    public function employeeProfile($id) {
        $user=User::where('id',$id)->get();
        if($user){
            return response()->json([
                'success' => true,
                'message' => 'Employee Profile',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Employee Not Found',
                'data' => ''
            ], 404);
        }
    }

}
