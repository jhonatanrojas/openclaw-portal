<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management - OpenClaw Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <i class="fas fa-tasks text-blue-600 text-2xl mr-3"></i>
                        <h1 class="text-2xl font-bold text-gray-900">Task Management</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/dashboard" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-home mr-1"></i> Dashboard
                        </a>
                        <a href="/tasks/my-tasks" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-user mr-1"></i> My Tasks
                        </a>
                        <a href="/tasks/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-1"></i> New Task
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-tasks text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Tasks</p>
                            <p class="text-2xl font-bold" id="total-tasks">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Pending</p>
                            <p class="text-2xl font-bold" id="pending-tasks">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Completed</p>
                            <p class="text-2xl font-bold" id="completed-tasks">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="bg-red-100 p-3 rounded-full">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Overdue</p>
                            <p class="text-2xl font-bold" id="overdue-tasks">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <div class="flex flex-wrap gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status-filter" class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="blocked">Blocked</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select id="priority-filter" class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="category-filter" class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All</option>
                            <option value="backend">Backend</option>
                            <option value="frontend">Frontend</option>
                            <option value="devops">DevOps</option>
                            <option value="documentation">Documentation</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" id="search-filter" placeholder="Search tasks..." 
                               class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="self-end">
                        <button id="apply-filters" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Apply Filters
                        </button>
                        <button id="reset-filters" class="ml-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tasks Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">All Tasks</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tasks-table-body" class="bg-white divide-y divide-gray-200">
                            <!-- Tasks will be loaded here -->
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500" id="pagination-info">
                            Showing 0 of 0 tasks
                        </div>
                        <div class="flex space-x-2" id="pagination-controls">
                            <!-- Pagination will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let currentPage = 1;
        let totalPages = 1;
        let filters = {};

        // Load tasks on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadTasks();
            loadStats();
            
            // Setup filter events
            document.getElementById('apply-filters').addEventListener('click', applyFilters);
            document.getElementById('reset-filters').addEventListener('click', resetFilters);
            document.getElementById('search-filter').addEventListener('keyup', function(e) {
                if (e.key === 'Enter') applyFilters();
            });
        });

        function loadTasks(page = 1) {
            const params = new URLSearchParams({
                page: page,
                per_page: 20,
                ...filters
            });

            fetch(`/api/tasks?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderTasks(data.data.data);
                        updatePagination(data.data);
                        updateStats(data.stats);
                    }
                })
                .catch(error => console.error('Error loading tasks:', error));
        }

        function loadStats() {
            fetch('/api/tasks/stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateStats(data.data);
                    }
                })
                .catch(error => console.error('Error loading stats:', error));
        }

        function renderTasks(tasks) {
            const tbody = document.getElementById('tasks-table-body');
            tbody.innerHTML = '';

            if (tasks.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p class="text-lg">No tasks found</p>
                            <p class="text-sm mt-2">Create your first task to get started</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tasks.forEach(task => {
                const row = document.createElement('tr');
                
                // Status badge
                let statusClass = 'bg-gray-100 text-gray-800';
                let statusIcon = 'fa-clock';
                switch(task.status) {
                    case 'in_progress':
                        statusClass = 'bg-blue-100 text-blue-800';
                        statusIcon = 'fa-spinner';
                        break;
                    case 'completed':
                        statusClass = 'bg-green-100 text-green-800';
                        statusIcon = 'fa-check-circle';
                        break;
                    case 'blocked':
                        statusClass = 'bg-red-100 text-red-800';
                        statusIcon = 'fa-ban';
                        break;
                }

                // Priority badge
                let priorityClass = 'bg-gray-100 text-gray-800';
                switch(task.priority) {
                    case 'high':
                        priorityClass = 'bg-orange-100 text-orange-800';
                        break;
                    case 'critical':
                        priorityClass = 'bg-red-100 text-red-800';
                        break;
                    case 'low':
                        priorityClass = 'bg-green-100 text-green-800';
                        break;
                }

                // Category badge
                let categoryClass = 'bg-gray-100 text-gray-800';
                switch(task.category) {
                    case 'backend':
                        categoryClass = 'bg-purple-100 text-purple-800';
                        break;
                    case 'frontend':
                        categoryClass = 'bg-blue-100 text-blue-800';
                        break;
                    case 'devops':
                        categoryClass = 'bg-yellow-100 text-yellow-800';
                        break;
                    case 'documentation':
                        categoryClass = 'bg-green-100 text-green-800';
                        break;
                }

                // Format due date
                let dueDate = 'No due date';
                if (task.due_date) {
                    const date = new Date(task.due_date);
                    const now = new Date();
                    const daysDiff = Math.ceil((date - now) / (1000 * 60 * 60 * 24));
                    
                    if (daysDiff < 0) {
                        dueDate = `<span class="text-red-600 font-medium">Overdue ${Math.abs(daysDiff)} days</span>`;
                    } else if (daysDiff === 0) {
                        dueDate = `<span class="text-orange-600 font-medium">Today</span>`;
                    } else if (daysDiff <= 3) {
                        dueDate = `<span class="text-orange-600 font-medium">In ${daysDiff} days</span>`;
                    } else {
                        dueDate = date.toLocaleDateString();
                    }
                }

                row.innerHTML = `
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tasks text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="/tasks/${task.id}" class="hover:text-blue-600">${task.title}</a>
                                </div>
                                <div class="text-sm text-gray-500 truncate max-w-xs">${task.description || 'No description'}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            <i class="fas ${statusIcon} mr-1"></i>
                            ${task.status.replace('_', ' ').toUpperCase()}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${priorityClass}">
                            ${task.priority.toUpperCase()}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${categoryClass}">
                            ${task.category.toUpperCase()}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${task.assignee ? task.assignee.name : 'Unassigned'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${dueDate}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="/tasks/${task.id}" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/tasks/${task.id}/edit" class="text-green-600 hover:text-green-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="autoAssignTask(${task.id})" class="text-purple-600 hover:text-purple-900" title="Auto Assign">
                            <i class="fas fa-robot"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function updatePagination(pagination) {
            currentPage = pagination.current_page;
            totalPages = pagination.last_page;
            
            const info = document.getElementById('pagination-info');
            const controls = document.getElementById('pagination-controls');
            
            info.textContent = `Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total} tasks`;
            
            let html = '';
            
            // Previous button
            html += `
                <button onclick="changePage(${currentPage - 1})" 
                        ${currentPage === 1 ? 'disabled' : ''}
                        class="px-3 py-1 rounded-lg ${currentPage === 1 ? 'bg-gray-100 text-gray-400' : 'bg-gray-200 hover:bg-gray-300'}">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    html += `
                        <button onclick="changePage(${i})" 
                                class="px-3 py-1 rounded-lg ${i === currentPage ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300'}">
                            ${i}
                        </button>
                    `;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    html += `<span class="px-3 py-1">...</span>`;
                }
            }
            
            // Next button
            html += `
                <button onclick="changePage(${currentPage + 1})" 
                        ${currentPage === totalPages ? 'disabled' : ''}
                        class="px-3 py-1 rounded-lg ${currentPage === totalPages