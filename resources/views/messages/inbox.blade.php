@extends('front.app_front')

@section('content')
<div class="container">
    <h3>Inbox</h3>
    @foreach ($messages as $message)
        <div class="card mb-3">
            <div class="card-body">
                <p><strong>From:</strong> {{ $message->sender->name }}</p>
                <p><strong>Listing:</strong> {{ $message->listing->listing_name }}</p>
                <p>{{ $message->message }}</p>
                
            </div>
        </div>
    @endforeach
    <form action="{{ route('messages.reply', $message->id) }}" method="POST">
                    @csrf
                    <input type="text" name="message" placeholder="Write your reply..." required>
                    <button type="submit" class="btn btn-primary btn-sm">Reply</button>
                </form>
</div>
@endsection
