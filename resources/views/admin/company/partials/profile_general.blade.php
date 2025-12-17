<div class="card mb-5 mb-xl-10" >
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details" >
        <!--begin::Card title-->
        <div class="card-title m-0" >
            <h3 class="fw-bold m-0">{{ __("Company Information") }}</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->

    <!--begin::Content-->
    <div class="collapse show" >
        <!--begin::Form-->
        <form id="form-general-information"
              action="{{ route('admin.company.store') }}"
              method="post"
              class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
            @csrf
            <input type="hidden" name="id" value="{{ $company->id ?? '' }}">

            <!--begin::Card body-->
            <div class="card-body border-top p-9">


                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-2 col-form-label required fw-semibold fs-6">{{ __('Name') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-10">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                <input type="text"
                                       name="name"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('name') is-invalid @enderror"
                                       placeholder="{{ __('Name') }}"
                                       value="{{ old('name', $company->name ?? '') }}">
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
                    <label class="col-lg-2 col-form-label  fw-semibold fs-6">{{ __('Office number') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-10">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                <input type="text"
                                       name="official_number"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('official_number') is-invalid @enderror"
                                       placeholder="{{ __('Office number') }}"
                                       value="{{ old('official_number', $company->official_number ?? '') }}">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    @error('official_number')
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
                    <label class="col-lg-2 col-form-label required fw-semibold fs-6">{{ __('Address') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-10">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                <input type="text"
                                       name="address_line_1"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('address_line_1') is-invalid @enderror"
                                       placeholder="{{ __('Address') }}"
                                       value="{{ old('address_line_1', $company->address_line_1 ?? '') }}">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    @error('address_line_1')
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
                    <label class="col-lg-2 col-form-label required fw-semibold fs-6">{{ __('Phone') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-10">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                <input type="text"
                                       name="phone"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('phone') is-invalid @enderror"
                                       placeholder="{{ __('Phone') }}"
                                       value="{{ old('phone', $company->phone ?? '') }}">
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
                    <label class="col-lg-2 col-form-label  fw-semibold fs-6">{{ __('Fax') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-10">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                <input type="text"
                                       name="fax"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('fax') is-invalid @enderror"
                                       placeholder="{{ __('Fax') }}"
                                       value="{{ old('fax', $company->fax ?? '') }}">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    @error('fax')
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
                    <label class="col-lg-2 col-form-label required fw-semibold fs-6">{{ __('Email') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-10">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                <input type="text"
                                       name="email"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('email') is-invalid @enderror"
                                       placeholder="{{ __('Email') }}"
                                       value="{{ old('email', $company->email ?? '') }}">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    @error('email')
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
            <div class="card-footer d-flex justify-content-center py-6 px-9">
                <button type="submit" class="btn btn-primary">{{ __("Save Changes") }}</button>
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Content-->
</div>





