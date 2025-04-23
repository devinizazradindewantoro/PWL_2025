<div class="sidebar">
  <!-- SidebarSearch Form -->
  <div class="form-inline mt-2">
    <div class="input-group" data-widget="sidebar-search">
      <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
      <div class="input-group-append">
        <button class="btn btn-sidebar">
          <i class="fas fa-search fa-fw"></i>
        </button>
      </div>
    </div>
  </div>
  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <li class="nav-item">
        <a href="{{ url('/') }}" class="nav-link {{ ($activeMenu == 'dashboard')? 'active' : '' }} ">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>Dashboard</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/profile') }}" class="nav-link {{ ($activeMenu == 'profile') ? 'active' : '' }}">
          <i class="nav-icon fas fa-user"></i>
          <p>Profile</p>
        </a>
      </li>
      
      <li class="nav-header">Data Transaksi</li>
      <li class="nav-item">
        <a href="{{ url('/stok') }}" class="nav-link {{ ($activeMenu == 'stok')? 'active' : '' }} ">
          <i class="nav-icon fas fa-cubes"></i>
          <p>Stok Barang</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/penjualan') }}" class="nav-link {{ ($activeMenu == 'penjualan')? 'active' : '' }} ">
          <i class="nav-icon fas fa-cash-register"></i>
          <p>Transaksi Penjualan</p>
        </a>
      </li>
      <!-- Menambahkan Menu Logout -->
      <li class="nav-item">
          <a href="{{ url('/login') }}" class="nav-link text-danger {{ ($activeMenu == 'login') ? 'active' : '' }}">
              <i class="nav-icon fas fa-sign-out-alt"></i> 
              <p>Logout</p>
          </a>            
      </li>
    </ul>
  </nav>
</div>