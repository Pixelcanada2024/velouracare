@extends('backend.layouts.app')

@section('content')
@php
  $lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';
@endphp
<div class="col-lg-6 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('FAQ Category Information')}}</h5>
        </div>
        <div class="card-body">
              <ul class="nav nav-tabs nav-fill language-bar">
                @foreach (get_all_active_language() as $key => $language)
                <li class="nav-item">
                    <a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3" href="{{ request()->fullUrlWithQuery(['lang' => $language->code]) }}">
                        <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                        <span>{{$language->name}}</span>
                    </a>
                </li>
                @endforeach
            </ul>
            <form class="p-4" action="{{ route('faq-categories.update', $category->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="lang" value="{{ $lang }}">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Category Name')}} <span class="text-danger">*</span>
                      <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Category Name')}}" id="name" name="name_trans[{{ $lang }}]" value="{{ $category['name_trans']["{$lang}"] ?? '' }}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <a href="{{ route('faqs.index') }}" class="btn btn-light">{{ translate('Cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
