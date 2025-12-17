<div class="modal fade" tabindex="-1" id="modal-confirm-change-login">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirm Change Login</h3>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times fs-1"></i>
                </div>
                <!--end::Close-->
            </div>
            <form method="post" id="form-change-login" action="{{ route('admin.user.change_login') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id">
            <div class="modal-body">
                <p >{{ __("Are you sure you want to change allow login this user?") }}</p>
            </div>

            <div class="modal-footer text-center">
                <div class="w-100 text-center">
                    <button type="button" class="btn btn-secondary mx-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Change</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
