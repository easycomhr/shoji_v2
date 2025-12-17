@extends("layouts.app")
@section('title', $title ?? 'Create User')
@section("content")
    <div class="col-md-6 " style="margin: 0 auto;">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">{{ $title ?? '' }}</h3>
                <div class="card-toolbar">

                </div>
            </div>
            <form method="post" action="{{ route('admin.user.store', $data->id ?? '') }}" enctype="multipart/form-data">
                <div class="card-body">
                    @include('admin.user.elements._form')
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-secondary btn-form mx-2">Cancel</a>
                    <button type="submit" class="btn btn-primary  btn-form">Update</button>
                </div>
            </form>

        </div>
    </div>


@endsection

@section("pagescript")

@endsection


