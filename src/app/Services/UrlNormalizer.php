<?php

namespace App\Services;

class UrlNormalizer
{
    public static function normalize(?string $productCategorySlug, ?string $brandSlug): array
    {
        if ($productCategorySlug && str_starts_with($productCategorySlug, '/')) {
            $brandSlug = ltrim($productCategorySlug, '/');
            $productCategorySlug = null;
        }

        if (!$productCategorySlug) {
            $productCategorySlug = 'osszes_termek';
        }

        return [$productCategorySlug, $brandSlug];
    }
}