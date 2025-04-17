@extends('admin.app_admin')
@section('admin_content')
<div class="container">
    <h3>Finance Inquiries</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Buyer</th>
                <th>Listing</th>
                <th>Term</th>
                <th>Down Payment</th>
                <th>Message</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inquiries as $inquiry)
                <tr>
                    <td>{{ $inquiry->buyer->name }}</td>
                    <td>{{ $inquiry->listing->title }}</td>
                    <td>{{ $inquiry->term }}</td>
                    <td>${{ number_format($inquiry->down_payment, 2) }}</td>
                    <td>{{ $inquiry->message }}</td>
                    <td>
                        <span class="badge bg-{{ $inquiry->status === 'Pending' ? 'warning' : 'success' }}">
                            {{ $inquiry->status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection