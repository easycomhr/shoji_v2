<div class="card mb-5 mb-xl-10" >
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details" >
        <!--begin::Card title-->
        <div class="card-title m-0" >
            <h3 class="fw-bold m-0">{{ __("Personal Information") }} [1]</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->

    <!--begin::Content-->
    <div class="collapse show" >
        <!--begin::Form-->
        <form id="form-general-information"
              action="{{ route('admin.user.store_personal') }}"
              method="post"
              class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
            @csrf
            <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
            <input type="hidden" name="code" value="{{ $employee->code ?? '' }}">
            <input type="hidden" name="type" value="personal_info">

            <!--begin::Card body-->
            <div class="card-body border-top p-9">

                <div class="row gx-5 gx-xl-10" >
                    <div class="col-xxl-6 " >
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __("Birthday") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                @include('admin.elements._input_date_custom', [
                                    'name_html' => 'birthday',
                                    'value' => !empty($employee->birthday) ? $employee->birthday->format('d/m/Y') : '',
                                ])
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Birth place') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="birth_place"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('birth_place') is-invalid @enderror"
                                               placeholder="{{ __('Birth place') }}"
                                               value="{{ old('birth_place', $employee->birth_place ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('birth_place')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __("Married") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <!--begin::Options-->
                                <div class="d-flex align-items-center mt-3">
                                    <!--begin::Option-->
                                    <label class="form-check form-check-custom form-check-inline form-check-solid me-5">
                                        <input class="form-check-input @error('is_married') is-invalid @enderror"
                                               name="is_married"
                                               type="radio"
                                               value="1"
                                                {{ old('is_married', $employee->is_married ?? '') == 1 ? 'checked' : '' }}>
                                        <span class="fw-semibold ps-2 fs-6">
                                            {{ __("Yes") }}
                                        </span>
                                    </label>
                                    <!--end::Option-->

                                    <!--begin::Option-->
                                    <label class="form-check form-check-custom form-check-inline form-check-solid">
                                        <input class="form-check-input @error('is_married') is-invalid @enderror"
                                               name="is_married"
                                               type="radio"
                                               value="0"
                                                {{ old('is_married', $employee->is_married ?? '') == 0 ? 'checked' : '' }}>
                                        <span class="fw-semibold ps-2 fs-6">
                                            {{ __("No") }}
                                        </span>
                                    </label>
                                    <!--end::Option-->
                                </div>
                                <!--end::Options-->
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    @error('gender')
                                    {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                    </div>
                    <div class="col-xxl-6 " >
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('Religion') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="religion"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('religion') is-invalid @enderror"
                                               placeholder="{{ __('Religion') }}"
                                               value="{{ old('religion', $employee->religion ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('religion')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6" >
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Nationality") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row" >
                                <select class="form-select form-select-solid" name="nationality" aria-label="">
                                    <option>---</option>
                                    @foreach($nations as $nation)
                                        <option value="{{ $nation->id }}" {{ old('nationality', $employee->nationality ?? '') == $nation->id ? 'selected' : '' }}>{{ $nation->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                    </div>
                </div>

            </div>
            <!--end::Card body-->

            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="submit" class="btn btn-primary">{{ __("Save Changes") }}</button>
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Content-->
</div>

<div class="row gx-5 gx-xl-10" >
    <div class="col-xxl-6 " >
        <div class="card mb-5 mb-xl-10" >
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details" >
                <!--begin::Card title-->
                <div class="card-title m-0" >
                    <h3 class="fw-bold m-0">{{ __("CCCD & Passport") }} [2]</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->

            <!--begin::Content-->
            <div class="collapse show" >
                <!--begin::Form-->
                <form id="form-general-information"
                      action="{{ route('admin.user.store_personal') }}"
                      method="post"
                      class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                    @csrf
                    <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
                    <input type="hidden" name="code" value="{{ $employee->code ?? '' }}">
                    <input type="hidden" name="type" value="cccd_passport">

                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">


                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('CCCD') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="id_card"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('id_card') is-invalid @enderror"
                                               placeholder="{{ __('CCCD') }}"
                                               value="{{ old('id_card', $employee->id_card ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('id_card')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Issue date") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                @include('admin.elements._input_date_custom', [
                                    'name_html' => 'id_card_issue_date',
                                    'value' => !empty($employee->id_card_issue_date) ? $employee->id_card_issue_date->format('d/m/Y') : '',
                                ])
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Issue place') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="id_card_issue_place"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('id_card_issue_place') is-invalid @enderror"
                                               placeholder="{{ __('Issue place') }}"
                                               value="{{ old('id_card_issue_place', $employee->id_card_issue_place ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('id_card_issue_place')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->


                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Passport') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="passport"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('passport') is-invalid @enderror"
                                               placeholder="{{ __('Passport') }}"
                                               value="{{ old('passport', $employee->passport ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('passport')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Issue date") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                @include('admin.elements._input_date_custom', [
                                    'name_html' => 'passport_issue_date',
                                    'value' => !empty($employee->passport_issue_date) ? $employee->passport_issue_date->format('d/m/Y') : '',
                                ])
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Expired date") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                @include('admin.elements._input_date_custom', [
                                    'name_html' => 'passport_expiry_date',
                                    'value' => !empty($employee->passport_expiry_date) ? $employee->passport_expiry_date->format('d/m/Y') : '',
                                ])
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Issue place') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="passport_issue_place"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('passport_issue_place') is-invalid @enderror"
                                               placeholder="{{ __('Issue place') }}"
                                               value="{{ old('passport_issue_place', $employee->passport_issue_place ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('passport_issue_place')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                    </div>
                    <!--end::Card body-->

                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary">{{ __("Save Changes") }}</button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content-->
        </div>

        <div class="card mb-5 mb-xl-10" >
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details" >
                <!--begin::Card title-->
                <div class="card-title m-0" >
                    <h3 class="fw-bold m-0">{{ __("Address & Vehicle") }} [3]</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->

            <!--begin::Content-->
            <div  class="collapse show" >
                <!--begin::Form-->
                <form id="form-position-office"
                      action="{{ route('admin.user.store_personal') }}"
                      method="post"
                      class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                    @csrf
                    <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
                    <input type="hidden" name="code" value="{{ $employee->code ?? '' }}">
                    <input type="hidden" name="type" value="address_vehicle">

                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Home address') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                <textarea type="text"
                                          name="home_address"
                                          class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('home_address') is-invalid @enderror"
                                          placeholder="{{ __('Home address') }}"
                                >{{ old('home_address', $employee->home_address ?? '') }}</textarea>
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('home_address')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Temporary address') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                <textarea type="text"
                                          name="temporary_address"
                                          class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('temporary_address') is-invalid @enderror"
                                          placeholder="{{ __('Temporary address') }}"
                                >{{ old('temporary_address', $employee->temporary_address ?? '') }}</textarea>
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('temporary_address')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                    </div>
                    <!--end::Card body-->

                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary">{{ __("Save Changes") }}</button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content-->
        </div>

        <div class="card mb-5 mb-xl-10" >
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details" >
                <!--begin::Card title-->
                <div class="card-title m-0" >
                    <h3 class="fw-bold m-0">{{ __("Timekeeping ID") }} [7]</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->

            <!--begin::Content-->
            <div class="collapse show" >
                <!--begin::Form-->
                <form id="form-general-information"
                      action="{{ route('admin.user.store_personal') }}"
                      method="post"
                      class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                    @csrf
                    <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
                    <input type="hidden" name="code" value="{{ $employee->code ?? '' }}">
                    <input type="hidden" name="type" value="timekeeping">

                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">


                        <!--begin::Input group-->
                        <div class="row mb-6" >
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Timekeeping ID") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="timekeeper_card_id"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('timekeeper_card_id') is-invalid @enderror"
                                               placeholder="{{ __('Timekeeping ID') }}"
                                               value="{{ old('timekeeper_card_id', $employee->timekeeper_card_id ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('timekeeper_card_id')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->




                    </div>
                    <!--end::Card body-->

                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary">{{ __("Save Changes") }}</button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content-->
        </div>



    </div>
    <div class="col-xxl-6 " >
        <div class="card mb-5 mb-xl-10" >
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details" >
                <!--begin::Card title-->
                <div class="card-title m-0" >
                    <h3 class="fw-bold m-0">{{ __("Bank Account") }} [4]</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->

            <!--begin::Content-->
            <div class="collapse show" >
                <!--begin::Form-->
                <form id="form-general-information"
                      action="{{ route('admin.user.store_personal') }}"
                      method="post"
                      class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                    @csrf
                    <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
                    <input type="hidden" name="code" value="{{ $employee->code ?? '' }}">
                    <input type="hidden" name="type" value="bank_account">

                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">


                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Account number') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="bank_account_number"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('bank_account_number') is-invalid @enderror"
                                               placeholder="{{ __('Account number') }}"
                                               value="{{ old('bank_account_number', $employee->bank_account_number ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('bank_account_number')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Bank') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="bank_name"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('bank_name') is-invalid @enderror"
                                               placeholder="{{ __('Bank') }}"
                                               value="{{ old('bank_name', $employee->bank_name ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('bank_name')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Tax code') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="tax_code"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('tax_code') is-invalid @enderror"
                                               placeholder="{{ __('Tax code') }}"
                                               value="{{ old('tax_code', $employee->tax_code ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('tax_code')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Accounting code') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="accounting_code"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('accounting_code') is-invalid @enderror"
                                               placeholder="{{ __('Accountant number') }}"
                                               value="{{ old('accounting_code', $employee->accounting_code ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('accounting_code')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->



                    </div>
                    <!--end::Card body-->

                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary">{{ __("Save Changes") }}</button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content-->
        </div>

        <div class="card mb-5 mb-xl-10" >
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details" >
                <!--begin::Card title-->
                <div class="card-title m-0" >
                    <h3 class="fw-bold m-0">{{ __("Insurance") }} [5]</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->

            <!--begin::Content-->
            <div class="collapse show" >
                <!--begin::Form-->
                <form id="form-general-information"
                      action="{{ route('admin.user.store_personal') }}"
                      method="post"
                      class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                    @csrf
                    <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
                    <input type="hidden" name="code" value="{{ $employee->code ?? '' }}">
                    <input type="hidden" name="type" value="insurance">

                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">


                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Social insurance number') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="insurance_number"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('insurance_number') is-invalid @enderror"
                                               placeholder="{{ __('Social insurance number') }}"
                                               value="{{ old('insurance_number', $employee->insurance_number ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('insurance_number')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Insurance date") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                @include('admin.elements._input_date_custom', [
                                    'name_html' => 'social_insurance_date',
                                    'value' => !empty($employee->social_insurance_date) ? $employee->social_insurance_date->format('d/m/Y') : '',
                                ])
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Insurance place') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="social_insurance_place"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('social_insurance_place') is-invalid @enderror"
                                               placeholder="{{ __('Insurance place') }}"
                                               value="{{ old('social_insurance_place', $employee->social_insurance_place ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('social_insurance_place')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Health insurance number') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="health_insurance_number"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('health_insurance_number') is-invalid @enderror"
                                               placeholder="{{ __('Health insurance number') }}"
                                               value="{{ old('health_insurance_number', $employee->health_insurance_number ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('health_insurance_number')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                    </div>
                    <!--end::Card body-->

                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary">{{ __("Save Changes") }}</button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content-->
        </div>

        <div class="card mb-5 mb-xl-10" >
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details" >
                <!--begin::Card title-->
                <div class="card-title m-0" >
                    <h3 class="fw-bold m-0">{{ __("Employee Status") }} [6]</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->

            <!--begin::Content-->
            <div class="collapse show" >
                <!--begin::Form-->
                <form id="form-general-information"
                      action="{{ route('admin.user.store_personal') }}"
                      method="post"
                      class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                    @csrf
                    <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
                    <input type="hidden" name="code" value="{{ $employee->code ?? '' }}">
                    <input type="hidden" name="type" value="employee_status">

                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">


                        <!--begin::Input group-->
                        <div class="row mb-6" >
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Current status") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row" >
                                <select class="form-select form-select-solid" name="user_status_id" aria-label="">
                                    <option>---</option>
                                    @foreach($user_statuses as $user_status)
                                        <option value="{{ $user_status->id }}" {{ old('user_status_id', $employee->user_status_id ?? '') == $user_status->id ? 'selected' : '' }}>{{ $user_status->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("From date") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                @include('admin.elements._input_date_custom', [
                                    'name_html' => 'user_status_from_date',
                                    'value' => !empty($employee->user_status_from_date) ? $employee->user_status_from_date->format('d/m/Y') : '',
                                ])
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->


                    </div>
                    <!--end::Card body-->

                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary">{{ __("Save Changes") }}</button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content-->
        </div>

    </div>
</div>






