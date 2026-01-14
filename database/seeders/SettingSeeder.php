<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'company_name' => 'Bengawan Computer',

                'address' => "Jl. Al Ikhlas, Mendungan, Pabelan,\nKec. Kartasura, Kab. Sukoharjo",

                'about_title' => 'SOLUSI TEKNOLOGI TANPA BATAS',
                'about_desc' => 'Bengawan Komputer adalah perusahaan IT yang memberikan solusi untuk pengadaan barang dan jasa. Kami melayani kebutuhan perusahaan, pengadaan institusi pemerintahan dan pembelian pribadi.',

                'phone' => '6285799599723',

                'google_maps_link' => 'https://maps.app.goo.gl/ybq7fnTQqtNZR4uW6',
                'social_media' => json_decode('[{"platform":"instagram","url":"https:\/\/www.instagram.com\/laptopsecondsolo\/?hl=de"},{"platform":"facebook","url":"https:\/\/www.facebook.com\/BENGAWANKOMPUTER"},{"platform":"tiktok","url":"https:\/\/www.tiktok.com\/@laptopsecondsoloraya"}]'),
                'banners' => null,
            ]
        );
    }
}
