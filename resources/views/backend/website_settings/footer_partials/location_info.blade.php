@php
  $lang = $_GET['lang'] ?? 'en';
  $allLangs = get_all_active_language();
@endphp
<div class="card">
  <div class="card-header">
    <h6 class="fw-600 mb-0">{{ translate('Location Info') }}</h6>
  </div>

  <div class="card-body ">
    <div class=" shadow-none ">
      <div class="alert alert-info mt-3" role="alert">
        <strong>Tip:</strong> Use <code>\n</code> to create a new line.
      </div>

      <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @php
          $locations = json_decode(get_setting('contact_us_locations'), true) ?: [];
        @endphp

        <div class="w3-locations-target">
          <input type="hidden" name="types[]" value="contact_us_locations">

          @if (!empty($locations))
            @foreach ($locations as $key => $loc)
              @php
                $addr = $loc['address'] ?? [];
                $iframe = $loc['iframe'] ?? '';
              @endphp
              <div class="row gutters-5 mb-3">
                <div class="col">
                    <div class="form-group">
                      <label class="col-from-label"><span>{{ $loop->iteration }}</span> - {{ translate('Contact address') }} ({{ translate('Translatable') }})</label>
                      @foreach ($allLangs as $l)
                        @if ($l->code == $lang)
                          <input type="text" class="form-control"
                            placeholder="Address country,city,street,postal code ({{ $l->name }})"
                            name="contact_us_locations[{{ $key }}][address][{{ $l->code }}]"
                            value="{{ $addr[$l->code] ?? '' }}">
                        @else
                          <input type="hidden" name="contact_us_locations[{{ $key }}][address][{{ $l->code }}]"
                            value="{{ $addr[$l->code] ?? '' }}">
                        @endif
                      @endforeach
                    </div>
                  </div>
                <div class="col">
                  <div class="form-group">
                    <label class="col-from-label"> {{ translate('Map Iframe Src') }}</label>
                    <input type="text" class="form-control" placeholder="{{ translate('Map Iframe Src') }}"
                      name="contact_us_locations[{{ $key }}][iframe]" value="{{ $iframe }}">
                  </div>
                </div>
                <div class="col-auto d-flex align-items-center">
                  <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                    data-toggle="remove-parent" data-parent=".row">
                    <i class="las la-times"></i>
                  </button>
                </div>
              </div>
            @endforeach
          @endif
        </div>



        <div class="mt-4 text-right">
          <button type="button" class="btn btn-soft-secondary btn-md mx-4 " data-toggle="add-more"
            data-target=".w3-locations-target"
            data-content='@php ob_start(); @endphp
                <div class="row gutters-5 mb-3">
                  <div class="col">
                    <div class="form-group">
                        <label class="col-from-label"><span>#</span> - {{ translate('Contact address') }} ({{ translate('Translatable') }})</label>
                        @foreach ($allLangs as $l)
                        @if ($l->code == $lang)
                        <input type="text" class="form-control" placeholder="Address country,city,street,postal code ({{ $l->name }})" name="contact_us_locations[][address][{{ $l->code }}]">
                        @else
                        <input type="hidden" name="contact_us_locations[][address][{{ $l->code }}]" value="">
                        @endif
                        @endforeach
                      </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label class="col-from-label"> {{ translate('Map Iframe Src') }}</label>
                      <input type="text" class="form-control" placeholder="{{ translate('Map Iframe Src') }}" name="contact_us_locations[][iframe]">
                    </div>
                  </div>
                  <div class="col-auto d-flex align-items-center">
                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                      <i class="las la-times"></i>
                    </button>
                  </div>
                </div>
              @php
                $c = trim(ob_get_clean());
                echo e($c);
              @endphp'>
            {{ translate('Add New') }}
          </button>
          <button type="submit"
            class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
