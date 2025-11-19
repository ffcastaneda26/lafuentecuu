<?php

namespace Database\Seeders;

use App\Models\ContactInfo;
use Illuminate\Database\Seeder;

class ContactInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContactInfo::create([
            'name' => 'La Fuente',
            'phone' => '614 1234 5678',
            'email' => 'contacto@lafuente.com',
            'address' => 'Dirección Calle número-colonia-ciudad-pais y código postal',
            'social_facebook' => 'url_facebook',
            'social_instagram' => 'url_instagram',
            'social_tiktok' => 'url_tiktok',
            'social_twitter' => 'url_twitter',
            'about_text' => 'Acerca de nosotros todo un rollo por favor',
            'logo' => '',
        ]);

    }
}
