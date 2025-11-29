<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectApplication extends Model
{
    use HasFactory;

    protected $table = 'project_applications'; // تعريف اسم الجدول

    protected $fillable = [
        'project_id',
        'volunteer_id',
        'status', // حالة الطلب: قيد المراجعة، مقبول، مرفوض
        'notes', // أي ملاحظات إضافية
    ];

    // ----------------------------------------------------------------------
    // العلاقات (Relationships)
    // ----------------------------------------------------------------------

    /**
     * الحصول على المشروع الذي تم تقديم الطلب إليه (Many-to-One)
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * الحصول على ملف المتطوع الذي قدم الطلب (Many-to-One)
     */
    public function volunteer()
    {
        // يربط الطلب بملف المتطوع عبر volunteer_id
        return $this->belongsTo(VolunteerProfile::class, 'volunteer_id');
    }
}