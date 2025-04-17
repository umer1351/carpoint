@extends('admin.app_admin')
@section('admin_content')
<div class="container">
    <h3>Conversation</h3>
    <div class="card">
        <div class="card-body">
            @foreach($messages as $message)
                <div class="mb-3">
                    <strong>{{ $message->sender->name }}:</strong>
                    <p>{{ $message->message }}</p>
                    <small>{{ $message->created_at->format('d M Y, h:i A') }}</small>
                    <hr>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
