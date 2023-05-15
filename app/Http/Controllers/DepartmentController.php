<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Lecturer;

class DepartmentController extends Controller
{

    public function store(Request $request)
    {
        $departmentExists = Department::where('department_code', $request->department_code)->first();
        if ($departmentExists) {
            return response()->json([
                'message' => 'Department already exists!',
                'data' => $departmentExists
            ], 201);
        } else {
            $validatedData = $request->validate([
                'name' => 'required',
                'department_code' => 'required|unique:departments,department_code',
                'semester' => 'required|string',
                'level' => 'required|string',

            ]);

            $department = Department::create($validatedData);
            $department->save();
            return response()->json([
                'message' => 'Department created successfully!',
                'data' => $department
            ], 201);
        }
    }
    public function update(Request $request, Department $department)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'department_code' => 'required|unique:departments,department_code,' . $department->id,
            'semester' => 'required|string',
            'level' => 'required|string',
        ]);

        $department->update($validatedData);
        return response()->json([
            'message' => 'Department updated successfully!',
            'data' => $department
        ], 200);
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json([
            'message' => 'Department deleted successfully',
            'data' => $department
        ], 201);
    }
    public function show(Department $department)
    {
        return response()->json([
            'message' => 'Department retrieved successfully',
            'data' => $department
        ], 200);
    }
    public function getNamesOfAllDepartments()
    {
        $departments = Department::all();
        $departmentNames = [];
        foreach ($departments as $department) {
            $departmentNames[] = $department->name;
        }
        return response()->json([
            'message' => 'Department names retrieved successfully',
            'data' => $departmentNames
        ], 200);
    }
    public function allDepartments()
    {
        $departments = Department::all();
        return response()->json([
            'message' => 'Departments retrieved successfully',
            'data' => $departments,
            'status' => 200
        ], 200);
    }
    public function getDepartmentById($id)
    {
        $department = Department::find($id);
        return response()->json([
            'message' => 'Department retrieved successfully',
            'data' => $department,
            'status' => 200
        ], 200);
    }
    public function getAllCoursesByDepartmentId($id)
    {
        $courses = Course::where('department_id', $id)->get();
        return response()->json([
            'message' => 'Courses retrieved successfully',
            'data' => $courses,
            'status' => 200
        ], 200);
    }

    public function getClassroomIdByDepartmentId($departmentId)
    {
        $classroom = Classroom::where('classrooms.id', $departmentId)
            ->join('courses', 'classrooms.course_id', '=', 'courses.id')
            ->where('courses.department_id', $departmentId)
            ->first();
        if ($classroom) {
            return $classroom;
        }

        // Return null or throw an exception as needed
        return null;
    }
}
