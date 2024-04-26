<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $driverRole = 'driver'; // Adjust based on your role names
        $employeeRole = 'employee';

        // Get random user IDs based on roles
        $driverIds = User::where('role', $driverRole)->pluck('id')->toArray();
        $employeeIds = User::where('role', $employeeRole)->pluck('id')->toArray();

        return [
            'id' => $this->faker->randomNumber(8,true),
            'vehicle_number' => $this->faker->randomElement(['45958515', '45958514', '45958513']),
            'filling_datetime' => $this->faker->dateTimeThisMonth(),
            'Car_currently_tank' => $this->faker->randomFloat(2, 0, 1000),
            'Coupon_fuel_quantity' => $this->faker->randomFloat(2, 1, 500),
            'Global_fuel_price' => $this->faker->randomFloat(2, 1, 100),
            'Coupon_fuel_quantity_price' => $this->faker->randomFloat(2, 1, 1000),
            'currency' => $this->faker->randomElement(['Dollar', 'Euro', 'Dinar', 'NIS']),
            'driver_id' => $this->faker->randomElement($driverIds),
            'Employee_id' => $this->faker->randomElement($employeeIds),
            'Fuel_station_name' => $this->faker->randomElement(['StationOne', 'StationTwo', 'StationThree']),
            'Coupon_pdf_path' => null, // Adjust as needed
            'status' => $this->faker->randomElement(['paid', 'unpaid', 'cancelled']),
            'Reigon' => $this->faker->country, // Example of using Faker to generate city names
            'City' => $this->faker->city,
        ];
    }
}
