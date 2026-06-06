<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use App\Mail\SupportFormMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Project;
use App\Models\Blog;
use Predis\Command\Container\FUNCTIONS;

class PageController extends Controller
{





    public function supportForm(Request $request)
    {

        $validator = Validator::make($request->all(),[
         
            'name' => 'required|min:3|string',
            'email' => 'required|email|min:6',
            'subject' => 'required|string|max:255',
            'description' => 'required|max:10000',
        ]);

         if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $data = $request->only('name', 'email', 'subject', 'description');
            // $adminEmail = "man411210@gmail.com"; 
            $adminEmail = Setting::where("key", "admin_mail")->value('value');

            Mail::to($adminEmail)->send(new SupportFormMail($data));

            return response()->json([
                'message' => 'Your inquiry has been sent successfully!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Email sending failed: ' . $e->getMessage()
            ], 500);
        }




    }
public function projectForm(Request $request)
{
    $length = (int) $request->input('length', 10);
    $page   = (int) $request->input('page', 1);

    $length = $length > 0 ? $length : 10;
    $page   = $page > 0 ? $page : 1;

    $offset = ($page - 1) * $length;

    $query = Project::with('galleries');

    // 🔍 Search
    $query->when($request->search, function ($q) use ($request) {
        $q->where('name', 'like', '%' . $request->search . '%');
    });

    // 🔎 Filters
    $query->when($request->id, fn($q) => $q->where('id', $request->id));
    $query->when($request->author, fn($q) => $q->where('author', $request->author));

    // 📊 Count (filtered)
    $count = (clone $query)->count();

    // 📦 Data
    $data = $query
        ->orderByDesc('date')   // IMPORTANT: order BEFORE pagination logic
        ->skip($offset)
        ->take($length)
        ->get();

    return response()->json([
        'recordsTotal' => Project::count(),
        'recordsFiltered' => $count,
        'page' => $page,
        'length' => $length,
        'offset' => $offset,
        'last_page' => ceil($count / $length),
        'data' => $data,
    ]);
}

    public function singleProject($slug)
{
    try {

        $project = Project::with('galleries')
            ->where('slug', $slug)
            ->first();

        // ❌ Not Found
        if (!$project) {
            return response()->json([
                'status' => false,
                'message' => 'Project not found'
            ], 404);
        }

        // ✅ Success
        return response()->json([
            'status' => true,
            'data' => $project
        ], 200);

    } catch (\Exception $e) {

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function blogs(Request $request)
    {

        $length = $request->input('length', 50);
        $page   = $request->input('page', 1);
        $offset = ($page - 1) * $length;

        //Query
        $query = Blog::query()->with('category', 'author');

        //Filter
        if($request->filled('search')) {
                $query->where('title', 'like', '%'.$request->search.'%');
        }
        if($request->has('id') && $request->id != '') {
            $query->where('id',$request->id);
        }

        $count = (clone $query)->count();
        $data = $query->select([
                    '*'
                ])
                ->skip($offset)
                ->take($length)
                ->orderByDesc('id')
                ->get()
                ->map(function($item){
                    return $item;
                });
            
        return response()->json([
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'page' => $page,
            'offset' => $offset,
            'last_page' => ceil($count / $length),
            'data' => $data,
        ]);

    }
    public function singleBlog($slug)
    {
        try {

            $blog = Blog::with('category', 'author','details')
                ->where('slug', $slug)
                ->first();

            // ❌ Not Found
            if (!$blog) {
                return response()->json([
                    'status' => false,
                    'message' => 'Blog not found'
                ], 404);
            }

            // ✅ Success
            return response()->json([
                'status' => true,
                'data' => $blog
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}