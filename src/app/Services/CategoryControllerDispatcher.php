<?php

namespace App\Services;

class CategoryControllerDispatcher
{
    public static function dispatch(array $data)
    {
        if (isset($data['brand'])) {
            return view('categories.type', [
                'category' => $data['category'],
                'subcategory' => $data['subcategory'],
                'brand' => $data['brand'],
                'types' => $data['types'] ?? [],
                'productCategory' => $data['productCategory'] ?? null
            ]);
        }

        if (isset($data['rareBrand'])) {
            return view('categories.raretypes', [
                'category' => $data['category'],
                'subcategory' => $data['subcategory'],
                'rareBrand' => $data['rareBrand'],
                'rareTypes' => $data['rareTypes'] ?? [],
                'productCategory' => $data['productCategory'] ?? null
            ]);
        }

        if (isset($data['productCategories'])) {
            return view('categories.productCategories', [
                'category' => $data['category'],
                'subcategory' => $data['subcategory'],
                'productCategories' => $data['productCategories']
            ]);
        }

        // Ez a rész kezelje azt az esetet, amikor nincs productCategory, de van már brands lista
        if (isset($data['brands']) || isset($data['rareBrands'])) {
            return view('categories.brands', [
                'category' => $data['category'],
                'subcategory' => $data['subcategory'],
                'brands' => $data['brands'] ?? [],
                'rareBrands' => $data['rareBrands'] ?? [],
                'productCategory' => $data['productCategory'] ?? null
            ]);
        }

        if (isset($data['products'])) {
            return view('categories.products', [
                'category' => $data['category'],
                'subcategory' => $data['subcategory'],
                'productCategory' => $data['productCategory'] ?? null,
                'products' => $data['products']
            ]);
        }

        // Debug: ide már nem kéne eljutni, de ha igen, logoljunk
        logger()->warning('CategoryControllerDispatcher::dispatch reached abort', array_keys($data));
        abort(404);
    }




    public static function render(array $data)
    {
        if (isset($data['brand']) && $data['vintages'] !== null) {
            return view('categories.vintage', $data);
        }

        if (isset($data['rareBrand']) && $data['vintages'] !== null) {
            return view('categories.rarevintage', $data);
        }

        if (isset($data['rareBrand']) && $data['vintages'] === null) {
            return view('categories.raremodel', $data);
        }

        abort(404, 'Nincs megfelelő adat a megjelenítéshez.');
    }

    public static function renderModelPage(array $data)
    {
        if (isset($data['brand'])) {
            return view('categories.model', $data);
        }

        if (isset($data['rareBrand'])) {
            return view('categories.raremodel', $data);
        }

        abort(404, 'Nincs megfelelő adat a megjelenítéshez.');
    }

    public static function renderProductPage(array $data)
    {
        if (isset($data['rareBrand'])) {
            return view('categories.rareproducts', $data);
        }

        return view('categories.products', $data);
    }
}
