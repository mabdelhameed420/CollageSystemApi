<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Department;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();

        return response()->json([
            'message' => 'Courses retrieved successfully',
            'data' => $courses,
            'status' => 200
        ], 200);
    }

    public function store(Request $request)
    {
        $courseExists = Course::where('course_code', $request->input('course_code'))->first();
        if ($courseExists) {
            return response()->json([
                'message' => 'Course already exists',
                'data' => $courseExists,
                'status' => 400
            ], 400);
        } else {
            $course = Course::create([
                'name' => $request->input('name'),
                'course_code' => $request->input('course_code'),
                'department_id' => $request->input('department_id'),
                'level' => $request->input('level'),
                'semester' => $request->input('semester'),
            ]);

            return response()->json([
                'message' => 'Course created successfully',
                'data' => $course,
                'status' => 201
            ], 201);
        }
    }

    public function show($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'message' => 'Course not found',
                'data' => null,
                'status' => 404
            ], 404);
        }

        return response()->json([
            'message' => 'Course retrieved successfully',
            'data' => $course,
            'status' => 200
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'message' => 'Course not found',
                'data' => null,
                'status' => 404
            ], 404);
        }

        $course->name = $request->input('name');
        $course->course_code = $request->input('course_code');
        $course->department_id = $request->input('department_id');
        $course->level = $request->input('level');
        $course->semester = $request->input('semester');
        $course->save();

        return response()->json([
            'message' => 'Course updated successfully',
            'data' => $course,
            'status' => 200
        ], 200);
    }

    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'message' => 'Course not found',
                'data' => null,
                'status' => 404
            ], 404);
        }

        $course->delete();

        return response()->json([
            'message' => 'Course deleted successfully',
            'data' => $course,
            'status' => 200
        ], 200);
    }
    public function getAllCourses()
    {
        $courses = Course::all();
        foreach ($courses as $course) {
            $department=Department::find($course->department_id);
            $course->department_name=$department->name;
        }

        return response()->json([
            'message' => 'Courses retrieved successfully',
            'data' => $courses,
            'status' => 200
        ], 200);
    }
    public function getCoursesByDepartmentId($department_id)
    {
        $courses = Course::where('department_id', $department_id)->get();
        return response()->json([
            'message' => 'Courses retrieved successfully',
            'data' => $courses,
            'status' => 200
        ], 200);
    }
}
