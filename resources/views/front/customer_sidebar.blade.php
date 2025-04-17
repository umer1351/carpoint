<ul class='uul'>
	<li><a href="{{ route('customer_dashboard') }}" class="btn btn-md btn-block btn-dark"><i class="fas fa-fw fa-home pr-10"></i> {{ DASHBOARD }}</a></li>
	<li><a href="{{ route('customer_package') }}" class="btn btn-md btn-block btn-dark"><i class="far fa-caret-square-right pr-10"></i> {{ PACKAGES }}</a></li>
	<li><a href="{{ route('customer_package_purchase_history') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-folder pr-10"></i> {{ PURCHASE_HISTORY }}</a></li>

@if(Auth::user()->user_role == 'seller')
<li><a href="{{ route('customer_listing_view') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-list pr-10"></i> {{ ALL_LISTINGS }}</a></li>

<li><a href="{{ route('customer_listing_add') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-plus-circle pr-10"></i> {{ ADD_LISTING }}</a></li>

<li><a href="{{ route('seller_inspections') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-search pr-10"></i> Inspection Requests</a></li>

<li><a href="{{ route('garages_index') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-warehouse pr-10"></i> Garage</a></li>

<li><a href="{{ route('garages_create') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-tools pr-10"></i> Create Garage</a></li>
@endif

<li><a href="{{ route('customer_my_reviews') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-star pr-10"></i> {{ MY_REVIEWS }}</a></li>

<li><a href="{{ route('customer_wishlist') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-heart pr-10"></i> {{ WISHLIST }}</a></li>

<li><a href="{{ route('customer_update_profile') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-user-edit pr-10"></i> {{ EDIT_PROFILE }}</a></li>

<li><a href="{{ route('customer_update_password') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-key pr-10"></i> {{ EDIT_PASSWORD }}</a></li>

<li><a href="{{ route('customer_update_photo') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-camera pr-10"></i> {{ EDIT_PHOTO }}</a></li>

<li><a href="{{ route('customer_update_banner') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-image pr-10"></i> {{ EDIT_BANNER }}</a></li>

<li><a href="{{ route('messages.inbox') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-envelope pr-10"></i> Message</a></li>

<li><a href="{{ route('customer_logout') }}" class="btn btn-md btn-block btn-dark">
    <i class="fas fa-sign-out-alt pr-10"></i> {{ LOGOUT }}</a></li>
</ul>
<style>
	.pr-10{
		padding-right:10px;
	}
	.uul li a{
		text-align: left;
		padding-left: 20px;
	}
</style>