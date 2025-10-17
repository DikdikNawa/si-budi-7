<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Culture;
use App\Models\CultureMedia;
use Illuminate\Http\Request;

class CultureController extends Controller
{
    // Ambil semua data budaya yang disetujui
    public function index()
    {
        $cultures = Culture::with('media')->where('status', 'approved')->get();
        return response()->json($cultures);
    }

    // Simpan pengajuan budaya baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category' => 'required|in:Bahasa,Tradisi Lisan,Manuskrip,Adat Istiadat,Ritus,Pengetahuan Tradisional,Teknologi Tradisional,Seni,Permainan Rakyat,Olahraga Tradisional,Cagar Budaya',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'media' => 'array',
            'media.*.type' => 'in:photo,video,audio',
            'media.*.url' => 'required|string',
        ]);

        $culture = Culture::create([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'pending',
            'submitted_by' => $request->submitted_by,
        ]);

        if ($request->has('media')) {
            foreach ($request->media as $media) {
                CultureMedia::create([
                    'culture_id' => $culture->culture_id,
                    'type' => $media['type'],
                    'url' => $media['url']
                ]);
            }
        }

        return response()->json([
            'message' => 'Pengajuan budaya berhasil dikirim',
            'data' => $culture->load('media')
        ]);
    }

    // Admin: semua data
    public function all()
    {
        return response()->json(Culture::with('media')->get());
    }

    // Admin: ubah status
    public function updateStatus(Request $request, $id)
    {
        $culture = Culture::findOrFail($id);
        $culture->status = $request->status;
        $culture->curated_by = $request->curated_by;
        $culture->save();
        return response()->json(['message' => 'Status diperbarui', 'data' => $culture]);
    }

    // Hapus data
    public function destroy($id)
    {
        Culture::findOrFail($id)->delete();
        return response()->json(['message' => 'Data dihapus']);
    }
}
