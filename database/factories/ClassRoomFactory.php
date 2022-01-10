<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ClassRoom;
use App\Models\Student;

class ClassRoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClassRoom::class;

    public function configure()
    {
        return $this->afterMaking(function (ClassRoom $class) {
        })->afterCreating(function (ClassRoom $class) {
            $num = rand(1,20);
            $class->students()->saveMany(Student::factory()->count($num)->make());
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => $this->faker->unique()->numberBetween(1, 10),
        ];
    }
}
