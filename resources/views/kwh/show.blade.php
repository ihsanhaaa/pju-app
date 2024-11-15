@extends('layouts.app')

@section('title')
    Detail KWH
@endsection

@section('content')
    @push('css-plugins')
    @endpush

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- header -->
        @include('components.navbar_admin')

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Detail KWH</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">KWH</a></li>
                                        <li class="breadcrumb-item active">Detail KWH</li>
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
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body">

                                    <form action="{{ route('data-kwh.uploadFotos', $kwh->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="photo" class="form-label">Tambah Foto</label>
                                            <input class="form-control" type="file" name="photo[]" id="photo" accept="image/*" multiple required>
                                        </div>
                                        <button type="submit" class="btn btn-primary mb-2 btn-sm">Upload Foto</button>
                                    </form>
                                    
                                    <div class="zoom-gallery">

                                        @if($kwh->fotoKwhs && $kwh->fotoKwhs->isNotEmpty())
                                            @foreach($kwh->fotoKwhs as $foto)
                                                <a class="float-start my-2" href="{{ asset($foto->path_foto) }}" title="{{ $kwh->nama_kwh }}"><img src="{{ asset($foto->path_foto) }}" alt="img-3" width="350"></a>
                                            @endforeach
                                        @else
                                            <p>Tidak ada foto tersedia.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-9">
                            <div class="card">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="invoice-title">
                                                
                                                <h3>
                                                    <i class="fas fa-battery-three-quarters"></i> 
                                                </h3>
                                            </div>
                                            <hr>
                                            
                                            <div class="row">
                                                <div class="d-flex mb-3">
                                                    <a href="{{ route('data-kwh.edit', $kwh->id) }}" class="btn btn-warning waves-effect waves-light"><i class="fas fa-edit"></i> Edit</a>
    
                                                    <form id="input"
                                                        action="{{ route('data-kwh.destroy', $kwh->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah anda yakin ingin menghapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" style="border: none;" class="btn btn-danger waves-effect waves-light ms-2"><i class="fas fa-trash-alt"></i> Hapus</button>
                                                    </form>
                                                </div>
                                                <div class="row-12">
                                                    <h4 class="font-size-18"><strong>{{ $kwh->nama_kwh }}</strong></h4>
                                                    <strong>ID Pelanggan: {{ $kwh->id_pelanggan ?? '-' }}</strong><br>
                                                    <strong>Jenis KWH: {{ $kwh->jenis_kwh ?? '-' }}</strong><br>
                                                    <strong>Kecamatan: {{ $kwh->kecamatan->nama_kecamatan ?? '-' }}</strong><br>
                                                </div>
                                            </div>
                                        </div>

                                        <h4 class="font-size-18 mt-4"><strong>PJU yang Terhubung ({{ $pjuCount->count() }})</strong></h4>

                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama PJU</th>
                                                <th>Zona</th>
                                                <th>Kelompok</th>
                                            </tr>
                                            </thead>
        
    
                                            <tbody>
                                            @foreach ($kwh->pjus as $key => $pju)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>
                                                        <a href="{{ route('data-pju.show', $pju->id) }}">{{ $pju->nama_pju }}</a>
                                                    </td>
                                                    <td>{{ $pju->zona }}</td>
                                                    <td>{{ $pju->kelompok }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                    </div>

                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

        </div>
        <!-- end main content-->

        <!-- footer -->
        @include('components.footer_admin')

    </div>
    <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    @push('javascript-plugins')
    @endpush
@endsection
