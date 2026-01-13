<div class="tab-pane fade" id="home-page-partners-in-success" role="tabpanel"
  aria-labelledby="home-page-partners-in-success-tab">
  @php
    $lang = $_GET['lang'] ?? 'en';
    $settingNameExtra = $lang != 'en' ? '_ar' : '';
  @endphp
  <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tab" value="home-page-partners-in-success">

    <div class="bg-white p-3 p-sm-2rem">

      <!-- Partners In Success Section -->
      <div class="mb-5">
        <h5 class="mb-3">Home Page Partners In Success</h5>
        <div class="row">
          <!-- Partners In Success Image (Web/Desktop) -->
          <div class="col-md-4 mb-4">
            <div class="form-group">
              <label
                class="col-from-label fs-13 fw-500">{{ translate('Partners In Success Image (Web/Desktop)') }}</label><br>
              <p class="text-muted">{{ translate('Recommended size: 890x400px') }}</p>
              <div class="input-group" data-toggle="aizuploader" data-type="image">
                <div class="input-group-prepend">
                  <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                <input type="hidden" name="types[]" value="partners_in_success_web{{ $settingNameExtra }}">
                <input type="hidden" name="partners_in_success_web{{ $settingNameExtra }}"
                  value="{{ get_setting('partners_in_success_web' . $settingNameExtra) }}" class="selected-files">
              </div>
              <div class="file-preview box sm"></div>
            </div>
          </div>

          <!-- Partners In Success Image (Tablet) -->
          <div class="col-md-4 mb-4">
            <div class="form-group">
              <label
                class="col-from-label fs-13 fw-500">{{ translate('Partners In Success Image (Tablet)') }}</label><br>
              <p class="text-muted">{{ translate('Recommended size: 770x582px') }}</p>
              <div class="input-group" data-toggle="aizuploader" data-type="image">
                <div class="input-group-prepend">
                  <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                <input type="hidden" name="types[]" value="partners_in_success_tablet{{ $settingNameExtra }}">
                <input type="hidden" name="partners_in_success_tablet{{ $settingNameExtra }}"
                  value="{{ get_setting('partners_in_success_tablet' . $settingNameExtra) }}" class="selected-files">
              </div>
              <div class="file-preview box sm"></div>
            </div>
          </div>

          <!-- Partners In Success Image (Mobile) -->
          <div class="col-md-4 mb-4">
            <div class="form-group">
              <label
                class="col-from-label fs-13 fw-500">{{ translate('Partners In Success Image (Mobile)') }}</label><br>
              <p class="text-muted">{{ translate('Recommended size: 353x720px') }}</p>
              <div class="input-group" data-toggle="aizuploader" data-type="image">
                <div class="input-group-prepend">
                  <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                <input type="hidden" name="types[]" value="partners_in_success_mobile{{ $settingNameExtra }}">
                <input type="hidden" name="partners_in_success_mobile{{ $settingNameExtra }}"
                  value="{{ get_setting('partners_in_success_mobile' . $settingNameExtra) }}" class="selected-files">
              </div>
              <div class="file-preview box sm"></div>
            </div>
          </div>




        </div>

      </div>

      <!-- Save Button -->
      <div class="mt-4 text-right">
        <button type="submit"
          class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
      </div>
    </div>

  </form>

  <!-- Home Page Partners In Success Preview Section -->
  <div class="mt-5 border-top pt-4">
    <h5 class="mb-3">{{ translate('Home Page Partners In Success Preview') }}</h5>
    <div class=" p-4 rounded">
      <div class="row">
        <div class="col-md-4 text-center bg-light m-4">
          @if (get_setting('partners_in_success_web' . $settingNameExtra))
            <img src="{{ uploaded_asset(get_setting('partners_in_success_web' . $settingNameExtra)) }}"
              class="img-fluid" alt="web">
          @else
            <div class="text-muted">{{ translate('No web image set') }}</div>
          @endif
        </div>
        <div class="col-md-4 text-center bg-light m-4">
          @if (get_setting('partners_in_success_tablet' . $settingNameExtra))
            <img src="{{ uploaded_asset(get_setting('partners_in_success_tablet' . $settingNameExtra)) }}"
              class="img-fluid" alt="tablet">
          @else
            <div class="text-muted">{{ translate('No tablet image set') }}</div>
          @endif
        </div>
        <div class="col-md-4 text-center bg-light m-4">
          @if (get_setting('partners_in_success_mobile' . $settingNameExtra))
            <img src="{{ uploaded_asset(get_setting('partners_in_success_mobile' . $settingNameExtra)) }}"
              class="img-fluid" alt="mobile">
          @else
            <div class="text-muted">{{ translate('No mobile image set') }}</div>
          @endif
        </div>

      </div>
    </div>
  </div>
</div>
