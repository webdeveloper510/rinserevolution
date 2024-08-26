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

        $map_products =  $products->map(function ($m_prod) use ($users) {
            $product_key = $m_prod->order->products->isNotEmpty() ?? array_keys($m_prod);
            $m_prod->users_id = $users[$product_key]->order->customer->user->id;
            return $m_prod;
        });
        return $map_products;
    }
}
