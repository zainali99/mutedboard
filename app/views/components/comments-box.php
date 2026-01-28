<div 
    class="comments-box" 
    data-component="CommentsBox" 
    data-component-id="<?= htmlspecialchars($componentId) ?>"
    data-props='<?= json_encode($props) ?>'
>
    <div class="comments-header">
        <h3>Comments (<?= $comment_count ?? 0 ?>)</h3>
    </div>

    <!-- Add Comment Form -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="comment-form-wrapper">
        <form class="comment-form" data-component-form  data-reset-on-success>
            <input type="hidden" name="thread_id" value="<?= htmlspecialchars($thread_id ?? $props['thread_id'] ?? '') ?>">
            
            <div class="form-group">
                <textarea 
                    name="content" 
                    class="form-control" 
                    rows="3" 
                    placeholder="Write your comment..."
                    required
                ></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" data-action="addComment" class="btn btn-primary">
                    Post Comment
                </button>
            </div>
        </form>
    </div>
    <?php else: ?>
    <div class="login-prompt">
        <p>Please <a href="/login">login</a> to post a comment.</p>
    </div>
    <?php endif; ?>

    <!-- Comments List -->
    <div class="comments-list">
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
            <div class="comment-item" data-id="<?= $comment['id'] ?>">
                <div class="comment-header">
                    <span class="comment-author">
                        <?= htmlspecialchars($comment['author_username'] ?? 'Anonymous') ?>
                    </span>
                    <span class="comment-date">
                        <?= date('M d, Y g:i A', strtotime($comment['created_at'])) ?>
                    </span>
                </div>
                
                <div class="comment-content">
                    <?= nl2br(htmlspecialchars($comment['content'])) ?>
                </div>
                
                <!-- Comment Actions -->
                <?php if (isset($_SESSION['user_id']) && 
                         ($comment['user_id'] == $_SESSION['user_id'] || isset($_SESSION['is_admin']))): ?>
                <div class="comment-actions">
                    <button 
                        class="btn btn-sm btn-link" 
                        data-action="deleteComment"
                        data-id="<?= $comment['id'] ?>"
                        onclick="return confirm('Are you sure you want to delete this comment?')"
                    >
                        Delete
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
        <div class="no-comments">
            <p>No comments yet. Be the first to comment!</p>
        </div>
        <?php endif; ?>
    </div>
</div>

