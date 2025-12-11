@php
    $user = auth()->user();
@endphp
<!-- SIDEBAR -->
<div
    class="fixed inset-y-0 left-0 bg-white shadow-lg w-64 transform transition-transform duration-500 z-40"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div class="p-4.5 border-b bg-[#f18325] text-[#FFF] border-[#CCCCCC] font-semibold text-xl">
        Archer Luxury Travel
    </div>

    <ul class="space-y-3 text-lg text-[#696969]">
        <!-- Administration -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid w-[40px] fa-shield-halved"></i>
                Administration
                <span class="ml-auto" :class="open ? 'rotate-180' : ''">
                    <i class="fa-solid fa-angle-down"></i>
                </span>
            </button>

            <div x-show="open" x-collapse class="ml-10 mt-2 space-y-2">
                <a href="#" class="block py-1 hover:text-blue-600">System Users </a>
                <a href="#" class="block py-1 hover:text-blue-600">Timeline Tasks </a>
                <a href="#" class="block py-1 hover:text-blue-600">Product Configuration </a>
                <a href="#" class="block py-1 hover:text-blue-600">Agency Profile </a>
                <a href="#" class="block py-1 hover:text-blue-600">Forms Manager </a>
                <a href="#" class="block py-1 hover:text-blue-600">Newsletters </a>
                <a href="#" class="block py-1 hover:text-blue-600">Automated Emails </a>
                <a href="#" class="block py-1 hover:text-blue-600">Itinerary </a>
            </div>
        </li>

        <!-- Customers -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid fa-users w-[40px]"></i>
                Customers
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="ml-10 mt-2 space-y-2">
                <a href="/cases" class="block py-1 hover:text-blue-600">Customer List</a>
                <a href="#" class="block py-1 hover:text-blue-600">New Customer </a>
                <a href="#" class="block py-1 hover:text-blue-600">Invite New Customer</a>
            </div>
        </li>

        <!-- Leads -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid w-[40px] fa-bookmark"></i>
                Leads
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="ml-10 mt-2 space-y-2">
                <a href="/clients" class="block py-1 hover:text-blue-600">Current Leads </a>
                <a href="#" class="block py-1 hover:text-blue-600">New Lead </a>
            </div>
        </li>

        <!-- Reservations  -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid  w-[40px] fa-tag "></i>
                Reservations
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="ml-10 mt-2 space-y-2">
                <a href="/clients" class="block py-1 hover:text-blue-600">Current Reservations </a>
                <a href="#" class="block py-1 hover:text-blue-600">New Reservation </a>
            </div>
        </li>

        <!-- Reports -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid w-[40px] fa-chart-column "></i>
                Reports
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="ml-10 mt-2 space-y-2">
                <a href="#" class="block py-1 hover:text-blue-600">Vendor Report </a>
                <a href="#" class="block py-1 hover:text-blue-600">1099 Report </a>
                <a href="#" class="block py-1 hover:text-blue-600">Check History </a>
                <a href="#" class="block py-1 hover:text-blue-600">Current Checks </a>
                <a href="#" class="block py-1 hover:text-blue-600">Commission Claim </a>
                <a href="#" class="block py-1 hover:text-blue-600">Commission Not Claimed </a>
                <a href="#" class="block py-1 hover:text-blue-600">Reservations Not Paid By ALT </a>
                <a href="#" class="block py-1 hover:text-blue-600">Reservations Paid By ALT </a>
                <a href="#" class="block py-1 hover:text-blue-600">Unknown Reservations </a>
                <a href="#" class="block py-1 hover:text-blue-600">Booked Trips by State </a>
                <a href="#" class="block py-1 hover:text-blue-600">Product Sales by Agent </a>
                <a href="#" class="block py-1 hover:text-blue-600">Agent Sales by Product </a>
                <a href="#" class="block py-1 hover:text-blue-600">Total Sales </a>
                <a href="#" class="block py-1 hover:text-blue-600">Agent Expenses </a>
                <a href="#" class="block py-1 hover:text-blue-600">Alias Total Sales </a>
                <a href="#" class="block py-1 hover:text-blue-600">Alias Total Gross Commission</a>
                <a href="#" class="block py-1 hover:text-blue-600">All Reservations By Date</a>
                <a href="#" class="block py-1 hover:text-blue-600">All Trips By Travel Date </a>
                <a href="#" class="block py-1 hover:text-blue-600">Top 15 Expensive Trips </a>
                <a href="#" class="block py-1 hover:text-blue-600"> Product Sales (With Deposit) by Agent </a>
                <a href="#" class="block py-1 hover:text-blue-600"> Final Agency Commission </a>
                <a href="#" class="block py-1 hover:text-blue-600">Agents Report </a>
                <a href="#" class="block py-1 hover:text-blue-600">Booked Reservations Per Month</a>
                <a href="#" class="block py-1 hover:text-blue-600">Resleads Report </a>
                <a href="#" class="block py-1 hover:text-blue-600">Total Commission Report </a>
                <a href="#" class="block py-1 hover:text-blue-600">Hotel Only Report </a>
                <a href="#" class="block py-1 hover:text-blue-600">Classic Vacations Report </a>
                <a href="#" class="block py-1 hover:text-blue-600">Apple Vacations Report </a>
                <a href="#" class="block py-1 hover:text-blue-600">Travel Impressions Report </a>
                <a href="#" class="block py-1 hover:text-blue-600">Vacation Express Report </a>
                <a href="#" class="block py-1 hover:text-blue-600">Expedia Report </a>
                <a href="#" class="block py-1 hover:text-blue-600">Forms Sent Per Agent </a>
                <a href="#" class="block py-1 hover:text-blue-600">Rebooking Rate Report</a>
                <a href="#" class="block py-1 hover:text-blue-600">Sales Report </a>
            </div>
        </li>

        <!-- Dashboards -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid w-[40px] fa-chart-line "></i>
                Dashboards
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="ml-10 mt-2 space-y-2">
                <a href="/clients" class="block py-1 hover:text-blue-600">Agent Dashboard </a>
                <a href="#" class="block py-1 hover:text-blue-600">Overall Task Dashboard </a>
                <a href="#" class="block py-1 hover:text-blue-600">My Overall Task Dashboard </a>
                <a href="#" class="block py-1 hover:text-blue-600">Owners Dashboard </a>
                <a href="#" class="block py-1 hover:text-blue-600">Checking in this Week </a>
            </div>
        </li>


    <!-- Commissions -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid w-[40px] fa-comment-dollar "></i>
                Commissions
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="ml-10 mt-2 space-y-2">
                <a href="/clients" class="block py-1 hover:text-blue-600">Commission Remittances </a>
                <a href="#" class="block py-1 hover:text-blue-600">Check Writer </a>
            </div>
        </li>

        <!-- Commissions -->
        <li x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-3 py-2 cursor-pointer rounded hover:bg-gray-200">
                <i class="fa-solid w-[40px] fa-solid fa-building "></i>
                Vendors
                <span class="ml-auto" :class="open ? 'rotate-180' : ''"><i class="fa-solid fa-angle-down"></i></span>
            </button>

            <div x-show="open" x-collapse class="ml-10 mt-2 space-y-2">
                <a href="/clients" class="block py-1 hover:text-blue-600">Vendor List </a>
            </div>
        </li>

    </ul>
</div>
