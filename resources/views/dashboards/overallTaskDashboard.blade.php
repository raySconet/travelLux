<x-app-layout>

    <div class=" space-y-6">
        <div class="flex flex-col gap-1">
            <select name="agents" id="agents" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 bg-[#fff]">
                <option value="-1" onclick="showLoaderOnSubmit()">--All Agents--</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" onclick="showLoaderOnSubmit()">
                        {{ $user->fname . ' ' . $user->lname }}
                    </option>
                @endforeach
            </select>
        </div>

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
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="High" data-period="past_due" onclick="showLoaderOnSubmit()">
                            <p class="text-9xl leading-none text-[#e53935]">
                                <span id="high-past-due">
                                    {{ $highPriority->past_due ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Due Today</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="High" data-period="due_today" onclick="showLoaderOnSubmit()">
                            <p class="text-9xl leading-none text-[#f9a825]">
                                <span id="high-due-today">
                                    {{ $highPriority->due_today ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Two Weeks</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="High" data-period="two_weeks" onclick="showLoaderOnSubmit()">
                            <p class="text-5xl leading-none text-[#1565c0]">
                                <span id="high-two-weeks">
                                    {{ $highPriority->two_weeks ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">30 Days</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="High" data-period="thirty_days" onclick="showLoaderOnSubmit()">
                            <p class="text-5xl leading-none text-[#1565c0]">
                                <span id="high-thirty-days">
                                    {{ $highPriority->thirty_days ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <a href="#" class="text-base viewAll task-link-overall" data-priority="High" data-period="all" onclick="showLoaderOnSubmit()">
                        View All
                    </a>
                </div>
                <div class="task-details hidden mt-6"></div>
            </div>

            
            <div class="dashboard-card bg-white shadow sm:rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold  flex items-center gap-2 text-xl">
                        <i class="far fa-clock text-xl text-[#ffc107]"></i>
                        Medium Priority Tasks
                    </h3>
                    <i title="Fullscreen" class="fas fa-expand text-base expand-btn cursor-pointer"></i>
                </div>

                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Past Due</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="Medium" data-period="past_due" onclick="showLoaderOnSubmit()">
                            <p class="text-9xl leading-none text-[#e53935]">
                                <span id="medium-past-due">
                                    {{ $mediumPriority->past_due ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Due Today</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="Medium" data-period="due_today" onclick="showLoaderOnSubmit()">
                            <p class="text-9xl leading-none text-[#f9a825]">
                                <span id="medium-due-today">
                                    {{ $mediumPriority->due_today ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Two Weeks</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="Medium" data-period="two_weeks" onclick="showLoaderOnSubmit()">
                            <p class="text-5xl leading-none text-[#1565c0]">
                                <span id="medium-two-weeks">
                                    {{ $mediumPriority->two_weeks ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">30 Days</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="Medium" data-period="thirty_days" onclick="showLoaderOnSubmit()">
                            <p class="text-5xl leading-none text-[#1565c0]">
                                <span id="medium-thirty-days">
                                    {{ $mediumPriority->thirty_days ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <a href="#" class="text-base viewAll task-link-overall" data-priority="Medium" data-period="all" onclick="showLoaderOnSubmit()">
                        View All
                    </a>
                </div>
                <div class="task-details hidden mt-6"></div>
            </div>
        </div>

        
        <div id="dashboard-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            
            <div class="dashboard-card bg-white shadow sm:rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold  flex items-center gap-2 text-xl">
                        <i class="far fa-clock text-xl text-[#50c878]"></i>
                        Low Priority Tasks
                    </h3>
                    <i title="Fullscreen" class="fas fa-expand text-base expand-btn cursor-pointer"></i>
                </div>

                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Past Due</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="Low" data-period="past_due" onclick="showLoaderOnSubmit()">
                            <p class="text-9xl leading-none text-[#e53935]">
                                <span id="low-past-due">
                                    {{ $lowPriority->past_due ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Due Today</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="Low" data-period="due_today" onclick="showLoaderOnSubmit()">
                            <p class="text-9xl leading-none text-[#f9a825]">
                                <span id="low-due-today">
                                    {{ $lowPriority->due_today ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Two Weeks</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="Low" data-period="two_weeks" onclick="showLoaderOnSubmit()">
                            <p class="text-5xl text-[#1565c0] leading-none">
                                <span id="low-two-weeks">
                                    {{ $lowPriority->two_weeks ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">30 Days</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="Low" data-period="thirty_days" onclick="showLoaderOnSubmit()">
                            <p class="text-5xl text-[#1565c0] leading-none">
                                <span id="low-thirty-days">
                                    {{ $lowPriority->thirty_days ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <a href="#" class="text-base viewAll task-link-overall" data-priority="Low" data-period="all" onclick="showLoaderOnSubmit()">
                        View All
                    </a>
                </div>
                <div class="task-details hidden mt-6"></div>
            </div>

          
            <div class="dashboard-card bg-white shadow sm:rounded-lg p-9">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold  flex items-center gap-2 text-xl">
                        <i class="far fa-clock text-xl text-[#bdbdbd]"></i>
                        All Tasks
                    </h3>
                    <i title="Fullscreen" class="fas fa-expand text-base expand-btn cursor-pointer"></i>
                </div>

                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Past Due</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="All" data-period="past_due" onclick="showLoaderOnSubmit()">
                            <p class="text-9xl leading-none text-[#e53935]">
                                <span id="all-past-due">
                                    {{ $allTasks->past_due ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Due Today</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="All" data-period="due_today" onclick="showLoaderOnSubmit()">
                            <p class="text-9xl leading-none text-[#f9a825]">
                                <span id="all-due-today">
                                    {{ $allTasks->due_today ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">Two Weeks</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="All" data-period="two_weeks" onclick="showLoaderOnSubmit()">
                            <p class="text-5xl text-[#1565c0] leading-none">
                                <span id="all-two-weeks">
                                    {{ $allTasks->two_weeks ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                    <div class="hover:shadow-2xl transition-shadow duration-200">
                        <p class="text-xl mt-1">30 Days</p>
                        <a href="#" class="task-link-overall hover:shadow-lg transition-shadow duration-200" data-priority="All" data-period="thirty_days" onclick="showLoaderOnSubmit()">
                            <p class="text-5xl text-[#1565c0] leading-none">
                                <span id="all-thirty-days">
                                    {{ $allTasks->thirty_days ?? 0 }}
                                </span>
                            </p>
                        </a>
                        <p class="text-sm text-[#212121] mt-1">Tasks</p>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <a href="#" class="text-base viewAll task-link-overall" data-priority="All" data-period="all" onclick="showLoaderOnSubmit()">
                        View All
                    </a>
                </div>
                <div class="task-details hidden mt-6"></div>
            </div>

        </div>
    </div>
</x-app-layout>
<x-delete-modal />