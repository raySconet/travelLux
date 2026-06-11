<x-app-layout>

    <div class=" space-y-6">
        <div id="dashboard-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="dashboard-card bg-white shadow sm:rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold flex items-center gap-2 text-xl">
                        <i class="fas fa-chart-line text-[#2196f3]"></i>
                        Agency Total Sales
                    </h3>
                    <i title="Fullscreen" class="fas fa-expand text-base expand-btn cursor-pointer"></i>
                </div>

                <div id="ownersDashboardAgencyTotalSalesChartContainer" style="height:300px;"></div>
                <div id="ownersDashboardAgencyTotalSalesChartContainerFullScreen" class="hidden" style="height:500px;"></div>
            </div>

            
            <div class="dashboard-card bg-white shadow sm:rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold  flex items-center gap-2 text-xl">
                        <i class="fas fa-birthday-cake text-xl text-[#2196f3]"></i>
                        Agent Birthdays
                    </h3>
                    <i title="Fullscreen" class="fas fa-expand text-base expand-btn cursor-pointer"></i>
                </div>

                <div class="grid grid-cols-2 gap-4 text-center owners-birthday-grid">

                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Today</p>

                        <a href="#" class="birthday-link" data-range="today" onclick="showLoaderOnSubmit()">
                            <p id="birthday-today" class="text-9xl leading-none text-[#1565c0]">0</p>
                        </a>

                        <p class="text-sm text-[#212121] mt-1">Birthdays</p>
                    </div>

                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">7 Days</p>

                        <a href="#" class="birthday-link" data-range="week" onclick="showLoaderOnSubmit()">
                            <p id="birthday-week" class="text-9xl text-[#1565c0]">0</p>
                        </a>

                        <p class="text-sm text-[#212121] mt-1">Birthdays</p>
                    </div>

                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">30 Days</p>

                        <a href="#" class="birthday-link" data-range="month" onclick="showLoaderOnSubmit()">
                            <p id="birthday-month" class="text-5xl text-[#1565c0]">0</p>
                        </a>

                        <p class="text-sm text-[#212121] mt-1">Birthdays</p>
                    </div>

                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">6 Months</p>

                        <a href="#" class="birthday-link" data-range="sixMonths" onclick="showLoaderOnSubmit()">
                            <p id="birthday-sixmonths" class="text-5xl text-[#1565c0]">0</p>
                        </a>

                        <p class="text-sm text-[#212121] mt-1">Birthdays</p>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <a href="#" class="birthday-link text-base" data-range="all" onclick="showLoaderOnSubmit()">
                        View All
                    </a>
                </div>

                <div class="birthday-details hidden mt-6"></div>
            </div>
        </div>

        
        <div id="dashboard-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            
            <div class="dashboard-card bg-white shadow sm:rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold  flex items-center gap-2 text-xl">
                        <i class="fas fa-birthday text-xl text-[#2196f3]"></i>
                        Customer Birthdays
                    </h3>
                    <i title="Fullscreen" class="fas fa-expand text-base expand-btn cursor-pointer"></i>
                </div>

                <div class="grid grid-cols-2 gap-4 text-center customer-birthday-grid">

                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Today</p>

                        <a href="#" class="customer-birthday-link" data-range="today" onclick="showLoaderOnSubmit()">
                            <p id="customer-birthday-today" class="text-9xl leading-none text-[#1565c0]">0</p>
                        </a>

                        <p class="text-sm text-[#212121] mt-1">Birthdays</p>
                    </div>

                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">7 Days</p>

                        <a href="#" class="customer-birthday-link" data-range="week" onclick="showLoaderOnSubmit()">
                            <p id="customer-birthday-week" class="text-9xl text-[#1565c0]">0</p>
                        </a>

                        <p class="text-sm text-[#212121] mt-1">Birthdays</p>
                    </div>

                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">30 Days</p>

                        <a href="#" class="customer-birthday-link" data-range="month" onclick="showLoaderOnSubmit()">
                            <p id="customer-birthday-month" class="text-5xl text-[#1565c0]">0</p>
                        </a>

                        <p class="text-sm text-[#212121] mt-1">Birthdays</p>
                    </div>

                    <div class="hover:shadow-2xl transition-shadow duration-200" onclick="showLoaderOnSubmit()">
                        <p class="text-xl mt-1">6 Months</p>

                        <a href="#" class="customer-birthday-link" data-range="sixMonths">
                            <p id="customer-birthday-sixmonths" class="text-5xl text-[#1565c0]">0</p>
                        </a>

                        <p class="text-sm text-[#212121] mt-1">Birthdays</p>
                    </div>

                </div>

                <div class="text-center mt-6">
                    <a href="#" class="customer-birthday-link text-base" data-range="all" onclick="showLoaderOnSubmit()">
                        View All
                    </a>
                </div>

                <div class="customer-birthday-details hidden mt-6"></div>
            </div>

            <div class="dashboard-card bg-white shadow sm:rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold flex items-center gap-2 text-xl">
                        <i class="fas fa-glass-cheers text-xl text-[#2196f3]"></i>
                        Customer Anniversaries
                    </h3>
                    <i title="Fullscreen" class="fas fa-expand text-base expand-btn cursor-pointer"></i>
                </div>

                <div class="grid grid-cols-2 gap-4 text-center customer-anniversary-grid">

                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Today</p>
                        <a href="#" class="customer-anniversary-link" data-range="today" onclick="showLoaderOnSubmit()">
                            <p id="customer-anniversary-today" class="text-9xl leading-none text-[#1565c0]">0</p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Anniversaries</p>
                    </div>

                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">7 Days</p>
                        <a href="#" class="customer-anniversary-link" data-range="week" onclick="showLoaderOnSubmit()">
                            <p id="customer-anniversary-week" class="text-9xl text-[#1565c0]">0</p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Anniversaries</p>
                    </div>

                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">30 Days</p>
                        <a href="#" class="customer-anniversary-link" data-range="month" onclick="showLoaderOnSubmit()">
                            <p id="customer-anniversary-month" class="text-5xl text-[#1565c0]">0</p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Anniversaries</p>
                    </div>

                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">6 Months</p>
                        <a href="#" class="customer-anniversary-link" data-range="sixMonths" onclick="showLoaderOnSubmit()">
                            <p id="customer-anniversary-sixmonths" class="text-5xl text-[#1565c0]">0</p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Anniversaries</p>
                    </div>

                </div>

                <div class="text-center mt-6">
                    <a href="#" class="customer-anniversary-link text-base" data-range="all" onclick="showLoaderOnSubmit()">
                        View All
                    </a>
                </div>

                <div class="customer-anniversary-details hidden mt-6"></div>
            </div>

        </div>
    </div>
</x-app-layout>
