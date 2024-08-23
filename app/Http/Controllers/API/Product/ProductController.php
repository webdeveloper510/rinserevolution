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

        $currentUserId = auth()->user();

        $products = (new ProductRepository())
            ->getProductsByServiceIdAndVariantId($serviceId, $variantId, $searchKey, $currentUserId);

        return $this->json('product list', [
            'products' => ProductResource::collection($products)
        ]);
    }
}
