<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    public function index()
    {
        $serviceId = \request('service_id');
        $variantId = \request('variant_id');
        $searchKey = \request('search');

        $user_id = auth()->user()->id;

        $products = (new ProductRepository())
            ->getProductsByServiceIdAndVariantId($serviceId, $variantId, $searchKey);
        $products = $products->map(function ($item) use ($user_id) {



            $match_case = 0;
            if (isset($item->payments_data) && $item->payments_data->isNotEmpty()) {
                foreach ($item->payments_data as $key_pay => $value_pay) {
                    if ($value_pay['user_id'] == $user_id && $item->id == $value_pay['product_id']) {
                        $match_case = 1;
                    }
                }
            } else {
                $match_case = 0;
            }


            $item->login_user = $user_id;
            $item->match_case = $match_case;
            return $item;
        });
        return $this->json('product list', [
            'products' => ProductResource::collection($products)
        ]);
    }
}
