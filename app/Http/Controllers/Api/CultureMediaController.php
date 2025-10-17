<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CultureMedia;
use Illuminate\Http\Request;

class CultureMediaController extends Controller
{
    /**
     * Tampilkan semua media (opsional: filter by culture_id)
     */
    public function index(Request $request)
    {
        if ($request->has('culture_id')) {
            $media = CultureMedia::where('culture_id', $request->culture_id)->get();
        } else {
            $media = CultureMedia::all();
        }

        return response()->json($media);
    }

    /**
     * Simpan media baru (foto/video/audio)
     */
    public function store(Request $request)
    {
        $request->validate([
            'culture_id' => 'required|exists:cultures,culture_id',
            'type' => 'required|in:photo,video,audio',
            'url' => 'required|string'
        ]);

        $media = CultureMedia::create([
            'culture_id' => $request->culture_id,
            'type' => $request->type,
            'url' => $request->url
        ]);

        return response()->json([
            'message' => 'Media berhasil ditambahkan',
            'data' => $media
        ]);
    }

    /**
     * Tampilkan detail satu media
     */
    public function show($id)
    {
        $media = CultureMedia::findOrFail($id);
        return response()->json($media);
    }

    /**
     * Update media (ganti url atau type)
     */
    public function update(Request $request, $id)
    {
        $media = CultureMedia::findOrFail($id);

        $request->validate([
            'type' => 'in:photo,video,audio',
            'url' => 'string'
        ]);

        $media->update($request->only(['type', 'url']));

        return response()->json([
            'message' => 'Media berhasil diperbarui',
            'data' => $media
        ]);
    }

    /**
     * Hapus media dari database
     */
    public function destroy($id)
    {
        $media = CultureMedia::findOrFail($id);
        $media->delete();

        return response()->json(['message' => 'Media berhasil dihapus']);
    }
}
