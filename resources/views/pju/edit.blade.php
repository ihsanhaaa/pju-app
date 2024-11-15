@extends('layouts.app')

@section('title')
    Edit Data PJU
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
                                <h4 class="mb-sm-0">Edit Data PJU</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('data-pju.index') }}">PJU</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Detail PJU</a></li>
                                        <li class="breadcrumb-item active">Edit PJU</li>
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
        
                                    <h4 class="card-title">Edit Data PJU {{ $pju->nama_pju }}</h4>

                                    <form action="{{ route('data-pju.update', $pju->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="row mb-3">
                                            <label for="nama_pju" class="col-sm-2 col-form-label">Nama PJU</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('nama_pju') is-invalid @enderror" type="text" id="nama_pju" name="nama_pju" value="{{ old('nama_pju', $pju->nama_pju ?? '') }}" required>
                                                @error('nama_pju')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="zona" class="col-sm-2 col-form-label">Jenis PJU</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('zona') is-invalid @enderror" type="text" id="zona" name="zona" value="{{ old('zona', $pju->zona ?? '') }}" required>
                                                @error('zona')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="kelompok" class="col-sm-2 col-form-label">Panel PJU</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('kelompok') is-invalid @enderror" type="text" id="kelompok" name="kelompok" value="{{ old('kelompok', $pju->kelompok ?? '') }}" required>
                                                @error('kelompok')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="kategori" class="col-sm-2 col-form-label">Tahun Pemasangan</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('kategori') is-invalid @enderror" type="text" id="kategori" name="kategori" value="{{ old('kategori', $pju->kategori ?? '') }}" required>
                                                @error('kategori')
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
                                                        <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', $pju->kecamatan_id ?? '') == $kecamatan->id ? 'selected' : '' }}>
                                                            {{ $kecamatan->nama_kecamatan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('kecamatan_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Update Data PJU</button>
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