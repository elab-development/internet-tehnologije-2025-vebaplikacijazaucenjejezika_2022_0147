<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *   name="Courses",
 *   description="Public listing/show; admin-only create/update/delete; admin can fetch courses by teacher"
 * )
 */
class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::query()
            ->with(['language:id,name,img_url'])
            ->orderByDesc('is_active')
            ->orderBy('title')
            ->get();

        if ($courses->isEmpty()) {
            return response()->json('No courses found.', 404);
        }

        return response()->json([
            'courses' => CourseResource::collection($courses),
        ]);
    }

    public function teacherCourses($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $authUser = Auth::user();

        if ($authUser->role === 'admin') {

            $teacher = User::where('id', (int) $id)
                ->where('role', 'teacher')
                ->first();

            if (!$teacher) {
                return response()->json(['error' => 'Teacher not found'], 404);
            }
        } elseif ($authUser->role === 'teacher') {

            if ($authUser->id != (int) $id) {
                return response()->json([
                    'error' => 'You can only access your own courses'
                ], 403);
            }

            $teacher = $authUser;
        } else {
            return response()->json([
                'error' => 'Only teachers or admins can access this resource'
            ], 403);
        }

        $courses = Course::where('teacher_id', $teacher->id)
            ->with(['language:id,name,img_url'])
            ->orderByDesc('is_active')
            ->orderBy('title')
            ->get();

        return response()->json([
            'teacher' => [
                'id'    => $teacher->id,
                'name'  => $teacher->name,
                'email' => $teacher->email,
            ],
            'courses' => CourseResource::collection($courses),
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Only admins can create courses'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'language_id' => ['required', 'integer', 'exists:languages,id'],
            'level' => ['required', 'string', Rule::in(['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])],
            'teacher_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn($q) => $q->where('role', 'teacher')),
            ],
            'is_active'  => 'sometimes|boolean',
        ]);

        $course = Course::create($validated);

        return response()->json([
            'message' => 'Course created successfully',
            'course' => new CourseResource($course->load(['language:id,name,img_url'])),
        ]);
    }

    public function show(Course $course)
    {
        $course->load(['language:id,name,img_url']);

        return response()->json([
            'course' => new CourseResource($course),
        ]);
    }

    public function update(Request $request, Course $course)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Only admins can update courses'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'language_id' => ['sometimes', 'integer', 'exists:languages,id'],
            'level' => ['sometimes', 'string', Rule::in(['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])],
            'teacher_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn($q) => $q->where('role', 'teacher')),
            ],
            'is_active' => 'sometimes|boolean',
        ]);

        $course->update($validated);

        return response()->json([
            'message' => 'Course updated successfully',
            'course' => new CourseResource($course->fresh()->load(['language:id,name,img_url'])),
        ]);
    }

    public function destroy(Course $course)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Only admins can delete courses'], 403);
        }

        $course->delete();

        return response()->json(['message' => 'Course deleted successfully']);
    }
}
