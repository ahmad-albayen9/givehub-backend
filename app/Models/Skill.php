<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    // بما أن اسم الجدول هو skills_lookup، يجب تعريفه صراحة
    protected $table = 'skills_lookup';

    // تعريف الأعمدة التي يمكن إسناد قيم إليها بشكل جماعي
    protected $fillable = [
        'name_ar',
        'name_en',
    ];

    // تعطيل الطوابع الزمنية (created_at, updated_at)
    public $timestamps = false; 

    // ----------------------------------------------------------------------
    // علاقات المهارات (Relationships)
    // ----------------------------------------------------------------------
    
    /**
     * المهارات المشتركة بين المتطوعين (Many-to-Many)
     */
    public function volunteers()
    {
        // يربط مع جدول volunteer_skills الوسيط
        return $this->belongsToMany(VolunteerProfile::class, 'volunteer_skills', 'skill_id', 'volunteer_id');
    }

    /**
     * المهارات المطلوبة للمشاريع (Many-to-Many)
     */
    public function projects()
    {
        // يربط مع جدول project_skills الوسيط
        return $this->belongsToMany(Project::class, 'project_skills', 'skill_id', 'project_id');
    }
}