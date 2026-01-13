@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Sytem Settings -->
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-0 h6">{{ translate('Registration Notification Email') }}</h1>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <!-- System Name -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Registration Notification Email') }} </label>
                            <div class="col-sm-8">
                                <input type="hidden" name="types[]" value="registration_notification_email">
                                <input type="text" name="registration_notification_email" class="form-control"
                                    placeholder="{{ translate('Registration Notification Email') }}" value="{{ get_setting('registration_notification_email') ?? 'info@skybusinesstrade.com ' }} ">
                            </div>
                        </div>
                        <!-- Update Button -->
                        <div class="mt-4 text-right">
                            <button type="submit"
                                class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


