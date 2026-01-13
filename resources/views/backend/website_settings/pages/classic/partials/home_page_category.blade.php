<div class="tab-pane fade" id="home-page-category" role="tabpanel" aria-labelledby="home-page-category-tab">

  <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tab" value="home-page-category">

    <div class="bg-white p-3 p-sm-2rem">

      <!-- Categories Section -->
      <div class="mb-5">
        <h5 class="mb-3">Categories Section</h5>
        <div class="row home-category-target">
          @php
            $home_categories = json_decode(get_setting('home_categories'), true) ?: [];
            $categories = \App\Models\Category::all();
            $categoryNames = [];
            foreach ($categories as $cat) {
                $categoryNames[$cat->id] = $cat->getTranslation('name');
            }
            foreach (['1','2','3','4'] as $idx) {
              if (!isset($home_categories[$idx])) {
                $home_categories[$idx] = ['image' => '', 'category_id' => ''];
              }
            }
          @endphp
          @foreach ($home_categories as $key => $category)
            <div class="col-md-5 m-4 p-3"
              style="border-radius: 10px; border: 2px solid rgba({{ in_array($key, ['1','2','3','4']) ? (180 + 10 * (int)$key) : 200 }}, {{ in_array($key, ['1','2','3','4']) ? (180 + 10 * (int)$key) : 200 }}, {{ in_array($key, ['1','2','3','4']) ? (180 + 10 * (int)$key) : 200 }}, 0.7)">
              <div class="form-group">
                <label class="col-from-label fs-13 fw-500">{{ translate(in_array($key, ['1','2','3','4']) ? "Category Image {$key}" : 'Category Image') }}</label><br>
                <p class="text-muted">{{ translate('Recommended size: 400x400px') }}
                </p>
                <div class="input-group" data-toggle="aizuploader" data-type="image">
                  <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary">
                      {{ translate('Browse') }}</div>
                  </div>
                  <div class="form-control file-amount">
                    {{ translate('Choose File') }}</div>
                  <input type="hidden" name="types[]" value="home_categories">
                  <input type="hidden" name="home_categories[{{ $key }}][image]"
                    value="{{ $category['image'] ?? '' }}" class="selected-files">
                </div>
                <div class="file-preview box sm"></div>
              </div>
              <div class="form-group">
                <label class="col-from-label fs-13 fw-500">{{ translate(in_array($key, ['1','2','3','4']) ? "Category {$key}" : 'Category') }}</label>
                <select class="form-control aiz-selectpicker" name="home_categories[{{ $key }}][category_id]"
                  data-live-search="true" required>
                  @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}"
                      {{ ($category['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>
                      {{ $cat->getTranslation('name') }}</option>
                  @endforeach
                </select>
              </div>
              @if (!in_array($key, ['1','2','3','4']))
                <div class="form-group">
                  <button type="button" class="btn btn-icon btn-circle btn-sm btn-soft-danger"
                    data-toggle="remove-parent" data-parent=".col-md-5">
                    <i class="las la-times"></i>
                  </button>
                </div>
              @endif
            </div>
          @endforeach
        </div>




        <!-- Add button -->
        <div class="">
          <button type="button"
            class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
            style="background: #fcfcfc;" data-toggle="add-more"
            data-content='
                <div class="col-md-5 m-4 p-3" style="border-radius: 10px; border: 2px solid rgba(200, 200, 200, 0.7);">
                    <div class="form-group">
                        <label class="col-from-label fs-13 fw-500">{{ translate('Category') }}</label>
                        <select class="form-control aiz-selectpicker" name="home_categories[][category_id]" data-live-search="true" required>
                          @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->getTranslation('name') }}</option>
                          @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-from-label fs-13 fw-500">{{ translate('Category Image') }}</label><br>
                        <p class="text-muted">{{ translate('Recommended size: 400x400px') }}</p>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="types[]" value="home_categories">
                            <input type="hidden" name="home_categories[][image]" class="selected-files">
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".col-md-5">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                </div>'
            data-target=".home-category-target">
            <i class="las la-2x text-success la-plus-circle"></i>
            <span class="ml-2">{{ translate('Add New Category') }}</span>
          </button>
        </div>



      </div>

      <!-- Save Button -->
      <div class="mt-4 text-right">
        <button type="submit"
          class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
      </div>
    </div>

  </form>

  <!-- Category Preview Section -->
  <div class="mt-5 border-top pt-4">
    <h5 class="mb-3">{{ translate('Category Preview') }}</h5>
    <div class="bg-light p-4 rounded">
      @php
        $home_categories = $home_categories ?: [];
        foreach (['1','2','3','4'] as $idx) {
          if (!isset($home_categories[$idx])) {
            $home_categories[$idx] = ['image' => '', 'category_id' => ''];
          }
        }
      @endphp
      <div class="row">
        @foreach ($home_categories as $key => $category)
          <div class="col-3 mb-3">
            <div class="border rounded overflow-hidden">
              @php
                $img = $category['image'] ?? null;
              @endphp
              @if (isset($img) && !!$img)
                @if (str_starts_with($img, 'http'))
                  <img src="{{ $img }}" style="width: 100%; aspect-ratio: 1 / 1;">
                @else
                  <img src="{{ uploaded_asset($img) }}" style="width: 100%; aspect-ratio: 1 / 1;">
                @endif
              @else
                <div
                  style="width: 100%; aspect-ratio: 1 / 1; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
                  No Image</div>
              @endif
              <br>
              <strong class="text-muted">{{ $categoryNames[$category['category_id'] ?? null] ?? 'Category' }}</strong>
              <i class="las la-language text-danger" title="{{ translate('Translatable') }}"></i>
            </div>
            <strong class="text-muted">{{ translate('Category') . ' ' . $key }}</strong>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
