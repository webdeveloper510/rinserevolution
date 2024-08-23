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
        $orders = self::with('order.customer.user')->first();

        $data['orders'] = $orders->toArray();
        return $data;
    }
}
