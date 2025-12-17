@csrf


<div class="mb-5">
    <label for="email" class="required form-label">{{ __("File") }}</label>
    <input type="text" class="dropzone" id="image-upload" value="" />

</div>


@section("pagescript")
    <script>
        Dropzone.options.imageUpload = {
            maxFilesize: 2, // MB
            acceptedFiles: 'image/*',
            success: function(file, response) {
                // You can customize what to do when upload is successful
                console.log(response);
            }
        };
    </script>
@endsection
