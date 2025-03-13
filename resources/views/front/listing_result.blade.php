@extends('front.app_front')

@section('content')

<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$listing_page_data->banner) }}')">
    <div class="page-banner-bg"></div>
    <h1>{{ $listing_page_data->name }}</h1>
    <nav>
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
            <li class="breadcrumb-item active">{{ $listing_page_data->name }}</li>
        </ol>
    </nav>
</div>

<div class="page-content">
    <div class="container">
        <div class="row listing pt_0 pb_0">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <form id="searchFormId">
                    <div class="listing-filter">
                        <div class="lf-heading">
                            {{ FILTERS }}
                        </div>
                        <div class="lf-widget">
                            <input type="text" id="text" name="text" class="form-control" placeholder="{{ FIND_ANYTHING }}" value="{{ request()->has('text') ? request()->get('text') : '' }}">
                        </div>
                        <div class="lf-widget">
                            <h2>{{ TYPE }}</h2>
                            <select name="listing_type" class="form-control" id="listing_type">
								<option value="" >{{ ALL }}</option>
                                @if (request()->has('listing_type'))
                                    <option {{ request()->get('listing_type') ==  'New Car' ? 'selected' : ''  }}  value="New Car" >{{ NEW_CAR }}</option>
								    <option {{ request()->get('listing_type') ==  'Used Car' ? 'selected' : ''  }} value="Used Car" >{{ USED_CAR }}</option>
                                @else
                                <option value="New Car" >{{ NEW_CAR }}</option>
								<option value="Used Car" >{{ USED_CAR }}</option>
                                @endif
							</select>
                        </div>
                        @php
                            $sort_cat = [];
                            if(request()->has('brand')){
                                foreach(request()->get('brand') as $cat){
                                    array_push($sort_cat,(int)$cat);
                                }
                            }
                        @endphp
                        <div class="lf-widget">
                            <h2>{{ BRANDS }}</h2>
                            @php $ii=0; @endphp
                            @foreach($listing_brands as $index => $row)
                                <div class="form-check">
                                    <input {{ in_array($row->id ,$sort_cat) ? 'checked' : '' }} name="brand[]" class="form-check-input" type="checkbox" value="{{ $row->id }}" id="cat{{ $index }}">
                                    <label class="form-check-label" for="cat{{ $index }}">
                                        {{ $row->listing_brand_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @php
                            $sort_aminity = [];
                            if(request()->has('amenity')){
                                foreach(request()->get('amenity') as $cat){
                                    array_push($sort_aminity,(int)$cat);
                                }
                            }
                        @endphp
                        <div class="lf-widget">
                            <h2>{{ AMENITIES }}</h2>
                            @foreach($amenities as $index => $row)
                                <div class="form-check">
                                    <input {{ in_array($row->id ,$sort_aminity) ? 'checked' : '' }} name="amenity[]" class="form-check-input" type="checkbox" value="{{ $row->id }}" id="amn{{ $index }}" >
                                    <label class="form-check-label" for="amn{{ $index }}">
                                        {{ $row->amenity_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @php
                            $sort_loc = [];
                            if(request()->has('location')){
                                foreach(request()->get('location') as $cat){
                                    array_push($sort_loc,(int)$cat);
                                }
                            }
                        @endphp
                        <div class="lf-widget">
                            <h2>{{ LOCATIONS }}</h2>
                            @foreach($listing_locations as $index => $row)
                                <div class="form-check">
                                    <input {{ in_array($row->id ,$sort_loc) ? 'checked' : '' }} name="location[]" class="form-check-input" type="checkbox" value="{{ $row->id }}" id="loc{{ $index }}">
                                    <label class="form-check-label" for="loc{{ $index }}">
                                        {{ $row->listing_location_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <input type="submit" class="form-control filter-button" value="{{ FILTER }}">
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-8 col-md-6 col-sm-12">
                <div class="right-area">
                    <div class="row d-none" id="loader-area">
                        <div class="col-12 text-center mt-5">
                            <div>
                                <img src="{{ asset('loader.gif') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="content-area">
                        <div class="col-12 text-center mt-5">
                            <div>
                                <img src="{{ asset('loader.gif') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let loaderHtml = $("#loader-area").html();
    (function($) {
        "use strict";
        $(document).ready(function () {
            loadListingUsingAjax();
            $("#searchFormId").on('submit', function(e){
                e.preventDefault();
                submitSearchForm()
            })
            $("#listing_type").on('change', function(){
                submitSearchForm()
            })
            $(".form-check-input").on('click', function(){
                submitSearchForm()
            })
            $("#text").on('keyup', function(e){
                if(e.target.keyCode === '13'){
                    submitSearchForm()
                }
            })
        });
    })(jQuery);

    function loadListingUsingAjax(){
        submitSearchForm()
    }

    function submitSearchForm(){
        $('#content-area').html(loaderHtml);

        $.ajax({
            type: 'get',
            data: $('#searchFormId').serialize(),
            url: "{{ route('search-front_listing_result') }}",
            success: function (response) {
                $('#content-area').html(response);
            },
            error: function(err) {}
        });
    }

    function loadAjaxListing(url){
        $('#content-area').html(loaderHtml);
        $.ajax({
            type: 'get',
            url: url,
            success: function (response) {
                $('#content-area').html(response);
            },
            error: function(err) {}
        });
    }
</script>
@endsection