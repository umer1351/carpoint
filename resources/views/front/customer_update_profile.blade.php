@extends('front.app_front')

@section('content')

<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$page_other_item->customer_panel_page_banner) }}')">
	<div class="page-banner-bg"></div>
	<h1>{{ EDIT_PROFILE_INFORMATION }}</h1>
	<nav>
		<ol class="breadcrumb justify-content-center">
			<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
			<li class="breadcrumb-item active">{{ EDIT_PROFILE_INFORMATION }}</li>
		</ol>
	</nav>
</div>

<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="user-sidebar">
					@include('front.customer_sidebar')
				</div>
			</div>
			<div class="col-md-9">
	
				<form action="{{ route('customer_update_profile_confirm') }}" method="post">
                    @csrf
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="form-group">
									<div class="card-header">
										<h5>Badge Earned</h5>
									</div>
									<div class="card-body">
										@foreach(auth()->user()->badges as $badge)
											<img src="{{ asset('uploads/badges/'.$badge->icon) }}" class="w_40" title="{{ $badge->name }}">
										@endforeach
									</div>
								</div>	
							</div>
						</div>
					</div>		
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ NAME }}</label>
								<input type="text" class="form-control" name="name" value="{{ $user_data->name }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ EMAIL_ADDRESS }}</label>
								<input type="email" class="form-control" name="email" value="{{ $user_data->email }}">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ PHONE }}</label>
								<input type="text" class="form-control" name="phone" value="{{ $user_data->phone }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ COUNTRY }}</label>
								<input type="text" class="form-control" name="country" value="{{ $user_data->country }}">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ ADDRESS }}</label>
								<input type="text" class="form-control" name="address" value="{{ $user_data->address }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ STATE }}</label>
								<input type="text" class="form-control" name="state" value="{{ $user_data->state }}">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ CITY }}</label>
								<input type="text" class="form-control" name="city" value="{{ $user_data->city }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ ZIP }}</label>
								<input type="text" class="form-control" name="zip" value="{{ $user_data->zip }}">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ WEBSITE }}</label>
								<input type="text" class="form-control" name="website" value="{{ $user_data->website }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ FACEBOOK }}</label>
								<input type="text" class="form-control" name="facebook" value="{{ $user_data->facebook }}">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ TWITTER }}</label>
								<input type="text" class="form-control" name="twitter" value="{{ $user_data->twitter }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ LINKEDIN }}</label>
								<input type="text" class="form-control" name="linkedin" value="{{ $user_data->linkedin }}">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ INSTAGRAM }}</label>
								<input type="text" class="form-control" name="instagram" value="{{ $user_data->instagram }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ PINTEREST }}</label>
								<input type="text" class="form-control" name="pinterest" value="{{ $user_data->pinterest }}">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ YOUTUBE }}</label>
								<input type="text" class="form-control" name="youtube" value="{{ $user_data->youtube }}">
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-primary">{{ UPDATE }}</button>
				</form>

			</div>
		</div>
	</div>
</div>

@endsection
