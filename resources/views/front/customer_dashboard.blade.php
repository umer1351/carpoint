@extends('front.app_front')

@section('content')

<!-- <div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$page_other_item->customer_panel_page_banner) }}')">
	<div class="page-banner-bg"></div>
	<h1>{{ DASHBOARD }}</h1>
	<nav>
		<ol class="breadcrumb justify-content-center">
			<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
			<li class="breadcrumb-item active">{{ DASHBOARD }}</li>
		</ol>
	</nav>
</div> -->

<div class="page-content py-5">
	<div class="container">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-md-3 mb-4">
				<div class="user-sidebar shadow-sm p-3 bg-white rounded">
					@include('front.customer_sidebar')
				</div>
			</div>

			<!-- Main Content -->
			<div class="col-md-9">

				<!-- Dashboard Stats -->
				<div class="row g-4 mb-4">
					<div class="col-md-6">
						<div class="dashboard-box dashboard-box-1 p-4  text-white rounded shadow-sm">
							<h6 class="text-uppercase mb-2" style="color:white;"> <i class="fas fa-list pr-10"></i>{{ ACTIVE_LISTING_ITEMS }}</h6>
							<h3 class="fw-bold" style="color:white;">{{ $total_active_listing }}</h3>
						</div>
					</div>
					<div class="col-md-6">
						<div class="dashboard-box dashboard-box-2 p-4 bg-warning text-dark rounded shadow-sm">
							<h6 class="text-uppercase mb-2" style="color:white;"><i class="fas fa-list pr-10"></i>{{ PENDING_LISTING_ITEMS }}</h6>
							<h3 class="fw-bold" style="color:white;">{{ $total_pending_listing }}</h3>
						</div>
					</div>
				</div>

				<!-- Active Package Details -->
				@if(!$detail == null)
				<div class="dashboard-box dashboard-box-3 p-4 bg-light rounded shadow-sm">
					<h5 class="mb-4 text-secondary"></h5>
					<div class="table-responsive">
						<table class="table table-bordered align-middle">
							<tr>
								<th class="w-300 bg-white">Package</th>
								<td>{{ $detail->rPackage->package_name }}</td>
							</tr>
							<tr>
								<th class="bg-white">{{ PACKAGE_START_DATE }}</th>
								<td>
									@php
									$good_format = date('d F, Y', strtotime($detail->package_start_date));
									@endphp
									{{ $good_format }}
								</td>
							</tr>
							<tr>
								<th class="bg-white">{{ PACKAGE_END_DATE }}</th>
								<td>
									@php
									$good_format = date('d F, Y', strtotime($detail->package_end_date));
									@endphp
									{{ $good_format }}
								</td>
							</tr>
							<tr>
								<th class="bg-white">{{ LISTING_ALLOWED }}</th>
								<td>{{ $detail->rPackage->total_listings }}</td>
							</tr>
							<tr>
								<th class="bg-white">{{ DAYS_REMAINING }}</th>
								<td>
									@php
									$dt1 = strtotime(date('Y-m-d'));
									$dt2 = strtotime($detail->package_end_date);
									$final_days = (int)(($dt2 - $dt1)/86400);
									@endphp

									@if($final_days < 0)
										<span class="badge bg-danger">{{ EXPIRED }}</span>
									@else
										<span class="badge bg-success">{{ $final_days }} {{ __('days') }}</span>
									@endif
								</td>
							</tr>
							<tr>
								<th class="bg-white">{{ QUESTION_FEATURED_LISTING_ALLOWED }}</th>
								<td>{{ $detail->rPackage->allow_featured }}</td>
							</tr>
						</table>
					</div>
				</div>
				@endif
				<div class="row g-4 mb-4">
					<div class="col-md-6">
						<div class="dashboard-box dashboard-box-1 p-4  text-white rounded shadow-sm">
							<h6 class="text-uppercase mb-2" style="color:white;"> <i class="fas fa-warehouse pr-10"></i>Visit</h6>
							<h3 class="fw-bold " ><a href="{{ route('garages_index') }}" style="color:white;">Garage</a></h3>
						</div>
					</div>
					<div class="col-md-6">
						<div class="dashboard-box dashboard-box-2 p-4 bg-warning text-dark rounded shadow-sm">
							<h6 class="text-uppercase mb-2" style="color:white;"><i class="fas fa-list pr-10"></i>List</h6>
							<h3 class="fw-bold"><a href="{{ route('customer_my_reviews') }}" style="color:white;">Reviews</a></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


@endsection
<style>
	/* Dashboard Box Styling */
.dashboard-box {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.dashboard-box h6 {
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.dashboard-box h3 {
    font-size: 2rem;
    font-weight: bold;
}

.dashboard-box-1 {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: #fff;
}

.dashboard-box-2 {
    background: linear-gradient(135deg, #ffc107, #e0a800);
    color: #212529;
}

.dashboard-box-3 {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

/* Table Enhancements */
.table th, .table td {
    vertical-align: middle !important;
    padding: 12px 16px;
}

.table th {
    background-color: #f1f1f1;
    font-weight: 600;
}

.w-300 {
    width: 300px;
}

/* Badge Styling */
.badge {
    font-size: 0.85rem;
    padding: 0.45em 0.65em;
    border-radius: 50rem;
}

.badge-danger {
    background-color: #dc3545;
}

.badge-success {
    background-color: #28a745;
}

.user-sidebar {
    background: #ffffff;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}

.table tr td {
    border: 1px solid #dee2e6 !important;
}

</style>