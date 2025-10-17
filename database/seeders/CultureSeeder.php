<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Culture;
use App\Models\CultureMedia;

class CultureSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan kamu sudah jalankan: php artisan storage:link
        $cultures = [
            [
                'name' => 'Tari Jaipong',
                'description' => 'Tari tradisional dari Jawa Barat yang dinamis dan ekspresif.',
                'category' => 'Seni',
                'latitude' => '-6.9147440',
                'longitude' => '107.6098100',
                'status' => 'approved',
                'submitted_by' => 'Pengguna',
                'curated_by' => 'Admin Dikdik',
                'media' => [
                    // 🔹 Foto diambil dari storage publik
                    ['type' => 'photo', 'url' => 'storage/culturemedia/frierennn.jpeg'],
                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=example1']
                ]
            ],
            [
                'name' => 'Upacara Ngaben',
                'description' => 'Upacara pembakaran jenazah masyarakat Hindu Bali.',
                'category' => 'Ritus',
                'latitude' => '-8.4095180',
                'longitude' => '115.1889190',
                'status' => 'approved',
                'submitted_by' => 'Pengguna',
                'curated_by' => 'Admin Dikdik',
                'media' => [
                    ['type' => 'photo', 'url' => 'storage/culturemedia/ngaben.jpg'],
                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=example3'],
                    ['type' => 'audio', 'url' => 'storage/culturemedia/ngaben.mp3']
                ]
            ],
        ];

        foreach ($cultures as $data) {
            $media = $data['media'];
            unset($data['media']);

            $culture = Culture::create($data);

            foreach ($media as $m) {
                CultureMedia::create([
                    'culture_id' => $culture->culture_id,
                    'type' => $m['type'],
                    'url' => $m['url'], // Langsung simpan path yang bisa diakses publik
                ]);
            }
        }
    }
}
