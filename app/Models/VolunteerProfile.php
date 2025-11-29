<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerProfile extends Model
{
    use HasFactory;

    protected $table = 'volunteer_profile'; // تعريف اسم الجدول

   protected $fillable = [
    'user_id',
    'city_id',
    'bio',
    'phone',
    'birth_date',
    'name', // ✨ تم إضافة الحقل الجديد هنا
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
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * الحصول على المدينة المرتبطة بهذا الملف (Many-to-One)
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * المهارات التي يمتلكها المتطوع (Many-to-Many)
     */
    public function skills()
    {
        // يربط بجدول volunteer_skills الوسيط
        return $this->belongsToMany(Skill::class, 'volunteer_skills', 'volunteer_id', 'skill_id');
    }

    /**
     * تقييمات المتطوعين للمشاريع التي عملوا بها (Many-to-Many)
     */
    public function projectRatings()
    {
        // هذا يمثل العلاقة الوسيطة volunteer_project_skills
        // هذا هو الجدول الذي يحمل عمود 'rating'
        return $this->belongsToMany(Project::class, 'volunteer_project_skills', 'volunteer_id', 'project_id')
                    ->withPivot('skill_id', 'rating', 'hours_spent', 'is_completed'); // إضافة أعمدة الجدول الوسيط
    }
}