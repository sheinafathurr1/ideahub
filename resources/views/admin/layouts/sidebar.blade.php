<nav class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="brand-logo brand-font">
        <div style="width: 36px; height: 36px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: black;">
            <i class="bi bi-grid-fill"></i>
        </div>
        IdeaHub Admin
    </a>

    <div class="d-flex flex-column gap-1 flex-grow-1">
        
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        
        <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Users
        </a>
        
        <a href="{{ route('admin.history') }}" class="nav-link {{ request()->routeIs('admin.history') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> History
        </a>
        
        <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph-fill"></i> Reports
        </a>

        <a href="{{ route('admin.news.index') }}" class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
            <i class="bi bi-newspaper"></i> News
        </a>
        
        <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <i class="bi bi-gear-fill"></i> Settings
        </a>

    </div>

    <button onclick="confirmLogout()" class="logout-btn border-0">
        <i class="bi bi-box-arrow-right"></i> Keluar
    </button>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
</nav>