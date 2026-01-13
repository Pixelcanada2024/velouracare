@extends('backend.layouts.app')

@section('content')

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0 h6">{{ translate('Product Bulk Upload') }}</h5>
    </div>
    <div class="card-body">
      <div class="alert"
        style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
        <strong>{{ translate('Step 1') }}:</strong>
        <p>1. For <strong>categories and brands</strong>: download the Categories and Brands files, use its corresponding
          ID in the file.</p>
        <p>2. If the category or brand is new, you must add them first from the admin panel, then re-download the file and
          use the new IDs.</p>
        <p>3. For <strong>Country</strong>: download the Countries file, use its corresponding ID in the file.</p>
        <p>4. For <strong>images</strong>: upload them first (manually), then copy-paste the image Ids into the file.</p>
        <p><strong>Note on categories:</strong></p>
        <ul style="margin: 0 0 0 20px;">
          <li>Main Category: The primary category of the product.</li>
          <li>Multiple Categories: The product can belong to more than one additional category.</li>
        </ul>
        <p>5. {{ translate('For Available document ') }}</p>
        <ul style="margin: 0 0 0 20px;">
          <li>0 means Commercial Invoice</li>
          <li>1 means MSDS</li>
          <li> 2 means MSDS, Commercial Invoice </li>
        </ul>

      </div>
      <br>
      <div class="">
        <a href="{{ static_asset('download/product_bulk_demo.xlsx') }}" download><button
            class="btn btn-info">{{ translate('Download CSV') }}</button></a>
      </div>
      <div class="alert"
        style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
        <strong>{{ translate('Step 2') }}:</strong>
        <p>1. {{ translate('Category, Brand and Country should be in numerical id') }}.</p>
        <p>2. {{ translate('You can download the pdf to get Category, Brand and Country id') }}.</p>
      </div>
      <br>
      <div class="">
        <a href="{{ route('pdf.download_category') }}"><button
            class="btn btn-info">{{ translate('Download Category') }}</button></a>
        <a href="{{ route('pdf.download_brand') }}"><button
            class="btn btn-info">{{ translate('Download Brand') }}</button></a>
        <a href="{{ route('pdf.download_country') }}"><button
            class="btn btn-info">{{ translate('Download Countries') }}</button></a>
      </div>
      <br>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0 h6"><strong>{{ translate('Upload Product File') }}</strong></h5>
    </div>
    <div class="card-body">
      <form class="form-horizontal" action="{{ route('bulk_product_upload') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
          <div class="col-sm-9">
            <div class="custom-file">
              <label class="custom-file-label">
                <input type="file" name="bulk_file" class="custom-file-input" required>
                <span class="custom-file-name">{{ translate('Choose File') }}</span>
              </label>
            </div>
          </div>
        </div>
        <div class="form-group mb-0">
          <button
            onclick="this.style.display='none';
                  this.parentNode.querySelector('.btn-warning').style.display='block';
                  this.form.submit();"
            type="button" class="btn btn-info">{{ translate('Upload Excel') }}</button>
          <button type="button" class="btn btn-warning" style="display: none;">{{ translate('Loading') }}</button>
        </div>
      </form>
    </div>
  </div>

  @if (session()->has('import_errors'))
    <div class="card mt-3">
      <div class="card-header bg-danger text-white">
        <h5 class="mb-0 h6"><strong>{{ translate('Import Errors') }}</strong></h5>
      </div>
      <div class="card-body">
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach (session('import_errors') as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

@endsection
