@extends('backend.layouts.app')

@section('content')
  <div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h1 class="h3">{{ translate('All Promotion Banners') }}</h1>
      </div>
      <div class="col-md-6 text-md-right">
        <a href="{{ route('promotion-banners.create') }}" class="btn btn-primary">
          <span>{{ translate('Add New Banner') }}</span>
        </a>
      </div>
    </div>
  </div>

  <div class="card">
    <form class="" id="sort_products" action="" method="GET">
      <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
          <h5 class="mb-md-0 h6">{{ translate('All Banners') }}</h5>
        </div>
        <div class="col-md-4">
          <div class="input-group input-group-sm">
            <input type="text" class="form-control" id="search" name="search"
              @isset($search) value="{{ $search }}" @endisset
              placeholder="{{ translate('Search by title') }}">
          </div>
        </div>
      </div>
    </form>

    <div class="card-body">
      <table class="table aiz-table mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th width="20%">{{ translate('Title') }}</th>
            <th>{{ translate('Brand') }}</th>
            <th>{{ translate('Discount') }}</th>
            <th>{{ translate('Tablet Banner') }}</th>
            <th>{{ translate('Mobile Banner') }}</th>
            <th>{{ translate('Start Date') }}</th>
            <th>{{ translate('End Date') }}</th>
            <th class="text-right">{{ translate('Options') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($banners as $key => $banner)
            <tr>
              <td>{{ $key + 1 }}</td>
              <td>{{ $banner->title }}</td>
              <td>{{ optional($banner->brand)->name }}</td>
              <td>{{ $banner->discount_percent }}%</td>
              <td>
                <img src="{{ uploaded_asset($banner->tablet_banner) }}" alt="Tablet Banner" class="h-50px">
              </td>
              <td>
                <img src="{{ uploaded_asset($banner->mobile_banner) }}" alt="Mobile Banner" class="h-50px">
              </td>
              <td>{{ date('Y-m-d', strtotime($banner->start_at)) }}</td>
              <td>{{ date('Y-m-d', strtotime($banner->end_at)) }}</td>
              <td class="text-right">
                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                  href="{{ route('promotion-banners.edit', $banner->id) }}" title="{{ translate('Edit') }}">
                  <i class="las la-edit"></i>
                </a>
                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                  data-href="{{ route('promotion-banners.destroy', $banner->id) }}" title="{{ translate('Delete') }}">
                  <i class="las la-trash"></i>
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="aiz-pagination">
        {{ $banners->links() }}
      </div>
    </div>
  </div>
@endsection

@section('modal')
  @include('modals.delete_modal')
@endsection

@section('script')
@endsection
