<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Repositories\ServiceRepository;

class ServiceController extends Controller
{
    public function index()
    {
        $services = (new ServiceRepository())->getActiveServices();
        $servicesNew = [];
        $key = $_GET['type'] ?? '';
        foreach ($services as $sKey => $sValue) {
            if ($sValue['type'] == $key) {
                $servicesNew[] = $sValue;
            }
        }

        return $this->json('service list', [
            'services' => ServiceResource::collection($servicesNew)
        ]);
    }
}
