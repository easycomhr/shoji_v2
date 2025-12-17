@extends("layouts.app")
@section('title', $title ?? 'Create User')
@section("content")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <div class="col-md-6 " style="margin: 0 auto;">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">{{ $title ?? '' }}</h3>
                <div class="card-toolbar">

                </div>
            </div>


            <form action="{{ route('admin.file.upload') }}" method="POST" class="dropzone" id="imageUpload">
                @csrf
            </form>

            <div id="preview" style="margin-top: 20px;">
                <h3>Preview:</h3>
                <img id="previewImage" src="#" alt="Image Preview" style="max-width: 300px; display: none;">
            </div>

        </div>
    </div>


@endsection

@section("pagescript")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

    <script>
        Dropzone.options.imageUpload = {
            acceptedFiles: "image/*",
            maxFilesize: 2, // 2MB
            init: function() {
                this.on("thumbnail", function(file) {
                    const previewImage = document.getElementById('previewImage');
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block';
                    };

                    reader.readAsDataURL(file);
                });
            }
        };
    </script>
@endsection


