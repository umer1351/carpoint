@extends('admin.app_admin')
@section('admin_content')
<div class="container">
    <h3>All Messages</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Buyer</th>
                <th>Seller</th>
                <th>Listing</th>
                <th>Last Message</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($messages as $message)
                <tr>
                    <td>{{ $message->sender->name }}</td>
                    <td>{{ $message->receiver->name }}</td>
                    <td>{{ $message->listing->title }}</td>
                    <td>{{ $message->message }}</td>
                    <td>
                        <a href="{{ route('admin.messages.conversation', [$message->sender_id, $message->receiver_id]) }}" class="btn btn-sm btn-primary">View Conversation</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
