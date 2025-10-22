<?php

namespace App\Http\Controllers;

use App\Models\Products\Product;
use App\Models\Shipping\ShippingCountry;
use App\Models\Shipping\ShippingState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
  public function countries($code = null)
  {
    if ($code) {
      // Fetch specific country with states and methods
      $country = ShippingCountry::where('code', '=', $code)
  
        ->with([
          'states:name,id,is_active,country_id',
          'methods' => function ($query) {
            $query->where('is_active', true)
              ->whereHas('method', function ($q) {
                $q->where('is_active', true);
              })
              ->with(['method' => function ($q) {
                $q->where('is_active', true);
              }]);
          }
        ])
        ->first()->withActiveMethods();

      Log::info('Fetching country with code: ', [$code, $country]);

      return response()->json($country);
    }

    // Fetch all active countries (code and name only)
    $countries = ShippingCountry::where('is_active', true)
      ->select('code', 'name')
      ->get();

    return response()->json($countries);
  }

  public function shipping(string $type, string $id)
  {
    if ($type === 'state') {
      // Fetch specific country with states and methods
      $state = ShippingState::where('id', '=', $id)
        ->with([
          'methods' => function ($query) {
            $query->where('is_active', true)
              ->whereHas('method', function ($q) {
                $q->where('is_active', true);
              })
              ->with(['method' => function ($q) {
                $q->where('is_active', true);
              }]);
          }
        ])
        ->first()->withActiveMethods();

      Log::info('Fetching country with code: ', [$id, $state]);

      return response()->json($state);
    }

    return response()->json([]);
  }

  public function productQuickView(string $id)
  {
    // Fetch product details for quick view
    Log::info("Fetching product for quick view: ", [$id]);
    $product = Product::with([ 'variants', 'category:id,name'])
      ->where('id', $id)
      ->first();

    if (!$product) {
      return response()->json(['error' => 'Product not found'], 404);
    }

    return response()->json($product);
  }
}
