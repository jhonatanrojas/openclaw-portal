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
                        <a href="/" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-home mr-1"></i> Home
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
                            <p class="text-2xl font-bold" id="total-tasks">Loading...</p>
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
                            <p class="text-2xl font-bold" id="pending-tasks">Loading...</p>
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
                            <p class="text-2xl font-bold" id="completed-tasks">Loading...</p>
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
                            <p class="text-2xl font-bold" id="overdue-tasks">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Recent Tasks</h2>
                </div>
                <div class="divide-y divide-gray-200" id="tasks-list">
                    <!-- Tasks will be loaded here -->
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <p>Loading tasks...</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="/tasks/create" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-200 border border-gray-200 hover:border-blue-300">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-plus text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-medium text-gray-900">Create New Task</h3>
                            <p class="text-sm text-gray-500 mt-1">Add a new task to the system</p>
                        </div>
                    </div>
                </a>
                <a href="/api/tasks" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-200 border border-gray-200 hover:border-green-300">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-code text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-medium text-gray-900">API Access</h3>
                            <p class="text-sm text-gray-500 mt-1">Access tasks via REST API</p>
                        </div>
                    </div>
                </a>
                <a href="/tasks/stats" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-200 border border-gray-200 hover:border-purple-300">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-medium text-gray-900">Detailed Statistics</h3>
                            <p class="text-sm text-gray-500 mt-1">View comprehensive task analytics</p>
                        </div>
                    </div>
                </a>
            </div>
        </main>
    </div>

    <script>
        // Load stats and tasks on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadStats();
            loadTasks();
        });

        function loadStats() {
            fetch('/api/tasks/stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('total-tasks').textContent = data.data.total || 0;
                        document.getElementById('pending-tasks').textContent = data.data.pending || 0;
                        document.getElementById('completed-tasks').textContent = data.data.completed || 0;
                        document.getElementById('overdue-tasks').textContent = data.data.overdue || 0;
                    }
                })
                .catch(error => {
                    console.error('Error loading stats:', error);
                    document.getElementById('total-tasks').textContent = 'Error';
                    document.getElementById('pending-tasks').textContent = 'Error';
                    document.getElementById('completed-tasks').textContent = 'Error';
                    document.getElementById('overdue-tasks').textContent = 'Error';
                });
        }

        function loadTasks() {
            fetch('/api/tasks?per_page=10&sort_by=created_at&sort_order=desc')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderTasks(data.data.data);
                    }
                })
                .catch(error => {
                    console.error('Error loading tasks:', error);
                    document.getElementById('tasks-list').innerHTML = `
                        <div class="p-8 text-center text-red-500">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>Error loading tasks</p>
                            <p class="text-sm mt-2">Please try again later</p>
                        </div>
                    `;
                });
        }

        function renderTasks(tasks) {
            const container = document.getElementById('tasks-list');
            
            if (tasks.length === 0) {
                container.innerHTML = `
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p class="text-lg">No tasks found</p>
                        <p class="text-sm mt-2">Create your first task to get started</p>
                        <a href="/tasks/create" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-1"></i> Create Task
                        </a>
                    </div>
                `;
                return;
            }

            let html = '';
            
            tasks.forEach(task => {
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

                html += `
                    <div class="p-6 hover:bg-gray-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        <a href="/tasks/${task.id}" class="hover:text-blue-600">${task.title}</a>
                                    </h3>
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                        <i class="fas ${statusIcon} mr-1"></i>
                                        ${task.status.replace('_', ' ')}
                                    </span>
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${priorityClass}">
                                        ${task.priority}
                                    </span>
                                </div>
                                <p class="text-gray-600 mb-3">${task.description || 'No description'}</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span class="mr-4">
                                        <i class="fas fa-tag mr-1"></i>
                                        ${task.category}
                                    </span>
                                    <span class="mr-4">
                                        <i class="fas fa-user mr-1"></i>
                                        ${task.assignee ? task.assignee.name : 'Unassigned'}
                                    </span>
                                    <span>
                                        <i class="fas fa-calendar mr-1"></i>
                                        ${dueDate}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <a href="/tasks/${task.id}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }
    </script>
</body>
</html>