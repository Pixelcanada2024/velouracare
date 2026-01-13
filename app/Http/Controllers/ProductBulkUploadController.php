<?php

namespace App\Http\Controllers;

use PDF;
use Auth;
use App\Models\User;
use App\Models\Brand;
use MyCLabs\Enum\Enum;
use App\Models\Country;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductsExport;
use App\Models\ProductsImport;
use App\Models\ProductsImageImport;
use App\Models\ProductsUpdateExport;
use App\Models\ProductsUpdateImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ProductStocksUpdateExport;
use App\Models\ProductStocksUpdateImport;


class ProductBulkUploadController extends Controller
{
  public function __construct()
  {

    $this->middleware(['permission:product_bulk_import'])->only('index');
    $this->middleware(['permission:product_bulk_export'])->only('export');
  }

  public function index()
  {
    if (Auth::user()->user_type == 'seller') {
      if (Auth::user()->shop->verification_status) {
        return view('seller.product_bulk_upload.index');
      } else {
        flash(translate('Your shop is not verified yet!'))->warning();
        return back();
      }
    } elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
      return view('backend.product.bulk_upload.index');
    }
  }

  public function export()
  {
    return Excel::download(new ProductsExport, 'products.xlsx');
  }

  public function pdf_download_category()
  {
    $categories = Category::all();

    return PDF::loadView('backend.downloads.category', [
      'categories' => $categories,
    ], [], [])->download('category.pdf');
  }

  public function pdf_download_brand()
  {
    $brands = Brand::all();

    return PDF::loadView('backend.downloads.brand', [
      'brands' => $brands,
    ], [], [])->download('brands.pdf');
  }

  public function pdf_download_country()
  {
    $countries = Country::all();

    return PDF::loadView('backend.downloads.country', [
      'countries' => $countries,
    ], [], [])->download('countries.pdf');
  }


  public function pdf_download_seller()
  {
    $users = User::where('user_type', 'seller')->get();

    return PDF::loadView('backend.downloads.user', [
      'users' => $users,
    ], [], [])->download('user.pdf');
  }

  public function bulk_upload(Request $request)
  {
    if ($request->hasFile('bulk_file')) {
      $import = new ProductsImport;
      Excel::import($import, request()->file('bulk_file'));
    }

    return back();
  }


  public function productsUpdateIndex()
  {
    return view('backend.product.product_bulk_update.index');
  }

  public function productsUpdateExport()
  {
    return Excel::download(new ProductsUpdateExport, 'products_update.xlsx');
  }

  public function productsUpdateImport(Request $request)
  {
    if ($request->hasFile('bulk_file')) {
      $import = new ProductsUpdateImport;
      Excel::import($import, request()->file('bulk_file'));
    }

    return back();
  }

  public function productStocksUpdateIndex()
  {
    return view('backend.product.product_stocks_bulk_update.index');
  }

  public function productStocksUpdateExport()
  {
    return Excel::download(new ProductStocksUpdateExport, 'product_stocks_update.xlsx');
  }

  public function productStocksUpdateImport(Request $request)
  {
    if ($request->hasFile('bulk_file')) {
      $import = new ProductStocksUpdateImport;
      Excel::import($import, request()->file('bulk_file'));
    }

    return back();
  }

    
  public function downloadProductImageIndex()
  {
    return view('backend.product.product_bulk_update.img-index');
  }

  public function downloadProductImage( Request $request )
  {
    if ($request->hasFile('bulk_file')) {
      $import = new ProductsImageImport;
      Excel::import($import, request()->file('bulk_file'));
    }

    return back();
  }
}
