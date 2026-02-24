<?php

namespace App\Http\Controllers;

/**
 * @OA\OpenApi(openapi="3.0.0")
 *
 * @OA\Info(
 *   title="Duolingo API",
 *   version="1.0.0",
 *   description="API documentation for Duolingo application."
 * )
 *
 * @OA\Server(
 *   url="http://localhost:8000",
 *   description="Local development server"
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="sanctum",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="Token",
 *   description="Use 'Bearer {token}' where token is a Laravel Sanctum personal access token."
 * )
 *
 * ------------------------------------------------------------
 * Reusable Schemas (components)
 * ------------------------------------------------------------
 *
 * @OA\Schema(
 *   schema="Language",
 *   type="object",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="name", type="string", example="German"),
 *   @OA\Property(property="img_url", type="string", nullable=true, example="https://.../german.png")
 * )
 *
 * @OA\Schema(
 *   schema="Course",
 *   type="object",
 *   @OA\Property(property="id", type="integer", example=12),
 *   @OA\Property(property="title", type="string", example="German B1 – Conversation"),
 *   @OA\Property(property="language_id", type="integer", example=1),
 *   @OA\Property(property="level", type="string", example="B1"),
 *   @OA\Property(property="teacher_id", type="integer", nullable=true, example=7),
 *   @OA\Property(property="is_active", type="boolean", example=true),
 *   @OA\Property(property="language", ref="#/components/schemas/Language")
 * )
 *
 * @OA\Schema(
 *   schema="Lesson",
 *   type="object",
 *   @OA\Property(property="id", type="integer", example=101),
 *   @OA\Property(property="course_id", type="integer", example=12),
 *   @OA\Property(property="teacher_id", type="integer", example=7),
 *   @OA\Property(property="title", type="string", example="Unit 3: Past Tense"),
 *   @OA\Property(property="starts_at", type="string", format="date-time", example="2025-09-01T10:00:00Z"),
 *   @OA\Property(property="ends_at", type="string", format="date-time", nullable=true, example="2025-09-01T11:30:00Z")
 * )
 *
 * @OA\Schema(
 *   schema="Enrollment",
 *   type="object",
 *   @OA\Property(property="id", type="integer", example=201),
 *   @OA\Property(property="course_id", type="integer", example=12),
 *   @OA\Property(property="student_id", type="integer", example=33),
 *   @OA\Property(property="status", type="string", example="active")
 * )
 *
 * @OA\Schema(
 *   schema="LabelValue",
 *   type="object",
 *   @OA\Property(property="label", type="string", example="German"),
 *   @OA\Property(property="value", type="integer", example=10)
 * )
 *
 * @OA\Schema(
 *   schema="AdminStatsPayload",
 *   type="object",
 *   @OA\Property(
 *     property="kpis",
 *     type="object",
 *     @OA\Property(property="users_total", type="integer", example=120),
 *     @OA\Property(property="languages", type="integer", example=10),
 *     @OA\Property(property="courses", type="integer", example=35),
 *     @OA\Property(property="lessons", type="integer", example=180),
 *     @OA\Property(property="enrollments", type="integer", example=420)
 *   ),
 *   @OA\Property(
 *     property="users_by_role",
 *     type="array",
 *     @OA\Items(
 *       type="object",
 *       @OA\Property(property="role", type="string", example="student"),
 *       @OA\Property(property="count", type="integer", example=90)
 *     )
 *   ),
 *   @OA\Property(property="courses_by_language", type="array", @OA\Items(ref="#/components/schemas/LabelValue")),
 *   @OA\Property(property="courses_by_level", type="array", @OA\Items(ref="#/components/schemas/LabelValue")),
 *   @OA\Property(property="enrollments_by_status", type="array", @OA\Items(ref="#/components/schemas/LabelValue")),
 *   @OA\Property(property="top_teachers_by_active_courses", type="array", @OA\Items(ref="#/components/schemas/LabelValue")),
 *   @OA\Property(property="top_courses_by_enrollments", type="array", @OA\Items(ref="#/components/schemas/LabelValue")),
 *   @OA\Property(property="lessons_per_month", type="array", @OA\Items(ref="#/components/schemas/LabelValue"))
 * )
 */
class ApiDoc extends Controller {}
