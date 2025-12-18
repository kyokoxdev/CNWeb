<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request){
        $classId = $request->query('class_id');
        
        if ($classId) {
            $students = Student::where('class_id', $classId)->get();
        } else {
            $students = Student::all();
        }
        
        return view("studentsview", compact("students", "classId"));
    }
}
