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

    <ul class="space-y-3 text-md text-[#696969]" style="overflow-y: auto; height:91%;">
        <!-- Administration -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid w-[40px] fa-shield-halved"></i>
                Administration
                <span class="ml-auto" :class="open ? 'rotate-180' : ''">
                    <i class="fa-solid fa-angle-down"></i>
                </span>
            </button>

            <div x-show="open" x-collapse class="mt-2 text-sm space-y-2" >
                <a href="/system-users" class="block py-1  sidebarA">System Users <i class="fas fa-user-circle"></i> </a>
                <a href="#" class="block py-1 sidebarA">Timeline Tasks <i class="fas fa-clock"></i> </a>
                <a href="/productConfiguration" class="block py-1 sidebarA">Product Configuration  <i class="fas fa-ship"></i></a>
                <a href="#" class="block py-1 sidebarA">Agency Profile  <i class="fas fa-id-card"></i> </a>
                <a href="#" class="block py-1 sidebarA">Forms Manager <i class="fas fa-server"></i></a>
                <a href="#" class="block py-1 sidebarA">Newsletters <i class="fas fa-envelope-open-text"></i></a>
                <a href="#" class="block py-1 sidebarA">Automated Emails  <i class="fas fa-envelope"></i></a>
                <a href="#" class="block py-1 sidebarA">Itinerary <i class="fa fa-plane"></i></a>
            </div>
        </li>

        <!-- Customers -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid fa-users w-[40px]"></i>
                Customers
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="mt-2 text-sm space-y-2"  >
                <a href="/cases" class="block py-1 sidebarA">Customer List<i class="fas fa-list"></i></a>
                <a href="#" class="block py-1 sidebarA">New Customer <i class="fas fa-plus-circle"></i></a>
                <a href="#" class="block py-1 sidebarA">Invite New Customer <i class="fas fa-envelope-open-text"></i></a>
            </div>
        </li>

        <!-- Leads -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid w-[40px] fa-bookmark"></i>
                Leads
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="mt-2 text-sm space-y-2"  >
                <a href="/clients" class="block py-1 sidebarA">Current Leads <i class="fas fa-list"></i></a>
                <a href="#" class="block py-1 sidebarA">New Lead <i class="fas fa-plus-circle"></i></a>
            </div>
        </li>

        <!-- Reservations  -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid  w-[40px] fa-tag "></i>
                Reservations
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="mt-2 text-sm space-y-2"  >
                <a href="/clients" class="block py-1 sidebarA">Current Reservations <i class="fas fa-list"></i></a>
                <a href="#" class="block py-1 sidebarA">New Reservation <i class="fas fa-plus-circle"></i></a>
            </div>
        </li>

        <!-- Reports -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid w-[40px] fa-chart-column "></i>
                Reports
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="text-sm mt-2 space-y-2"  >
                <a href="#" class="block py-1 sidebarA">Vendor Report <i class="fas fa-building"></i></a>
                <a href="#" class="block py-1 sidebarA">1099 Report <i class="fas fa-list-alt"></i></a>
                <a href="#" class="block py-1 sidebarA">Check History <i class="fas fa-history"></i></a>
                <a href="#" class="block py-1 sidebarA">Current Checks <i class="fas fa-search-dollar"></i></a>
                <a href="#" class="block py-1 sidebarA">Commission Claim <i class="fas fa-dollar-sign"></i></a>
                <a href="#" class="block py-1 sidebarA">Commission Not Claimed <i class="fas fa-dollar-sign"></i></a>
                <a href="#" class="block py-1 sidebarA">Reservations Not Paid By ALT <i class="fas fa-money-check"></i></a>
                <a href="#" class="block py-1 sidebarA">Reservations Paid By ALT <i class="fas fa-money-bill-alt"></i></a>
                <a href="#" class="block py-1 sidebarA">Unknown Reservations <i class="fas fa-question"></i></a>
                <a href="#" class="block py-1 sidebarA">Booked Trips by State <i class="fas fa-flag-usa"></i></a>
                <a href="#" class="block py-1 sidebarA">Product Sales by Agent <i class="fas fa-poll"></i></a>
                <a href="#" class="block py-1 sidebarA">Agent Sales by Product <i class="fas fa-poll-h"></i></a>
                <a href="#" class="block py-1 sidebarA">Total Sales <i class="fas fa-chart-area"></i></a>
                <a href="#" class="block py-1 sidebarA">Agent Expenses <i class="fas fa-money-check-alt"></i></a>
                <a href="#" class="block py-1 sidebarA">Alias Total Sales <i class="fas fa-project-diagram"></i></a>
                <a href="#" class="block py-1 sidebarA">Alias Total Gross Commission <i class="fas fa-chart-pie"></i></a>
                <a href="#" class="block py-1 sidebarA">All Reservations By Date <i class="fas fa-book-open"></i></a>
                <a href="#" class="block py-1 sidebarA">All Trips By Travel Date <i class="fas fa-plane-departure"></i></a>
                <a href="#" class="block py-1 sidebarA">Top 15 Expensive Trips <i class="fas fa-briefcase"></i></a>
                <a href="#" class="block py-1 sidebarA"> Product Sales (With Deposit) by Agent <i class="fas fa-vote-yea"></i></a>
                <a href="#" class="block py-1 sidebarA"> Final Agency Commission <i class="fas fa-wifi"></i></a>
                <a href="#" class="block py-1 sidebarA">Agents Report <i class="fas fa-user-circle"></i></a>
                <a href="#" class="block py-1 sidebarA">Booked Reservations Per Month <i class="fas fa-list-ol"></i></a>
                <a href="#" class="block py-1 sidebarA">Resleads Report <i class="fas fa-grip-horizontal"></i></a>
                <a href="#" class="block py-1 sidebarA">Total Commission Report <i class="fas fa-umbrella"></i></a>
                <a href="#" class="block py-1 sidebarA">Hotel Only Report <i class="fas fa-hotel"></i></a>
                <a href="#" class="block py-1 sidebarA">Classic Vacations Report <i class="fas fa-map-pin"></i></a>
                <a href="#" class="block py-1 sidebarA">Apple Vacations Report <i class="fas fa-apple-alt"></i></a>
                <a href="#" class="block py-1 sidebarA">Travel Impressions Report <i class="fas fa-plane"></i></a>
                <a href="#" class="block py-1 sidebarA">Vacation Express Report <i class="fas fa-globe"></i></a>
                <a href="#" class="block py-1 sidebarA">Expedia Report <i class="fab fa-edge"></i></a>
                <a href="#" class="block py-1 sidebarA">Forms Sent Per Agent <i class="fas fa-envelope"></i></a>
                <a href="#" class="block py-1 sidebarA">Rebooking Rate Report <i class="fas fa-percent"></i></a>
                <a href="#" class="block py-1 sidebarA">Sales Report <i class="fas fa-sign"></i></a>
            </div>
        </li>

        <!-- Dashboards -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid w-[40px] fa-chart-line "></i>
                Dashboards
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="text-sm mt-2 space-y-2"  >
                <a href="/clients" class="block py-1 sidebarA">Agent Dashboard <i class="fas fa-compass"></i></a>
                <a href="#" class="block py-1 sidebarA">Overall Task Dashboard <i class="fas fa-check-double"></i></a>
                <a href="#" class="block py-1 sidebarA">My Overall Task Dashboard <i class="fas fa-stopwatch"></i></a>
                <a href="#" class="block py-1 sidebarA">Owners Dashboard <i class="fas fa-map"></i></a>
                <a href="#" class="block py-1 sidebarA">Checking in this Week <i class="fas fa-calendar-check"></i></a>
            </div>
        </li>


    <!-- Commissions -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid w-[40px] fa-comment-dollar "></i>
                Commissions
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="text-sm mt-2 space-y-2"  >
                <a href="/clients" class="block py-1 sidebarA">Commission Remittances <i class="fas fa-download"></i></a>
                <a href="#" class="block py-1 sidebarA">Check Writer <i class="fas fa-pen"></i></a>
            </div>
        </li>

        <!-- Commissions -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid w-[40px] fa-solid fa-building "></i>
                Vendors
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="text-sm mt-2 space-y-2"  >
                <a href="/clients" class="block py-1 sidebarA">Vendor List <i class="fas fa-list"></i></a>
            </div>
        </li>

    </ul>
</div>
