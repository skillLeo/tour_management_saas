<?php

namespace Botble\Tours\Repositories\Caches;

use Botble\Tours\Repositories\Eloquent\TourCategoryRepository;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Tours\Repositories\Interfaces\TourCategoryInterface;

class TourCategoryCacheDecorator extends CacheAbstractDecorator implements TourCategoryInterface
{
    public function __construct(TourCategoryRepository $repository)
    {
        parent::__construct($repository);
    }
}