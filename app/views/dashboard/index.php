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

<style>
.dashboard {
    padding: 20px;
}

.dashboard-header {
    margin-bottom: 30px;
}

.dashboard-header h2 {
    margin: 0 0 10px 0;
    color: #333;
}

.dashboard-header p {
    color: #666;
    margin: 0;
}

.dashboard-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.alert {
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.dashboard-content {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 30px;
}

.threads-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.thread-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    transition: box-shadow 0.3s;
}

.thread-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.thread-header h4 {
    margin: 0 0 10px 0;
}

.thread-header h4 a {
    color: #333;
    text-decoration: none;
}

.thread-header h4 a:hover {
    color: #007bff;
}

.badge {
    display: inline-block;
    padding: 2px 8px;
    font-size: 12px;
    border-radius: 4px;
    margin-right: 8px;
}

.badge-pin {
    background: #ffc107;
    color: #000;
}

.badge-locked {
    background: #dc3545;
    color: white;
}

.thread-meta {
    display: flex;
    gap: 15px;
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.thread-excerpt {
    color: #555;
    line-height: 1.6;
}

.sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.widget {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
}

.widget h3 {
    margin: 0 0 15px 0;
    font-size: 18px;
    color: #333;
}

.groups-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.groups-list li {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.groups-list li:last-child {
    border-bottom: none;
}

.groups-list strong {
    display: block;
    margin-bottom: 5px;
}

.groups-list p {
    margin: 0;
    font-size: 14px;
    color: #666;
}

.stats-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.stats-list li {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.stats-list li:last-child {
    border-bottom: none;
}

.stat-value {
    font-weight: bold;
    color: #007bff;
}

.no-data {
    color: #999;
    font-style: italic;
}

@media (max-width: 768px) {
    .dashboard-content {
        grid-template-columns: 1fr;
    }
    
    .dashboard-actions {
        flex-direction: column;
    }
    
    .btn {
        text-align: center;
        justify-content: center;
    }
}
</style>
