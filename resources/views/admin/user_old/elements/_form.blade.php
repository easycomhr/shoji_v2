@csrf

<input type="hidden" name="id" value="{{ old('id', $data->id ?? null) }}">

@include('admin.elements._notification')

<div class="mb-5">
    <label for="name" class="required form-label">{{ __("Name") }}</label>
    <input type="text" id="name" name="name" class="form-control " value="{{ old('name', $data->name ?? '') }}" />
    @error('name')
        <div class="form-text text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="fv-row mb-5">
    <!--begin::Label-->
    <label class="d-block fw-semibold fs-6 mb-5">Avatar</label>
    <!--end::Label-->
    @include('admin.elements._input_image', [
        'image_path' => !empty($data->avatar) ? asset('assets/avatars/' . $data->avatar) : '',
        'name_html' => 'avatar',
        'name_remove' => 'avatar_remove',
    ])

</div>

<div class="mb-5">
    <label for="email" class="required form-label">{{ __("Email") }}</label>
    <input type="text" id="email" name="email" class="form-control " value="{{ old('email', $data->email ?? '') }}" />

    @error('email')
    <div class="form-text text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-5">
    <label for="email" class="{{ empty($data) ? 'required' : '' }} form-label">{{ __("Password") }}</label>

    <div class="input-group mb-5">
        <input type="password" class="form-control " id="password" name="password" />
        <span class="input-group-text js-on-show-password" >
            <i class="fas fa-eye-slash"></i>
        </span>
    </div>

    @error('password')
    <div class="form-text text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-5">
    <label for="email" class="{{ empty($data) ? 'required' : '' }}  form-label">{{ __("Password retype") }}</label>

    <div class="input-group mb-5">
        <input type="password" class="form-control " id="password_confirmation" name="password_confirmation" />
        <span class="input-group-text js-on-show-password" >
            <i class="fas fa-eye-slash"></i>
        </span>
    </div>

    @error('password_confirmation')
    <div class="form-text text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-5">
    <label for="birthday" class=" form-label">{{ __("Birthday") }}</label>

    @include('admin.elements._input_date', [
        'name_html' => 'birthday',
        'value' => $data->birthday_format ?? '',
    ])

    @error('birthday')
        <div class="form-text text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-5">
    <label class=" form-label">{{ __("Role") }}</label>

    <div class="mb-5 d-flex">
        <div class="form-check form-check-custom form-check-solid form-check-sm me-10">
            <input class="form-check-input h-25px w-25px" type="radio" name="role" value="{{ \App\Enums\UserRole::Admin }}" id="role-{{ \App\Enums\UserRole::Admin }}" {{ old('role', $data->role ?? '') == \App\Enums\UserRole::Admin || empty($data) ? 'checked' : '' }} />
            <label class="form-check-label" for="role-{{ \App\Enums\UserRole::Admin }}">
                Administrator
            </label>
        </div>

        <div class="form-check form-check-custom form-check-solid form-check-sm me-10">
            <input class="form-check-input h-25px w-25px" type="radio" name="role" value="{{ \App\Enums\UserRole::Manager }}" id="role-{{ \App\Enums\UserRole::Manager }}" {{ old('role', $data->role ?? '') == \App\Enums\UserRole::Manager ? 'checked' : '' }} />
            <label class="form-check-label" for="role-{{ \App\Enums\UserRole::Manager }}">
                Manager
            </label>
        </div>

        <div class="form-check form-check-custom form-check-solid form-check-sm me-10">
            <input class="form-check-input h-25px w-25px" type="radio" name="role" value="{{ \App\Enums\UserRole::Customer }}" id="role-{{ \App\Enums\UserRole::Customer }}" {{ old('role', $data->role ?? '') == \App\Enums\UserRole::Customer ? 'checked' : '' }} />
            <label class="form-check-label" for="role-{{ \App\Enums\UserRole::Customer }}">
                Customer
            </label>
        </div>
    </div>



    @error('role')
    <div class="form-text text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-5">
    <div class="form-check form-switch  form-check-solid">
        <input class="form-check-input" type="checkbox" name="is_login" id="is_login" {{ old('is_login', $data->is_login ?? '' ) == 1 ? 'checked' : '' }} />
        <label class="form-check-label" for="is_login">
            Login
        </label>
    </div>

    @error('is_login')
    <div class="form-text text-danger">{{ $message }}</div>
    @enderror
</div>

@section("pagescript")
    <script>
        document.querySelectorAll('.js-on-show-password').forEach(item => {
            item.addEventListener('click', event => {
                let input = item.previousElementSibling;
                if (input.type === 'password') {
                    input.type = 'text';
                    item.querySelector('i').classList.remove('fa-eye-slash');
                    item.querySelector('i').classList.add('fa-eye');
                } else {
                    input.type = 'password';
                    item.querySelector('i').classList.remove('fa-eye');
                    item.querySelector('i').classList.add('fa-eye-slash');
                }
            });
        });
    </script>
@endsection
