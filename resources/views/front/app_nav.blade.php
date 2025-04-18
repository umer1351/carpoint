@php
$g_settings = \App\Models\GeneralSetting::where('id',1)->first();
$dynamic_pages = \App\Models\DynamicPage::get();
$page_about_item = \App\Models\PageAboutItem::where('id',1)->first();
$page_faq_item = \App\Models\PageFaqItem::where('id',1)->first();
$page_blog_item = \App\Models\PageBlogItem::where('id',1)->first();
$page_listing_item = \App\Models\PageListingItem::where('id',1)->first();
$page_pricing_item = \App\Models\PagePricingItem::where('id',1)->first();
$page_contact_item = \App\Models\PageContactItem::where('id',1)->first();
$page_listing_location_item = \App\Models\PageListingLocationItem::where('id',1)->first();
$page_listing_brand_item = \App\Models\PageListingBrandItem::where('id',1)->first();
@endphp
<style>
	@keyframes rotateDrive {
		0%   { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
		}

		.rotating {
		animation: rotateDrive 1s linear infinite;
		}

</style>
<!-- Start Navbar Area -->
<div class="navbar-area" id="stickymenu">

	<!-- Menu For Mobile Device -->
	<div class="mobile-nav">
		<a href="{{ url('/') }}" class="logo">
			<img src="{{ asset('uploads/site_photos/'.$g_settings->logo) }}" alt="">
		</a>
		
	</div>

	<!-- Menu For Desktop Device -->
	<div class="main-nav">
		<div class="container">
			<nav class="navbar navbar-expand-md navbar-light">
				<a class="navbar-brand" href="{{ url('/') }}">
					<img src="{{ asset('uploads/site_photos/'.$g_settings->logo) }}" alt="">
				</a>
				<div id="tyre-toggle" style="display: inline-flex; align-items: center; cursor: pointer; margin-left: 10px;">
					<div id="tyre-wrapper"
						class="rotating"
						style="width: 0.5in; height: 0.5in; background-color: orange; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
						<svg id="tyre" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="white" viewBox="0 0 24 24">
						<path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 17.93V19a1 1 0 10-2 0v.93a8.001 8.001 0 01-6.928-6.928H5a1 1 0 100-2h-.93a8.001 8.001 0 016.928-6.928V5a1 1 0 102 0v.07a8.001 8.001 0 016.928 6.928H19a1 1 0 100 2h.93a8.001 8.001 0 01-6.928 6.928z"/>
						</svg>
					</div>
				</div>

					<audio id="engine-sound" loop autoplay>
					<source src="{{ asset('uploads/sounds/engine-sound.mp3') }}" type="audio/mpeg">
					Your browser does not support the audio element.
					</audio>






				<div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
					<ul class="navbar-nav {{ $g_settings->layout_direction == 'ltr' ? 'ml-auto' : 'mr-auto' }}">

						<li class="nav-item">
							<a href="{{ url('/') }}" class="nav-link">{{ MENU_HOME }}</a>
						</li>

                        @if($page_listing_item->status == 'Show')
						<li class="nav-item">
							<a href="{{ url('listing-result') }}" class="nav-link">{{ MENU_LISTING }}</a>
						</li>
                        @endif

                        @if($page_pricing_item->status == 'Show')
						<li class="nav-item">
							<a href="{{ route('front_pricing') }}" class="nav-link">{{ MENU_PRICING }}</a>
						</li>
                        @endif

						@if($page_faq_item->status == 'Show')
						<li class="nav-item">
							<a href="{{ route('front_faq') }}" class="nav-link">{{ MENU_FAQ }}</a>
						</li>
						@endif

                        @if($page_listing_location_item->status == 'Show' || $page_listing_brand_item->status == 'Show' || (!$dynamic_pages->isEmpty()))
						<li class="nav-item">
							<a href="javascript:void;" class="nav-link dropdown-toggle">{{ MENU_PAGES }}</a>
							<ul class="dropdown-menu">

                                @if($page_listing_location_item->status == 'Show')
								<li class="nav-item">
									<a href="{{ route('front_listing_location_all') }}" class="nav-link">{{ MENU_LOCATION }}</a>
								</li>
                                @endif

                                @if($page_listing_brand_item->status == 'Show')
								<li class="nav-item">
									<a href="{{ route('front_listing_brand_all') }}" class="nav-link">{{ MENU_BRAND }}</a>
								</li>
                                @endif

                                @if(!$dynamic_pages->isEmpty())
								@foreach($dynamic_pages as $row)
                                    <li class="nav-item">
                                        <a href="{{ url('page/'.$row->dynamic_page_slug) }}" class="nav-link">{{ $row->dynamic_page_name }}</a>
                                    </li>
                                @endforeach
                                @endif
							</ul>
						</li>
                        @endif

						@if($page_blog_item->status == 'Show')
						<li class="nav-item">
							<a href="{{ route('front_blogs') }}" class="nav-link">{{ MENU_BLOG }}</a>
						</li>
						@endif

                        @if($page_contact_item->status == 'Show')
						<li class="nav-item">
							<a href="{{ route('front_contact') }}" class="nav-link">{{ MENU_CONTACT }}</a>
						</li>
                        @endif

					</ul>
				</div>
			</nav>
		</div>
	</div>
</div>
<!-- End Navbar Area -->

<script>
  const tyreWrapper = document.getElementById('tyre-wrapper');
  const engine = document.getElementById('engine-sound');
  let isRolling = true;

  // Autoplay when page loads
  window.addEventListener('DOMContentLoaded', () => {
    try {
      engine.play();
    } catch (err) {
      // Some browsers block autoplay
      console.warn('Autoplay failed. User interaction may be needed.');
    }
  });

  document.getElementById('tyre-toggle').addEventListener('click', () => {
    isRolling = !isRolling;

    if (isRolling) {
      tyreWrapper.classList.add('rotating');
      engine.play();
    } else {
      tyreWrapper.classList.remove('rotating');
      engine.pause();
      engine.currentTime = 0;
    }
  });
</script>


