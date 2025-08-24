@extends(getTemplate() . '.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')
    @php
        $registerMethod = getGeneralSettings('register_method') ?? 'mobile';
        $showOtherRegisterMethod = getFeaturesSettings('show_other_register_method') ?? false;
        $showCertificateAdditionalInRegister = getFeaturesSettings('show_certificate_additional_in_register') ?? false;
        $selectRolesDuringRegistration = getFeaturesSettings('select_the_role_during_registration') ?? null;
    @endphp

    <div class="container">
        <div class="row login-container">
            <div class="col-12 col-md-6 pl-0">
                <img src="{{ getPageBackgroundSettings('register') }}" class="img-cover" alt="Login">
            </div>
            <div class="col-12 col-md-6">
                <div class="login-card">
                    <h1 class="font-20 font-weight-bold">{{ trans('auth.signup') }}</h1>

                    <form method="post" action="/register" class="mt-35" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        @if (!empty($selectRolesDuringRegistration) and count($selectRolesDuringRegistration))
                            <div class="form-group">
                                <label class="input-label">{{ trans('financial.account_type') }}</label>

                                <div class="d-flex align-items-center wizard-custom-radio mt-5">
                                    <div class="wizard-custom-radio-item flex-grow-1">
                                        <input type="radio" name="account_type" value="user" id="role_user"
                                            class="" checked>
                                        <label class="font-12 cursor-pointer px-15 py-10"
                                            for="role_user">{{ trans('update.role_user') }}</label>
                                    </div>

                                    @foreach ($selectRolesDuringRegistration as $selectRole)
                                        @continue(strtolower($selectRole) !== 'student')
                                        <div class="wizard-custom-radio-item flex-grow-1">
                                            <input type="radio"
                                                name="account_type"
                                                value="{{ $selectRole }}"
                                                id="role_{{ strtolower($selectRole) }}"
                                                class="">
                                            <label class="font-12 cursor-pointer px-15 py-10"
                                                for="role_{{ strtolower($selectRole) }}">
                                                {{ trans('update.role_' . strtolower($selectRole)) }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        @endif

                        @if ($registerMethod == 'mobile')
                            @include('web.default.auth.register_includes.mobile_field')
                            @if ($showOtherRegisterMethod)
                                @include('web.default.auth.register_includes.email_field', [
                                    'optional' => true,
                                ])
                            @endif
                        @else
                            @include('web.default.auth.register_includes.email_field')
                            @if ($showOtherRegisterMethod)
                                @include('web.default.auth.register_includes.mobile_field', [
                                    'optional' => true,
                                ])
                            @endif
                        @endif

                        <div class="form-group">
                            <label class="input-label" for="full_name">{{ trans('auth.full_name') }}:</label>
                            <input name="full_name" type="text" value="{{ old('full_name') }}"
                                class="form-control @error('full_name') is-invalid @enderror">
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="row">

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="input-label">{{ __('Sex') }}</label>
                                    <select name="sex" class="form-control @error('sex') is-invalid @enderror">
                                        <option value="">{{ trans('public.select') }}</option>
                                        <option value="male" @selected(old('sex') === 'male')>{{ __('Male') }}</option>
                                        <option value="female" @selected(old('sex') === 'female')>{{ __('Female') }}</option>
                                        <option value="other" @selected(old('sex') === 'other')>{{ __('Other') }}</option>
                                    </select>
                                    @error('sex')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="input-label">{{ __('Birthdate') }}</label>
                                    <input type="date" name="birthdate"
                                        class="form-control @error('birthdate') is-invalid @enderror"
                                        value="{{ old('birthdate') }}">
                                    @error('birthdate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="input-label">{{ __('Nationality') }}</label>
                                    <input type="text" name="nationality"
                                        class="form-control @error('nationality') is-invalid @enderror"
                                        value="{{ old('nationality') }}">
                                    @error('nationality')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="input-label">{{ __('Religion') }}</label>
                                    <input type="text" name="religion"
                                        class="form-control @error('religion') is-invalid @enderror"
                                        value="{{ old('religion') }}">
                                    @error('religion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="input-label">{{ __('NID Number') }}</label>
                                    <input type="text" name="nid_number"
                                        class="form-control @error('nid_number') is-invalid @enderror"
                                        value="{{ old('nid_number') }}">
                                    @error('nid_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="input-label">{{ __('Address') }}</label>
                                    <input type="text" name="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        value="{{ old('address') }}">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="font-14 font-weight-bold mt-10 mb-10">{{ __("Father's Information") }}
                                        </h5>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ __("Father's Name") }}</label>
                                            <input type="text" name="fathers_name"
                                                class="form-control @error('fathers_name') is-invalid @enderror"
                                                value="{{ old('fathers_name') }}">
                                            @error('fathers_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ __("Father's Email") }}</label>
                                            <input type="email" name="fathers_email"
                                                class="form-control @error('fathers_email') is-invalid @enderror"
                                                value="{{ old('fathers_email') }}">
                                            @error('fathers_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ __("Father's Contact Number") }}</label>
                                            <input type="text" name="fathers_contact_number"
                                                class="form-control @error('fathers_contact_number') is-invalid @enderror"
                                                value="{{ old('fathers_contact_number') }}">
                                            @error('fathers_contact_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="font-14 font-weight-bold mt-10 mb-10">{{ __("Mother's Information") }}
                                        </h5>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ __("Mother's Name") }}</label>
                                            <input type="text" name="mothers_name"
                                                class="form-control @error('mothers_name') is-invalid @enderror"
                                                value="{{ old('mothers_name') }}">
                                            @error('mothers_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ __("Mother's Email") }}</label>
                                            <input type="email" name="mothers_email"
                                                class="form-control @error('mothers_email') is-invalid @enderror"
                                                value="{{ old('mothers_email') }}">
                                            @error('mothers_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ __("Mother's Contact Number") }}</label>
                                            <input type="text" name="mothers_contact_number"
                                                class="form-control @error('mothers_contact_number') is-invalid @enderror"
                                                value="{{ old('mothers_contact_number') }}">
                                            @error('mothers_contact_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label">{{ __('User Image') }}</label>
                                    <input id="user_image" name="user_image" type="file" accept="image/*"
                                        class="form-control @error('user_image') is-invalid @enderror">
                                    <small class="text-muted d-block">{{ __('Max 2MB · JPG/PNG/WEBP') }}</small>
                                    <div class="mt-2"><img id="user_image_preview"
                                            style="max-height:120px; display:none;" alt="preview"></div>
                                    @error('user_image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label">{{ __('NID Image') }}</label>
                                    <input id="nid_image" name="nid_image" type="file" accept="image/*"
                                        class="form-control @error('nid_image') is-invalid @enderror">
                                    <small class="text-muted d-block">{{ __('Max 4MB · JPG/PNG/WEBP') }}</small>
                                    <div class="mt-2"><img id="nid_image_preview"
                                            style="max-height:120px; display:none;" alt="preview"></div>
                                    @error('nid_image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        @if ($showCertificateAdditionalInRegister)
                            <div class="form-group">
                                <label class="input-label"
                                    for="certificate_additional">{{ trans('update.certificate_additional') }}</label>
                                <input name="certificate_additional" id="certificate_additional"
                                    class="form-control @error('certificate_additional') is-invalid @enderror" />
                                @error('certificate_additional')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        @if (getFeaturesSettings('timezone_in_register'))
                            @php $selectedTimezone = getGeneralSettings('default_time_zone'); @endphp
                            <div class="form-group">
                                <label class="input-label">{{ trans('update.timezone') }}</label>
                                <select name="timezone" class="form-control select2" data-allow-clear="false">
                                    <option value="" {{ empty($user->timezone) ? 'selected' : '' }} disabled>
                                        {{ trans('public.select') }}</option>
                                    @foreach (getListOfTimezones() as $timezone)
                                        <option value="{{ $timezone }}"
                                            @if ($selectedTimezone == $timezone) selected @endif>{{ $timezone }}</option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        @if (!empty($referralSettings) and $referralSettings['status'])
                            <div class="form-group ">
                                <label class="input-label"
                                    for="referral_code">{{ trans('financial.referral_code') }}:</label>
                                <input name="referral_code" type="text"
                                    class="form-control @error('referral_code') is-invalid @enderror" id="referral_code"
                                    value="{{ !empty($referralCode) ? $referralCode : old('referral_code') }}"
                                    aria-describedby="confirmPasswordHelp">
                                @error('referral_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="js-form-fields-card">
                            @if (!empty($formFields))
                                {!! $formFields !!}
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="password">{{ trans('auth.password') }}:</label>
                            <input name="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" id="password"
                                aria-describedby="passwordHelp">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group ">
                            <label class="input-label" for="confirm_password">{{ trans('auth.retype_password') }}:</label>
                            <input name="password_confirmation" type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                id="confirm_password" aria-describedby="confirmPasswordHelp">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if (!empty(getGeneralSecuritySettings('captcha_for_register')))
                            @include('web.default.includes.captcha_input')
                        @endif

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="term" value="1"
                                {{ (!empty(old('term')) and old('term') == '1') ? 'checked' : '' }}
                                class="custom-control-input @error('term') is-invalid @enderror" id="term">
                            <label class="custom-control-label font-14" for="term">
                                {{ trans('auth.i_agree_with') }}
                                <a href="pages/terms" target="_blank"
                                    class="text-secondary font-weight-bold font-14">{{ trans('auth.terms_and_rules') }}</a>
                            </label>
                            @error('term')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @error('term')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <button type="submit"
                            class="btn btn-primary btn-block mt-20">{{ trans('auth.signup') }}</button>
                    </form>


                    <div class="text-center mt-20">
                        <span class="text-secondary">
                            {{ trans('auth.already_have_an_account') }}
                            <a href="/login" class="text-secondary font-weight-bold">{{ trans('auth.login') }}</a>
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        (function() {
            function preview(inputId, imgId) {
                const input = document.getElementById(inputId);
                const img = document.getElementById(imgId);
                if (!input || !img) return;
                input.addEventListener('change', function() {
                    const file = this.files && this.files[0];
                    if (file) {
                        img.src = URL.createObjectURL(file);
                        img.style.display = 'block';
                    } else {
                        img.src = '';
                        img.style.display = 'none';
                    }
                });
            }
            preview('user_image', 'user_image_preview');
            preview('nid_image', 'nid_image_preview');
        })();
    </script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script src="/assets/default/js/parts/forms.min.js"></script>
    <script src="/assets/default/js/parts/register.min.js"></script>
@endpush
