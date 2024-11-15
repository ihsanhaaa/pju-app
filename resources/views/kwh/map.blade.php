@extends('layouts.app')

@section('title')
    Peta PJU
@endsection

@section('content')
    @push('css-plugins')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
        <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

        <style>
            .carousel-image {
                width: 100%;
                height: auto;
                max-width: 320px;
                max-height: 320px;
                object-fit: cover;
            }

            #map {
                height: 700px;
            }
        </style>
        
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
                                <h4 class="mb-sm-0">Peta PJU</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">PJU</a></li>
                                        <li class="breadcrumb-item active">Peta</li>
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
                                    <div>
                                        <div id="map" style="width: 100%; height: 500px;"></div>
                                    </div>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

        <script>
            $(document).ready(function() {
                var map = L.map('map').setView([-0.03299816477049434, 109.321822724986769], 13);
            
                // Definisi dua basemap
                var openStreetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                });
    
                var googleTraffic = L.tileLayer('https://{s}.google.com/vt/lyrs=m@221097413,traffic&x={x}&y={y}&z={z}', {
                    maxZoom: 22,
                    minZoom: 2,
                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                });
    
                var Esri_WorldImagery = L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
                });
            
                // Menambahkan salah satu basemap ke peta (OpenStreetMap sebagai default)
                googleTraffic.addTo(map);
            
                // Membuat objek untuk kontrol basemap
                var baseMaps = {
                    "Googlr Traffic": googleTraffic,
                    "OpenStreetMap": openStreetMap,
                    "ESRI": Esri_WorldImagery,
                };
            
                // Menambahkan kontrol untuk beralih basemap
                L.control.layers(baseMaps).addTo(map);
            
                var kwhIcon = L.icon({
                    iconUrl: 'flash.png',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
                });
            
                var lampuIcon = L.icon({
                    iconUrl: 'idea.png',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
                });
            
                // Membuat grup cluster untuk KWH dan PJU
                var kwhClusterGroup = L.markerClusterGroup();
                var pjuClusterGroup = L.markerClusterGroup();
            
                $.getJSON('/peta-lokasi', function(data) {
                    var kwhs = data.kwhs;
                    var pjUs = [];
            
                    kwhs.forEach(function(kwh) {
                        pjUs = pjUs.concat(kwh.pjus);
                    });
            
                    kwhs.forEach(function(kwh) {
                        var kwhGeoJson = JSON.parse(kwh.geojson);
                        var kwhMarker = L.geoJSON(kwhGeoJson, {
                            pointToLayer: function(feature, latlng) {
                                return L.marker(latlng, { icon: kwhIcon })
                                    .bindPopup('KWH Meter: ' + kwh.nama_kwh);
                            }
                        });
                        kwhClusterGroup.addLayer(kwhMarker); // Menambahkan marker KWH ke grup cluster
            
                        kwh.pjus.forEach(function(lampu) {
                            var lampuGeoJson = JSON.parse(lampu.geojson);
                            var lampuMarker = L.geoJSON(lampuGeoJson, {
                                pointToLayer: function(feature, latlng) {
                                    var popupContent = `
                                        <strong>Lampu Jalan: ${lampu.nama_pju}</strong><br>
                                        <strong>Tersambung pada: ${
                                            lampu.connected_to_kwh ? `KWH: ${kwhs.find(k => k.id === lampu.connected_to_kwh)?.nama_kwh || 'Tidak Ditemukan'}` :
                                            lampu.connected_to_pju ? `PJU: ${pjUs.find(p => p.id === lampu.connected_to_pju)?.nama_pju || 'Tidak Ditemukan'}` :
                                            'Tidak Terhubung'
                                        }</strong><br>
                                        <label for="connection">Sambungkan ke:</label>
                                        <select id="connection-${lampu.id}" class="connection-select" data-pju-id="${lampu.id}">
                                            <option value="">-- Pilih --</option>
                                            ${kwhs.map(k => {
                                                const isSelectedKWH = lampu.connected_to_kwh === k.id ? 'selected' : '';
                                                return `<option value="kwh-${k.id}" ${isSelectedKWH}>KWH: ${k.nama_kwh}</option>`;
                                            }).join('')}
                                            ${pjUs.filter(p => p.id !== lampu.id).map(p => {
                                                const isSelectedPJU = lampu.connected_to_pju === p.id ? 'selected' : '';
                                                return `<option value="pju-${p.id}" ${isSelectedPJU}>PJU: ${p.nama_pju}</option>`;
                                            }).join('')}
                                        </select>
                                        <button class="update-connection-btn" data-pju-id="${lampu.id}">Update</button>
                                    `;
                                    return L.marker(latlng, { icon: lampuIcon }).bindPopup(popupContent);
                                }
                            });
                            pjuClusterGroup.addLayer(lampuMarker); // Menambahkan marker PJU ke grup cluster
            
                            // Menggambar garis polyline di luar grup cluster
                            var latlngs = [];
                            var kwhCoordinates = kwhGeoJson.coordinates || kwhGeoJson.features?.[0]?.geometry?.coordinates;
                            var lampuCoordinates = lampuGeoJson.coordinates || lampuGeoJson.features?.[0]?.geometry?.coordinates;
            
                            if (lampu.connected_to_kwh && kwhCoordinates && lampuCoordinates) {
                                latlngs = [
                                    [kwhCoordinates[1], kwhCoordinates[0]],
                                    [lampuCoordinates[1], lampuCoordinates[0]]
                                ];
                                L.polyline(latlngs, { color: 'blue' }).addTo(map);
                            }
            
                            if (lampu.connected_to_pju && lampu.parent_pju) {
                                var parentLampuGeoJson = JSON.parse(lampu.parent_pju.geojson);
                                var parentCoordinates = parentLampuGeoJson.coordinates || parentLampuGeoJson.features?.[0]?.geometry?.coordinates;
            
                                if (parentCoordinates && lampuCoordinates) {
                                    latlngs = [
                                        [parentCoordinates[1], parentCoordinates[0]],
                                        [lampuCoordinates[1], lampuCoordinates[0]]
                                    ];
                                    L.polyline(latlngs, { color: 'red' }).addTo(map);
                                }
                            }
                        });
                    });
            
                    // Menambahkan grup cluster ke peta
                    map.addLayer(kwhClusterGroup);
                    map.addLayer(pjuClusterGroup);
                });
            
                $(document).on('click', '.update-connection-btn', function() {
                    var pjuId = $(this).data('pju-id');
                    var selectedValue = $(`#connection-${pjuId}`).val();
            
                    if (selectedValue) {
                        $.ajax({
                            url: '/update-connection',
                            type: 'POST',
                            data: {
                                pju_id: pjuId,
                                connection: selectedValue,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    alert('Koneksi berhasil diperbarui!');
                                    location.reload();
                                }
                            }
                        });
                    }
                });

                var layerBatasKecamatanData = @json($kecamatans);
                layerBatasKecamatanData.forEach(data => {
                    var geojsonUrl = data.geojson;

                    fetch(geojsonUrl)
                    .then(response => response.json())
                    .then(geojsonData => {
                        // Add GeoJSON layer with popup for each feature
                        L.geoJSON(geojsonData, {
                            style: {
                                color: 'gray',
                                weight: 1
                            },
                            onEachFeature: function (feature, layer) {
                                // Get Kecamatan name from properties
                                var kecamatanName = feature.properties.KECAMATAN;

                                // Bind a popup to each feature with the Kecamatan name
                                layer.bindPopup(`<strong>Nama Kecamatan:</strong> ${kecamatanName}`);
                            }
                        }).addTo(map);
                    })
                    .catch(error => {
                        console.error("Error fetching or processing GeoJSON data:", error);
                    });
                });
            });
        </script>
    @endpush
@endsection