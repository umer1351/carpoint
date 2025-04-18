@extends('admin.app_admin')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">{{ CUSTOMER_DETAIL }}</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
                    <div class="float-right d-inline">
                        <a href="{{ route('admin_customer_view') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ BACK_TO_PREVIOUS }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td>{{ BADGE }}</td>
                                <td>
                                @if($customer_detail->badges->count())
    
                                    @foreach($customer_detail->badges as $badge)
                                        <li class="list-inline-item">
                                            <img src="{{ asset('uploads/badges/'.$badge->icon) }}" alt="{{ $badge->name }}" width="40" class="rounded-circle">
                                            <span class="badge badge-primary">{{ $badge->name }}</span>
                                        </li>
                                    @endforeach
                                        
                                @else
                                    <p>No badges assigned.</p>
                                @endif

                                </td>
                            </tr>

                            <tr>
                                <td>{{ PHOTO }}</td>
                                <td>
                                     @if($customer_detail->photo == '')
                                        <img src="{{ asset('uploads/user_photos/default_photo.jpg') }}" class="w_100">
                                    @else
                                        <img src="{{ asset('uploads/user_photos/'.$customer_detail->photo) }}" class="w_100">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>{{ BANNER }}</td>
                                <td>
                                     @if($customer_detail->banner == '')
                                        <img src="{{ asset('uploads/user_photos/default_banner.jpg') }}" class="w_200">
                                    @else
                                        <img src="{{ asset('uploads/user_photos/'.$customer_detail->banner) }}" class="w_100">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>{{ NAME }}</td>
                                <td>{{ $customer_detail->name }}</td>
                            </tr>
                            <tr>
                                <td>{{ EMAIL }}</td>
                                <td>{{ $customer_detail->email }}</td>
                            </tr>
                            <tr>
                                <td>{{ PHONE }}</td>
                                <td>{{ $customer_detail->phone }}</td>
                            </tr>
                            <tr>
                                <td>{{ COUNTRY }}</td>
                                <td>{{ $customer_detail->country }}</td>
                            </tr>
                            <tr>
                                <td>{{ ADDRESS }}</td>
                                <td>{{ $customer_detail->address }}</td>
                            </tr>
                            <tr>
                                <td>{{ STATE }}</td>
                                <td>{{ $customer_detail->state }}</td>
                            </tr>
                            <tr>
                                <td>{{ CITY }}</td>
                                <td>{{ $customer_detail->city }}</td>
                            </tr>
                            <tr>
                                <td>{{ ZIP }}</td>
                                <td>{{ $customer_detail->zip }}</td>
                            </tr>
                            <tr>
                                <td>{{ WEBSITE }}</td>
                                <td>{{ $customer_detail->website }}</td>
                            </tr>
                            <tr>
                                <td>{{ FACEBOOK }}</td>
                                <td>{{ $customer_detail->facebook }}</td>
                            </tr>
                            <tr>
                                <td>{{ TWITTER }}</td>
                                <td>{{ $customer_detail->twitter }}</td>
                            </tr>
                            <tr>
                                <td>{{ LINKEDIN }}</td>
                                <td>{{ $customer_detail->linkedin }}</td>
                            </tr>
                            <tr>
                                <td>{{ INSTAGRAM }}</td>
                                <td>{{ $customer_detail->instagram }}</td>
                            </tr>
                            <tr>
                                <td>{{ PINTEREST }}</td>
                                <td>{{ $customer_detail->pinterest }}</td>
                            </tr>
                            <tr>
                                <td>{{ YOUTUBE }}</td>
                                <td>{{ $customer_detail->youtube }}</td>
                            </tr>
                            <tr>
                                <td>{{ STATUS }}</td>
                                <td>{{ $customer_detail->status }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
        @if($customer_detail->user_role == 'buyer')
            <h3>Orders Placed</h3>
            <table class="table table-bordered">
                <tr>
                    <th>Order ID</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->paid_amount }} {{ $order->paid_currency }}</td>
                    <td>{{ $order->payment_status }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </table>
        @endif
        @if($customer_detail->user_role== 'seller')
            <h3>Orders Received (As Seller)</h3>
            <table class="table table-bordered">
                <tr>
                    <th>Order ID</th>
                    <th>Buyer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
                @foreach($sellerOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->buyer->name ?? 'N/A' }}</td>
                    <td>{{ $order->paid_amount }} {{ $order->paid_currency }}</td>
                    <td>{{ $order->payment_status }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </table>
        @endif


    <div class="card mt-3">
    <div class="card-header">
        <h5>Assign Badge</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin_assign_badge', $customer_detail->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="badge_id">Select Badge:</label>
                <select name="badge_id" class="form-control">
                    @if($badges->count() > 0)
                        @foreach($badges as $badge)
                            <option value="{{ $badge->id }}">{{ $badge->name }}</option>
                        @endforeach
                    @else
                        <option disabled>No badges available</option>
                    @endif
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Assign Badge</button>
        </form>
    </div>
    <!-- <table class="table">
    <thead>
        <tr>
            <th>Badge</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customer_detail->badges as $badge)
        <tr>
            <td>
                <img src="{{ asset('uploads/badges/'.$badge->icon) }}" class="w_40"> 
                {{ $badge->name }}
            </td>
            <td>
                <form action="{{ route('admin_customers_remove_badge', $customer_detail->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="badge_id" value="{{ $badge->id }}">
                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table> -->

</div>

@endsection