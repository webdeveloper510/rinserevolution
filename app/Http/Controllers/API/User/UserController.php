<?php

namespace App\Http\Controllers\API\User;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfilePhotoRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Repositories\DriverRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function update(UserRequest $request)
    {
        $user = (new UserRepository())->updateByRequest($request, auth()->user());

        return $this->json('Profile is updated successfully', [
            'user' => (new UserResource($user))
        ]);
    }
    public function updateProfilePhoto(ProfilePhotoRequest $request)
    {
        $user = (new UserRepository())->updateProfilePhotoByRequest($request, auth()->user());

        return $this->json('Profile photo is updated successfully', [
            'user' => (new UserResource($user))
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = (new DriverRepository())->changePasswordByRequest($request, auth()->user());
        if ($user) {
            return $this->json('Password change successfully', [
                'user' => (new UserResource($user))
            ]);
        }
        return $this->json('Incurrect password', [], Response::HTTP_BAD_REQUEST);
    }
    public function UserDelete(Request $request)
    {
        try {
            $id = $request->id;
            // DB::table('device_keys')->where('customer_id', $id)->delete();
            $orderID = DB::table('orders')->where('customer_id', $id)->get();
            
            // print_r($orderID);
            // die();
            
            foreach($orderID as $orderKey => $orderValue) {
                
                DB::table('ratings')->where('customer_id', $id)->orWhere('order_id',$orderValue->id)->delete();
                DB::table('driver_histories')->where('order_id', $orderValue->id)->delete();
                DB::table('driver_orders')->where('order_id', $orderValue->id)->delete();
                DB::table('order_products')->where('order_id', $orderValue->id)->delete();
            }
                DB::table('orders')->where('customer_id', $id)->delete();
            

            DB::table('addresses')->where('customer_id', $id)->delete();
            $customerID = DB::table('customers')->where('id', $id)->first();
            
            DB::table('customers')->where('id', $id)->delete();
            DB::table('users')->where('id', $customerID->user_id)->delete();

            return response(['success' => true, 'msg' => 'User deleted'], 200);
        } catch (\Throwable $e) {
            return response(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }
}
