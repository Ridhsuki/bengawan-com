<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $table = 'company_profiles';

    protected $fillable = [
        'name',
        'address',
        'about',
        'phone',
        'email',
        'maps_link',
        'social_media',
    ];
    protected $casts = [
        'social_media' => 'array',
    ];
    protected $attributes = [
        'social_media' => '[]',
    ];

    public function getWhatsappLinkAttribute(): string
    {
        $phone = $this->phone;

        // Jika dimulai dengan '0', ganti dengan '62'
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        return "https://wa.me/{$phone}";
    }
}
