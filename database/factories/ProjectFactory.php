<?php

namespace Database\Factories;

use App\Models\Projects;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Projects::class;
    
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->url,
            'description' => $this->faker->paragraph,
            'image' => $this->faker->imageUrl,
            'url' => $this->faker->url,
            'category_id' => $this->faker->numberBetween(1, 10),
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
        ];
    }
}
