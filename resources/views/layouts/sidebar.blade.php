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
                <a href="#" class="block py-1  sidebarA">System Users </a>
                <a href="#" class="block py-1 sidebarA">Timeline Tasks </a>
                <a href="productConfiguration" class="block py-1 sidebarA">Product Configuration </a>
                <a href="#" class="block py-1 sidebarA">Agency Profile </a>
                <a href="#" class="block py-1 sidebarA">Forms Manager </a>
                <a href="#" class="block py-1 sidebarA">Newsletters </a>
                <a href="#" class="block py-1 sidebarA">Automated Emails </a>
                <a href="#" class="block py-1 sidebarA">Itinerary </a>
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
                <a href="/cases" class="block py-1 sidebarA">Customer List</a>
                <a href="#" class="block py-1 sidebarA">New Customer </a>
                <a href="#" class="block py-1 sidebarA">Invite New Customer</a>
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
                <a href="/clients" class="block py-1 sidebarA">Current Leads </a>
                <a href="#" class="block py-1 sidebarA">New Lead </a>
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
                <a href="/clients" class="block py-1 sidebarA">Current Reservations </a>
                <a href="#" class="block py-1 sidebarA">New Reservation </a>
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
                <a href="#" class="block py-1 sidebarA">Vendor Report </a>
                <a href="#" class="block py-1 sidebarA">1099 Report </a>
                <a href="#" class="block py-1 sidebarA">Check History </a>
                <a href="#" class="block py-1 sidebarA">Current Checks </a>
                <a href="#" class="block py-1 sidebarA">Commission Claim </a>
                <a href="#" class="block py-1 sidebarA">Commission Not Claimed </a>
                <a href="#" class="block py-1 sidebarA">Reservations Not Paid By ALT </a>
                <a href="#" class="block py-1 sidebarA">Reservations Paid By ALT </a>
                <a href="#" class="block py-1 sidebarA">Unknown Reservations </a>
                <a href="#" class="block py-1 sidebarA">Booked Trips by State </a>
                <a href="#" class="block py-1 sidebarA">Product Sales by Agent </a>
                <a href="#" class="block py-1 sidebarA">Agent Sales by Product </a>
                <a href="#" class="block py-1 sidebarA">Total Sales </a>
                <a href="#" class="block py-1 sidebarA">Agent Expenses </a>
                <a href="#" class="block py-1 sidebarA">Alias Total Sales </a>
                <a href="#" class="block py-1 sidebarA">Alias Total Gross Commission</a>
                <a href="#" class="block py-1 sidebarA">All Reservations By Date</a>
                <a href="#" class="block py-1 sidebarA">All Trips By Travel Date </a>
                <a href="#" class="block py-1 sidebarA">Top 15 Expensive Trips </a>
                <a href="#" class="block py-1 sidebarA"> Product Sales (With Deposit) by Agent </a>
                <a href="#" class="block py-1 sidebarA"> Final Agency Commission </a>
                <a href="#" class="block py-1 sidebarA">Agents Report </a>
                <a href="#" class="block py-1 sidebarA">Booked Reservations Per Month</a>
                <a href="#" class="block py-1 sidebarA">Resleads Report </a>
                <a href="#" class="block py-1 sidebarA">Total Commission Report </a>
                <a href="#" class="block py-1 sidebarA">Hotel Only Report </a>
                <a href="#" class="block py-1 sidebarA">Classic Vacations Report </a>
                <a href="#" class="block py-1 sidebarA">Apple Vacations Report </a>
                <a href="#" class="block py-1 sidebarA">Travel Impressions Report </a>
                <a href="#" class="block py-1 sidebarA">Vacation Express Report </a>
                <a href="#" class="block py-1 sidebarA">Expedia Report </a>
                <a href="#" class="block py-1 sidebarA">Forms Sent Per Agent </a>
                <a href="#" class="block py-1 sidebarA">Rebooking Rate Report</a>
                <a href="#" class="block py-1 sidebarA">Sales Report </a>
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
                <a href="/clients" class="block py-1 sidebarA">Agent Dashboard </a>
                <a href="#" class="block py-1 sidebarA">Overall Task Dashboard </a>
                <a href="#" class="block py-1 sidebarA">My Overall Task Dashboard </a>
                <a href="#" class="block py-1 sidebarA">Owners Dashboard </a>
                <a href="#" class="block py-1 sidebarA">Checking in this Week </a>
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
                <a href="/clients" class="block py-1 sidebarA">Commission Remittances </a>
                <a href="#" class="block py-1 sidebarA">Check Writer </a>
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
                <a href="/clients" class="block py-1 sidebarA">Vendor List </a>
            </div>
        </li>

    </ul>
</div>
