<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\FotoKwh;
use App\Models\Kecamatan;
use App\Models\Kwh;
use App\Models\Pju;
use Illuminate\Http\Request;

class KwhController extends Controller
{
    public function index()
    {
        $kwhs = Kwh::all();

        return view('kwh.index', compact('kwhs'));
    }

    public function getLocations()
    {
        // Ambil data KWH dan lampu dari database
        $kwhs = Kwh::with('pjus.parentPju')->get();

        // Return data dalam format JSON
        return response()->json([
            'kwhs' => $kwhs
        ]);
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
            'geojson_file' => 'required|file|mimes:json,geojson|max:7048',
        ]);
    
        // Dapatkan file yang diunggah
        $file = $request->file('geojson_file');
    
        // Buat nama file unik dengan menggunakan time()
        $filename = 'geojson_' . time() . '.' . $file->getClientOriginalExtension();
        $path = 'File-Geojson/' . $filename;
    
        // Pindahkan file ke direktori public/File-Geojson
        $file->move(public_path('File-Geojson'), $filename);
    
        // Baca dan parse file GeoJSON yang baru saja disimpan
        $geojsonContent = file_get_contents(public_path($path));
        $geojsonData = json_decode($geojsonContent, true);
    
        // Iterasi setiap fitur dalam GeoJSON dan simpan ke tabel kwhs
        foreach ($geojsonData['features'] as $feature) {
            $properties = $feature['properties'];
            $geometry = $feature['geometry'];

            $kecamatan = Kecamatan::where('nama_kecamatan', strtoupper($properties['Kecamatan']))->first();
            
            Kwh::create([
                'kecamatan_id' => $kecamatan ? $kecamatan->id : null,
                'nama_kwh' => $properties['Nama'] ?? null,
                'jenis_kwh' => $properties['Jenis KWH'] ?? null,
                'kategori_perangkat' => $properties['Kategori P'] ?? null,
                'geojson' => json_encode($geometry),
            ]);
        }

        return back()->with('success', 'File GeoJSON berhasil diunggah dan data telah disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kwh = Kwh::findOrFail($id);
        $pjuCount = Pju::where('kwh_id', $kwh->id)->get();

        return view('kwh.show', compact('kwh', 'pjuCount'));
    }

    public function showMap()
    {
        $kwhs = Kwh::all();
        $kecamatans = Kecamatan::all();

        return view('pju.map', compact('kwhs', 'kecamatans'));
    }

    public function updateConnection(Request $request)
    {
        $pju = Pju::find($request->pju_id);

        // dd($request->connection);

        if (strpos($request->connection, 'kwh-') !== false) {
            $kwhId = str_replace('kwh-', '', $request->connection);
            $pju->kwh_id = $kwhId;
            $pju->connected_to_pju = null;
            $pju->connected_to_kwh = true;
        } else if (strpos($request->connection, 'pju-') !== false) {
            $parentPjuId = str_replace('pju-', '', $request->connection);
            $pju->connected_to_pju = $parentPjuId;
            $pju->connected_to_kwh = false;
        }

        $pju->save();

        return response()->json(['success' => true]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kwh = Kwh::findOrFail($id);
        $kecamatans = Kecamatan::all();
        
        return view('kwh.edit', compact('kwh', 'kecamatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kwh' => 'required|string|max:255',
            'id_pelanggan' => 'nullable|string|max:255',
            'jenis_kwh' => 'nullable|string|max:255',
            'kategori_perangkat' => 'nullable|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        $pju = Kwh::findOrFail($id);
        
        $pju->update([
            'nama_kwh' => $request->nama_kwh,
            'id_pelanggan' => $request->id_pelanggan,
            'jenis_kwh' => $request->jenis_kwh,
            'kategori_perangkat' => $request->kategori_perangkat,
            'kecamatan_id' => $request->kecamatan_id,
        ]);

        return redirect()->route('data-kwh.show', $id)->with('success', 'Data KWH berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kwh = Kwh::findOrFail($id);
        $kwh->delete();

        return redirect()->route('data-kwh.index')->with('success', 'Data KWH berhasil dihapus.');
    }

    public function uploadPhotoDetail(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required',
            'photo.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $kwh = Kwh::findOrFail($id);
        
        // Proses setiap file yang diunggah
        foreach ($request->file('photo') as $file) {
            // Menentukan path dan nama unik untuk setiap file
            $path = 'foto-kwh/';
            $new_name = 'kwh-' . $id . '-' . date('Ymd') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        
            // Memindahkan file ke folder yang ditentukan
            $file->move(public_path($path), $new_name);
        
            // Menyimpan path ke database
            FotoKwh::create([
                'kwh_id' => $id,
                'path_foto' => $path . $new_name
            ]);
        }
    
        return redirect()->route('data-kwh.show', $kwh->id)->with('success', 'Foto berhasil diupload');
    }
}
