<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    
    // تعريف الأعمدة التي يمكن إسناد قيم إليها بشكل جماعي (Mass Assignment)
    protected $fillable = [
        'name_ar',
        'name_en',
        'country_code',
    ];

    // تعطيل الطوابع الزمنية (created_at, updated_at) لأننا لم نضفها في الهجرة لهذا الجدول
    public $timestamps = false; 

    // ----------------------------------------------------------------------
    // علاقات المدن (Relationships)
    // ----------------------------------------------------------------------

    /**
     * الحصول على جميع ملفات الجمعيات المرتبطة بهذه المدينة (One-to-Many)
     */
    public function charities()
    {
        return $this->hasMany(CharityProfile::class, 'city_id');
    }

    /**
     * الحصول على جميع ملفات المتطوعين المرتبطة بهذه المدينة (One-to-Many)
     */
    public function volunteers()
    {
        return $this->hasMany(VolunteerProfile::class, 'city_id');
    }
}