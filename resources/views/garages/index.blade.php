@extends('front.app_front')

@section('content')
<div class="container">
    <h3>Available Garages & Services</h3>
    @foreach($garages as $garage)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $garage->name }}</h5>
                <p class="card-text">{{ $garage->description }}</p>
                <p><strong>Location:</strong> {{ $garage->location }}</p>

                <h6>Available Services:</h6>
                <ul>
                    @foreach($garage->services as $service)
                        <li>{{ $service->name }} - ${{ number_format($service->price, 2) }}</li>
                    @endforeach
                </ul>

                @if(Auth::check() && Auth::id() == $garage->seller_id)
                    <form action="{{ route('garages.addService', $garage->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Service Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="number" name="price" class="form-control" placeholder="Service Price">
                        </div>
                        <button type="submit" class="btn btn-sm btn-success">Add Service</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
