@extends('backend.layouts.app')

@section('content')
    @php
        $lang = app()->getLocale();
        $hiddenLang = $lang == 'ar' ? 'en' : 'ar';
        $isNotAmerica = !config('app.is_america');
    @endphp
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('All FAQs') }}</h1>
        </div>
    </div>

    <div class="row">
        <div class=" col-lg-7 ">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">{{ translate('FAQs') }}</h5>
                    </div>
                    <div class="col-md-4">
                        <form class="" id="sort_faqs" action="" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="search" name="search"
                                    @isset($sort_search)
                value="{{ $sort_search }}" @endisset
                                    placeholder="{{ translate('Type question & Enter') }}">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Question') }}
                                    @if ($isNotAmerica)
                                        [{{ $lang }}]
                                        <span class="badge badge-info" style="cursor: pointer"
                                            title="hover to show translation">
                                            {{ $hiddenLang }}
                                        </span>
                                    @endif
                                </th>
                                <th>{{ translate('Category') }}</th>
                                <th>{{ translate('Answer') }}
                                    @if ($isNotAmerica)
                                        [{{ $lang }}]
                                        <span class="badge badge-info" style="cursor: pointer"
                                            title="hover to show translation">
                                            {{ $hiddenLang }}
                                        </span>
                                    @endif
                                </th>
                                <th>{{ translate('Show in Home') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($faqs as $key => $faq)
                                <tr>
                                    <td>{{ $key + 1 + ($faqs->currentPage() - 1) * $faqs->perPage() }}</td>
                                    <td>{{ $faq->question }}
                                        @if ($isNotAmerica)
                                            <span class="badge badge-info" style="cursor: pointer"
                                                title="{{ $faq->question_trans[$hiddenLang] ?? '' }}">
                                                {{ $hiddenLang }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $faq->category->name ?? translate('No Category') }}</td>
                                    <td>{{ Str::limit($faq->answer, 50) }}
                                        @if ($isNotAmerica)
                                            <span class="badge badge-info" style="cursor: pointer"
                                                title="{{ $faq->answer_trans[$hiddenLang] ?? '' }}">
                                                {{ $hiddenLang }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_faq_home_status(this)" value="{{ $faq->id }}"
                                                type="checkbox" <?php if ($faq->is_in_home == 1) {
                                                    echo 'checked';
                                                } ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('faqs.edit', $faq->id) }}" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('faqs.destroy', $faq->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $faqs->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Add New FAQ') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('faqs.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="category_id">{{ translate('Category') }}</label>
                            <select name="category_id" class="form-control aiz-selectpicker" required>
                                <option value="">{{ translate('Select Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="question">{{ translate('Question') }}</label>
                            <input type="text" placeholder="{{ translate('Question') }}" name="question_trans[en]"
                                class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="answer">{{ translate('Answer') }}</label>
                            <textarea name="answer_trans[en]" rows="5" class="form-control" placeholder="{{ translate('Answer') }}" required></textarea>
                        </div>
                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Add New FAQ Category') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('faq-categories.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">{{ translate('Category Name') }}</label>
                            <input type="text" placeholder="{{ translate('Category Name') }}" name="name_trans[en]"
                                class="form-control" required>
                        </div>
                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('FAQ Categories') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>{{ translate('Name') }}
                                    @if ($isNotAmerica)
                                        [{{ $lang }}]
                                        <span class="badge badge-info" style="cursor: pointer"
                                            title="hover to show translation">
                                            {{ $hiddenLang }}
                                        </span>
                                    @endif
                                </th>
                                <th>{{ translate('FAQs Count') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}
                                        @if ($isNotAmerica)
                                            <span class="badge badge-info" style="cursor: pointer"
                                                title="{{ $category->name_trans[$hiddenLang] ?? 'no translated text' }}">
                                                {{ $hiddenLang }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $category->faqs()->count() }}</td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('faq-categories.edit', $category->id) }}"
                                            title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('faq-categories.destroy', $category->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function sort_faqs(el) {
            $('#sort_faqs').submit();
        }

        function update_faq_home_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('faqs.update_home_status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('FAQ home status updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
