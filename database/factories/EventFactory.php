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
            'atty_initials' => strtoupper($this->faker->randomLetter . $this->faker->randomLetter),
            'stage_of_process' => $this->faker->randomElement(['Filing', 'Discovery', 'Trial', 'Appeal']),
            'client_name' => $this->faker->name(),
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
            6 => ['2025-10-23'],
            7 => ['2025-10-02'],
            8 => ['2025-08-04', '2025-08-05', '2025-08-06', '2025-08-07', '2025-08-08'],
            9 => [
                '2025-09-01', '2025-09-02', '2025-09-03', '2025-09-04', '2025-09-05',
                '2025-09-08', '2025-09-09', '2025-09-10', '2025-09-11', '2025-09-12',
                '2025-09-15', '2025-09-16', '2025-09-17', '2025-09-18', '2025-09-19',
                '2025-09-22', '2025-09-23', '2025-09-24', '2025-09-25', '2025-09-26',
                '2025-09-29', '2025-09-30',
            ],
            10 => [
                '2025-10-01', '2025-10-02', '2025-10-03',
                '2025-10-06', '2025-10-07', '2025-10-08', '2025-10-09', '2025-10-10',
                '2025-10-13', '2025-10-14', '2025-10-15', '2025-10-16', '2025-10-17',
                '2025-10-20', '2025-10-21', '2025-10-22', '2025-10-23', '2025-10-24',
                '2025-10-27', '2025-10-28', '2025-10-29', '2025-10-30',
            ],
            11 => [
                '2025-11-01', '2025-11-02', '2025-11-03',
                '2025-11-06', '2025-11-07', '2025-11-08', '2025-11-09', '2025-11-10',
                '2025-11-13', '2025-11-14', '2025-11-15', '2025-11-16', '2025-11-17',
                '2025-11-20', '2025-11-21', '2025-11-22', '2025-11-23', '2025-11-24',
                '2025-11-27', '2025-11-28', '2025-11-29', '2025-11-30',
            ],

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

