<?php
$page_title = 'Settings';
?>
<div class="portal-page portal-page-settings">
    <header class="portal-page-header">
        <nav class="portal-breadcrumb" aria-label="Breadcrumb">
            <ol class="portal-breadcrumb-list">
                <li class="portal-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard">Dashboard</a></li>
                <li class="portal-breadcrumb-item portal-breadcrumb-current" aria-current="page">Settings</li>
            </ol>
        </nav>
    </header>
    <section class="portal-section">
        <h2 class="portal-section-title">Settings</h2>

        <div class="portal-settings-block">
            <h3 class="portal-settings-block-title">Display</h3>
            <div class="portal-slider-wrap">
                <label for="itemsPerPage">Items per page</label>
                <input type="range" id="itemsPerPage" class="portal-slider" name="items_per_page" min="10" max="50" value="25" step="5" aria-valuemin="10" aria-valuemax="50" aria-valuenow="25">
                <div class="portal-slider-value" id="itemsPerPageValue" aria-live="polite">25 items</div>
            </div>
        </div>

        <div class="portal-settings-block">
            <h3 class="portal-settings-block-title">Notifications</h3>
            <div class="portal-slider-wrap">
                <label for="notifyVolume">Notification volume</label>
                <input type="range" id="notifyVolume" class="portal-slider" name="notify_volume" min="0" max="100" value="80" aria-valuemin="0" aria-valuemax="100" aria-valuenow="80">
                <div class="portal-slider-value" id="notifyVolumeValue" aria-live="polite">80%</div>
            </div>
        </div>

        <div class="portal-placeholder portal-mt-24">
            <p class="portal-placeholder-message">More settings coming soon.</p>
            <p class="portal-text-muted">Application and user settings will expand here when available.</p>
        </div>
    </section>
</div>
