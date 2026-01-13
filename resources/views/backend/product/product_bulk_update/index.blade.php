@extends('backend.layouts.app')

@section('content')

<div class="card">
  <div class="card-header">
    <h5 class="mb-0 h6">{{translate('Product Bulk Update')}}</h5>
  </div>
  <div class="card-body">
    <div class="alert"
      style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
      <strong>{{ translate('Instructions')}}:</strong>
      <p>1. {{ translate('Download the Excel file below containing all your products') }}</p>
      <p>2. {{ translate('Edit only the fields you want to update (leave other fields unchanged)') }}</p>
      <p>3. {{ translate('The ID column is required and must not be changed') }}</p>
      <p>4. {{ translate('For categories and brands: use their corresponding ID from the category/brand lists') }}</p>
      <p>5. {{ translate('Upload the edited file to update your products') }}</p>
      <p>6. {{ translate('For Available document ') }}</p>
      <ul style="margin: 0 0 0 20px;">
        <li>0 means Commercial Invoice</li>
        <li>1 means MSDS</li>
        <li> 2 means MSDS, Commercial Invoice </li>
        </ul>
    </div>
    <br>
    <div class="">
      <a href="{{ route('products_bulk_update.export') }}" class="btn btn-primary">{{ translate('Download Product
        Excel')}}</a>
    </div>
    <div class="alert"
      style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
      <p style="margin-bottom:0">{{translate('You can download the pdf to get Category, Brand and countries id')}}.</p>
    </div>
    <br>
    <div class="">
      <a href="{{ route('pdf.download_category') }}"><button class="btn btn-info">{{translate('Download
          Category')}}</button></a>
      <a href="{{ route('pdf.download_brand') }}"><button class="btn btn-info">{{translate('Download
          Brand')}}</button></a>

    <a href="{{ route('pdf.download_country') }}"><button class="btn btn-info">{{translate('Download Countries')}}</button></a>

    </div>
    <br>
    <br>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="mb-0 h6"><strong>{{translate('Upload Updated Product File')}}</strong></h5>
  </div>
  <div class="card-body">
    <form class="form-horizontal" action="{{ route('products_bulk_update.import') }}" method="POST"
      enctype="multipart/form-data">
      @csrf
      <div class="form-group row">
        <div class="col-sm-9">
          <div class="custom-file">
            <label class="custom-file-label">
              <input type="file" name="bulk_file" class="custom-file-input" required>
              <span class="custom-file-name">{{ translate('Choose File')}}</span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group mb-0">
        <button type="submit" class="btn btn-primary">{{translate('Update Products')}}</button>
      </div>
    </form>
  </div>
</div>

@if(session()->has('import_result'))
<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0 h6"><strong>{{translate('Import Result')}}</strong></h5>
    </div>
    <div class="card-body">
        @if(session('import_result') == 'success')
        <div class="alert alert-success">
            <i class="las la-check-circle"></i>
            {{ translate('All products updated successfully!') }}
            @if(session('updated_count'))
            ({{ session('updated_count') }} {{ translate('products processed') }})
            @endif
        </div>
        @endif
    </div>
</div>
@endif

@if(session()->has('import_errors'))
<div class="card mt-3">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0 h6"><strong>{{translate('Update Errors')}}</strong></h5>
    </div>
    <div class="card-body">
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach(session('import_errors') as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

@endsection
