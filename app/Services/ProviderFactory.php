<?php

namespace App\Services;

use App\Interfaces\ProviderServiceInterfaces;
use App\Services\Mobile\AppleService;
use App\Services\Mobile\FacebookService;
use App\Services\Mobile\GoogleService;
use App\Services\Mobile\LineService;

class ProviderFactory
{
    protected ProviderServiceInterfaces $providerService;

    public function createProvider($provider)
    {
        switch ($provider) {
            case 'apple':
                $this->providerService = new AppleService();
                break;
            case 'facebook':
                $this->providerService = new FacebookService();
                break;
            case 'google':
                $this->providerService = new GoogleService();
                break;
            case 'line':
                $this->providerService = new LineService();
                break;
            default:
                break;
        }
        return $this->providerService;
    }
}
