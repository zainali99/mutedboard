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

<style>
.comments-box {
    margin: 20px 0;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.comments-header h3 {
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #e0e0e0;
    color: #333;
}

.comment-form-wrapper {
    margin-bottom: 30px;
}

.comment-form .form-group {
    margin-bottom: 15px;
}

.comment-form textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-family: inherit;
    font-size: 14px;
    resize: vertical;
}

.comment-form textarea:focus {
    outline: none;
    border-color: #007bff;
}

.comment-form .form-actions {
    text-align: right;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-primary.loading {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
}

.btn-link {
    background: none;
    color: #dc3545;
    padding: 0;
    text-decoration: none;
}

.btn-link:hover {
    text-decoration: underline;
}

.login-prompt {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 4px;
    margin-bottom: 20px;
    text-align: center;
}

.login-prompt p {
    margin: 0;
    color: #666;
}

.login-prompt a {
    color: #007bff;
    text-decoration: none;
}

.login-prompt a:hover {
    text-decoration: underline;
}

.comments-list {
    margin-top: 20px;
}

.comment-item {
    padding: 15px;
    margin-bottom: 15px;
    background: #f8f9fa;
    border-radius: 6px;
    border-left: 3px solid #007bff;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e0e0e0;
}

.comment-author {
    font-weight: 600;
    color: #333;
}

.comment-date {
    font-size: 12px;
    color: #666;
}

.comment-content {
    color: #444;
    line-height: 1.6;
    margin-bottom: 10px;
}

.comment-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.no-comments {
    padding: 40px 20px;
    text-align: center;
    color: #666;
    background: #f8f9fa;
    border-radius: 6px;
}

.no-comments p {
    margin: 0;
    font-style: italic;
}
</style>
