@extends('backend.layouts.app')
@php
    $lang = app()->getLocale();
    $hiddenLang = $lang == 'ar' ? 'en' : 'ar';
    $isNotAmerica = !config('app.is_america');
@endphp
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('All Brands') }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="@if (auth()->user()->can('add_brand')) col-lg-7 @else col-lg-12 @endif">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">{{ translate('Brands') }}</h5>
                    </div>
                    <div class="col-md-4">
                        <form class="" id="sort_brands" action="" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="search" name="search"
                                    @isset($sort_search) value="{{ $sort_search }}" @endisset
                                    placeholder="{{ translate('Type name & Enter') }}">
                            </div>
                        </form>
                    </div>
                </div>
                {{-- @php
                    $json_top_brand_ids = get_setting('top_brands');
                    $top_brand_ids = json_decode($json_top_brand_ids, true) ?? [];
                @endphp
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data"
                    id="top_brands_form">
                    @csrf
                    <input type="hidden" name="tab" value="brands">
                    <input type="hidden" name="types[]" value="top_brands">
                </form> --}}
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Name') }}
                                    @if ($isNotAmerica)
                                        [{{ $lang }}]
                                        <span class="badge badge-info" style="cursor: pointer"
                                            title="hover to show translation">
                                            {{ $hiddenLang }}
                                        </span>
                                    @endif
                                </th>
                                <th>{{ translate('Logo') }}</th>
                                {{-- <th>{{ translate('Is Top Brand?') }}</th> --}}
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands as $key => $brand)
                                <tr>
                                    <td>{{ $key + 1 + ($brands->currentPage() - 1) * $brands->perPage() }}</td>
                                    <td>{{ $brand->getTranslation('name') }}
                                        @if ($isNotAmerica)
                                            <span class="badge badge-info" style="cursor: pointer"
                                                title="{{ $brand->name_trans[$hiddenLang] ?? 'no translated text' }}">
                                                {{ $hiddenLang }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <img src="{{ uploaded_asset($brand->logo) }}" alt="{{ translate('Brand') }}"
                                            class="h-50px">
                                    </td>
                                    {{-- <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="document.getElementById('top_brands_form').submit()"
                                                name="top_brands[]" form="top_brands_form" value="{{ $brand->id }}"
                                                type="checkbox" <?php if (in_array($brand->id, $top_brand_ids)) {
                                                    echo 'checked';
                                                } ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </td> --}}
                                    <td class="text-right">
                                        @can('edit_brand')
                                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                href="{{ route('brands.edit', ['id' => $brand->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete_brand')
                                            <a href="#"
                                                class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                                data-href="{{ route('brands.destroy', $brand->id) }}"
                                                title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $brands->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
        @can('add_brand')
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Add New Brand') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('brands.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">{{ translate('Name') }}</label>
                                <input type="text" placeholder="{{ translate('Name') }}" name="name_trans[en]"
                                    class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">{{ translate('Logo') }}
                                    <small>({{ translate('120x80') }})</small></label>
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="logo" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                <small
                                    class="text-muted">{{ translate('Minimum dimensions required: 126px width X 100px height.') }}</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="name">{{ translate('Tablet && Website Banner') }}
                                    <small>({{ translate('1920*550') }})</small></label>
                                <div class="input-group mt-1" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="web_banner_trans[en]" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                <small
                                    class="text-muted">{{ translate('Minimum dimensions required: 1920px width X 550px height.') }}</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="name">{{ translate('Mobile Banner') }}
                                    <small>({{ translate('393x229') }})</small></label>
                                <div class="input-group mt-1" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="mobile_banner_trans[en]" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                <small
                                    class="text-muted">{{ translate('Minimum dimensions required: 393px width X 229px height.') }}</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="name">{{ translate('Meta Title') }}</label>
                                <input type="text" class="form-control " name="meta_title"
                                    placeholder="{{ translate('Meta Title') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">{{ translate('Description & Meta Description') }}</label>
                                <textarea name="meta_description" rows="5" class="form-control"></textarea>
                            </div>
                            <div class="form-group mb-3 text-right">
                                <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Select Top Brands') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tab" value="brands">
                            <div class="bg-white ">
                                <div class="w-100">
                                    <label
                                        class="col-from-label fs-13 fw-500 mb-3">{{ translate('Top Brands (Max 12)') }}</label>
                                    <!-- Brands -->
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="top_brands">
                                        <select name="top_brands[]" class="form-control aiz-selectpicker" multiple
                                            data-max-options="12" data-live-search="true"
                                            data-selected="{{ get_setting('top_brands') }}">
                                            @foreach (\App\Models\Brand::all() as $key => $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Save Button -->
                                <div class="mt-4 text-right">
                                    <button type="submit"
                                        class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function sort_brands(el) {
            $('#sort_brands').submit();
        }
    </script>
@endsection
