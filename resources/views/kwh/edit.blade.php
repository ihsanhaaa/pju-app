@extends('layouts.app')

@section('title')
    Edit Data KWH
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
                                <h4 class="mb-sm-0">Edit Data KWH</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('data-kwh.index') }}">KWH</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Detail KWH</a></li>
                                        <li class="breadcrumb-item active">Edit KWH</li>
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

                            <div class="card">
                                <div class="card-body">
        
                                    <h4 class="card-title">Edit Data KWH {{ $kwh->nama_kwh }}</h4>

                                    <form action="{{ route('data-kwh.update', $kwh->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="row mb-3">
                                            <label for="nama_kwh" class="col-sm-2 col-form-label">Nama KWH</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('nama_kwh') is-invalid @enderror" type="text" id="nama_kwh" name="nama_kwh" value="{{ old('nama_kwh', $kwh->nama_kwh ?? '') }}" required>
                                                @error('nama_kwh')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="id_pelanggan" class="col-sm-2 col-form-label">ID Pelanggan</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('id_pelanggan') is-invalid @enderror" type="text" id="id_pelanggan" name="id_pelanggan" value="{{ old('id_pelanggan', $kwh->id_pelanggan ?? '') }}" required>
                                                @error('id_pelanggan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="jenis_kwh" class="col-sm-2 col-form-label">Jenis KWH</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('jenis_kwh') is-invalid @enderror" type="text" id="jenis_kwh" name="jenis_kwh" value="{{ old('jenis_kwh', $kwh->jenis_kwh ?? '') }}" required>
                                                @error('jenis_kwh')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="kategori_perangkat" class="col-sm-2 col-form-label">Kategori Perangkat</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('kategori_perangkat') is-invalid @enderror" type="text" id="kategori_perangkat" name="kategori_perangkat" value="{{ old('kategori_perangkat', $kwh->kategori_perangkat ?? '') }}" required>
                                                @error('kategori_perangkat')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="kecamatan_id" class="col-sm-2 col-form-label">Kecamatan</label>
                                            <div class="col-sm-10">
                                                <select class="form-control @error('kecamatan_id') is-invalid @enderror" id="kecamatan_id" name="kecamatan_id" required>
                                                    <option value="">-- Pilih Kecamatan --</option>
                                                    @foreach($kecamatans as $kecamatan)
                                                        <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', $kwh->kecamatan_id ?? '') == $kecamatan->id ? 'selected' : '' }}>
                                                            {{ $kecamatan->nama_kecamatan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('kecamatan_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Update Data KWH</button>
                                    </form>


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