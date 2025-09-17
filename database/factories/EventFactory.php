<?php

namespace Database\Factories;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    // Static variable to track used time slots
    protected static $usedSlots = [];

    public function definition(): array
    {
        // Get an active category
        $category = Categorie::getActiveCategories()->random();

        $start = $this->getUniqueTimeSlot();

        $end = (clone $start)->modify('+1 hour'); // fixed 1 hour duration

        return [
            'title' => $this->faker->sentence(3),
            'user_id' => 25,
            'date_from' => $start,
            'date_to' => $end,
            'categoryId' => $category->id,
        ];
    }

    /**
     * Generate a unique start time slot
     */
    private function getUniqueTimeSlot(): \DateTime
    {
        $days = ['2025-09-22', '2025-09-23', '2025-09-24', '2025-09-25', '2025-09-26'];
        $hours = range(8, 17); // Working hours: 08:00 to 17:00 (1-hour slots)

        do {
            $day = $this->faker->randomElement($days);
            $hour = $this->faker->randomElement($hours);
            $slotKey = "$day-$hour";

        } while (in_array($slotKey, self::$usedSlots));

        // Mark this slot as used
        self::$usedSlots[] = $slotKey;

        return new \DateTime("$day $hour:00");
    }
}
