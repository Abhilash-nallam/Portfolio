<header class="admin-topbar">
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="fa-solid fa-bars"></i>
    </button>

    <h1 class="topbar-title"><?= isset($pageTitle) ? e($pageTitle) : 'Dashboard' ?></h1>

    <div class="topbar-right">
        <div class="dropdown">
            <button class="profile-btn" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="profile-avatar"><?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)) ?></span>
                <span class="profile-name d-none d-md-inline"><?= e($_SESSION['admin_name'] ?? 'Admin') ?></span>
                <i class="fa-solid fa-chevron-down small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text small text-muted">Signed in as <strong><?= e($_SESSION['admin_username'] ?? '') ?></strong></span></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="../index.php" target="_blank"><i class="fa-solid fa-globe me-2"></i>View Website</a></li>
                <li><a class="dropdown-item text-danger" href="logout.php" onclick="return confirm('Sign out of LeadDesk Mini?');"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</header>
