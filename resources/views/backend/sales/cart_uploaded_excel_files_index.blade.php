@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="align-items-center">
        <h1 class="h3">{{translate('Cart Uploaded Excel Files')}}</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Email')}}</th>
                    <th>{{translate('Actions')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($files as $file)
                    <tr>
                        <td>{{ $file->user->name ?? 'N/A' }}</td>
                        <td>{{ $file->user->email ?? 'N/A' }}</td>
                        <td class="">
                            @if($file->file_path)
                                <a href="{{ asset('public/storage/' . $file->file_path) }}" download class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Download Excel') }}">
                                    <i class="las la-download"></i>
                                </a>
                            @endif
                            <a href="{{route('customers.user-info', $file->user->id)}}" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{ translate('View Business Info') }}">
                                <i class="las la-user"></i>
                            </a>
                            @if($file->order)
                                <a href="{{ route('orders.show', encrypt($file->order->id)) }}" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('View Order') }}">
                                    <i class="las la-shopping-cart"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $files->links() }}
        </div>
    </div>
</div>

@endsection
