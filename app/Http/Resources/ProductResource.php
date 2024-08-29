<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $discount = null;
        if ($this->price > $this->discount_price && $this->discount_price > 0) {
            $percentage = ceil(($this->discount_price - $this->price) / $this->price * 100);
            $incrementOrDecrement = substr($percentage, 0, 1);
            if ($incrementOrDecrement == '-') {
                $discount = $percentage;
            }
        }

        /* if (isset($this->payments_data) && $this->payments_data->isNotEmpty()) {
            foreach ($this->payments_data as $key_pay => $value_pay) {
                if (!empty($value_pay->order->products[0])) {
                    $match_case = $value_pay->users_id == $this->login_user && $this->id == $value_pay->order->products[0]->id  ? true : false;
                }
            }
        } else {
            $match_case = '';
        } */
        /* $match_case = 0;
        if (isset($this->payments_data) && $this->payments_data->isNotEmpty()) {
            foreach ($this->payments_data as $key_pay => $value_pay) {
                if ($value_pay['user_id'] == $this->login_user && $this->id == $value_pay['product_id']) {
                    $match_case = 1;
                }
            }
        } else {
            $match_case = 0;
        } */

        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'subscription_status' => $this->subscription_status ?? false,
            'subscription_status' => (int)$this->match_case,
            'subscription_type' => $this->subscription_type ?? '',
            'name_bn' => $this->name_bn,
            'slug' => $this->slug,
            'current_price' => (int) ($this->discount_price ? $this->discount_price :  $this->price),
            'old_price' => (int) ($this->discount_price ? $this->price : 0),
            'description' => $this->description,
            'image_path' => $this->thumbnailPath,
            'discount_percentage' => $discount,
            'sub_products' => SubProductResource::collection($this->subProducts),
            'service' => (new ServiceResource($this->service)),
            'variant' => (new VariantResource($this->variant)),
            'payments' => $this->payments_data,
        ];
    }
}
