<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Job;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(2, true),
            'salary' => fake()->numberBetween(40000, 120000),
            'tags' => implode(", ", fake()->words(3)),
            'job_type' => fake()->randomElement(["Full-Time", "Part-Time", "Contract", "Temporary", "Internship", "Volunteer", "On-Call"]),
            'remote' => fake()->boolean(),
            'requirements' => fake()->sentence(15),
            'benefits' => fake()->sentence(15),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'contact_email' => fake()->safeEmail(),
            'contact_phone' => fake()->phoneNumber(),
            'company_name' => fake()->company(),
            'company_description' => fake()->sentence(20),
            'company_logo' => fake()->imageUrl(100, 100, "business", true, "logo"),
            'company_website' => fake()->url()
        ];
    }
}
