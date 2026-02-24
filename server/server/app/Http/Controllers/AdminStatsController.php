<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Language;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *   name="Admin",
 *   description="Admin-only endpoints for analytics and dashboard stats"
 * )
 */
class AdminStatsController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/admin/stats",
     *   tags={"Admin"},
     *   summary="Get dashboard statistics (admin only)",
     *   security={{"sanctum":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/AdminStatsPayload")
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Unauthorized"))
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Forbidden",
     *     @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Only admins can access this resource"))
     *   )
     * )
     */
    public function stats(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Only admins can access this resource'], 403);
        }

        // --- KPI counts
        $usersTotal = User::count();
        $usersByRole = User::select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->orderBy('role')
            ->get()
            ->map(fn($r) => ['role' => $r->role, 'count' => (int)$r->count])
            ->values();

        $languagesCount = Language::count();
        $coursesCount = Course::count();
        $lessonsCount = Lesson::count();
        $enrollmentsCount = Enrollment::count();

        // --- Courses grouped
        $coursesByLanguage = Course::query()
            ->join('languages', 'languages.id', '=', 'courses.language_id')
            ->select('languages.name as label', DB::raw('COUNT(courses.id) as value'))
            ->groupBy('languages.name')
            ->orderByDesc('value')
            ->get();

        $coursesByLevel = Course::query()
            ->select('level as label', DB::raw('COUNT(id) as value'))
            ->groupBy('level')
            ->orderBy('level')
            ->get();

        // --- Enrollments grouped
        $enrollmentsByStatus = Enrollment::query()
            ->select('status as label', DB::raw('COUNT(id) as value'))
            ->groupBy('status')
            ->orderByDesc('value')
            ->get();

        // --- Top teachers by active courses
        $topTeachersByCourses = Course::query()
            ->join('users', 'users.id', '=', 'courses.teacher_id')
            ->select('users.id', 'users.name as label', DB::raw('COUNT(courses.id) as value'))
            ->where('users.role', 'teacher')
            ->where('courses.is_active', true)
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('value')
            ->limit(5)
            ->get();

        // --- Top courses by enrollments
        $topCoursesByEnrollments = Enrollment::query()
            ->join('courses', 'courses.id', '=', 'enrollments.course_id')
            ->select('courses.id', 'courses.title as label', DB::raw('COUNT(enrollments.id) as value'))
            ->groupBy('courses.id', 'courses.title')
            ->orderByDesc('value')
            ->limit(5)
            ->get();

        // --- Lessons per month (last 6 months)
        // Works well for charts. Uses starts_at (datetime).
        $lessonsPerMonth = Lesson::query()
            ->whereNotNull('starts_at')
            ->select(
                DB::raw("DATE_FORMAT(starts_at, '%Y-%m') as label"),
                DB::raw('COUNT(id) as value')
            )
            ->groupBy(DB::raw("DATE_FORMAT(starts_at, '%Y-%m')"))
            ->orderBy('label')
            ->limit(6)
            ->get();

        return response()->json([
            'kpis' => [
                'users_total' => $usersTotal,
                'languages' => $languagesCount,
                'courses' => $coursesCount,
                'lessons' => $lessonsCount,
                'enrollments' => $enrollmentsCount,
            ],
            'users_by_role' => $usersByRole,
            'courses_by_language' => $coursesByLanguage,
            'courses_by_level' => $coursesByLevel,
            'enrollments_by_status' => $enrollmentsByStatus,
            'top_teachers_by_active_courses' => $topTeachersByCourses,
            'top_courses_by_enrollments' => $topCoursesByEnrollments,
            'lessons_per_month' => $lessonsPerMonth,
        ]);
    }
}
