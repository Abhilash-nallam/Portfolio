<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <i class="fa-solid fa-chart-line"></i>
        <span>LeadDesk <b>Mini</b></span>
    </div>

    <nav class="sidebar-nav">
        <a href="dashboard.php" class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-gauge-high"></i> <span>Dashboard</span>
        </a>
        <a href="dashboard.php?status=New" class="<?= (isset($_GET['status']) && $_GET['status'] === 'New') ? 'active' : '' ?>">
            <i class="fa-solid fa-envelope-open-text"></i> <span>New Leads</span>
        </a>
        <a href="dashboard.php?status=Contacted" class="<?= (isset($_GET['status']) && $_GET['status'] === 'Contacted') ? 'active' : '' ?>">
            <i class="fa-solid fa-phone-volume"></i> <span>Contacted</span>
        </a>
        <a href="dashboard.php?status=Closed" class="<?= (isset($_GET['status']) && $_GET['status'] === 'Closed') ? 'active' : '' ?>">
            <i class="fa-solid fa-circle-check"></i> <span>Closed</span>
        </a>
        <a href="../index.php" target="_blank">
            <i class="fa-solid fa-globe"></i> <span>View Website</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="logout-link" onclick="return confirm('Sign out of LeadDesk Mini?');">
            <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
        </a>
    </div>
</aside>
