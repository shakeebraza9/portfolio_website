<?php

namespace App\Http\Controllers\Api\Master;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $length = (int) $request->input('length', 10);
        $page   = (int) $request->input('page', 1);

        $length = $length > 0 ? $length : 10;
        $page   = $page > 0 ? $page : 1;

        $query = Project::with('galleries');

        // 🔍 Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 🔎 Filters
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('author')) {
            $query->where('author', $request->author);
        }

        // 🚀 Laravel pagination (BEST WAY)
        $paginator = $query->orderByDesc('id')
            ->paginate($length, ['*'], 'page', $page);

        return response()->json([
            'recordsTotal' => Project::count(), // total without filter
            'recordsFiltered' => $paginator->total(), // filtered count
            'page' => $paginator->currentPage(),
            'length' => $paginator->perPage(),
            'last_page' => $paginator->lastPage(),
            'data' => $paginator->items(),
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|url|max:255',
            'github_link' => 'nullable|url|max:255',
            'technologies' => 'nullable', // flexible rakho
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'database' => 'nullable|string|max:255',
            'framework' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'video' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'developed_by' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
        ]);

        // ✅ Technologies FIX (array → comma string)
        if ($request->has('technologies')) {
            if (is_array($request->technologies)) {
                $validated['technologies'] = implode(',', $request->technologies);
            } else {
                $validated['technologies'] = $request->technologies;
            }
        }

        // ✅ Slug
        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $i = 1;

        while (Project::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }

        $validated['slug'] = $slug;

        // ✅ Default values
        $validated['developed_by'] = $validated['developed_by'] ?? "Muhammad Shakeeb Raza";
        $validated['author'] = $validated['author'] ?? "Muhammad Shakeeb Raza";

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $imageName = time() . '_' . $image->getClientOriginalName();

            $image->move(public_path('uploads/project'), $imageName);

            $validated['image'] = env('APP_URL_IMAGE') . '/uploads/project/' . $imageName;
        }

        $project = Project::create($validated);

        return response()->json([
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }


    public function show($id)
    {
        try {
            $project = Project::findOrFail($id);

            // ✅ Technologies array → comma separated string
            if (!empty($project->technologies)) {
                if (is_array($project->technologies)) {
                    $project->technologies = implode(',', $project->technologies);
                } else {
                    // agar already string hai to safe raho
                    $project->technologies = $project->technologies;
                }
            }

            return response()->json([
                'message' => 'Project fetched successfully',
                'data' => $project
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'message' => 'Project not found'
            ], 404);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {

            $project = Project::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'url' => 'nullable|url|max:255',
                'github_link' => 'nullable|url|max:255',
                'technologies' => 'nullable',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'database' => 'nullable|string|max:255',
                'framework' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'video' => 'nullable|string|max:255',
                'date' => 'nullable|date',
                'developed_by' => 'nullable|string|max:255',
                'author' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // ✅ Technologies array fix
            if ($request->has('technologies')) {
                if (is_array($request->technologies)) {
                    $validated['technologies'] = implode(',', $request->technologies);
                } else {
                    $validated['technologies'] = $request->technologies;
                }
            }

            // ✅ Slug update if name changed
            if ($project->name !== $validated['name']) {
                $baseSlug = Str::slug($validated['name']);
                $slug = $baseSlug;
                $i = 1;

                while (Project::where('slug', $slug)
                    ->where('id', '!=', $project->id)
                    ->exists()) {

                    $slug = $baseSlug . '-' . $i++;
                }

                $validated['slug'] = $slug;
            }

            // ✅ Image Upload (public folder)
            if ($request->hasFile('image')) {

                // 🔥 Old image delete
                if ($project->image && File::exists(public_path($project->image))) {
                    File::delete(public_path($project->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();

                $image->move(public_path('uploads/project'), $imageName);

                $validated['image'] = 'uploads/project/' . $imageName;
            }

            $project->update($validated);

            return response()->json([
                'message' => 'Project updated successfully',
                'data' => $project->load('galleries')
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'message' => 'Project not found'
            ], 404);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }
}
