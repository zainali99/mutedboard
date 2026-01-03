<div class="thread-page">
    <div class="page-header">
        <a href="/dashboard" class="btn btn-secondary">← Back to Dashboard</a>
    </div>

    <article class="thread-detail">
        <header class="thread-header">
            <div class="thread-badges">
                <?php if ($thread['is_pinned']): ?>
                    <span class="badge badge-pin">📌 Pinned</span>
                <?php endif; ?>
                <?php if ($thread['is_locked']): ?>
                    <span class="badge badge-locked">🔒 Locked</span>
                <?php endif; ?>
            </div>
            
            <h1><?= htmlspecialchars($thread['title']) ?></h1>
            
            <div class="thread-meta">
                <div class="meta-item">
                    <span class="meta-label">Author:</span>
                    <strong><?= htmlspecialchars($thread['author_username']) ?></strong>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Group:</span>
                    <strong><?= htmlspecialchars($thread['group_name']) ?></strong>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Created:</span>
                    <?= date('F j, Y \a\t g:i A', strtotime($thread['created_at'])) ?>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Views:</span>
                    <?= $thread['views'] ?>
                </div>
            </div>
        </header>

        <div class="thread-content">
            <?= nl2br(htmlspecialchars($thread['content'])) ?>
        </div>

        <?php if ($thread['updated_at'] != $thread['created_at']): ?>
            <div class="thread-footer">
                <small>Last updated: <?= date('F j, Y \a\t g:i A', strtotime($thread['updated_at'])) ?></small>
            </div>
        <?php endif; ?>
    </article>

    <aside class="thread-sidebar">
        <div class="widget">
            <h3>Thread Information</h3>
            <dl class="info-list">
                <dt>Group</dt>
                <dd><?= htmlspecialchars($thread['group_name']) ?></dd>
                
                <?php if (!empty($thread['group_description'])): ?>
                    <dt>Group Description</dt>
                    <dd><?= htmlspecialchars($thread['group_description']) ?></dd>
                <?php endif; ?>
                
                <dt>Status</dt>
                <dd>
                    <?php if ($thread['is_locked']): ?>
                        <span class="status-locked">🔒 Locked</span>
                    <?php else: ?>
                        <span class="status-open">✓ Open</span>
                    <?php endif; ?>
                </dd>
            </dl>
        </div>

        <div class="widget">
            <h3>Actions</h3>
            <div class="action-buttons">
                <a href="/dashboard/threads" class="btn btn-outline">View All Threads</a>
                <a href="/dashboard/create-thread" class="btn btn-outline">Create New Thread</a>
            </div>
        </div>
    </aside>
</div>

<style>
.thread-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 30px;
}

.page-header {
    grid-column: 1 / -1;
    margin-bottom: 20px;
}

.thread-detail {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 30px;
}

.thread-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.thread-badges {
    margin-bottom: 15px;
}

.badge {
    display: inline-block;
    padding: 4px 10px;
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

.thread-header h1 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 28px;
    line-height: 1.3;
}

.thread-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    font-size: 14px;
    color: #666;
}

.meta-item {
    display: flex;
    gap: 5px;
}

.meta-label {
    color: #999;
}

.thread-content {
    font-size: 16px;
    line-height: 1.8;
    color: #333;
    margin-bottom: 20px;
}

.thread-footer {
    padding-top: 20px;
    border-top: 1px solid #f0f0f0;
    color: #999;
}

.thread-sidebar {
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

.info-list {
    margin: 0;
}

.info-list dt {
    font-weight: 600;
    color: #333;
    margin-top: 10px;
    margin-bottom: 5px;
}

.info-list dt:first-child {
    margin-top: 0;
}

.info-list dd {
    margin: 0;
    color: #666;
    padding-bottom: 10px;
    border-bottom: 1px solid #f0f0f0;
}

.info-list dd:last-child {
    border-bottom: none;
}

.status-locked {
    color: #dc3545;
}

.status-open {
    color: #28a745;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
    text-align: center;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-outline {
    background: white;
    color: #333;
    border: 1px solid #ddd;
}

.btn-outline:hover {
    background: #f8f9fa;
}

@media (max-width: 768px) {
    .thread-page {
        grid-template-columns: 1fr;
    }
    
    .thread-meta {
        flex-direction: column;
        gap: 8px;
    }
}
</style>
