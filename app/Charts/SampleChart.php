<?php

declare(strict_types = 1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\Student;

class SampleChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $classrooms = ClassRoom::all();
        foreach ($classrooms as $classroom) {
            $students = Student::where('class_room_id', $classroom->id)->get();
            $data[] = $students->count();
        }
        return Chartisan::build()
            ->labels($classrooms->pluck('number')->toArray())
            ->dataset('Students', $data);
    }
}