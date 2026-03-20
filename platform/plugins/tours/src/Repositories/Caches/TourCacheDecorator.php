<?php

namespace Botble\Tours\Repositories\Caches;

use Botble\Tours\Repositories\Eloquent\TourRepository;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Tours\Repositories\Interfaces\TourInterface;

class TourCacheDecorator extends CacheAbstractDecorator implements TourInterface
{
    public function __construct(TourRepository $repository)
    {
        parent::__construct($repository);
    }
}