@csrf


<div class="mb-5">
    <label for="email" class="required form-label">{{ __("Url") }}</label>
    <input type="text" name="url" class="form-control " value="{{ old('url', $data->url ?? '') }}" />
</div>


@section("pagescript")

@endsection
