@extends('backend.layouts.app')

@section('content')

@php
function isImage($filePath) {
$ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
}

function getBusinessTypeText($type) {
  switch($type) {
    case 0: return 'Offline';
    case 1: return 'Online';
    case 2: return 'Both';
    default: return 'Not Available';
  }
}

function getFindUsText($type) {
  switch($type) {
    case 0: return 'Google/Yahoo/Bing';
    case 1: return 'Social media ads';
    case 2: return 'Online webinar';
    case 3: return 'Offline event';
    case 4: return 'Others';
    default: return 'Not Available';
  }
}
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
  <div class="align-items-center">
    <h1 class="h3">{{ translate('User Business Information') }}</h1>
  </div>
  <div class="row align-items-center">
    <div class="col-auto">
      <a href="{{ route('customers.index') }}" class="btn btn-circle btn-info">
        <span>{{ translate('Back to All Customers') }}</span>
      </a>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="mb-0 h6">{{ translate('User Personal Details') }}</h5>
  </div>
  <div class="card-body">
    <div class="row">
      <!-- Name, Email, Phone -->
      <div class="col-md-6">
        <div class="form-group">
          <label class="font-weight-bold text-primary">{{ translate('Name') }}</label>
          <p>{{ $user->name ?? translate('Not Available') }}</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="font-weight-bold text-primary">{{ translate('Email') }}</label>
          <p>{{ $user->email ?? translate('Not Available') }}</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="font-weight-bold text-primary">{{ translate('Phone') }}</label>
          <p>{{ $user->phone ?? translate('Not Available') }}</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="font-weight-bold text-primary">{{ translate('Registration Date') }}</label>
          <p>{{ $user->created_at ? $user->created_at->format('M d, Y') : translate('Not Available') }}</p>
        </div>
      </div>
    </div>
  </div>
</div>

@if($user->businessInfo)
{{-- Address Information --}}
<div class="card mt-4">
  <div class="card-header">
    <h5 class="mb-0 h6">{{ translate('Address Information') }}</h5>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <label class="font-weight-bold text-primary">{{ translate('Address Line 1') }}</label>
        <p>{{ $user->businessInfo->address_line_one ?? translate('Not Available') }}</p>
      </div>
      <div class="col-md-6">
        <label class="font-weight-bold text-primary">{{ translate('Address Line 2') }}</label>
        <p>{{ $user->businessInfo->address_line_two ?? translate('Not Available') }}</p>
      </div>
      <div class="col-md-6">
        <label class="font-weight-bold text-primary">{{ translate('Country') }}</label>
        <p>{{ $user->businessInfo->country->name ?? translate('Not Available') }}</p>
      </div>
      @if ( config( "app.sky_type" , "Gulf" ) == "America")
        <div class="col-md-6">
          <label class="font-weight-bold text-primary">{{ translate('State') }}</label>
          <p>{{ $user->businessInfo->state ?? translate('Not Available') }}</p>
        </div>
      @endif
      <div class="col-md-6">
        <label class="font-weight-bold text-primary">{{ translate('City') }}</label>
        <p>{{ $user->businessInfo->city ?? translate('Not Available') }}</p>
      </div>
      <div class="col-md-6">
        <label class="font-weight-bold text-primary">{{ translate('Postal Code') }}</label>
        <p>{{ $user->businessInfo->postal_code ?? translate('Not Available') }}</p>
      </div>
    </div>
  </div>
</div>

{{-- Business Information --}}
<div class="card mt-4">
  <div class="card-header">
    <h5 class="mb-0 h6">{{ translate('Business Information') }}</h5>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <label class="font-weight-bold text-primary">{{ translate('Company Name') }}</label>
        <p>{{ $user->businessInfo->company_name ?? translate('Not Available') }}</p>
      </div>
      <div class="col-md-6">
        <label class="font-weight-bold text-primary">{{ translate('Store Link') }}</label>
        <p>
          @if($user->businessInfo->store_link)
            <a href="{{ $user->businessInfo->store_link }}" target="_blank" class="text-primary">
              {{ $user->businessInfo->store_link }}
            </a>
          @else
            {{ translate('Not Available') }}
          @endif
        </p>
      </div>
      <div class="col-md-6">
        <label class="font-weight-bold text-primary">{{ translate('Business ID') }}</label>
        <p>{{ $user->businessInfo->business_id ?? translate('Not Available') }}</p>
      </div>
      <div class="col-md-6">
        <label class="font-weight-bold text-primary">{{ translate('Business Type') }}</label>
        <p>{{ getBusinessTypeText($user->businessInfo->business_type) }}</p>
      </div>
      <div class="col-md-6">
        <label class="font-weight-bold text-primary">{{ translate('How did you find us?') }}</label>
        <p>{{ getFindUsText($user->businessInfo->find_us) }}</p>
      </div>
    </div>
  </div>
</div>

{{-- Business Proof Assets --}}
@if($user->businessInfo->business_proof_assets)
@php
$businessProofAssets = json_decode($user->businessInfo->business_proof_assets, true);
@endphp
<div class="card mt-4">
  <div class="card-header">
    <h5 class="mb-0 h6">{{ translate('Business Proof Documents') }}</h5>
  </div>
  <div class="card-body">
    @if(is_array($businessProofAssets) && count($businessProofAssets) > 0)
      <div class="row">
        @foreach($businessProofAssets as $index => $assetPath)
          @php
          $assetUrl = asset('public/storage/' . $assetPath);
          @endphp
          <div class="col-md-4 mb-3">
            <div class="card">
              <div class="card-header">
                <h6 class="mb-0">{{ translate('Document') }} {{ $index + 1 }}</h6>
              </div>
              <div class="card-body text-center">
                @if(isImage($assetPath))
                  <img src="{{ $assetUrl }}" alt="Business Proof {{ $index + 1 }}" class="img-fluid img-thumbnail mb-2" style="max-height: 150px;">
                @else
                  <div class="mb-2">
                    <i class="las la-file-pdf" style="font-size: 48px; color: #dc3545;"></i>
                  </div>
                @endif
                <div>
                  <a href="{{ $assetUrl }}" target="_blank" class="btn btn-sm btn-primary">
                    <i class="las la-eye mr-1"></i> {{ translate('View') }}
                  </a>
                  <a href="{{ $assetUrl }}" download class="btn btn-sm btn-info ml-1">
                    <i class="las la-download mr-1"></i> {{ translate('Download') }}
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <p class="text-muted">{{ translate('No business proof documents available.') }}</p>
    @endif
  </div>
</div>
@endif

@else
<div class="card mt-4">
  <div class="card-body text-center py-4">
    <p class="h5 text-muted">{{ translate('No business information available for this user.') }}</p>
  </div>
</div>
@endif

{{-- Account Status --}}
<div class="card mt-4">
  <div class="card-header">
    <h5 class="mb-0 h6">{{ translate('Account Status') }}</h5>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <label class="font-weight-bold text-primary">{{ translate('Current Status') }}</label>
        <p>
          @if($user->status === 0)
          <span class="badge badge-inline badge-warning">{{ translate('Pending') }}</span>
          @elseif($user->status === 1)
          <span class="badge badge-inline badge-success">{{ translate('Approved') }}</span>
          @elseif($user->status === 2)
          <span class="badge badge-inline badge-danger">{{ translate('Rejected') }}</span>
          @endif
        </p>
      </div>
      <div class="col-md-6">
        <form action="{{ route('customers.update_status', $user->id) }}" method="POST">
          @csrf
          <label class="font-weight-bold text-primary">{{ translate('Update Status') }}</label>
          <select class="form-control aiz-selectpicker mb-2" name="status">
            <option value="0" @if($user->status == 0) selected @endif>{{ translate('Pending') }}</option>
            <option value="1" @if($user->status == 1) selected @endif>{{ translate('Approve') }}</option>
            <option value="2" @if($user->status == 2) selected @endif>{{ translate('Reject') }}</option>
          </select>
          <button type="submit" class="btn btn-sm btn-primary">{{ translate('Update Status') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
