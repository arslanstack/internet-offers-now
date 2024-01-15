@extends('admin.admin_app')
@push('styles')
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2> Service Providers & Offers </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url('admin/locations/') }}">Locations</a>
            </li>
            <li class="breadcrumb-item active">
                <strong> Service Providers & Offers </strong>
            </li>
        </ol>
    </div>

    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary text-white t_m_25" href="{{url('admin/locations')}}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Locations
        </a>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-content">
                    <h4 class="mb-4">Location Details</h4>
                    <hr>
                    <form action="#" id="" class="m-4" method="">
                        @csrf
                        <input type="text" name="id" value="{{$ziploc->id}}" hidden>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label"><strong>Zip Code</strong></label>
                                <input type="text" name="zip" id="zip" class="form-control" disabled value="{{$ziploc->zip}}">
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label"><strong>City</strong></label>
                                <input type="text" class="form-control" disabled value="{{$ziploc->city}}">
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label"><strong>State</strong></label>
                                <input type="text" class="form-control" disabled value="{{$ziploc->state}}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-content">
                    <span style="display: flex; justify-content: space-between; align-items: center;">
                        <h4>Service Providers & Offers</h4>
                        <button class="btn btn-primary text-white" data-toggle="modal" data-target="#add_modalbox"><i class="fa fa-plus" aria-hidden="true"></i> Add Provider & Offers in Location</button>
                    </span>
                    <hr>
                    <div class="table-responsive">
                        <table id="manage_tbl" class="table table-striped table-bordered dt-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Service Provider</th>
                                    <th>Total Local Offers</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach($providers as $item)
                                <tr class="gradeX">
                                    <td>{{ $i++ }}</td>
                                    <td>{{$item->name}}</td>
                                    <td class="text-center">{{count_offers_in_zip($item->id, $ziploc->zip)}}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" id="btn_provider_edit" data-id="{{$item->id}}" data-zip="{{$ziploc->zip}}" data-placement="top" title="Details" data-toggle="modal" data-target="#edit_modalbox"> Details </button>
                                        <button class="btn btn-danger btn-sm btn_provider_delete" data-id="{{$item->id}}" data-zip="{{$ziploc->zip}}" data-text="This action will remove this service provider from specified location." type="button" data-placement="top" title="Remove">Remove</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    $('#manage_tbl').dataTable({
        "paging": true,
        "searching": true,
        "bInfo": true,
        "responsive": true,
        "pageLength": 50,
        "columnDefs": [{
                "responsivePriority": 1,
                "targets": 0
            },
            {
                "responsivePriority": 2,
                "targets": -1
            },
        ]
    });
    $(document).on("click", ".btn_provider_delete", function() {
        var id = $(this).attr('data-id');
        var zip = $(this).attr('data-zip');
        swal({
                title: "Are you sure?",
                text: "You want to remove this provider from this location!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, please!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $(".confirm").prop("disabled", true);
                    $.ajax({
                        url: "{{ url('admin/locations/remove-provider') }}",
                        type: 'post',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'provider_id': id,
                            'zip': zip,
                        },
                        dataType: 'json',
                        success: function(status) {
                            $(".confirm").prop("disabled", false);
                            if (status.msg == 'success') {
                                swal({
                                        title: "Success!",
                                        text: status.response,
                                        type: "success"
                                    },
                                    function(data) {
                                        location.reload();
                                    });
                            } else if (status.msg == 'error') {
                                swal("Error", status.response, "error");
                            }
                        }
                    });
                } else {
                    swal("Cancelled", "", "error");
                }
            });
    });
</script>

@endpush