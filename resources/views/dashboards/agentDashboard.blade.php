<x-app-layout>

    <div class=" space-y-6">

        <div id="dashboard-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="dashboard-card bg-white shadow rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold  flex items-center gap-2 text-xl">
                        <i class="far fa-clock text-xl text-red-500"></i>
                        High Priority Tasks
                    </h3>
                    <i class="fas fa-expand text-base expand-btn"></i>
                </div>

                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <p class="text-xl mt-1">Past Due</p>
                        <p class="text-8xl leading-none text-red-600">1</p>
                        <p class="text-sm text-gray-600 mt-1">Tasks</p>
                    </div>
                    <div>
                        <p class="text-xl mt-1">Due Today</p>
                        <p class="text-8xl text-orange-400">0</p>
                        <p class="text-sm text-gray-600 mt-1">Tasks</p>
                    </div>
                    <div>
                        <p class="text-xl mt-1">Two Weeks</p>
                        <p class="text-4xl text-blue-600">0</p>
                        <p class="text-sm text-gray-600 mt-1">Tasks</p>
                    </div>
                    <div>
                        <p class="text-xl mt-1">30 Days</p>
                        <p class="text-4xl text-blue-600">0</p>
                        <p class="text-sm text-gray-600 mt-1">Tasks</p>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <a href="#" class="text-base">View All</a>
                </div>
            </div>

            
            <div class="dashboard-card bg-white shadow rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold  flex items-center gap-2 text-xl">
                        <i class="fas fa-dollar-sign font-bold text-xl text-blue-500"></i>
                        Recent Commissions
                    </h3>
                    <i class="fas fa-expand text-base expand-btn"></i>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <select name="days" id="days" class="w-24 border-b focus:outline-none">
                        <option value="-1">30 Days</option>
                        <option value="90Days">90 Days</option>
                        <option value="180Days">180 Days</option>
                        <option value="oneYear">1 Year</option>
                        <option value="all">All</option>
                    </select>
                    <span class="bg-green-100 text-green-700 text-sm px-3 py-1 rounded">
                        Total Agent: $0.00
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-t-2 border-[#dee2e6]">
                                <th class="px-3 py-2 text-left font-bold">Check Date</th>
                                <th class="px-3 py-2 text-left font-bold">Reservation Number</th>
                                <th class="px-3 py-2 text-left font-bold">Customer Name</th>
                                <th class="px-3 py-2 text-left font-bold">Agent Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-gray-400 py-6">
                                    No data available in table
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end text-sm text-gray-400 mt-4">
                    Previous &nbsp;  &nbsp; Next
                </div>
            </div>
        </div>

        
        <div id="dashboard-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            
            <div class="dashboard-card bg-white shadow rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700 flex items-center gap-2 text-xl">
                        <i class="far fa-calendar-alt text-blue-500 text-xl"></i>
                        Upcoming Reservations (90 days)
                    </h3>
                    <i class="fas fa-expand text-base expand-btn"></i>
                </div>

                
                <div class="space-y-4">
                    <div class="flex flex-col items-center justify-center gap-7">
                        <p class="text-base mb-1">View Details</p>
                        <p class="text-xl mb-1">No Upcoming Reservations.</p>
                    </div>
                </div>
            </div>

          
            <div class="dashboard-card bg-white shadow rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold  flex items-center gap-2 text-xl">
                        <i class="fas fa-chart-line text-blue-500"></i>
                        Total Sales
                    </h3>
                    <i class="fas fa-expand text-base expand-btn"></i>
                </div>

               
            </div>

        </div>
    </div>
</x-app-layout>
