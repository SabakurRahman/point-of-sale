<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use App\Models\UserStore;
use Illuminate\Http\Request;

class UserStoreController extends Controller
{
    public $foundUserId;

    public function index($id)
{
    $users = Store::where('user_id', $id)->orderBy('id', 'desc')->paginate(10);
    
    if ($users->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No Data',
            'data' => []
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'User Store data',
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


    public function searchUserByEmail(Request $request)
{
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

    if ($user) {
        // User found, store the ID in the class-level variable
        $this->foundUserId = $user->id;
                $userId = $this->foundUserId;
                return $userId;
                
        // Perform any desired actions with the user ID
    } else {
       return 'not found';
    }
}

    public function store(Request $request, $id, $userId)
    {
        // $userId = $this->foundUserId;
        $user = UserStore::create([
            'store_id' => $id,
            'user_id' => $userId,
        ]); 
        $user->stores()->attach($request->input('stores'));
        
        if($user){
            return response()->json([
                'success' => true,
                'message' => 'Successfully registered',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Failed',
                'data' => ''
            ], 404);
        }
       
       
    }
    
}
