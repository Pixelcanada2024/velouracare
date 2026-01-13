@extends('backend.layouts.app')

@section('content')

<div class="card">
  <div class="card-header">
    <h5 class="mb-0 h6">{{translate('Product Variants Bulk Update')}}</h5>
  </div>
  <div class="card-body">
    <div class="alert"
      style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
      <strong>{{ translate('Instructions')}}:</strong>
      <p>1. {{ translate('Download the Excel file below containing all your product variants') }}</p>
      <p>2. {{ translate('Edit only the fields you want to update (leave other fields unchanged)') }}</p>
      <p>3. {{ translate('The stock_id column is required and must not be changed') }}</p>
      <p>4. {{ translate('You can update variant name, SKU, price, image, and quantity') }}</p>
      <p>5. {{ translate('Upload the edited file to update your product variants') }}</p>
    </div>
    <br>
    <div class="">
      <a href="{{ route('product_stocks_bulk_update.export') }}" class="btn btn-primary">{{ translate('Download Product Variants Excel')}}</a>
    </div>
    <br>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="mb-0 h6"><strong>{{translate('Upload Updated Product Variants File')}}</strong></h5>
  </div>
  <div class="card-body">
    <form class="form-horizontal" action="{{ route('product_stocks_bulk_update.import') }}" method="POST"
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
        <button type="submit" class="btn btn-primary">{{translate('Update Product Variants')}}</button>
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
            {{ translate('All Variants updated successfully!') }}
            @if(session('updated_count'))
            ({{ session('updated_count') }} {{ translate('Variants processed') }})
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