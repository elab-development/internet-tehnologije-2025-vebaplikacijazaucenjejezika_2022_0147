<?php

namespace App\Http\Controllers;

use App\Http\Resources\LanguageResource;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *   name="Languages",
 *   description="Public listing/show; admin-only create/update/delete"
 * )
 */
class LanguageController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/languages",
     *   tags={"Languages"},
     *   summary="List all languages (public)",
     *   @OA\Response(
     *     response=200,
     *     description="OK"
     *   )
     * )
     */
    public function index()
    {
        $languages = Language::query()->orderBy('name')->get();

        if ($languages->isEmpty()) {
            return response()->json('No languages found.', 404);
        }

        return response()->json([
            'languages' => LanguageResource::collection($languages),
        ]);
    }

    /**
     * @OA\Get(
     *   path="/api/languages/{language}",
     *   tags={"Languages"},
     *   summary="Get a single language (public)",
     *   @OA\Parameter(
     *     name="language", in="path", required=true, @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(Language $language)
    {
        return response()->json([
            'language' => new LanguageResource($language),
        ]);
    }

    /**
     * @OA\Post(
     *   path="/api/languages",
     *   tags={"Languages"},
     *   summary="Create a language (admin only)",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Created"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Only admins can create languages"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Only admins can create languages'], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80', Rule::unique('languages', 'name')],
            'imgUrl' => ['nullable', 'string'],
        ]);

        $language = Language::create([
            'name' => $validated['name'],
            'img_url' => $validated['imgUrl'] ?? null,
        ]);

        return response()->json([
            'message' => 'Language created successfully',
            'language' => new LanguageResource($language),
        ]);
    }

    /**
     * @OA\Put(
     *   path="/api/languages/{language}",
     *   tags={"Languages"},
     *   summary="Update a language (admin only)",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="language", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Updated"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Only admins can update languages"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, Language $language)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Only admins can update languages'], 403);
        }

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'string',
                'max:80',
                Rule::unique('languages', 'name')->ignore($language->id),
            ],
            'imgUrl' => ['nullable', 'string'],
        ]);

        $payload = [];
        if (array_key_exists('name', $validated)) $payload['name'] = $validated['name'];
        if (array_key_exists('imgUrl', $validated)) $payload['img_url'] = $validated['imgUrl'];

        if (empty($payload)) {
            return response()->json(['error' => 'No editable fields provided'], 422);
        }

        $language->update($payload);

        return response()->json([
            'message' => 'Language updated successfully',
            'language' => new LanguageResource($language->fresh()),
        ]);
    }

    /**
     * @OA\Delete(
     *   path="/api/languages/{language}",
     *   tags={"Languages"},
     *   summary="Delete a language (admin only)",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="language", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Deleted"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Only admins can delete languages")
     * )
     */
    public function destroy(Language $language)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Only admins can delete languages'], 403);
        }

        $language->delete();

        return response()->json(['message' => 'Language deleted successfully']);
    }
}
