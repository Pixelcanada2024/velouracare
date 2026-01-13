@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- System Settings -->
        <div class="card">
            <div class="card-header">
                <h1 class="mb-0 h6">{{ translate('Pop Up Marketing Settings') }}</h1>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Product Selection -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{ translate('Select Product') }}</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="types[]" value="pop_up_marketing_product_id" >
                            <select class="form-control aiz-selectpicker" id="pop_up_marketing_product_id" placeholder="Type To Search" name="pop_up_marketing_product_id" data-live-search="true" required>
                                <option value="0">No Product</option>
                                @if(get_setting('pop_up_marketing_product_id'))
                                    <option value="{{ get_setting('pop_up_marketing_product_id') }}" selected>
                                        {{ optional(\App\Models\Product::find(get_setting('pop_up_marketing_product_id')))->name }}
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <!-- Image Upload -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{ translate('Pop Up Image') }} <small>(Recommended size710x700)</small></label>
                        <div class="col-sm-8">

                            <input type="hidden" name="types[]" value="pop_up_marketing_image_id">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="pop_up_marketing_image_id" class="selected-files" value="{{ get_setting('pop_up_marketing_image_id') }}">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <!-- Update Button -->
                    <div class="mt-4 text-right">
                        <button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var $select = $('#pop_up_marketing_product_id');
            $select.on('shown.bs.select', function () {
                $('.bs-searchbox input').focus();
            });
            $select.parent().on('keyup', '.bs-searchbox input', function() {
                var search = $(this).val();
                if (search.length < 2) return;
                $.ajax({
                    url: '{{ route('marketing.pop-up-marketing.search-products') }}',
                    data: {q: search},
                    success: function(data) {
                        var options = '';
                        data.forEach(function(item) {
                            options += '<option value="'+item.id+'">'+item.name+'</option>';
                        });
                        $select.html(options);
                        $select.selectpicker('refresh');
                    }
                });
            });
        });
    </script>
@endsection



