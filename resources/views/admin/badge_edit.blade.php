@extends('admin.app_admin')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">{{ BADGE_DETAIL }}</h1>

    <div class="row">
        <div class="col-md-6">        
            <form action="{{ route('admin_badges_update',$badge->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
                        <div class="float-right d-inline">
                            <a href="{{ route('admin_currency_view') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ VIEW_ALL }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">{{ NAME }} *</label>
                            <input type="text" name="name" class="form-control" value="{{ $badge->name }}" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="">{{ EXISTING_PHOTO }}</label>
                            <div>
                                <img src="{{ asset('uploads/badges/'.$badge->icon) }}" alt="" class="w_40">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{ CHANGE_PHOTO }}</label>
                            <div>
                                <input type="file" name="icon">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{ DESCRIPTION }}</label>
                            <textarea name="description" class="form-control editor" cols="30" rows="10">{{ $badge->description }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success">{{ UPDATE }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
