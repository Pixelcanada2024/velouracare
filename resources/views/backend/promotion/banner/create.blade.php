@extends('backend.layouts.app')

@section('content')
  <div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{ translate('Add New Promotion Banner') }}</h5>
  </div>
  <div class="">
    <!-- Error Meassages -->
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form class="form form-horizontal mar-top" action="{{ route('promotion-banners.store') }}" method="POST"
      enctype="multipart/form-data" id="choice_form">
      @csrf
      <div class="row gutters-5">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0 h6">{{ translate('Banner Information') }}</h5>
            </div>
            <div class="card-body">
              <div class="form-group row">
                <label class="col-md-3 col-from-label">{{ translate('Banner Title') }} <span
                    class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="title" placeholder="{{ translate('Banner Title') }}"
                    required>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-from-label">{{ translate('Description') }} <span
                    class="text-danger">*</span></label>
                <div class="col-md-8">
                  <textarea class="form-control" name="description" placeholder="{{ translate('Description') }}" rows="8"  required></textarea>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-from-label">{{ translate('Brand') }} <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <select class="form-control aiz-selectpicker" name="brand_id" data-live-search="true" required>
                    <option value="">{{ translate('Select Brand') }}</option>
                    @foreach ($brands as $brand)
                      <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-from-label">{{ translate('Discount Percent') }} <span
                    class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input type="number" class="form-control" name="discount_percent" min="0" max="100"
                    placeholder="{{ translate('Discount Percent') }}" required>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-from-label">{{ translate('Start Date') }} <span
                    class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="start_at" value="{{ now()->format('Y-m-d') }}" readonly required>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-from-label">{{ translate('End Date') }} <span
                    class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input type="text" class="form-control aiz-date-range-single" name="end_at" required>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0 h6">{{ translate('Banner Images') }}</h5>
            </div>
            <div class="card-body">
              <div class="form-group row">
                <label class="col-md-12 col-form-label">{{ translate('Tablet Banner') }} <small>(Recommended size
                    1410x590)</small></label>
                <div class="col-md-12">
                  <div class="input-group" data-toggle="aizuploader" data-type="image">
                    <div class="input-group-prepend">
                      <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="tablet_banner" class="selected-files">
                  </div>
                  <div class="file-preview box sm">
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-12 col-form-label">{{ translate('Mobile Banner') }} <small>(Recommended size
                    353x148)</small></label>
                <div class="col-md-12">
                  <div class="input-group" data-toggle="aizuploader" data-type="image">
                    <div class="input-group-prepend">
                      <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="mobile_banner" class="selected-files">
                  </div>
                  <div class="file-preview box sm">
                  </div>
                </div>
              </div>
            </div>
          </div>


          <div class="mt-4">
            <button type="submit" name="button"
              class="btn btn-primary btn-block">{{ translate('Save Banner') }}</button>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@section('script')
  <script>
    $(document).ready(function() {
      // Only initialize datepicker for end_at, not for readonly start_at
      $("input[name='end_at']").daterangepicker({
        singleDatePicker: true,
        startDate: moment().startOf('day'),
        locale: {
          format: 'YYYY-MM-DD'
        }
      });
    });
  </script>
@endsection
