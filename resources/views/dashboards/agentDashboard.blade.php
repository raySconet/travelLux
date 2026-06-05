<x-app-layout>

    <div class=" space-y-6">

        <div id="dashboard-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="dashboard-card bg-white shadow sm:rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold  flex items-center gap-2 text-xl">
                        <i class="far fa-clock text-xl text-[#ff5722]"></i>
                        High Priority Tasks
                    </h3>
                    <i title="Fullscreen" class="fas fa-expand text-base expand-btn cursor-pointer"></i>
                </div>

                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Past Due</p>

                        <a href="#" class="task-link hover:shadow-lg transition-shadow duration-200" data-priority="High" data-period="past_due" onclick="showLoaderOnSubmit()">
                            <p class="text-9xl leading-none text-[#e53935]">
                                {{ $highPriority->past_due ?? 0 }}
                            </p>
                        </a>

                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Due Today</p>
                        <a href="#" class="task-link" data-priority="High" data-period="due_today" onclick="showLoaderOnSubmit()">
                            <p class="text-9xl text-[#f9a825]">
                                {{ $highPriority->due_today ?? 0 }}
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Two Weeks</p>
                        <a href="#" class="task-link" data-priority="High" data-period="two_weeks" onclick="showLoaderOnSubmit()">
                            <p class="text-5xl text-[#1565c0]">
                                {{ $highPriority->two_weeks ?? 0 }}
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">30 Days</p>
                        <a href="#" class="task-link" data-priority="High" data-period="thirty_days" onclick="showLoaderOnSubmit()">
                            <p class="text-5xl text-[#1565c0]">
                                {{ $highPriority->thirty_days ?? 0 }}
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <a href="#" class="text-base viewAll task-link" data-priority="High" data-period="all" onclick="showLoaderOnSubmit()">
                        View All
                    </a>
                </div>
                <div class="task-details hidden mt-6"></div>

            </div>
            
            <div class="dashboard-card bg-white shadow sm:rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold  flex items-center gap-2 text-xl">
                        <i class="fas fa-dollar-sign font-bold text-xl text-blue-500"></i>
                        Recent Commissions
                    </h3>
                    <i title="Fullscreen" class="fas fa-expand text-base expand-btn cursor-pointer"></i>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <select id="commissionRange" class="w-24 border-b focus:outline-none">
                        <option value="30days">30 Days</option>
                        <option value="90days">90 Days</option>
                        <option value="180days">180 Days</option>
                        <option value="1year">1 Year</option>
                        <option value="all">All</option>
                    </select>

                    <span class="agentDashboardTotalAgentCommissionAmount bg-green-100 text-green-700 text-sm px-3 py-1 rounded">
                        $0.00
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

                        <tbody id="recentCommissionsBody"></tbody>
                    </table>
                </div>

                <div id="recentCommissionsPagination" class="flex justify-end gap-3 mt-4"></div>
            </div>
        </div>

        
        <div id="dashboard-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            
            <div class="dashboard-card bg-white shadow sm:rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700 flex items-center gap-2 text-xl">
                        <i class="far fa-calendar-alt text-blue-500 text-xl"></i>
                        Upcoming Reservations (90 days)
                    </h3>
                    <i title="Fullscreen" class="fas fa-expand text-base expand-btn cursor-pointer"></i>
                </div>

                <div id="upcomingReservationsContainer">

                    <div class="text-center mt-4">
                        <a href="#" class="text-base upcoming-link hidden">
                            View Details
                        </a>

                        <span class="no-upcoming hidden text-gray-500">
                            No upcoming reservations.
                        </span>
                    </div>

                    <div id="upcomingReservationsChartContainer" style="height:300px;"></div>
                    
                </div>

                <div class="upcoming-details hidden mt-6"></div>
            </div>

          
            <div class="dashboard-card bg-white shadow sm:rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold  flex items-center gap-2 text-xl">
                        <i class="fas fa-chart-line text-[#2196f3]"></i>
                        Total Sales
                    </h3>
                    <i title="Fullscreen" class="fas fa-expand text-base expand-btn cursor-pointer"></i>
                </div>

               <div id="totalSalesChartContainer" style="height:300px;"></div>
            </div>

        </div>
    </div>
</x-app-layout>
<x-delete-modal />