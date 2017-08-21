@extends('admin.layout.sidebar')

@section('sidebar')
    @parent
@stop

@section('content')
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content-wrapper">
            <div class="page-content">
                <!-- BEGIN PAGE HEADER-->
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                        <h3 class="page-title" style="font-size:28px;">
                        Ubah Data Fasilitas Reflexy
                        </h3>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
                <br>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form action="{{ url('admin/pages/fasilitas/refleksi-persentase-update') }}" class="form-horizontal" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
                        <div class="form-body">
            
                            <input type="" class="hidden" placeholder="" value="{{ $id }}" name="id">
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label">Owner</label>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" placeholder="Enter text" value="{{ $owner }}" name="owner">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Terapis</label>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" placeholder="Enter text" value="{{ $terapis }}" name="terapis">
                                </div>
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="button" onclick="location.href = '{{ url('admin/pages/fasilitas/refleksi') }}';" class="btn btn-default">Cancel</button>
                                <button type="onclick" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                    <!-- END FORM-->
                </div>
            </div>
        </div>
</div>
    <!-- END CONTENT -->
@stop



