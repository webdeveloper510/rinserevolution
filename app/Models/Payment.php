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
        $order_id = self::order();

        $data['order_id'] = $order_id;
        return $data;
    }
}
