@extends("layouts.app")
@section('title', $title ?? '')
@section("content")
    <h1>Permission Denied</h1>
    <p>You do not have permission to access this resource.</p>
    <a href="{{ route('admin.dashboard.index') }}">Back to Home</a>
@endsection

@section("pagescript")

@endsection


