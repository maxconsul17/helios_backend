<?php

namespace Database\Factories;

use App\Models\Schedule\EmployeeSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeScheduleFactory extends Factory
{
    protected $model = EmployeeSchedule::class;

    public function definition()
    {
        return [
            'employee_id' => $this->faker->numberBetween(1, 100),
            'start' => $this->faker->dateTimeBetween('now', '+1 week'),
            'end' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            // Add other required fields from your EmployeeSchedule table
        ];
    }
}
