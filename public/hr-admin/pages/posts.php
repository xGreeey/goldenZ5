<?php
$page_title = 'Posts';

$filter = isset($_GET['filter']) ? trim($_GET['filter']) : 'all';
$allowed_filters = ['all', 'open', 'hold', 'closed', 'drafts'];
if (!in_array($filter, $allowed_filters, true)) {
    $filter = 'all';
}

// Sample post/job cards (placeholder data — replace with DB when posts module exists)
$posts = [
    [
        'status' => 'Open',
        'category' => 'Development',
        'title' => 'ReactJS Developer',
        'icon' => 'fa-cog',
        'icon_bg' => '#6366f1',
        'location' => 'Surat',
        'date' => 'Feb 24, 2025',
        'progress' => 90,
        'salary' => '$25K-30K annually',
        'applied' => 15,
        'interviewed' => 8,
        'tags' => ['On Site', 'Full Time', '3 Years exp.', '2 Positions'],
        'created_by' => 'Brooklyn',
    ],
    [
        'status' => 'Open',
        'category' => 'Development',
        'title' => 'iOS Developer',
        'icon' => 'fa-mobile-alt',
        'icon_bg' => '#22c55e',
        'location' => 'Surat',
        'date' => 'Feb 23, 2025',
        'progress' => 95,
        'salary' => '$30K-35K annually',
        'applied' => 14,
        'interviewed' => 10,
        'tags' => ['On Site', 'Full Time', '2-3 Years exp.', '4 Positions'],
        'created_by' => 'Samantha',
    ],
    [
        'status' => 'Hold',
        'category' => 'Design',
        'title' => '3D Animation (Junior)',
        'icon' => 'fa-palette',
        'icon_bg' => '#f59e0b',
        'location' => 'Remote',
        'date' => 'Feb 22, 2025',
        'progress' => 36,
        'salary' => '$20K-25K annually',
        'applied' => 12,
        'interviewed' => 3,
        'tags' => ['Remote', 'Full Time', '1 Years exp.', '2 Positions'],
        'created_by' => 'Robert',
    ],
    [
        'status' => 'Closed',
        'category' => 'Development',
        'title' => 'Backend Engineer',
        'icon' => 'fa-server',
        'icon_bg' => '#8b5cf6',
        'location' => 'Surat',
        'date' => 'Feb 20, 2025',
        'progress' => 100,
        'salary' => '$35K-40K annually',
        'applied' => 22,
        'interviewed' => 4,
        'tags' => ['On Site', 'Full Time', '2 Years exp.', '5 positions'],
        'created_by' => 'Robert',
    ],
];

$counts = ['all' => count($posts), 'open' => 2, 'hold' => 1, 'closed' => 1, 'drafts' => 0];
?>
<div class="hr-page hr-page-posts">
    <nav class="hr-breadcrumb" aria-label="Breadcrumb">
        <ol class="hr-breadcrumb-list">
            <li class="hr-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard">Dashboard</a></li>
            <li class="hr-breadcrumb-item hr-breadcrumb-current" aria-current="page">Posts</li>
        </ol>
    </nav>
    <header class="hr-posts-header">
        <div>
            <h1 class="hr-posts-title">Posts</h1>
            <p class="hr-posts-subtitle">Manage posted jobs and progress.</p>
        </div>
    </header>

    <div class="hr-post-tabs-wrap">
        <nav class="hr-post-tabs" role="tablist" aria-label="Filter posts">
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=posts&filter=all" class="hr-post-tab <?php echo $filter === 'all' ? 'active' : ''; ?>" role="tab">All (<?php echo (int) $counts['all']; ?>)</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=posts&filter=open" class="hr-post-tab <?php echo $filter === 'open' ? 'active' : ''; ?>" role="tab">Open (<?php echo (int) $counts['open']; ?>)</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=posts&filter=hold" class="hr-post-tab <?php echo $filter === 'hold' ? 'active' : ''; ?>" role="tab">Hold (<?php echo (int) $counts['hold']; ?>)</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=posts&filter=closed" class="hr-post-tab <?php echo $filter === 'closed' ? 'active' : ''; ?>" role="tab">Closed (<?php echo (int) $counts['closed']; ?>)</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=posts&filter=drafts" class="hr-post-tab <?php echo $filter === 'drafts' ? 'active' : ''; ?>" role="tab">Drafts (<?php echo (int) $counts['drafts']; ?>)</a>
            <button type="button" class="hr-post-tab hr-post-tab-filter" aria-label="Advance filter">
                <i class="fas fa-sliders-h" aria-hidden="true"></i>
                Advance Filter
            </button>
        </nav>
    </div>

    <div class="hr-posts-grid">
        <?php foreach ($posts as $post): ?>
        <article class="hr-post-card">
            <div class="hr-post-card-top">
                <span class="hr-post-card-badge">• <?php echo htmlspecialchars($post['status']); ?> | <?php echo htmlspecialchars($post['category']); ?></span>
                <button type="button" class="hr-post-card-menu" aria-label="More options"><i class="fas fa-ellipsis-v" aria-hidden="true"></i></button>
            </div>
            <h3 class="hr-post-card-title">
                <span class="hr-post-card-icon" style="--hr-post-icon-bg: <?php echo htmlspecialchars($post['icon_bg']); ?>"><i class="fas <?php echo htmlspecialchars($post['icon']); ?>" aria-hidden="true"></i></span>
                <?php echo htmlspecialchars($post['title']); ?>
            </h3>
            <div class="hr-post-card-meta">
                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> <?php echo htmlspecialchars($post['location']); ?></span>
                <span><i class="fas fa-calendar" aria-hidden="true"></i> <?php echo htmlspecialchars($post['date']); ?></span>
            </div>
            <div class="hr-post-card-progress-wrap">
                <div class="hr-post-card-progress" role="img" aria-label="<?php echo (int) $post['progress']; ?> percent">
                    <svg viewBox="0 0 36 36" class="hr-post-progress-ring">
                        <path class="hr-post-progress-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="hr-post-progress-fill" stroke-dasharray="<?php echo (int) $post['progress']; ?>, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <span class="hr-post-progress-value"><?php echo (int) $post['progress']; ?>%</span>
                </div>
                <span class="hr-post-card-progress-label">Strong Match</span>
            </div>
            <p class="hr-post-card-salary"><?php echo htmlspecialchars($post['salary']); ?></p>
            <div class="hr-post-card-stats">
                <span><i class="fas fa-users" aria-hidden="true"></i> <?php echo (int) $post['applied']; ?> Applied</span>
                <span><i class="fas fa-clipboard-check" aria-hidden="true"></i> <?php echo (int) $post['interviewed']; ?> Interview</span>
            </div>
            <div class="hr-post-card-tags">
                <?php foreach ($post['tags'] as $tag): ?>
                <span class="hr-post-tag"><?php echo htmlspecialchars($tag); ?></span>
                <?php endforeach; ?>
            </div>
            <div class="hr-post-card-footer">
                <span class="hr-post-card-created">Created by <?php echo htmlspecialchars($post['created_by']); ?></span>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=posts&amp;id=<?php echo urlencode($post['title']); ?>" class="hr-post-card-link">View details <i class="fas fa-chevron-right" aria-hidden="true"></i></a>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <?php if (empty($posts)): ?>
    <div class="hr-placeholder hr-mt-24">
        <p class="hr-placeholder-message">No posts in this filter.</p>
        <p class="hr-text-muted">Try another filter or add a new post.</p>
    </div>
    <?php endif; ?>
</div>
