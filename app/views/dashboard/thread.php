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
            <?= $thread['content'] ?>
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


<!-- Comments Component -->
<?php
use App\Components\CommentsBox;

$commentsBox = new CommentsBox([
    'thread_id' => $thread['id']
]);

echo $commentsBox->render();
?>
