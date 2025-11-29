<?php

namespace App\Models;

// استخدام النماذج الأخرى التي سيرتبط بها
use App\Models\CharityProfile; 
use App\Models\VolunteerProfile; 

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * يحتوي على الأعمدة التي يمكن إسناد قيم إليها بشكل جماعي (Mass Assignment)
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type', // تمت الإضافة بناءً على الهجرة
        'is_active', // تمت الإضافة بناءً على الهجرة
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean', // لضمان التعامل معها كقيمة منطقية
    ];

    // ----------------------------------------------------------------------
    // علاقات الملفات الشخصية (One-to-One Relationships)
    // ----------------------------------------------------------------------

    /**
     * الحصول على ملف الجمعية المرتبط بهذا المستخدم.
     */
    public function charityProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        // يربط مفتاح 'id' في جدول users مع 'user_id' في charities_profile
        return $this->hasOne(CharityProfile::class, 'user_id');
    }

    /**
     * الحصول على ملف المتطوع المرتبط بهذا المستخدم.
     */
    public function volunteerProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        // يربط مفتاح 'id' في جدول users مع 'user_id' في volunteer_profile
        return $this->hasOne(VolunteerProfile::class, 'user_id');
    }
    
    // ----------------------------------------------------------------------
    // ✨ العلاقات الجديدة (Many-to-Many Relationships)
    // ----------------------------------------------------------------------

    /**
     * المشاريع التي تطوع فيها المستخدم (علاقة Many-to-Many).
     * يستخدم الجدول الوسيط الجديد: project_volunteers
     */
    public function projects()
    {
        // ربط المستخدم بالمشاريع عبر الجدول الوسيط project_volunteers
        return $this->belongsToMany(\App\Models\Project::class, 'project_volunteers', 'user_id', 'project_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    /**
     * المهارات التي يمتلكها المستخدم (علاقة Many-to-Many).
     * يستخدم الجدول الوسيط الجديد: user_skills
     */
    public function userSkills()
    {
        // ربط المستخدم بالمهارات (Skills) عبر الجدول الوسيط user_skills
        return $this->belongsToMany(\App\Models\Skill::class, 'user_skills', 'user_id', 'skill_id')
                    ->withTimestamps();
    }
}