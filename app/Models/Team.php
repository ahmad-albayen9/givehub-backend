<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'leader_user_id',
        'name',
        'max_members',
    ];
    
    // ----------------------------------------------------------------------
    // العلاقات (Relationships)
    // ----------------------------------------------------------------------

    /**
     * الحصول على المشروع الذي ينتمي إليه الفريق (Many-to-One)
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * الحصول على قائد الفريق (Many-to-One)
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_user_id');
    }

    /**
     * الحصول على جميع أعضاء الفريق (Many-to-Many عبر team_members)
     */
    public function members()
    {
        // يربط الفريق بالمتطوعين عبر جدول team_members الوسيط
        // هنا نستخدم نموذج VolunteerProfile لأن الأعضاء سيكونون متطوعين
        return $this->belongsToMany(VolunteerProfile::class, 'team_members', 'team_id', 'volunteer_id')
                    ->withPivot('joined_at'); // إضافة عمود الوقت الذي انضم فيه العضو
    }
}