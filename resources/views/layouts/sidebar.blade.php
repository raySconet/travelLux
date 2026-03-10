@php
    $user = auth()->user();
@endphp
<!-- SIDEBAR -->
<div
    class="fixed inset-y-0 left-0 bg-white shadow-lg w-64 transform transition-transform duration-500 z-100"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div class="p-4.5 border-b bg-[#f18325] text-[#FFF] border-[#CCCCCC] font-semibold text-xl">
        Archer Luxury Travel
    </div>

    <ul class="space-y-3 text-base text-[#696969]" style="overflow-y: auto; height:91%;">
        <!-- Administration -->
        <li x-data="{ open: false }">
            <button type="button" @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="text-lg fa-solid w-[40px] fa-shield-halved"></i>
                Administration
                <span class="ml-auto" :class="open ? 'rotate-180' : ''">
                    <i class="fa-solid fa-angle-down"></i>
                </span>
            </button>

            <div x-show="open" x-collapse class="mt-2 text-sm space-y-3" >
                @if(auth()->user()->isAdmin())
                    <a href="/system-users" class="block py-1  sidebarA">System Users <i class="text-lg fas fa-user-circle"></i> </a>
                    <a href="/timelinetasks" class="block py-1 sidebarA">Timeline Tasks <i class="text-lg fas fa-clock"></i> </a>
                    <a href="/productConfiguration" class="block py-1 sidebarA">Product Configuration  <i class="text-lg fas fa-ship"></i></a>
                    <a href="/agencyProfile" class="block py-1 sidebarA">Agency Profile  <i class="text-lg fas fa-id-card"></i> </a>
                    <a href="/customersForms" class="block py-1 sidebarA">Forms Manager <i class="text-lg fas fa-server"></i></a>
                @endif
                    <a href="/automatedEmails" class="block py-1 sidebarA">Automated Emails  <i class="text-lg fas fa-envelope"></i></a>
                    <a href="/itinerary" class="block py-1 sidebarA">Itinerary <i class="text-lg fa fa-plane"></i></a>
            </div>
        </li>

        <!-- Customers -->
        <li x-data="{ open: false }">
            <button type="button" @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="text-lg fa-solid fa-users w-[40px]"></i>
                Customers
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="mt-2 text-sm space-y-3"  >
                <a href="/customer-list" class="block py-1 sidebarA">Customer List<i class="text-lg fas fa-list"></i></a>
                <a href="/customer/create" class="block py-1 sidebarA">New Customer <i class="text-lg fas fa-plus-circle"></i></a>
                <a href="/inviteNewCustomer" class="block py-1 sidebarA">Invite New Customer <i class="text-lg fas fa-envelope-open-text"></i></a>
            </div>
        </li>

        <!-- Reservations  -->
        <li x-data="{ open: false }">
            <button type="button" @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="text-lg fa-solid  w-[40px] fa-tag "></i>
                Reservations
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="mt-2 text-sm space-y-2"  >
                <a href="/reservation-list" class="block py-1 sidebarA">Current Reservations <i class="text-lg fas fa-list"></i></a>
                <a href="/reservation-list/create" class="block py-1 sidebarA">New Reservation <i class="text-lg fas fa-plus-circle"></i></a>
            </div>
        </li>

        <!-- Reports -->
        <li x-data="{ open: false }">
            <button type="button" @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="text-lg fa-solid w-[40px] fa-chart-column "></i>
                Reports
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="text-sm mt-2 space-y-2">
                <a href="/vendorReport" class="block py-1 sidebarA">Vendor Report <i class="text-lg fas fa-building"></i></a>
                <a href="/1099Report" class="block py-1 sidebarA">1099 Report <i class="text-lg fas fa-list-alt"></i></a>
                <a href="/checkHistoryReport" class="block py-1 sidebarA">Check History <i class="text-lg fas fa-history"></i></a>
                <a href="/currentChecksReport" class="block py-1 sidebarA">Current Checks <i class="text-lg fas fa-search-dollar"></i></a>
                <a href="/commissionClaimReport" class="block py-1 sidebarA">Commission Claim <i class="text-lg fas fa-dollar-sign"></i></a>
                <a href="/reservationsNotPaidByALTReport" class="block py-1 sidebarA">Reservations Not Paid By ALT <i class="text-lg fas fa-money-check"></i></a>
                <a href="/reservationsPaidByALTReport" class="block py-1 sidebarA">Reservations Paid By ALT <i class="text-lg fas fa-money-bill-alt"></i></a>
                @if(auth()->user()->isAdmin())
                    <a href="/unknownReservationsReport" class="block py-1 sidebarA">Unknown Reservations <i class="text-lg fas fa-question"></i></a>
                    <a href="/bookedTripsByStateReport" class="block py-1 sidebarA">Booked Trips by State <i class="text-lg fas fa-flag-usa"></i></a>
                    <a href="" class="block py-1 sidebarA">Reservations Changes Report <i class="text-lg fas fa-tag"></i></a>
                    <a href="" class="bloack py-1 sidebarA">Reservations By Agent Report <i class="text-lg fas fa-tags"></i></a>
                @endif
                <a href="/productSalesByAgentReport" class="block py-1 sidebarA">Product Sales by Agent <i class="text-lg fas fa-poll"></i></a>
                <a href="/agentSalesByProductReport" class="block py-1 sidebarA">Agent Sales by Product <i class="text-lg fas fa-poll-h"></i></a>
                <a href="/totalSalesReport" class="block py-1 sidebarA">Total Sales <i class="text-lg fas fa-chart-area"></i></a>
                <a href="/agentExpensesReport" class="block py-1 sidebarA">Agent Expenses <i class="text-lg fas fa-money-check-alt"></i></a>
                <a href="/aliasTotalSalesReport" class="block py-1 sidebarA">Alias Total Sales <i class="text-lg fas fa-project-diagram"></i></a>
                <a href="/aliasTotalGrossCommission" class="block py-1 sidebarA">Alias Total Gross Commission <i class="text-lg fas fa-chart-pie"></i></a>
                <a href="/allReservationsByDateReport" class="block py-1 sidebarA">All Reservations By Travel Date <i class="text-lg fas fa-book-open"></i></a>
                <a href="/allTripsByTravelDateReport" class="block py-1 sidebarA">All Trips By Travel Date <i class="text-lg fas fa-plane-departure"></i></a>
                @if(auth()->user()->isAdmin())
                    <a href="/top15ExpensiveTripsReport" class="block py-1 sidebarA">Top 15 Expensive Trips <i class="text-lg fas fa-briefcase"></i></a>
                    <a href="" class="block py-1 sidebarA">Booked Reservations by Product<i class="text-lg fas fa-list"></i></a>
                    <a href="" class="block py-1 sidebarA">Cruises Report<i class="text-lg fas fa-ship"></i></a>
                    <a href="/productSalesWithDepositByAgentReport" class="block py-1 sidebarA"> Product Sales (With Deposit) by Agent <i class="text-lg fas fa-vote-yea"></i></a>
                    <a href="/finalAgencyCommissionReport" class="block py-1 sidebarA"> Final Agency Commission <i class="text-lg fas fa-wifi"></i></a>
                    <a href="/agentsReport" class="block py-1 sidebarA">Agents Report <i class="text-lg fas fa-user-circle"></i></a>
                    <a href="/bookedReservationsPerMonthReport" class="block py-1 sidebarA">Booked Reservations Per Month <i class="text-lg fas fa-list-ol"></i></a>
                    <a href="/reservationsLeadReport" class="block py-1 sidebarA">Resleads Report <i class="text-lg fas fa-grip-horizontal"></i></a>
                    <a href="/totalCommissionReport" class="block py-1 sidebarA">Total Commission Report <i class="text-lg fas fa-umbrella"></i></a>
                    <a href="/hotelOnlyReport" class="block py-1 sidebarA">Hotel Only Report <i class="text-lg fas fa-hotel"></i></a>
                    <a href="/classicVacationsReport" class="block py-1 sidebarA">Classic Vacations Report <i class="text-lg fas fa-map-pin"></i></a>
                    <a href="" class="block py-1 sidebarA">Pleasant/Evoke Report<i class="text-lg fas fa-layer-group"></i></a>
                    <a href="/appleVacationsReport" class="block py-1 sidebarA">Apple Vacations Report <i class="text-lg fas fa-apple-alt"></i></a>
                    <a href="/travelImpressionsReport" class="block py-1 sidebarA">Travel Impressions Report <i class="text-lg fas fa-plane"></i></a>
                    <a href="/vacationExpressReport" class="block py-1 sidebarA">Vacation Express Report <i class="text-lg fas fa-globe"></i></a>
                    <a href="/expediaReport" class="block py-1 sidebarA">Expedia Report <i class="text-lg fab fa-edge"></i></a>
                    <a href="" class="block py-1 sidebarA">Forms Sent Per Agent<i class="text-lg fas fa-envelope"></i></a>
                @endif
                <a href="/rebookingRateReport" class="block py-1 sidebarA">Rebooking Rate Report <i class="text-lg fas fa-percent"></i></a>
                <a href="/salesReport" class="block py-1 sidebarA">Sales Report <i class="text-lg fas fa-sign"></i></a>
            </div>
        </li>

        <!-- Dashboards -->
        <li x-data="{ open: false }">
            <button type="button" @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="text-lg fa-solid w-[40px] fa-chart-line "></i>
                Dashboards
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="text-sm mt-2 space-y-3">
                <a href="/agentDashboard" class="block py-1 sidebarA">Agent Dashboard <i class="text-lg fas fa-compass"></i></a>
                @if(auth()->user()->isAdmin())
                 <a href="/overallTaskDashboard" class="block py-1 sidebarA">Overall Task Dashboard <i class="text-lg fas fa-check-double"></i></a>
                @endif
                <a href="/myOverallTaskDashboard" class="block py-1 sidebarA">My Overall Task Dashboard <i class="text-lg fas fa-stopwatch"></i></a>
                @if(auth()->user()->isAdmin())
                    <a href="/ownersDashboard" class="block py-1 sidebarA">Owners Dashboard <i class="text-lg fas fa-map"></i></a>
                @endif
                <a href="/checkingInThisWeek" class="block py-1 sidebarA">Checking in this Week <i class="text-lg fas fa-calendar-check"></i></a>
            </div>
        </li>


        <!-- Commissions -->
        @if(auth()->user()->isAdmin())
            <li x-data="{ open: false }">
                <button type="button" @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                    <i class="text-lg fa-solid w-[40px] fa-comment-dollar "></i>
                    Commissions
                    <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
                </button>

                <div x-show="open" x-collapse class="text-sm mt-2 space-y-3"  >
                    <a href="/commissionRemittances" class="block py-1 sidebarA">Commission Remittances <i class="text-lg fas fa-download"></i></a>
                    <a href="/checkWriter" class="block py-1 sidebarA">Check Writer <i class="text-lg fas fa-pen"></i></a>
                </div>
            </li>
        @endif

        <!-- Vendors -->
        <li x-data="{ open: false }">
            <button type="button" @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="text-lg fa-solid w-[40px] fa-solid fa-building "></i>
                Vendors
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="text-sm mt-2 space-y-2"  >
                <a href="/vendor-list" class="block py-1 sidebarA">Vendor List <i class="text-lg fas fa-list"></i></a>
            </div>
        </li>

    </ul>
</div>
