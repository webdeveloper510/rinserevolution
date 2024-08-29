<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public static function getPayments()
    {
        $users = self::with('order.customer.user')->get();
        $products = self::with('order.products')->get();

        // $data['users'] = $users->toArray();
        // $data['products'] = $products->toArray();
        // prx($data);

        /* $map_products = $products->map(function ($m_prod) use ($users) {
            $product_key = $m_prod->order->products->isNotEmpty() ?? array_keys($m_prod);
            $m_prod->users_id = $users[$product_key]->order->customer->user->id;
            return $m_prod;
        }); */
        /* $map_data = $users->map(function ($user) {
            $product_id = $user->order->product_id ?? null;
            $customer_id = $user->order->customer->user_id ?? null;

            $user->match_case = [
                'product_id' => $product_id,
                'customer_id' => $customer_id
            ];
            return $user;
        }); */
        $map_data = $users->map(function ($user) {
            $product_id = $user->order->product_id ?? null;
            $customer_id = $user->order->customer->id ?? null;
            $user_id = $user->order->customer->user->id ?? null;
            $order_id = $user->order_id ?? null;

            $user = [
                'product_id' => $product_id,
                'customer_id' => $customer_id,
                'user_id' => $user_id,
                'order_id' => $order_id,
            ];
            return $user;
        })->filter(function ($data) {
            if ($data['product_id'] != null && $data['customer_id'] != null) {
                return $data;
            }
        })->values()->sortBy([
            ['order_id', 'desc'],
        ]);
        return $map_data;
    }
}
