<?php
$page_title = 'Settings';
?>
<div class="hr-page hr-page-settings">
    <header class="hr-page-header">
        <nav class="hr-breadcrumb" aria-label="Breadcrumb">
            <ol class="hr-breadcrumb-list">
                <li class="hr-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard">Dashboard</a></li>
                <li class="hr-breadcrumb-item hr-breadcrumb-current" aria-current="page">Settings</li>
            </ol>
        </nav>
    </header>
    <section class="hr-section">
        <h2 class="hr-section-title">Settings</h2>

        <div class="hr-settings-block">
            <h3 class="hr-settings-block-title">Display</h3>
            <div class="hr-slider-wrap">
                <label for="itemsPerPage">Items per page</label>
                <input type="range" id="itemsPerPage" class="hr-slider" name="items_per_page" min="10" max="50" value="25" step="5" aria-valuemin="10" aria-valuemax="50" aria-valuenow="25">
                <div class="hr-slider-value" id="itemsPerPageValue" aria-live="polite">25 items</div>
            </div>
        </div>

        <div class="hr-settings-block">
            <h3 class="hr-settings-block-title">Notifications</h3>
            <div class="hr-slider-wrap">
                <label for="notifyVolume">Notification volume</label>
                <input type="range" id="notifyVolume" class="hr-slider" name="notify_volume" min="0" max="100" value="80" aria-valuemin="0" aria-valuemax="100" aria-valuenow="80">
                <div class="hr-slider-value" id="notifyVolumeValue" aria-live="polite">80%</div>
            </div>
        </div>

        <div class="hr-placeholder hr-mt-24">
            <p class="hr-placeholder-message">More settings coming soon.</p>
            <p class="hr-text-muted">Application and user settings will expand here when available.</p>
        </div>
    </section>
</div>
