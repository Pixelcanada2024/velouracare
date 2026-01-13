<?php

namespace App\Http\Controllers;

use App\Models\BrandsExport;
use App\Models\BrandsImport;
use App\Models\BrandsUpdate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BrandBulkUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:brand_bulk_upload'])->only('index');
    }

    public function index()
    {
        return view('backend.product.brand_bulk_upload.index');
    }

    public function export()
    {
        return Excel::download(new BrandsExport, 'brands.xlsx');
    }

    public function bulk_upload(Request $request)
    {
        if (!extension_loaded('zip')){
            flash(translate('Please enable the Zip extension'))->error();
            return back();
        }

        if ($request->hasFile('bulk_file')) {
            $import = new BrandsImport;
            Excel::import($import, request()->file('bulk_file'));
        }

        return back();
    }

    public function edit()
    {
        return view('backend.product.brand_bulk_upload.edit');
    }

    public function bulk_update(Request $request)
    {
          if (!extension_loaded('zip')){
            flash(translate('Please enable the Zip extension'))->error();
            return back();
        }

        if ($request->hasFile('bulk_file')) {
            $import = new BrandsUpdate;
            Excel::import($import, request()->file('bulk_file'));
        }

        return back();
    }
}
