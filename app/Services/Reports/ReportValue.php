<?php

namespace App\Services\Reports;

use Carbon\Carbon;

class ReportValue
{
    public static function format(mixed $value, ?string $format = null): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return match ($format) {
            'money' => is_numeric($value) ? '$'.number_format((float) $value, 2) : (string) $value,
            'percent' => is_numeric($value) ? number_format((float) $value, 2).'%' : (string) $value,
            'number' => is_numeric($value) ? number_format((float) $value) : (string) $value,
            'date' => self::date($value),
            default => (string) $value,
        };
    }

    private static function date(mixed $value): string
    {
        if (str_starts_with((string) $value, '0000-00-00')) {
            return '';
        }

        try {
            return Carbon::parse($value)->format('m/d/Y');
        } catch (\Throwable) {
            return (string) $value;
        }
    }

    /** Prevent spreadsheet applications from interpreting customer-entered text as formulas. */
    public static function csv(mixed $value): string
    {
        $value = (string) ($value ?? '');

        return preg_match('/^[\s]*[=+\-@]/u', $value) && ! is_numeric($value) ? "'".$value : $value;
    }
}
