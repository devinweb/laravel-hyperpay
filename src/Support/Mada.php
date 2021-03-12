<?php
namespace Devinweb\LaravelHyperpay\Support\NormalBrand;

use Devinweb\LaravelHyperpay\Contracts\Brand\BrandInterface;

final class Mada implements BrandInterface
{
    public function getEntityId(): string
    {
        return "mada_brand";
    }
}
