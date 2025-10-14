<?php

namespace Database\Factories;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected static $usedSlots = [];

    private $targetMonth;

    public function forMonth(int $month): static
    {
        $this->targetMonth = $month;
        return $this;
    }

    public function definition(): array
    {
        $category = Categorie::getActiveCategories()->random();
        $start = $this->getUniqueTimeSlot($this->targetMonth);
        $end = (clone $start)->modify('+1 hour');

        return [
            'title' => $this->faker->sentence(5),
            'user_id' => 25,
            'date_from' => $start,
            'date_to' => $end,
            'categoryId' => $category->id,
        ];
    }

    private function getUniqueTimeSlot(int $month): \DateTime
    {
        // Predefined weeks per month (Mon–Fri)
        $weeks = [
            7 => ['2025-10-02'],
            8 => ['2025-08-04', '2025-08-05', '2025-08-06', '2025-08-07', '2025-08-08'],
            9 => ['2025-09-09', '2025-09-10', '2025-09-11', '2025-09-12', '2025-09-13'],
            10 => ['2025-10-07', '2025-10-08', '2025-10-09', '2025-10-10', '2025-10-11'],
        ];

        $days = $weeks[$month];
        $hours = range(8, 17); // 08:00 to 17:00

        do {
            $day = $this->faker->randomElement($days);
            $hour = $this->faker->randomElement($hours);
            $slotKey = "$day-$hour";
        } while (in_array($slotKey, self::$usedSlots));

        self::$usedSlots[] = $slotKey;
        return new \DateTime("$day $hour:00");
    }
}

