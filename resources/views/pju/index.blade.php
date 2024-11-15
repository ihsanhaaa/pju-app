@extends('layouts.app')

@section('title')
    Data PJU
@endsection

@section('content')
    @push('css-plugins')
        
    @endpush

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- header -->
        @include('components.navbar_admin')
        
        <!-- Start right Content here -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Data PJU</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">PJU</a></li>
                                        <li class="breadcrumb-item active">Data PJU</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach ($errors->all() as $error)
                                <strong>{{ $error }}</strong><br>
                            @endforeach
                        </div>
                    @endif

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong>Success!</strong> {{ $message }}.
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-primary waves-effect waves-light mb-3 mx-1" data-bs-toggle="modal" data-bs-target="#uploadModalPju"><i class="fas fa-plus"></i> Tambah Data PJU</button>
                                    
                            <!-- First modal dialog -->
                            <div class="modal fade" id="uploadModalPju" aria-hidden="true" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload File Geojson PJU</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('data-pju.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-4">
                                                    <input class="form-control" type="file" name="geojson_file" accept=".geojson" required>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
        
                                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama PJU</th>
                                            <th>Panel KWh</th>
                                            <th>Kecamatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
    

                                        <tbody>
                                        @foreach ($pjus as $key => $pju)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $pju->nama_pju }}</td>
                                                <td>{{ $pju->kwh->nama_kwh ?? ' ' }}</td>
                                                <td>{{ $pju->kecamatan->nama_kecamatan ?? ' ' }}</td>
                                                <td>
                                                    <a href="{{ route('data-pju.show', $pju->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> lihat Detail</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <!-- end row -->

                </div>
                
            </div>
            <!-- End Page-content -->
           
            <!-- footer -->
            @include('components.footer_admin')
            
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    @push('javascript-plugins')
        
    @endpush
@endsection