
@extends('front.app_front')

@section('content')
<div class="container">
    <h2>Verify OTP</h2>
    <p>OTP has been sent to your phone number.</p>

    <form method="POST" action="{{ route('customer_otp_verify') }}">
        @csrf
        <input type="text" name="otp" class="form-control" placeholder="Enter OTP">
        <br>
        <button type="submit" class="btn btn-success">Verify OTP</button>
    </form>
</div>

@endsection

