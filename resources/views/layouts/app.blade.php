<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Dashboard</title>
    <style>
        /* Reset Dasar */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; color: #333; }
        a { text-decoration: none; color: inherit; }

        /* Sidebar */
        .sidebar { width: 250px; background: #fff; border-right: 1px solid #eaeaea; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; font-size: 20px; font-weight: bold; border-bottom: 1px solid #eaeaea; }
        .nav-menu { flex: 1; padding: 15px 0; }
        .nav-item { display: block; padding: 12px 20px; color: #555; transition: 0.2s; }
        .nav-item:hover, .nav-item.active { background-color: #f1f3f5; color: #000; font-weight: 600; }

        /* Main Content */
        .main-wrapper { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .topbar { height: 60px; background: #fff; border-bottom: 1px solid #eaeaea; display: flex; align-items: center; padding: 0 25px; }
        .topbar h1 { font-size: 18px; text-transform: capitalize; }
        .content { padding: 25px; overflow-y: auto; flex: 1; }

        /* Alerts */
        .alert { padding: 12px 20px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Buttons */
        .btn { padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600; }
        .btn-primary { background: #212529; color: #fff; }
        .btn-primary:hover { background: #343a40; }
        .btn-outline { background: #fff; border: 1px solid #ccc; color: #333; }
        .btn-outline:hover { background: #f8f9fa; }

        /* Table */
        .table-container { background: #fff; border-radius: 8px; border: 1px solid #eaeaea; overflow: visible; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eaeaea; }
        th { background-color: #f8f9fa; font-size: 14px; color: #666; }
        tr:hover { background-color: #fdfdfd; }
        
        /* Badges / Status */
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-active { background: #d4edda; color: #155724; }
        .badge-inactive { background: #f8d7da; color: #721c24; }

        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000; }
        .modal.show { display: flex; }
        .modal-content { background: #fff; padding: 25px; border-radius: 8px; width: 400px; max-width: 90%; }
        .modal-content h2 { margin-bottom: 20px; text-align: center; }
        
        /* Form Inputs */
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 13px; font-weight: 600; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; outline: none; }
        .form-control:focus { border-color: #212529; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }

        /* Dropdown Action */
        .dropdown { position: relative; display: inline-block; }
        .dropdown-btn { background: none; border: none; font-size: 18px; cursor: pointer; color: #666; padding: 5px 10px; }
        .dropdown-menu { display: none; position: absolute; right: 0; top: 30px; background: #fff; border: 1px solid #eaeaea; border-radius: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 100; min-width: 120px; }
        .dropdown-menu.show { display: block; }
        .dropdown-item { width: 100%; text-align: left; padding: 10px 15px; background: none; border: none; cursor: pointer; font-size: 13px; }
        .dropdown-item:hover { background: #f8f9fa; }
        .text-danger { color: #dc3545; }
        .dropdown-menu-link {
            display: block;
            width: 100%;
            padding: 10px 15px;
            font-size: 13px;
            text-decoration: none;
            box-sizing: border-box;
            text-align: left;
            color: #333;
        }
        .dropdown-menu-link:hover {
            background: #f8f9fa;
        }
        .pure-css-modal {
            display: none; 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0, 0, 0, 0.5); 
            justify-content: center; 
            align-items: center; 
            z-index: 9999;
        }
        .pure-css-modal:target {
            display: flex;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">ERP System</div>
        <nav class="nav-menu">
            <a href="{{ route('customers.index') }}" class="nav-item {{ $active == 'customers' ? 'active' : '' }}">Customers</a>
            <a href="{{ route('services.index') }}" class="nav-item {{ $active == 'services' ? 'active' : '' }}">Services</a>
            <a href="{{ route('subscriptions.index') }}" class="nav-item {{ $active == 'subscriptions' ? 'active' : '' }}">Subscriptions</a>
        </nav>
    </aside>

    <main class="main-wrapper">
        <header class="topbar">
            <h1>{{ $active ?? 'Dashboard' }}</h1>
        </header>
        
        <div class="content">
            @if(session('toast_success'))
                <div class="alert alert-success">{{ session('toast_success') }}</div>
            @endif
            @if(session('toast_error'))
                <div class="alert alert-error">{{ session('toast_error') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        // Fungsi Buka/Tutup Modal
        function openModal(id) { document.getElementById(id).classList.add('show'); }
        function closeModal(id) { document.getElementById(id).classList.remove('show'); }

        // Fungsi Buka/Tutup Dropdown Action
        function toggleDropdown(id) {
            // Tutup dropdown lain yang sedang terbuka
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if(menu.id !== id) menu.classList.remove('show');
            });
            document.getElementById(id).classList.toggle('show');
        }

        // Tutup dropdown jika klik di luar area
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-btn')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        }

        // Buka modal otomatis jika ada error validasi dari controller
        @if(session('open_modal'))
            window.onload = function() { openModal("{{ session('open_modal') }}"); }
        @endif
    </script>
</body>
</html>