<div class="card mb-5 mb-xl-10" >
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details" >
        <!--begin::Card title-->
        <div class="card-title m-0" >
            <h3 class="fw-bold m-0">{{ __("Basic Information") }} [1]</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->

    <!--begin::Content-->
    <div class="collapse show" >
        <!--begin::Form-->
        <form id="form-general-information"
              action="{{ route('admin.user.store_general') }}"
              method="post"
              enctype="multipart/form-data"
              class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
            @csrf
            <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
            <input type="hidden" name="code" value="{{ $employee->code ?? '' }}">
            <input type="hidden" name="type" value="basic_information">

            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __("Avatar") }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Image input-->
                        @include('admin.user.partials.profile_avatar', [
                            'image_path' => $employee->avatar_path ?? asset('images/no_avatar.svg'),
                        ])
                        <!--end::Hint-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Employee Code") }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                        <input type="text" readonly class="form-control form-control-lg form-control-solid"
                               value="{{ $employee->code ?? '' }}">
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            @error('code')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __('Full Name') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                <input type="text"
                                       name="name"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('name') is-invalid @enderror"
                                       placeholder="{{ __('Full name') }}"
                                       value="{{ old('name', $employee->name ?? '') }}">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    @error('name')
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
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Gender") }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                        <!--begin::Options-->
                        <div class="d-flex align-items-center mt-3">
                            <!--begin::Option-->
                            <label class="form-check form-check-custom form-check-inline form-check-solid me-5">
                                <input class="form-check-input @error('gender') is-invalid @enderror"
                                       name="gender"
                                       type="radio"
                                       value="1"
                                        {{ old('gender', $employee->gender ?? '') == 1 ? 'checked' : '' }}>
                                <span class="fw-semibold ps-2 fs-6">
                            {{ __("Male") }}
                        </span>
                            </label>
                            <!--end::Option-->

                            <!--begin::Option-->
                            <label class="form-check form-check-custom form-check-inline form-check-solid">
                                <input class="form-check-input @error('gender') is-invalid @enderror"
                                       name="gender"
                                       type="radio"
                                       value="0"
                                        {{ old('gender', $employee->gender ?? '') == 0 ? 'checked' : '' }}>
                                <span class="fw-semibold ps-2 fs-6">
                            {{ __("Female") }}
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
    <div class="col-xxl-6 mb-5 mb-xl-10" >
        <div class="card mb-5 mb-xl-10" >
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details" >
                <!--begin::Card title-->
                <div class="card-title m-0" >
                    <h3 class="fw-bold m-0">{{ __("Position & Offices") }} [2]</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->

            <!--begin::Content-->
            <div  class="collapse show" >
                <!--begin::Form-->
                <form id="form-position-office"
                      action="{{ route('admin.user.store_general') }}"
                      method="post"
                      class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                    @csrf
                    <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
                    <input type="hidden" name="code" value="{{ $employee->code ?? '' }}">
                    <input type="hidden" name="type" value="position_office">

                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">

                        <!--begin::Input group-->
                        <div class="row mb-6" >
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Department") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row" >
                                <select class="form-select form-select-solid" name="department_id" aria-label="">
                                    <option value="0">---</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id ?? '') == $department->id ? 'selected' : '' }}>{{ $department->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6" >
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Position") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row" >
                                <select class="form-select form-select-solid" name="position_id" aria-label="">
                                    <option value="0">---</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->id }}" {{ old('position_id', $employee->position_id ?? '') == $position->id ? 'selected' : '' }}>{{ $position->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->


                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Probation start") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                @include('admin.elements._input_date_custom', [
                                    'name_html' => 'join_date',
                                    'value' => !empty($employee->join_date) ? $employee->join_date->format('d/m/Y') : '',
                                ])
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Seniority Date") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                @include('admin.elements._input_date_custom', [
                                    'name_html' => 'seniority_date',
                                    'value' => !empty($employee->seniority_date) ? $employee->seniority_date->format('d/m/Y') : '',
                                ])
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __("Termination Date") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                @include('admin.elements._input_date_custom', [
                                    'name_html' => 'termination_date',
                                    'value' => !empty($employee->termination_date) ? $employee->termination_date->format('d/m/Y') : '',
                                ])
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __("Other") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <!--begin::Options-->
                                <div class="d-flex align-items-center mt-3">
                                    <!--begin::Option-->
                                    <label class="form-check form-check-custom form-check-inline form-check-solid me-5">
                                        <input class="form-check-input @error('is_foreigner') is-invalid @enderror"
                                               name="is_foreigner"
                                               type="checkbox"
                                               value="1"
                                                {{ old('is_foreigner', $employee->is_foreigner ?? '') == 1 ? 'checked' : '' }}>
                                        <span class="fw-semibold ps-2 fs-6">
                                    {{ __("Is foreigner") }}
                                </span>
                                    </label>
                                    <!--end::Option-->

                                    <!--begin::Option-->
                                    <label class="form-check form-check-custom form-check-inline form-check-solid">
                                        <input class="form-check-input @error('is_office') is-invalid @enderror"
                                               name="is_office"
                                               type="checkbox"
                                               value="1"
                                                {{ old('is_office', $employee->is_office ?? '') == 1 ? 'checked' : '' }}>
                                        <span class="fw-semibold ps-2 fs-6">
                                    {{ __("Is Office") }}
                                </span>
                                    </label>
                                    <!--end::Option-->
                                </div>


                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <div class="row mb-6" >
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __("Language") }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row" >
                                <select class="form-select form-select-solid" name="language" aria-label="">
                                    <option value="vi" {{ ($employee->language ?? 'vi') === 'vi' ? 'selected' : '' }}>VN</option>
                                    <option value="en" {{ ($employee->language ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                                </select>
                            </div>
                            <!--end::Col-->
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
    </div>
    <div class="col-xxl-6 mb-5 mb-xl-10" >
        <div class="card mb-5 mb-xl-10" >
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details" >
                <!--begin::Card title-->
                <div class="card-title m-0" >
                    <h3 class="fw-bold m-0">{{ __("Basic Information") }} [3]</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->

            <!--begin::Content-->
            <div class="collapse show" >
                <!--begin::Form-->
                <form id="form-general-information"
                      action="{{ route('admin.user.store_general') }}"
                      method="post"
                      class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                    @csrf
                    <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
                    <input type="hidden" name="code" value="{{ $employee->code ?? '' }}">
                    <input type="hidden" name="type" value="contact_info">

                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">


                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Office phone') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="office_phone"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('office_phone') is-invalid @enderror"
                                               placeholder="{{ __('Office phone') }}"
                                               value="{{ old('office_phone', $employee->office_phone ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('office_phone')
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
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Extension') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="extension"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('extension') is-invalid @enderror"
                                               placeholder="{{ __('Extension') }}"
                                               value="{{ old('extension', $employee->extension ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('extension')
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
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __('Mobile') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="phone"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('phone') is-invalid @enderror"
                                               placeholder="{{ __('Mobile') }}"
                                               value="{{ old('phone', $employee->phone ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('phone')
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
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('Company email') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="company_email"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('company_email') is-invalid @enderror"
                                               placeholder="{{ __('Company email') }}"
                                               value="{{ old('company_email', $employee->company_email ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('company_email')
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
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('Personal email') }}</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text"
                                               name="private_email"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('private_email') is-invalid @enderror"
                                               placeholder="{{ __('Personal email') }}"
                                               value="{{ old('private_email', $employee->private_email ?? '') }}">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            @error('private_email')
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
</div>


<div class="card mb-5 mb-xl-10" >
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details" >
        <!--begin::Card title-->
        <div class="card-title m-0" >
            <h3 class="fw-bold m-0">{{ __("Note") }} [4]</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->

    <!--begin::Content-->
    <div class="collapse show" >
        <!--begin::Form-->
        <form id="form-general-information"
              action="{{ route('admin.user.store_general') }}"
              method="post"
              class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
            @csrf
            <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
            <input type="hidden" name="code" value="{{ $employee->code ?? '' }}">
            <input type="hidden" name="type" value="note">

            <!--begin::Card body-->
            <div class="card-body border-top p-9">


                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label  fw-semibold fs-6">{{ __('Note') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                <textarea type="text"
                                       name="comments"
                                          rows="5"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('comments') is-invalid @enderror"
                                       placeholder="{{ __('Note') }}"
                                >{{ old('comments', $employee->comments ?? '') }}</textarea>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    @error('comments')
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



