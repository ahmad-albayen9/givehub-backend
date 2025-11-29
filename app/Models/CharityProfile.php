<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharityProfile extends Model
{
    use HasFactory;

    protected $table = 'charities_profile'; // تعريف اسم الجدول

    // ✨ تحديد المفتاح الأساسي لأنه ليس 'id' الافتراضي
    protected $primaryKey = 'user_id'; 
    
    // ✨ تعطيل الزيادة التلقائية لأن المفتاح الأساسي هو المفتاح الأجنبي user_id
    public $incrementing = false; 

    protected $fillable = [
        'user_id',
        'city_id',
        'description',
        'registration_number',
        'website_url',
        'phone',
        'name',
        'name_ar',
    ];

    // تعطيل الطوابع الزمنية (created_at, updated_at) لهذا الجدول
    public $timestamps = false;

    // ----------------------------------------------------------------------
    // العلاقات (Relationships)
    // ----------------------------------------------------------------------

    /**
     * الحصول على المستخدم الأساسي (One-to-One معكوس)
     */
    public function user()
    {
        // يربط مفتاح 'user_id' بالمفتاح الأساسي 'id' في جدول Users
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * الحصول على المدينة المرتبطة بهذا الملف (Many-to-One)
     */
    public function city()
    {
        return $this->belongsTo(\App\Models\City::class, 'city_id');
    }

    /**
     * الحصول على جميع المشاريع التي أنشأتها هذه الجمعية (One-to-Many)
     */
    public function projects()
    {
        // يربط الجمعية بجميع مشاريعها عبر charity_id في جدول projects
        return $this->hasMany(\App\Models\Project::class, 'charity_id');
    }
}