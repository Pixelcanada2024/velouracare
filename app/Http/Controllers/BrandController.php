<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\BrandTranslation;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductStock;
use App\Http\Resources\V2\Seller\ProductWithStockResource;

class BrandController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_brands'])->only('index');
        $this->middleware(['permission:add_brand'])->only('create');
        $this->middleware(['permission:edit_brand'])->only('edit');
        $this->middleware(['permission:delete_brand'])->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        // $brands = Brand::orderBy('name', 'asc');
        $brands = Brand::latest();
        if ($request->has('search')){
            $sort_search = $request->search;
            $brands = $brands->where('name', 'like', '%'.$sort_search.'%');
        }
        $brands = $brands->paginate(15);
        return view('backend.product.brands.index', compact('brands', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $brand = new Brand;
        $brand->name = $request->name;
        $brand->name_trans =  [
          'en' => $request->name_trans['en']
        ];
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $brand->slug = str_replace(' ', '-', $request->slug);
        }
        else {
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }

        $brand->logo = $request->logo;


        $brand->save();

        if ( isset ($request->web_banner_trans['en']) && $request->web_banner_trans['en'] != null )
        {
          $brand->setImage('web_banner', $request->web_banner_trans['en'] , 'en');
        }

        if ( isset ($request->mobile_banner_trans['en']) && $request->mobile_banner_trans['en'] != null )
        {
          $brand->setImage('mobile_banner', $request->mobile_banner_trans['en'] , 'en');
        }

        // $brand_translation = BrandTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'brand_id' => $brand->id]);
        // $brand_translation->name = $request->name;
        // $brand_translation->save();

        flash(translate('Brand has been inserted successfully'))->success();
        return redirect()->route('brands.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang   = $request->lang;
        $brand  = Brand::findOrFail($id);
        return view('backend.product.brands.edit', compact('brand','lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $lang = $request['lang'] ?? 'en';

        $brand = Brand::findOrFail($id);
        // if($request->lang == env("DEFAULT_LANGUAGE")){
        //     $brand->name = $request->name;
        // }

        $titleTrans = $brand->name_trans ?? [];
        $brand->name_trans = [
            ...$titleTrans,
            $lang => $request->name_trans[$lang]
        ];

        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $brand->slug = strtolower($request->slug);
        }
        else {
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }
        $brand->logo = $request->logo;
        $brand->web_banner = $request->web_banner;
        $brand->mobile_banner = $request->mobile_banner;

        $brand->save();
        if ( isset ($request->web_banner_trans[$lang]) && $request->web_banner_trans[$lang] != null )
          {
            $brand->setImage('web_banner', $request->web_banner_trans[$lang] , $lang);
          }else if ($request->web_banner_trans[$lang] == null){
            $brand->deleteIfExist('web_banner', $lang);
          }


        if ( isset ($request->mobile_banner_trans[$lang]) && $request->mobile_banner_trans[$lang] != null )
          {
            $brand->setImage('mobile_banner', $request->mobile_banner_trans[$lang] , $lang);
          }else if ($request->mobile_banner_trans[$lang] == null){
            $brand->deleteIfExist('mobile_banner', $lang);
          }

        // $brand_translation = BrandTranslation::firstOrNew(['lang' => $request->lang, 'brand_id' => $brand->id]);
        // $brand_translation->name = $request->name;
        // $brand_translation->save();

        flash(translate('Brand has been updated successfully'))->success();
        return back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->brand_translations()->delete();
        Product::where('brand_id', $brand->id)->update(['brand_id' => null]);
        Brand::destroy($id);

        flash(translate('Brand has been deleted successfully'))->success();
        return redirect()->route('brands.index');

    }


    public function all_brands(Request $request)
    {
        $brands = Brand::with('logo_image')->orderBy('name_trans->en')->get();

        return inertia('Brands/Brands', [
            'brands' => $brands->map(function($brand) {
                $logoUrl = null;

                if ($brand->logo_image) {
                    // If file_name,
                    if ($brand->logo_image->file_name) {
                        $logoUrl = asset("/public/".$brand->logo_image->file_name);
                    }
                    // Or,external_link
                    else if ($brand->logo_image->external_link) {
                        $logoUrl = $brand->logo_image->external_link;
                    }
                }

                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'name_trans' => $brand->name_trans,
                    'slug' => $brand->slug,
                    'logo' => $logoUrl
                ];
            })
        ]);
    }


    public function brand_show(Request $request, Brand $brand)
    {
        $brand->load([
            'logo_image',
            'web_banner_image',
            'mobile_banner_image',
        ]);

        // Paginate products directly
        $products = $brand->products()
            ->where('published', 1)
            ->with(['thumbnail'])
            ->select('id', 'name','thumbnail_img', 'discount_type', 'discount', 'brand_id','created_at','updated_at')
            ->paginate(16)
            ->withQueryString();

        return inertia('Brands/SingleBrand', [
            'brand' => (new BrandResource($brand))->toArray($request),
            'products' => [
                'data' => ProductWithStockResource::collection($products->items())->toArray($request),
                'last_page' => $products->lastPage(),
                'links' => $products->toArray()['links'],
            ],
        ]);
    }

}
