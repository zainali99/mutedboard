<?php

namespace App\Components;

use Core\Component;
use App\Models\Comment;

/**
 * CommentsBox Component
 * Displays and manages comments for a thread
 */
class CommentsBox extends Component
{
    /**
     * Component view template
     */
    protected $view = 'comments-box';

    /**
     * Initialize component
     */
    protected function mount()
    {
        // Get thread ID from props
        $threadId = $this->prop('thread_id');
        
        if ($threadId) {
            // Load initial comments
            $this->loadCommentsData($threadId);
        }
    }

    /**
     * Load comments data into state
     */
    protected function loadCommentsData($threadId)
    {
        $comments = Comment::getByThreadId($threadId);
        $count = Comment::getCountByThreadId($threadId);
        
        $this->setState('comments', $comments);
        $this->setState('comment_count', $count);
        $this->setState('thread_id', $threadId);
    }

    /**
     * Load comments (called via AJAX)
     * 
     * @param array $params
     * @return array
     */
    public function loadComments($params = [])
    {
        $threadId = $params['thread_id'] ?? $this->prop('thread_id');
        
        if (!$threadId) {
            return [
                'success' => false,
                'error' => 'Thread ID is required'
            ];
        }

        $this->loadCommentsData($threadId);
        
        return $this->refresh();
    }

    /**
     * Add a new comment (called via AJAX)
     * 
     * @param array $params
     * @return array
     */
    public function addComment($params = [])
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'error' => 'You must be logged in to comment'
            ];
        }

        $threadId = $params['thread_id'] ?? $this->prop('thread_id');
        $content = trim($params['content'] ?? '');

        // Validate
        if (empty($content)) {
            return [
                'success' => false,
                'error' => 'Comment content is required'
            ];
        }

        if (strlen($content) < 3) {
            return [
                'success' => false,
                'error' => 'Comment must be at least 3 characters'
            ];
        }

        if (strlen($content) > 5000) {
            return [
                'success' => false,
                'error' => 'Comment must be less than 5000 characters'
            ];
        }

        // Create comment
        $commentId = Comment::create([
            'thread_id' => $threadId,
            'user_id' => $_SESSION['user_id'],
            'content' => $content
        ]);

        if (!$commentId) {
            return [
                'success' => false,
                'error' => 'Failed to create comment'
            ];
        }

        // Reload comments
        $this->loadCommentsData($threadId);

        return [
            'success' => true,
            'message' => 'Comment added successfully',
            'html' => $this->render(),
            'state' => $this->state,
            'comment_id' => $commentId
        ];
    }

    /**
     * Delete a comment (called via AJAX)
     * 
     * @param array $params
     * @return array
     */
    public function deleteComment($params = [])
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'error' => 'You must be logged in to delete comments'
            ];
        }

        $commentId = $params['id'] ?? null;
        
        if (!$commentId) {
            return [
                'success' => false,
                'error' => 'Comment ID is required'
            ];
        }

        // Get comment to check ownership
        $comment = Comment::findById($commentId);
        
        if (!$comment) {
            return [
                'success' => false,
                'error' => 'Comment not found'
            ];
        }

        // Check if user owns the comment or is admin
        if ($comment['user_id'] != $_SESSION['user_id'] && !isset($_SESSION['is_admin'])) {
            return [
                'success' => false,
                'error' => 'You do not have permission to delete this comment'
            ];
        }

        // Delete comment
        if (!Comment::delete($commentId)) {
            return [
                'success' => false,
                'error' => 'Failed to delete comment'
            ];
        }

        // Reload comments
        $threadId = $this->prop('thread_id');
        $this->loadCommentsData($threadId);

        return [
            'success' => true,
            'message' => 'Comment deleted successfully',
            'html' => $this->render(),
            'state' => $this->state
        ];
    }

    /**
     * Edit a comment (called via AJAX)
     * 
     * @param array $params
     * @return array
     */
    public function editComment($params = [])
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'error' => 'You must be logged in to edit comments'
            ];
        }

        $commentId = $params['comment_id'] ?? null;
        $content = trim($params['content'] ?? '');

        if (!$commentId) {
            return [
                'success' => false,
                'error' => 'Comment ID is required'
            ];
        }

        // Validate content
        if (empty($content)) {
            return [
                'success' => false,
                'error' => 'Comment content is required'
            ];
        }

        if (strlen($content) < 3) {
            return [
                'success' => false,
                'error' => 'Comment must be at least 3 characters'
            ];
        }

        // Get comment to check ownership
        $comment = Comment::findById($commentId);
        
        if (!$comment) {
            return [
                'success' => false,
                'error' => 'Comment not found'
            ];
        }

        // Check if user owns the comment
        if ($comment['user_id'] != $_SESSION['user_id']) {
            return [
                'success' => false,
                'error' => 'You do not have permission to edit this comment'
            ];
        }

        // Update comment
        if (!Comment::update($commentId, $content)) {
            return [
                'success' => false,
                'error' => 'Failed to update comment'
            ];
        }

        // Reload comments
        $threadId = $this->prop('thread_id');
        $this->loadCommentsData($threadId);

        return [
            'success' => true,
            'message' => 'Comment updated successfully',
            'html' => $this->render(),
            'state' => $this->state
        ];
    }
}
