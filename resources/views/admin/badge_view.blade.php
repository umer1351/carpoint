@extends('admin.app_admin')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">{{ BADGE }}</h1>

    <div class="card shadow mb-4">
    <div class="card-header py-3">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a href="{{ route('admin_badges_create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ ADD_NEW }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{ SERIAL }}</th>
                        <th>{{ NAME }}</th>
                        <th>{{ PHOTO }}</th>
                        <th>{{ STATUS }}</th>
                        <th>{{ ACTION }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($badges as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->name }}</td>
                            <td>
                                @if($row->icon == '')
                                    <img src="{{ asset('uploads/badges/default.png') }}" class="w_40">
                                @else
                                    <img src="{{ asset('uploads/badges/'.$row->icon) }}" class="w_40">
                                @endif
                            </td>
                            <td>
                                @if ($row->status == 'Active')
                                <a href="" onclick="badgeStatus({{ $row->id }})"><input type="checkbox" checked data-toggle="toggle" data-on="Active" data-off="Pending" data-onstyle="success" data-offstyle="danger"></a>
                                @else
                                    <a href="" onclick="badgeStatus({{ $row->id }})"><input type="checkbox" data-toggle="toggle" data-on="Active" data-off="Pending" data-onstyle="success" data-offstyle="danger"></a>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin_badges_edit',$row->id) }}" class="btn btn-info btn-sm btn-block">{{ DETAIL }}</a>
                                <a href="{{ route('admin_badges_delete',$row->id) }}" class="btn btn-danger btn-sm btn-block" onClick="return confirm('{{ ARE_YOU_SURE }}');">{{ DELETE }}</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function badgeStatus(id){
            $.ajax({
                type:"get",
                url:"{{url('/admin/badge-status/')}}"+"/"+id,
                success:function(response){
                   toastr.success(response)
                },
                error:function(err){
                    console.log(err);
                }
            })
        }
    </script>
@endsection
