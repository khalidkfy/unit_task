<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Home;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  public function index(Request $request)
  {

    $homes = Home::with('facilities', 'images')
        ->when($request->city, function ($q) use ($request) {
          $q->where('city', 'like', "%$request->city%");
        })
        ->when($request->description, function ($q) use ($request) {
          $q->where('desc', 'like', "%$request->desc%");
        })
        ->when($request->price_start, function ($q) use ($request) {
          $q->where('price', '>=', $request->price_start);
        })
        ->when($request->price_end, function ($q) use ($request) {
          $q->where('price', '<=', $request->price_end);
        })
        ->when($request->price_end && $request->price_start, function ($q) use ($request) {
          $q->whereBetween('price', [$request->price_start, $request->price_end]);
        })
        ->paginate(10);

    return prepareResult(true, $homes, 'done', 200);

  }

  public function show($id)
  {
    $home = Home::with('images', 'facilities')->find($id);

    if (!$home) {
      return prepareResult(false, [], 'not found', 404);
    }

    return prepareResult(true, $home, 'done', 200);
  }
}
