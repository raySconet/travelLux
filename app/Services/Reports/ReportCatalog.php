<?php

namespace App\Services\Reports;

class ReportCatalog
{
    /** Existing URLs remain in routes/web.php; these definitions drive their shared report screen. */
    public static function all(): array
    {
        return [
            'reservationsNotPaidByTraveluxReport' => ['Reservations Not Paid By Travelux', FinancialReports::class],
            'reservationsPaidByTraveluxReport' => ['Reservations Paid By Travelux', FinancialReports::class],
            'unknownReservationsReport' => ['Unknown Reservations', ReservationReports::class, 'admin' => true, 'dates' => false, 'agents' => false],
            'bookedTripsByStateReport' => ['Booked Trips by State', ReservationReports::class, 'admin' => true, 'state' => true],
            'reservationsChangesReport' => ['Reservations Changes Report', ReservationReports::class, 'admin' => true],
            'reservationsByAgentReport' => ['Reservations By Agent Report', ReservationReports::class, 'admin' => true],
            'productSalesByAgentReport' => ['Product Sales by Agent', SalesReports::class],
            'agentSalesByProductReport' => ['Agent Sales by Product', SalesReports::class],
            'totalSalesReport' => ['Total Sales', SalesReports::class, 'basis' => true],
            'agentExpensesReport' => ['Agent Expenses', FinancialReports::class],
            'aliasTotalSalesReport' => ['Total Sales By Alias', SalesReports::class, 'agents' => false],
            'aliasTotalGrossCommission' => ['Alias Total Gross Commission', SalesReports::class, 'agents' => false],
            'allReservationsByDateReport' => ['All Reservations By Travel Date', ReservationReports::class],
            'allTripsByTravelDateReport' => ['All Trips By Travel Date', ReservationReports::class, 'agents' => false],
            'top15ExpensiveTripsReport' => ['Top 15 Expensive Trips', ReservationReports::class, 'admin' => true],
            'cruisesReport' => ['Cruises Report', ReservationReports::class, 'admin' => true],
            'productSalesWithDepositByAgentReport' => ['Product Sales (With Deposit) by Agent', SalesReports::class, 'admin' => true],
            'finalAgencyCommissionReport' => ['Final Agency Commission', FinancialReports::class, 'admin' => true],
            'bookedReservationsPerMonthReport' => ['Booked Reservations Per Month', ReservationReports::class, 'admin' => true, 'year' => true, 'dates' => false],
            'resleadsReport' => ['Reservations Leads Report', ReservationReports::class, 'admin' => true],
            'totalCommissionReport' => ['Total Commission Report', FinancialReports::class, 'admin' => true],
            'hotelOnlyReport' => ['Hotel Only Report', ReservationReports::class, 'admin' => true],
            'classicVacationsReport' => ['Classic Vacations Report', ReservationReports::class, 'admin' => true],
            'pleasantEvokeReport' => ['Pleasant/Evoke Report', ReservationReports::class, 'admin' => true],
            'appleVacationsReport' => ['Apple Vacations Report', ReservationReports::class, 'admin' => true],
            'travelImpressionsReport' => ['Travel Impressions Report', ReservationReports::class, 'admin' => true],
            'vacationExpressReport' => ['Vacation Express Report', ReservationReports::class, 'admin' => true],
            'expediaReport' => ['Expedia Report', ReservationReports::class, 'admin' => true],
            'rebookingRateReport' => ['Rebooking Rate Report', SalesReports::class, 'years' => true, 'dates' => false],
            'salesReport' => ['Sales Report', SalesReports::class, 'basis' => true],
        ];
    }

    public static function get(string $key): array
    {
        $definition = self::all()[$key] ?? null;
        abort_if($definition === null, 404);

        $icon = match ($key) {
            'reservationsNotPaidByTraveluxReport' => 'fa-money-check',
            'reservationsPaidByTraveluxReport' => 'fa-money-bill-alt',
            'unknownReservationsReport' => 'fa-question',
            'bookedTripsByStateReport' => 'fa-flag-usa',
            'reservationsChangesReport', 'reservationsByAgentReport' => 'fa-tags',
            'productSalesByAgentReport' => 'fa-poll',
            'productSalesWithDepositByAgentReport', 'salesReport' => 'fa-vote-yea',
            'agentSalesByProductReport' => 'fa-poll-h',
            'totalSalesReport' => 'fa-chart-area',
            'agentExpensesReport' => 'fa-money-check-alt',
            'aliasTotalSalesReport' => 'fa-project-diagram',
            'aliasTotalGrossCommission' => 'fa-chart-pie',
            'allReservationsByDateReport' => 'fa-book-open',
            'allTripsByTravelDateReport' => 'fa-plane-departure',
            'top15ExpensiveTripsReport' => 'fa-briefcase',
            'cruisesReport' => 'fa-ship',
            'bookedReservationsPerMonthReport' => 'fa-list-ol',
            'resleadsReport' => 'fa-grip-horizontal',
            'totalCommissionReport' => 'fa-umbrella',
            'hotelOnlyReport' => 'fa-hotel',
            'rebookingRateReport' => 'fa-percent',
            default => 'fa-chart-bar',
        };

        return [
            'title' => $definition[0],
            'service' => $definition[1],
            'icon' => $icon,
            'partial' => 'reports.partials.'.match ($definition[1]) {
                SalesReports::class => 'salesData',
                FinancialReports::class => 'financialData',
                ReservationReports::class => 'reservationData',
            },
            'admin' => $definition['admin'] ?? false,
            'dates' => $definition['dates'] ?? true,
            'agents' => in_array($key, ['rebookingRateReport', 'reservationsChangesReport', 'reservationsByAgentReport', 'cruisesReport', 'pleasantEvokeReport']),
            'search' => in_array($key, ['allReservationsByDateReport', 'unknownReservationsReport', 'salesReport']),
            'download' => $key !== 'salesReport',
            'rangeByYear' => $key === 'agentSalesByProductReport',
            'basis' => $definition['basis'] ?? false,
            'state' => $definition['state'] ?? false,
            'year' => $definition['year'] ?? false,
            'years' => $definition['years'] ?? false,
        ];
    }
}
