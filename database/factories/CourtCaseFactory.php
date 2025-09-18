<?php

namespace Database\Factories;

use App\Models\CourtCase;
use App\Models\Categorie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourtCaseFactory extends Factory
{
    protected static $usedSlots = [];

    // Set default interval (e.g. September 2025)
    private \DateTime $startDate;
    private \DateTime $endDate;

    public function __construct(...$args)
    {
        parent::__construct(...$args);

        // Default range if not overridden
        $this->startDate = new \DateTime('2025-10-01');
        $this->endDate = new \DateTime('2025-10-30');
    }

    /**
     * Allow overriding date interval
     */
    public function forDateRange(string $start, string $end): static
    {
        $this->startDate = new \DateTime($start);
        $this->endDate = new \DateTime($end);
        return $this;
    }

    public function configure(): static
    {
        return $this->afterCreating(function (CourtCase $case) {
            $userIds = User::inRandomOrder()->limit(2)->pluck('id')->toArray();
            $userIds[] = 2; // fallback user
            $case->users()->attach(array_unique($userIds));
        });
    }

    private function getUniqueTimeSlot(): \DateTime
    {
        $hours = range(8, 17);
        $maxAttempts = 100;

        for ($i = 0; $i < $maxAttempts; $i++) {
            $timestamp = $this->faker->numberBetween(
                $this->startDate->getTimestamp(),
                $this->endDate->getTimestamp()
            );

            $day = (new \DateTime())->setTimestamp($timestamp)->format('Y-m-d');
            $hour = $this->faker->randomElement($hours);
            $slotKey = "$day-$hour";

            if (!in_array($slotKey, self::$usedSlots)) {
                self::$usedSlots[] = $slotKey;
                return new \DateTime("$day $hour:00");
            }
        }

        // Fallback: just return start date
        return clone $this->startDate;
    }

    public function definition(): array
    {
        $category = Categorie::getActiveCategories()->random() ?? Categorie::factory()->create();
        $start = $this->getUniqueTimeSlot();
        $end = (clone $start)->modify('+1 day');

        return [
            'caseTitle' => $this->faker->sentence(4),
            'categoryId' => $category->id,
            'dateFrom' => $start->format('Y-m-d'),
            'dateTo' => $end->format('Y-m-d'),
        ];
    }
}

