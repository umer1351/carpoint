@extends('front.app_front')

@section('content')
<div class="container">
    <h3>List Your Garage</h3>
    <form action="{{ route('garages.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Garage Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">List Garage</button>
    </form>
</div>
@endsection
