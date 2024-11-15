<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\FotoPju;
use App\Models\Kecamatan;
use App\Models\Kwh;
use App\Models\Pju;
use Illuminate\Http\Request;

class PjuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pjus = Pju::all();

        return view('pju.index', compact('pjus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'geojson_file' => 'required|file|mimes:json,geojson',
        ]);

        $file = $request->file('geojson_file');

        // Simpan file GeoJSON ke dalam folder `public/File-Geojson`
        $filename = 'geojson_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/File-Geojson', $filename);

        // Membaca isi file GeoJSON
        $geojsonContent = file_get_contents($file);
        $geojsonData = json_decode($geojsonContent, true);

        if (isset($geojsonData['features'])) {
            foreach ($geojsonData['features'] as $feature) {

                $panelKwhName = $feature['properties']['Panel/KWH'] ?? null;

                // Cek apakah panel/KWH cocok dengan data di tabel kwhs
                $kwh = $panelKwhName ? Kwh::where('nama_kwh', $panelKwhName)->first() : null;

                // dd($kwh);

                $kecamatan = Kecamatan::where('nama_kecamatan', strtoupper($feature['properties']['Kecamatan']))->first();

                Pju::create([
                    'kecamatan_id' => $kecamatan ? $kecamatan->id : null,
                    'kwh_id' => $kwh ? $kwh->id : null,
                    'nama_pju' => $feature['properties']['Nama'] ?? 'Tanpa Nama',

                    'zona' => $feature['properties']['Zona > Nam'] ?? null,
                    'kelompok' => $feature['properties']['Kelompok P'] ?? null,
                    'kategori' => $feature['properties']['Kategori P'] ?? null,
                    'nomor_seri' => $feature['properties']['Nomor Seri'] ?? null,
                    
                    'geojson' => json_encode($feature['geometry']),
                    'connected_to_kwh' => $kwh ? true : false,
                ]);
            }
        }

        return back()->with('success', 'File GeoJSON berhasil diunggah dan data telah disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pju = Pju::findOrFail($id);

        return view('pju.show', compact('pju'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pju = Pju::findOrFail($id);
        $kecamatans = Kecamatan::all();
        
        return view('pju.edit', compact('pju', 'kecamatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pju' => 'required|string|max:255',
            'zona' => 'nullable|string|max:255',
            'kelompok' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        $pju = Pju::findOrFail($id);

        $pju->update([
            'nama_pju' => $request->nama_pju,
            'zona' => $request->zona,
            'kelompok' => $request->kelompok,
            'kategori' => $request->kategori,
            'kecamatan_id' => $request->kecamatan_id,
        ]);

        return redirect()->route('data-pju.show', $id)->with('success', 'Data CCTV berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pju = Pju::findOrFail($id);
        $pju->delete();

        return redirect()->route('data-pju.index')->with('success', 'Data PJU berhasil dihapus.');
    }

    public function uploadPhotoDetail(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required',
            'photo.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $pju = Pju::findOrFail($id);
        
        // Proses setiap file yang diunggah
        foreach ($request->file('photo') as $file) {
            // Menentukan path dan nama unik untuk setiap file
            $path = 'foto-pju/';
            $new_name = 'pju-' . $id . '-' . date('Ymd') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        
            // Memindahkan file ke folder yang ditentukan
            $file->move(public_path($path), $new_name);
        
            // Menyimpan path ke database
            FotoPju::create([
                'pju_id' => $id,
                'path_foto' => $path . $new_name
            ]);
        }
    
        return redirect()->route('data-pju.show', $pju->id)->with('success', 'Foto berhasil diupload');
    }
}
