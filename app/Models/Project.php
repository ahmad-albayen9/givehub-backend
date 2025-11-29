<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'charity_id',
        'leader_user_id',
        'city_id',
        'team_id',
        'title',
        'description',
        'max_volunteers',
        'start_date',
        'end_date',
        'status', 
    ];
    
    // ----------------------------------------------------------------------
    // العلاقات (Relationships)
    // ----------------------------------------------------------------------

    /**
     * الحصول على الجمعية المالكة للمشروع (Many-to-One)
     */
    public function charity()
    {
        return $this->belongsTo(\App\Models\CharityProfile::class, 'charity_id', 'user_id');
    }

    /**
     * الحصول على القائد أو المسؤول عن المشروع (Many-to-One)
     */
    public function leader()
    {
        return $this->belongsTo(\App\Models\User::class, 'leader_user_id');
    }

    /**
     * الحصول على الفرق المرتبطة بالمشروع (One-to-Many)
     */
    public function teams()
    {
        return $this->hasMany(\App\Models\Team::class, 'project_id');
    }

    /**
     * الحصول على طلبات الانضمام للمشروع (One-to-Many)
     */
    public function applications()
    {
        return $this->hasMany(\App\Models\ProjectApplication::class, 'project_id');
    }

    /**
     * المهارات المطلوبة لهذا المشروع (Many-to-Many عبر project_skills)
     */
    public function requiredSkills()
    {
        // يربط مع جدول project_skills الوسيط
        return $this->belongsToMany(\App\Models\Skill::class, 'project_skills', 'project_id', 'skill_id');
    }

    /**
     * المتطوعون الذين قيموا هذا المشروع (Many-to-Many عبر volunteer_project_skills)
     */
    public function ratings()
    {
        // يربط مع جدول volunteer_project_skills الوسيط الذي يحمل عمود 'rating'
        return $this->belongsToMany(\App\Models\VolunteerProfile::class, 'volunteer_project_skills', 'project_id', 'volunteer_id')
                     ->withPivot('skill_id', 'rating', 'hours_spent', 'is_completed');
    }
    
    /**
     * الحصول على المدينة التي يتم فيها المشروع (Many-to-One)
     */
    public function city()
    {
        return $this->belongsTo(\App\Models\City::class, 'city_id');
    }
    
    // ----------------------------------------------------------------------
    // ✨ العلاقة الجديدة: المتطوعون المسجلون في المشروع
    // ----------------------------------------------------------------------

    /**
     * المتطوعون المسجلون في المشروع (Many-to-Many عبر project_volunteers)
     */
    public function volunteers()
    {
        // ربط المشروع بالمتطوعين (Users) عبر الجدول الوسيط project_volunteers
        return $this->belongsToMany(\App\Models\User::class, 'project_volunteers', 'project_id', 'user_id')
                    ->withPivot('status') // ندرج حقل status لكي يظهر في الاستجابة
                    ->withTimestamps();
    }
}