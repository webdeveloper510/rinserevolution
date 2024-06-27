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
            DB::table('device_keys')->where('customer_id', $id)->delete();
            $orderID = DB::table('orders')->where('customer_id', $id)->first();
            DB::table('orders')->where('customer_id', $orderID->order_id)->delete();

            DB::table('addresses')->where('customer_id', $id)->delete();
            $customerID = DB::table('customers')->where('id', $id)->first();

            DB::table('customers')->where('id', $id)->delete();
            DB::table('users')->where('id', $customerID->user_id)->delete();

            $resMsg = [
                "code" => 200,
                "msg" => "User deleted successfully !!",
            ];
            return response($resMsg, 200);
        } catch (\Throwable $e) {
            $resMsg = [
                "code" => 400,
                "msg" => 'error' . $e->getMessage(),
            ];
            return response($resMsg, 400);
        }
    }
}
