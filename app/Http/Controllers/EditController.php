<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * The edit controller for frontend editing
 */
class EditController extends Controller
{
    /**
     * Construct and auth
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get all categories and color to use on frontend
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allCategoriesAndColors()
    {
        $categoryResult = [];
        $colorResult = [];

        // Get categories from DB (cached)
        $categories = DB::select('SELECT value FROM storage WHERE name = "CATEGORY_SELECT"');
        if (isset($categories[0])) {
            $data = unserialize($categories[0]->value);
            foreach ($data as $d) {
                if (
                    in_array(
                        $d['slug'],
                        array('negeren', 'onbekend', 'test')
                    )
                    || str_contains($d['slug'], 'test-')
                ) {
                    continue;
                }
                unset($d['front']);
                unset($d['image']);
                unset($d['slug']);
                $categoryResult[] = $d;
            }
        }

        // Get colors
        $colors = DB::select('SELECT id, attr_slug, attr_name FROM attributes WHERE attr_type_id = ? AND attr_active = ? ORDER BY attr_name', [1, 1]);
        foreach ($colors as $c) {
            $colorResult[] = array(
                'id' => $c->id,
                'slug' => 'c_'.$c->attr_slug,
                'name' => $c->attr_name
            );
        }

        if (count($categoryResult) == 0 || count($colorResult) == 0) {
            return response()->json(['status' => 'error']);
        }

        return response()->json(
            [
                'status' => 'success',
                'categories' => $categoryResult,
                'colors' => $colorResult
            ]
        );
    }

    /**
     * Store product data from overview pages
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function storeProductData(Request $request) {
        $productArr = [];
        $colorArr = [];

        $products = $request->input('products');
        $colors = $request->input('colors');
        $category = $request->input('category');

        if (empty($products) && (empty($colors) || empty($category))) {
            return response()->json([
                'status' => 'error',
                'data' => 'Geen gegevens ontvangen.',
            ], 200);
        }

        if ($products) {
            $productArr = explode(',', $products);
        }
        if ($colors) {
            $colorArr = explode(',', $colors);
        }

        if (count($productArr) == 0) {
            //error, stop
            return response()->json([
                'status' => 'error',
                'data' => 'Geen producten ontvangen.',
            ], 200);
        }


        // perform the update
        $update = '';
        foreach($productArr as $product) {
            $data = [];
            if ($category) {
                $data['category_id'] = (int)$category;
            }
            foreach($colorArr as $color) {
                $data[$color] = 1;
            }

            $update = DB::table('products2')
                ->where('id', $product)
                ->update($data);

            if ($update) {
                return response()->json([
                    'status' => 'success',
                    'data' => count($productArr) . ' producten geupdate. ' . var_export($data, true),
                ], 200);
            }
        }

        return response()->json([
            'status' => 'error',
            'data' => 'Fout tijdens update',
        ], 200);
    }

    /**
     * Store product data from overview pages
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function storeSingleProductData(Request $request) {
        $productId = 0;
        $colorArr = [];

        $product = $request->input('products');
        $colors = $request->input('colors');
        $category = $request->input('category');

        if ($product) {
            $productId = (int)$product;
        }
        if ($colors) {
            $colorArr = explode(',', $colors);
        }

        if ($productId == 0) {
            //error, stop
            return response()->json([
                'data' => 'error',
            ], 200);
        } else {
            return response()->json([
                'data' => 'success',
            ], 200);
        }
    }
}
