<div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
    <!--begin::Preview existing avatar-->
    <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $image_path }}');"></div>
    <!--end::Preview existing avatar-->
    <!--begin::Label-->
    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
        <i class="ki-duotone ki-pencil fs-7">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
        <!--begin::Inputs-->
        <input type="file" name="{{ $name_html }}" accept=".png, .jpg, .jpeg" />
        <input type="hidden" name="{{ $name_remove }}" />
        <!--end::Inputs-->
    </label>
    <!--end::Label-->
    <!--begin::Cancel-->
    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
            <i class="ki-duotone ki-cross fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
    <!--end::Cancel-->
    <!--begin::Remove-->
    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
            <i class="ki-duotone ki-cross fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
    <!--end::Remove-->
</div>
<!--end::Image input-->
<!--begin::Hint-->
<div class="form-text">Allowed file types: png, jpg, jpeg.</div>
<!--end::Hint-->
