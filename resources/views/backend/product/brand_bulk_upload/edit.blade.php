@extends('backend.layouts.app')

@section('content')

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0 h6">{{ translate('Brand Bulk Update') }}</h5>
    </div>
    <div class="card-body">
      <div class="alert"
        style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
        <strong>{{ translate('Steps') }}:</strong>
        <br>
        <p>1. {{ translate('Download the skeleton file and fill it with proper data') }}.</p>
        <p>
          2.{{ translate('Once you have downloaded and filled the skeleton file, upload it in the form below and submit') }}.
        </p>
        <p>3. {{ translate('For images Upload image -- first in uploads -- then Use image id from uploaded files') }}.</p>
      </div>
      <br>
      <div class="">
        <a href="{{  route('brand_bulk_export')  }}"><button
            class="btn btn-info">{{ translate('Download Current Brands') }}</button></a>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0 h6"><strong>{{ translate('Upload Brand File') }}</strong></h5>
    </div>
    <div class="card-body">
      <form class="form-horizontal" action="{{ route('brand_bulk_update') }}" method="POST" enctype="multipart/form-data">
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
          <button onclick="this.style.display='none';
                    this.parentNode.querySelector('.btn-warning').style.display='block';
                    this.form.submit();" type="button" class="btn btn-info">{{ translate('Update') }}</button>
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