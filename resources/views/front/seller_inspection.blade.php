@extends('front.app_front')

@section('content')
<div class="container">
    <h3>Inspection Requests</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Buyer</th>
                <th>Listing</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $request)
                <tr>
                    <td>{{ $request->buyer->name }}</td>
                    <td>{{ $request->listing->listing_name }}</td>
                    <td>{{ $request->status }}</td>
                    <td>
                        @if($request->status === 'Pending')
                            <form action="{{ route('inspection.update', [$request->id, 'Accepted']) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Accept</button>
                            </form>
                            <form action="{{ route('inspection.update', [$request->id, 'Rejected']) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        @else
                            <span class="badge {{ $request->status === 'Accepted' ? 'bg-success' : 'bg-danger' }}">
                                {{ $request->status }}
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
