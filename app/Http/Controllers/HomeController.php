<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Home;
use App\Models\Image;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use PHPUnit\Exception;
use Symfony\Component\HttpClient\HttpClient;


class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    $homes = Home::paginate(PER_PAGE);

    return view('home', compact('homes'));
  }

  public function data(Request $request)
  {

    $homes = Home::query()
        ->when($request->city, function ($q) use ($request) {
          $q->where('city', "like", "%$request->city%");
        })
        ->when($request->desc, function ($q) use ($request) {
          $q->where('desc', "like", "%$request->desc%");
        })
        ->orderBy('id', 'desc')
        ->get();

    return response()->json(compact('homes'));
  }

  public function create()
  {

    return view('form');
  }

  public function store(Request $request)
  {
    $request->validate([
        'name' => 'required',
        'size' => 'required',
        'city' => 'required',
        'price' => 'required',
        'bedrooms_count' => 'required',
        'bathrooms_count' => 'required',
        'desc' => 'required',
        'sale_agent' => 'required',
        'cover_image' => 'required',
        'facs' => 'required|array|min:1',
        'facs.*.name' => 'required',
    ], [], [
        "facs" => "facility name",
        "facs.*.name" => "facility name",
    ]);

    $img_path = null;
    if ($request->cover_image) {
      $img_path = moveImgFromTemp('uploads/homes', $request->cover_image['img'], $request->cover_image['name']);
    }

    $home = Home::create([
        'name' => $request->name,
        'size' => $request->size,
        'city' => $request->city,
        'price' => $request->price,
        'bedrooms_count' => $request->bedrooms_count,
        'bathrooms_count' => $request->bathrooms_count,
        'desc' => $request->desc,
        'sale_agent' => $request->sale_agent,
        'cover_image' => $img_path,
    ]);

    foreach ($request->facs as $fac) {
      Facility::create([
          'home_id' => $home->id,
          'name' => $fac['name']
      ]);
    }

    $imgs = [];
    if ($request->imgs) {
      $imgs = moveMultiFilesFromTemp($request->imgs, '/uploads/homes/gallery/');
    }

    foreach ($imgs as $img)
    {
      Image::create([
          'home_id' => $home->id,
          'name' =>$img
      ]);
    }

    return response()->json(['message' => 'success']);
  }

  public function edit(Home $home)
  {
    $home->load('facilities');
    return view('form', compact('home'));
  }

  public function update(Request $request, Home $home)
  {

    $request->validate([
        'name' => 'required',
        'size' => 'required',
        'city' => 'required',
        'price' => 'required',
        'bedrooms_count' => 'required',
        'bathrooms_count' => 'required',
        'desc' => 'required',
        'sale_agent' => 'required',
        'cover_image' => 'nullable',
        'facs' => 'required|array|min:1',
        'facs.*.name' => 'required',
    ], [], [
        "facs" => "facility name",
        "facs.*.name" => "facility name",
    ]);

    $img_path = $home->cover_image;
    if ($request->cover_image) {
      $img_path = moveImgFromTemp('uploads/homes', $request->cover_image['img'], $request->cover_image['name']);
    }

    $home->update([
        'name' => $request->name,
        'size' => $request->size,
        'city' => $request->city,
        'price' => $request->price,
        'bedrooms_count' => $request->bedrooms_count,
        'bathrooms_count' => $request->bathrooms_count,
        'desc' => $request->desc,
        'sale_agent' => $request->sale_agent,
        'cover_image' => $img_path,
    ]);

    $home->facilities()->delete();

    foreach ($request->facs as $fac) {
      Facility::create([
          'home_id' => $home->id,
          'name' => $fac['name']
      ]);
    }


    return response()->json(['message' => 'success']);

  }

  public function delete(Home $home)
  {
    $home->delete();
    return response()->json(['deleted' => true]);
  }

  public function scrap()
  {
    $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));


    $crawler = $client->request('GET', 'https://summerhomes.com/property-alanya?city=190');


    $url = parse_url($client->getRequest()->getUri(), PHP_URL_HOST);

    $url = "https://$url";

    try {
      $crawler->filter("figure.box-item .property-img-slide")->each(function ($node) use ($client, $url) {

        $href = $node->filter('.swiper-wrapper .swiper-slide:first-child a')->attr('href');

        try
        {
          $home_page = $client->request('GET', $url . $href);

          if ($home_page) {
            $name = $home_page->filter('.area-title h1')->text() ?? 'unknown';
            $city = $home_page->filter('.location h3')->text() ?? 'unknown';
            $price = $home_page->filter('.price-info h3 span:last-child')->text() ?? 0;
            $desc = $home_page->filter('.property-desc .content p:nth-child(2)')->text() ?? 'unknown';
            $size = $home_page->filter('.property-read div:first-child div:nth-child(3)')->text() ?? 'unknown';
            $beds = $home_page->filter('.property-read div:first-child div:nth-child(4)')->text() ?? 'unknown';
            $baths = $home_page->filter('.property-read div:first-child div:nth-child(5)')->text() ?? 'unknown';


            $url = $url . $href;

            $img = 'uploads/homes/logo'.$name .'.png';

            file_put_contents($img, file_get_contents($url));

            $home = Home::create([
                'name' => $name,
                'price' => $price,
                'city' => $city,
                'desc' => $desc,
                'size' => $size,
                'bedrooms_count' => $beds,
                'bathrooms_count' => $baths,
                'sale_agent' => "unknown",
                'cover_image' => $img
            ]);

            $home_page->filter('.features ul li')->each(function ($fa) use($home) {
              Facility::create([
                  'name' => $fa->text() ?? 'unknown',
                  'home_id' => $home->id
              ]);
            });

          }
        } catch (\Exception $exception) {
          return;
        }
      });
      return redirect()->route('home');
    } catch (Exception $exception) {
      return redirect()->route('home');
    }




    // try {
    //
    //
    // } catch (\Exception $e) {
    //   return redirect()->route('home');
    // }

  }

  public function uploadImg(Request $request)
  {

    $img = $request->file('file');
    $img_mime = $img->getClientOriginalExtension();
    $path = uploadImage($img, 'temps');
    $img_name = basename($path);
    return ['img' => $path, 'name' => $img_name];
  }

  public function uploadMultiFile(Request $request) {
    return $file = uploadFile($request->file('file'), 'temps');
  }
}
