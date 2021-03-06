<!DOCTYPE html>

<html lang="en" class="no-js">
<!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="refresh" content="10" />
        <title>Status Terapis Refleksi</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta content="" name="description"/>
        <meta content="" name="author"/>
        <meta name="MobileOptimized" content="320">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/bootstrap/css/bootstrapCustom.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet">
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="{{ asset('assets/plugins/select2/select2_conquer.css') }}" rel="stylesheet">
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME STYLES -->
        <link href="{{ asset('assets/css/style-conquer.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/styleCustom.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/style-responsive.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/themes/default.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/pages/loginWide.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/pages/terapis.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/pages/status-terapis.css') }}" rel="stylesheet">
        <!-- END THEME STYLES -->
        <link rel="shortcut icon" href="favicon.ico"/>
    </head>
    
    <!-- BEGIN BODY -->
    <body class="status-terapis">
        <!-- BEGIN LOGIN -->
        <br>
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <form class="login-form">    
                <!-- BEGIN CRUD TABLE -->
                <div class="portlet-body">
                    <p style='font-size: 70px; color: black; text-align: center;'>REFLEXY G2</p>
                    <div class="table1">
                    <table class="table daftar" >
                        <thead>
                        <tr>
                            <th>
                                 No.
                            </th>
                            <th>
                                 Nama Terapis
                            </th>
                            <th>
                                 Durasi
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                            <p class="hidden">{{ $count = 1 }}</p>
                            @foreach ($transaksi->forPage(1, 10) as $trans)
                        <tr>
                            <td>
                                {{ $count++ }}
                            </td>
                            <td>
                                {{ $trans->terapis->nama }}
                            </td>
                            <td>
                                {{ $trans->getDuration($trans->no_kartu) }} menit
                            </td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table class="table daftar" >
                        <thead>
                        <tr>
                            <th>
                                 No.
                            </th>
                            <th>
                                 Nama Terapis
                            </th>
                            <th>
                                 Durasi
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                            <p class="hidden">{{ $count = 11 }}</p>
                            @foreach ($transaksi->forPage(2, 10) as $trans)
                        <tr>
                            <td>
                                {{ $count++ }}
                            </td>
                            <td>
                                {{ $trans->terapis->nama }}
                            </td>
                            <td>
                                {{ $trans->getDuration($trans->no_kartu) }} menit
                            </td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                <!-- END CRUD TABLE -->
                
            </form>
            <!-- END LOGIN FORM -->
        </div>
        <!-- END LOGIN -->
    </body>
<!-- END BODY -->
</html>