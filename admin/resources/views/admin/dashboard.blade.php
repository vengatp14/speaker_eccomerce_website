<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            overflow-x: hidden;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            padding: 20px;
            background-color: #f8f9fa;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease-in-out;
        }
        .sidebar.collapsed {
            margin-left: -250px;
        }
        .main-content {
            margin-left: 250px;
            transition: all 0.3s ease-in-out;
        }
        .main-content.expanded {
            margin-left: 0;
        }
        .nav-link {
            color: #333;
            padding: 10px 15px;
            border-radius: 4px;
            transition: background-color 0.3s ease-in-out;
        }
        .nav-link:hover {
            background-color: #e9ecef;
        }
        .nav-link i {
            margin-right: 10px;
        }
        .active {
            background-color: #0d6efd;
            color: white;
        }
        .active:hover {
            color: white;
        }
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }
        .dropdown-toggle {
            outline: 0;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="d-flex flex-column h-100">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
                <span class="fs-4">Admin Panel</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-people"></i>
                        Users
                    </a>
                </li>
                <li>
                    <a href="{{route('products.index')}}" class="nav-link">
                        <i class="bi bi-grid"></i>
                        Products
                    </a>
                </li>
                <li>
                    <a href="{{route('categories.index')}}" class="nav-link">
                        <i class="bi bi-grid"></i>
                        categories
                    </a>
                </li>
<li>
                    <a href="{{route('orders.index')}}" class="nav-link">
                        <i class="bi bi-grid"></i>
                    orders
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-bar-chart"></i>
                        Reports
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-gear"></i>
                        Settings
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong>{{ Auth::guard('admin')->user()->username }}</strong>
                </a>
                <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Sign out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="main-content" id="main-content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </nav>

        <div class="container-fluid mt-4">
            <div class="row">
                <!-- Card 1 -->
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <h2 class="card-text">1,234</h2>
                            <small class="text-muted">↑ 12% from last month</small>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Revenue</h5>
                            <h2 class="card-text">$45,678</h2>
                            <small class="text-muted">↑ 8% from last month</small>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Orders</h5>
                            <h2 class="card-text">567</h2>
                            <small class="text-muted">↓ 3% from last month</small>
                        </div>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Products</h5>
                            <h2 class="card-text">890</h2>
                            <small class="text-muted">↑ 5% from last month</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            Recent Activity
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Activity</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>John Doe</td>
                                        <td>Created a new order</td>
                                        <td>2024-01-15</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Jane Smith</td>
                                        <td>Updated profile</td>
                                        <td>2024-01-14</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Bob Johnson</td>
                                        <td>Added new product</td>
                                        <td>2024-01-13</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            });

            // Handle responsive behavior
            function handleResize() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                }
            }

            window.addEventListener('resize', handleResize);
            handleResize(); // Call on initial load
        });
    </script>
</body>
</html>
