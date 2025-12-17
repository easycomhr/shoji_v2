@extends("layouts.app")
@section('title', $title ?? 'Import User')
@section("content")

    <div class="col-md-6 " style="margin: 0 auto;">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">{{ $title ?? '' }}</h3>
                <div class="card-toolbar">

                </div>
            </div>

            <form method="post" action="{{ route('admin.user.import_excel') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    @include('admin.elements._notification')
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Chosen file</label>
                        <input class="form-control" name="file" type="file" id="formFile">
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-secondary btn-form mx-2">Cancel</a>
                    <button type="submit" class="btn btn-primary  btn-form">Import</button>
                </div>
            </form>

        </div>
    </div>


@endsection

@section("pagescript")

@endsection


