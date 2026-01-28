<div class="threads-page">
    <div class="page-header">
        <h2>All Threads</h2>
        <div class="header-actions">
            <a href="/dashboard/create-thread" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z"/>
                </svg>
                Create New Thread
            </a>
            <a href="/dashboard" class="btn btn-secondary">← Back to Dashboard</a>
        </div>
    </div>

    <?php if (!empty($threads)): ?>
        <div class="threads-grid">
            <?php foreach ($threads as $thread): ?>
                <div class="thread-card">
                    <div class="thread-header">
                        <div class="thread-badges">
                            <?php if ($thread['is_pinned']): ?>
                                <span class="badge badge-pin">📌</span>
                            <?php endif; ?>
                            <?php if ($thread['is_locked']): ?>
                                <span class="badge badge-locked">🔒</span>
                            <?php endif; ?>
                        </div>
                        <h3>
                            <a href="/dashboard/thread/<?= $thread['id'] ?>">
                                <?= htmlspecialchars($thread['title']) ?>
                            </a>
                        </h3>
                    </div>
                    
                    <div class="thread-excerpt">
                        <?= htmlspecialchars(substr($thread['content'], 0, 200)) ?>
                        <?= strlen($thread['content']) > 200 ? '...' : '' ?>
                    </div>
                    
                    <div class="thread-meta">
                        <span class="meta-author">
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                            </svg>
                            <?= htmlspecialchars($thread['author_username']) ?>
                        </span>
                        <span class="meta-group">
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm5 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm6 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zM5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm6 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                            </svg>
                            <?= htmlspecialchars($thread['group_name']) ?>
                        </span>
                        <span class="meta-views">
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                            </svg>
                            <?= $thread['views'] ?>
                        </span>
                        <span class="meta-date">
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                            </svg>
                            <?= date('M j, Y', strtotime($thread['created_at'])) ?>
                        </span>
                    </div>
                    
                    <div class="thread-actions">
                        <a href="/dashboard/thread/<?= $thread['id'] ?>" class="btn btn-small">View Thread</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <svg width="64" height="64" viewBox="0 0 16 16" fill="currentColor">
                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
            </svg>
            <h3>No Threads Found</h3>
            <p>There are no threads yet. Be the first to create one!</p>
            <a href="/dashboard/create-thread" class="btn btn-primary">Create First Thread</a>
        </div>
    <?php endif; ?>
</div>

