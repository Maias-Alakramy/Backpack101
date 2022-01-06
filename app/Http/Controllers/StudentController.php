<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $class_room = $request->query('class');
        $studentsQuary = DB::table('students');
        if($class_room)
        {
            $class_room_id = ClassRoom::where('number' ,'=', $class_room)->firstorfail()->id;
            $studentsQuary->where('class_room_id', $class_room_id);
        }
        $students = $studentsQuary->get();
        return response()->json($students, 200);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Student $student
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        try{
            //if you want to search by code uncomment this line
            $student = student::where('code', '=', $code)->firstOrFail();

            //if you want to search by id uncomment this line
            //$student = student::findOrFail($code);

            return response()->json($student, 200);
        }catch(ModelNotFoundException $e){
            return response()->json(["message"=>"Not Found"], 404);
        }catch(Exception $e){
            return response()->json(["message"=>"Internal Server Error"], 500);
        }
    }
    
    //serach by name
    public function search(Request $request)
    {
        $name = $request->query('name');
        if($name)
        {
            $students = student::where('name', 'like', '%'.$name.'%')->get();
            return response()->json($students, 200);
        }
        return response()->json('hell', 200);
    }
}
