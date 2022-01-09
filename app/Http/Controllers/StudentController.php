<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if you want to change what happens when validation fails, you can do it here

        // $validator = Validator::make($request->all(), [
        //     'class' => 'integer|exists:App\Models\classRoom,number'
        // ]);
        // if ($validator->fails())
        //     return response()->json('Class not found', 404);

        $request->validate([
            'class' => 'integer|exists:App\Models\classRoom,number'
        ]);
        $class_room = $request->query('class');
        $name = $request->query('name');

        $offset = $request->query('offset');
        $limit = $request->query('limit');

        $studentsQuary = DB::table('students')
            ->join('class_rooms', 'class_rooms.id', '=', 'students.class_room_id')
            ->select('students.*', 'class_rooms.number');
        
        if($class_room)
        {
            $studentsQuary->where('number', '=', $class_room);
        }
        if($name)
        {
            $studentsQuary->where('name', 'like', '%' . $name . '%');
        }
        if($offset && $limit)
        {
            $studentsQuary->offset($offset)->limit($limit);
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
}
