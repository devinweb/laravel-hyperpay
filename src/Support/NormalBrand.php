<?php
namespace Devinweb\LaravelHyperpay\Support\NormalBrand;

use Devinweb\LaravelHyperpay\Contracts\Brand\BrandInterface;

final class NormalBrand implements BrandInterface
{
    public function getEntityId(): string
    {
        return "normal_brand";
    }
}
