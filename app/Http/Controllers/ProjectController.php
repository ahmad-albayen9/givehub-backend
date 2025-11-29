<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource (Open Projects).
     * هذه الدالة تعرض جميع المشاريع المفتوحة للمتطوعين (لا تتطلب مصادقة).
     */
    public function index(Request $request)
    {
        // 1. الحصول على المشاريع ذات الحالة 'open' والتحميل المسبق للعلاقات
        $projects = Project::where('status', 'open')
            ->with(['charity.user', 'city', 'leader']) 
            ->paginate(10); 

        // 2. إرجاع الاستجابة
        return response()->json([
            'message' => 'List of open projects retrieved successfully.',
            'projects' => $projects,
        ], 200);
    }
    
    /**
     * Store a newly created resource in storage.
     * هذه الدالة خاصة بالجمعيات لإنشاء المشاريع (تتطلب مصادقة).
     */
    public function store(Request $request)
    {
        // 1. التحقق من صلاحية المستخدم (هل هو جمعية خيرية؟)
        $user = Auth::user();
        
        // يجب أن يكون المستخدم الحالي مرتبطًا بـ CharityProfile
        if (!$user->charityProfile) {
            return response()->json([
                'message' => 'Unauthorized. Only charities can create projects.'
            ], 403);
        }

        // 2. التحقق من صحة البيانات (Validation)
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'required_volunteers' => 'nullable|integer|min:1',
            'city_id' => 'required|exists:cities,id',
            'leader_user_id' => [
                'nullable', 
                'exists:users,id',
                // التحقق من أن القائد المرشح ينتمي للجمعية (أو يمكن أن يكون هو نفسه)
                Rule::exists('charities_profile', 'user_id')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ],
            'team_id' => 'nullable|exists:teams,id',
        ]);
        
        // 3. إنشاء المشروع
        $project = Project::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'start_date' => $validatedData['start_date'],
            'required_volunteers' => $validatedData['required_volunteers'] ?? null,
            'city_id' => $validatedData['city_id'],
            // إذا لم يتم تحديد قائد، يكون المستخدم المنشئ هو القائد
            'leader_user_id' => $validatedData['leader_user_id'] ?? $user->id, 
            'charity_id' => $user->charityProfile->user_id, // ربط المشروع بالجمعية المنشئة
            'team_id' => $validatedData['team_id'] ?? null,
            'status' => 'open',
        ]);

        // 4. إرجاع استجابة النجاح
        return response()->json([
            'message' => 'Project created successfully.',
            'project' => $project,
        ], 201);
    }
    
    /**
     * Display the specified resource.
     * هذه الدالة تعرض تفاصيل مشروع واحد (لا تتطلب مصادقة).
     */
    public function show(Project $project)
    {
        // تحميل جميع العلاقات الضرورية بشكل مسبق
        $project->load(['charity.user', 'city', 'leader', 'requiredSkills']);

        return response()->json([
            'message' => 'Project details retrieved successfully.',
            'project' => $project,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * هذه الدالة خاصة بالجمعية المالكة لتعديل المشروع (تتطلب مصادقة).
     */
    public function update(Request $request, Project $project)
    {
        // 1. التحقق من الصلاحيات (Authorization)
        $user = Auth::user();

        // الشرط الأول: يجب أن يكون المستخدم مسجلاً كجمعية خيرية
        if (!$user->charityProfile) {
            return response()->json([
                'message' => 'Unauthorized. Only charities can update projects.'
            ], 403);
        }
        
        // الشرط الثاني: يجب أن تكون الجمعية هي المالكة للمشروع
        if ($project->charity_id !== $user->charityProfile->user_id) {
            return response()->json([
                'message' => 'Forbidden. You do not own this project.'
            ], 403);
        }

        // 2. التحقق من صحة البيانات (Validation)
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255', // 'sometimes' يسمح بالتعديل الجزئي
            'description' => 'sometimes|required|string',
            'start_date' => 'sometimes|required|date|after_or_equal:today',
            'required_volunteers' => 'sometimes|nullable|integer|min:1',
            'city_id' => 'sometimes|required|exists:cities,id',
            // يمكن تعديل الحالة فقط إلى القيم المسموحة
            'status' => ['sometimes', 'required', Rule::in(['open', 'in_progress', 'completed', 'cancelled'])],
        ]);
        
        // 3. تحديث المشروع
        $project->update($validatedData);

        // 4. إرجاع استجابة النجاح
        return response()->json([
            'message' => 'Project updated successfully.',
            'project' => $project,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * هذه الدالة ستكون الخطوة التالية.
     */
    public function destroy(Project $project)
    {
       // 1. التحقق من الصلاحيات (Authorization)
    $user = Auth::user();

    // الشرط الأول: يجب أن يكون المستخدم مسجلاً كجمعية خيرية
    if (!$user->charityProfile) {
        return response()->json([
            'message' => 'Unauthorized. Only charities can delete projects.'
        ], 403);
    }
    
    // الشرط الثاني: يجب أن تكون الجمعية هي المالكة للمشروع
    if ($project->charity_id !== $user->charityProfile->user_id) {
        return response()->json([
            'message' => 'Forbidden. You do not own this project.'
        ], 403);
    }

    // 2. الحذف
    $project->delete();

    // 3. إرجاع استجابة النجاح
    return response()->json([
        'message' => 'Project deleted successfully.'
    ], 200);
       
    }
    /**
 * Syncs the required skills for a specific project.
 * هذه الدالة تسمح للجمعية المالكة بربط المهارات المطلوبة (تتطلب مصادقة).
 */
public function syncSkills(Request $request, Project $project)
{
    // 1. التحقق من الصلاحيات (Authorization)
    $user = Auth::user();

    // الشرط الأول: يجب أن يكون المستخدم مسجلاً كجمعية خيرية
    if (!$user->charityProfile) {
        return response()->json([
            'message' => 'Unauthorized. Only charities can manage project skills.'
        ], 403);
    }
    
    // الشرط الثاني: يجب أن تكون الجمعية هي المالكة للمشروع
    if ($project->charity_id !== $user->charityProfile->user_id) {
        return response()->json([
            'message' => 'Forbidden. You do not own this project.'
        ], 403);
    }

    // 2. التحقق من صحة البيانات (Validation)
    // نتوقع مصفوفة من معرفات المهارات (skill_id)
    $validatedData = $request->validate([
        // يجب أن يكون 'skill_ids' مصفوفة، ويجب أن تكون جميع المعرفات موجودة في جدول 'skills'
        'skill_ids' => 'required|array',
        'skill_ids.*' => 'exists:skills_lookup,id', 
    ]);

    // 3. ربط المهارات (Syncing)
    $project->requiredSkills()->sync($validatedData['skill_ids']);

    // 4. إرجاع استجابة النجاح وتحميل المهارات الجديدة
    $project->load('requiredSkills');
    
    return response()->json([
        'message' => 'Project required skills updated successfully.',
        'project' => $project,
    ], 200);
}
public function apply(Request $request, $projectId)
{
    // 1. التحقق من أن المستخدم الحالي متطوع (اختياري، لكن يُفضل لضمان السلامة)
    if (Auth::user()->user_type !== 'volunteer') {
        return response()->json([
            'message' => 'Unauthorized. Only volunteers can apply to projects.'
        ], 403);
    }

    // 2. البحث عن المشروع
    $project = \App\Models\Project::findOrFail($projectId);

    // 3. ربط المتطوع بالمشروع باستخدام دالة العلاقة volunteers()
    try {
        // نستخدم attach لربط المتطوع، مع تعيين الحالة الافتراضية 'pending'
        $project->volunteers()->attach(Auth::id(), ['status' => 'pending']);

        return response()->json([
            'message' => 'Volunteer application submitted successfully.',
        ], 201);
    } catch (\Illuminate\Database\QueryException $e) {
        // إذا كان المتطوع مسجلًا مسبقًا، ستفشل العملية بسبب قيد UNIQUE
        if ($e->getCode() === '23000') {
            return response()->json([
                'message' => 'Volunteer is already registered or applied to this project.'
            ], 409); // 409 Conflict
        }
        // لأي خطأ آخر، نعيد الخطأ 500
        return response()->json(['message' => 'An error occurred.'], 500);
    }
}
}