<?php

namespace domain\Facades\ImageFacade;

use domain\Services\ImageService\ImageService;
use Illuminate\Support\Facades\Facade;

/**
 * Image Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class ImageFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return ImageService::class;
    }
}
