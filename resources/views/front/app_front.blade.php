@php
$g_setting = \App\Models\GeneralSetting::where('id',1)->first();
$currency_list = \App\Models\Currency::get();
@endphp

@if(!session()->get('currency_name'))
@php
$sess_arr = \App\Models\Currency::where('is_default','Yes')->first();
$name1 = $sess_arr->name;
$symbol1 = $sess_arr->symbol;
$value1 = $sess_arr->value;
session()->put('currency_name',$name1);
session()->put('currency_symbol',$symbol1);
session()->put('currency_value',$value1);
@endphp
@endif

<!DOCTYPE html>
<html lang="en">
   	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        @php
            $route = Route::currentRouteName();
        @endphp

        @if($route == null)
            @php $item_row = \App\Models\PageHomeItem::where('id',1)->first(); @endphp
		    <title>{{ $item_row->seo_title }}</title>
		    <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_about')
            @php $item_row = \App\Models\PageAboutItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_contact')
            @php $item_row = \App\Models\PageContactItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_blogs')
            @php $item_row = \App\Models\PageBlogItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_post')
            @php
                $main_url = Request::url();
                $slug = explode('post/',$main_url);
            @endphp
            @php $item_row = \App\Models\Blog::where('post_slug',$slug[1])->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_listing_result')
            @php $item_row = \App\Models\PageListingItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_listing_detail')
            @php
                $main_url = Request::url();
                $slug = explode('listing/',$main_url);
            @endphp
            @php $item_row = \App\Models\Listing::where('listing_slug',$slug[1])->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_pricing')
            @php $item_row = \App\Models\PagePricingItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_faq')
            @php $item_row = \App\Models\PageFaqItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_listing_location_all')
            @php $item_row = \App\Models\PageListingLocationItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_listing_brand_all')
            @php $item_row = \App\Models\PageListingBrandItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_dynamic_page')
            @php
                $main_url = Request::url();
                $slug = explode('page/',$main_url);
            @endphp
            @php $item_row = \App\Models\DynamicPage::where('dynamic_page_slug',$slug[1])->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_category')
            @php
                $main_url = Request::url();
                $slug = explode('category/',$main_url);
            @endphp
            @php $item_row = \App\Models\Category::where('category_slug',$slug[1])->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_listing_location_detail')
            @php
                $main_url = Request::url();
                $slug = explode('listing/location/',$main_url);
            @endphp
            @php $item_row = \App\Models\ListingLocation::where('listing_location_slug',$slug[1])->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_listing_brand_detail')
            @php
                $main_url = Request::url();
                $slug = explode('listing/brand/',$main_url);
            @endphp
            @php $item_row = \App\Models\ListingBrand::where('listing_brand_slug',$slug[1])->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'customer_login')
            @php $item_row = \App\Models\PageOtherItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->login_page_seo_title }}</title>
            <meta name="description" content="{{ $item_row->login_page_seo_meta_description }}">
        @endif

        @if($route == 'customer_registration')
            @php $item_row = \App\Models\PageOtherItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->registration_page_seo_title }}</title>
            <meta name="description" content="{{ $item_row->registration_page_seo_meta_description }}">
        @endif

        @if($route == 'customer_forget_password')
            @php $item_row = \App\Models\PageOtherItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->forget_password_page_seo_title }}</title>
            <meta name="description" content="{{ $item_row->forget_password_page_seo_meta_description }}">
        @endif

        @if($route == 'front_terms_and_conditions')
            @php $item_row = \App\Models\PageTermItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_privacy_policy')
            @php $item_row = \App\Models\PagePrivacyItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'customer_dashboard'||$route == 'customer_package'||$route == 'customer_package_purchase_history'||$route == 'customer_listing_view'||$route == 'customer_listing_view_detail'||$route == 'customer_listing_add'||$route == 'customer_listing_edit'||$route == 'customer_my_reviews'||$route == 'customer_my_review_edit'||$route == 'customer_wishlist'||$route == 'customer_update_profile'||$route == 'customer_update_password'||$route == 'customer_update_photo'||$route == 'customer_update_banner'||$route == 'customer_package_purchase_invoice'||$route == 'customer_package_purchase_history_detail')
            @php $item_row = \App\Models\PageOtherItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->customer_panel_page_seo_title }}</title>
            <meta name="description" content="{{ $item_row->customer_panel_page_seo_meta_description }}">
        @endif


		<link rel="icon" type="image/png" href="{{ asset('uploads/site_photos/'.$g_setting->favicon) }}">

		@include('front.app_styles')

		<link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700;800&display=swap" rel="stylesheet">

		@include('front.app_scripts')

		@if($g_setting->tawk_live_chat_status == 'Show')
		<style>
		.scroll-top {
			bottom: 88px!important;
		}
		@endif
		</style>

        @if($g_setting->cookie_consent_status == 'Show')
            <script src="https://cdn.websitepolicies.io/lib/cookieconsent/1.0.3/cookieconsent.min.js" defer></script><script>window.addEventListener("load",function(){window.wpcc.init({"colors":{"popup":{"background":"#{{ $g_setting->cookie_consent_bg_color }}","text":"#{{ $g_setting->cookie_consent_text_color }}","border":"#b3d0e4"},"button":{"background":"#{{ $g_setting->cookie_consent_button_bg_color }}","text":"#{{ $g_setting->cookie_consent_button_text_color }}"}},"position":"bottom","padding":"large","margin":"none","content":{"message":"{{ $g_setting->cookie_consent_message }}","button":"{{ $g_setting->cookie_consent_button_text }}"}})});</script>
        @endif


        @if($g_setting->google_analytic_status == 'Show')
        <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $g_setting->google_analytic_tracking_id }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '{{ $g_setting->google_analytic_tracking_id }}');
            </script>
        @endif


        <style>
            .mobile-nav.mean-container .mean-nav ul li a.active,
            .main-nav nav .navbar-nav .nav-item a:hover,
            .main-nav nav .navbar-nav .nav-item a:focus,
            .main-nav nav .navbar-nav .nav-item a.active,
            .main-nav nav .navbar-nav .nav-item:hover a,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li a:hover,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li a:focus,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li a.active,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li .dropdown-menu li a:hover,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li .dropdown-menu li a:focus,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li .dropdown-menu li a.active,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li:hover a,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li a:hover,
            .listing .listing-item .text .location,
            .listing .listing-item .text h3 a:hover,
            .footer-item h2,
            .footer-item ul li a:hover,
            .listing-filter .lf-heading,
            .listing .listing-item .text .location a,
            .listing-single-banner .listing-items a,
            .listing-page h2 i,
            .listing-page .amenities li i,
            .listing-page .contact a,
            .listing-page .review-overall .total,
            .listing-sidebar .ls-widget .agent-contact li,
            .listing-sidebar .ls-widget .agent-contact li a,
            .listing-sidebar .ls-widget .category ul li a,
            .faq h4.panel-title a,
            .sidebar .widget .type-1 ul li:before,
            .sidebar .widget .type-1 ul li a:hover,
            .contact-icon i,
            .reg-login-form .new-user a,
            .reg-login-form .link,
            .listing-page .room-all .item .price,
            .popular-city .popular-city-item:hover h4 {
                color: #{{ $g_setting->theme_color }};
            }

            .main-nav nav .navbar-nav .nav-item .dropdown-menu li a:hover,
            .mean-container a.meanmenu-reveal,
            .footer-social-link ul li a:hover {
                color: #{{ $g_setting->theme_color }}!important;
            }

            .search-section .input-group-append button,
            .listing .listing-item .photo .brand a,
            .popular-city-carousel .owl-nav .owl-prev,
            .popular-city-carousel .owl-nav .owl-next,
            .footer-social-link ul li a,
            .scroll-top,
            .page-banner,
            .filter-button,
            .listing-sidebar .ls-widget .agent-social ul li a,
            .listing-sidebar .ls-widget a.agent-view-profile,
            .pricing .btn,
            .contact-form .btn,
            .reg-login-form button,
            .listing .owl-nav .owl-prev, 
            .listing .owl-nav .owl-next,
            .listing-single-banner .social a:hover,
            .top,
            .agent-banner .social a:hover,
            .mean-container a.meanmenu-reveal span,
            .comment button {
                background: #{{ $g_setting->theme_color }};
            }

            .footer-social-link ul li a,
            .contact-form .btn {
                border-color: #{{ $g_setting->theme_color }};
            }

            .listing-filter .lf-heading {
                border-bottom-color: #{{ $g_setting->theme_color }};
            }

        </style>

   	</head>
   	<body>

   		<div id="mySidepanel" class="sidepanel">
			<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
			<a href="#">About</a>
			<a href="#">Services</a>
			<a href="#">Clients</a>
			<a href="#">Contact</a>
		</div>

        <div class="top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-5 col-sm-12">
                        <ul class="top-left">                            
                            @if($g_setting->top_phone!='')
                            <li><i class="fas fa-phone-alt"></i> {{ $g_setting->top_phone }}</li>
                            @endif
                            @if($g_setting->top_email!='')
                            <li><i class="fas fa-envelope"></i> {{ $g_setting->top_email }}</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-7 col-sm-12">
                        @if($g_setting->customer_listing_option == 'On')
                        <ul class="top-right">
                            <li>
                                <form action="{{ route('front_currency') }}" method="post">
                                    @csrf
                                    <select name="currency_name" class="nav-link" onchange="this.form.submit()">
                                        @foreach($currency_list as $row)
                                            <option value="{{ $row->name }}" @if($row->name == session()->get('currency_name')) selected @endif>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </li>
                            <li>
                                @if(Auth::user())
                                <a href="{{ route('customer_dashboard') }}" class="nav-link"><i class="fas fa-sign-in-alt"></i> {{ MENU_DASHBOARD }}</a>
                                @else
                                <a href="{{ route('customer_login') }}" class="nav-link"><i class="fas fa-sign-in-alt"></i> {{ MENU_LOGIN_REGISTER }}</a>
                                @endif
                            </li>
                            <!-- <li class="currency">
                                <a href="{{ route('customer_listing_add') }}" class="nav-link"><i class="fas fa-plus"></i> {{ MENU_ADD_LISTING }}</a>
                            </li>                             -->
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

		@include('front.app_nav')

		@yield('content')

		@include('front.app_footer')

      	<div class="scroll-top">
		  	<i class="fas fa-long-arrow-alt-up"></i>
	    </div>

	    @include('front.app_scripts_footer')

   </body>
</html>