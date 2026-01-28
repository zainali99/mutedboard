<div class="dashboard">
    <div class="dashboard-header">
        <h2>Dashboard</h2>
        <p>Welcome back, <?= htmlspecialchars($user['username']) ?>!</p>
    </div>

    <div class="dashboard-actions">
        <a href="/dashboard/create-thread" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z"/>
            </svg>
            Create New Thread
        </a>
        <a href="/dashboard/threads" class="btn btn-secondary">View All Threads</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="dashboard-content">
        <section class="recent-threads">
            <h3>Recent Threads</h3>
            <?php if (!empty($threads)): ?>
                <div class="threads-list">
                    <?php foreach ($threads as $thread): ?>
                        <div class="thread-card">
                            <div class="thread-header">
                                <h4>
                                    <?php if ($thread['is_pinned']): ?>
                                        <span class="badge badge-pin">📌 Pinned</span>
                                    <?php endif; ?>
                                    <a href="/dashboard/thread/<?= $thread['id'] ?>">
                                        <?= htmlspecialchars($thread['title']) ?>
                                    </a>
                                    <?php if ($thread['is_locked']): ?>
                                        <span class="badge badge-locked">🔒 Locked</span>
                                    <?php endif; ?>
                                </h4>
                            </div>
                            <div class="thread-meta">
                                <span class="author">
                                    By <strong><?= htmlspecialchars($thread['author_username']) ?></strong>
                                </span>
                                <span class="group">
                                    in <strong><?= htmlspecialchars($thread['group_name']) ?></strong>
                                </span>
                                <span class="views">👁 <?= $thread['views'] ?> views</span>
                                <span class="date">
                                    <?= date('M j, Y', strtotime($thread['created_at'])) ?>
                                </span>
                            </div>
                            <div class="thread-excerpt">
                                <?= htmlspecialchars(substr($thread['content'], 0, 150)) ?>
                                <?= strlen($thread['content']) > 150 ? '...' : '' ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-data">No threads yet. <a href="/dashboard/create-thread">Create the first one!</a></p>
            <?php endif; ?>
        </section>

        <aside class="sidebar">
            <div class="widget">
                <h3>Available Groups</h3>
                <?php if (!empty($groups)): ?>
                    <ul class="groups-list">
                        <?php foreach ($groups as $group): ?>
                            <li>
                                <strong><?= htmlspecialchars($group['name']) ?></strong>
                                <?php if (!empty($group['description'])): ?>
                                    <p><?= htmlspecialchars($group['description']) ?></p>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="no-data">No groups available.</p>
                <?php endif; ?>
            </div>

            <div class="widget">
                <h3>Quick Stats</h3>
                <ul class="stats-list">
                    <li>
                        <span class="stat-label">Total Threads:</span>
                        <span class="stat-value"><?= count($threads) ?></span>
                    </li>
                    <li>
                        <span class="stat-label">Total Groups:</span>
                        <span class="stat-value"><?= count($groups) ?></span>
                    </li>
                </ul>
            </div>
        </aside>
    </div>
</div>

