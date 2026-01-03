<div class="create-thread-page">
    <div class="page-header">
        <h2>Create New Thread</h2>
        <a href="/dashboard" class="btn btn-secondary">← Back to Dashboard</a>
    </div>

    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-error">
            <h4>Please fix the following errors:</h4>
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <div class="form-container">
        <form action="/dashboard/store-thread" method="POST" class="thread-form">
            <div class="form-group">
                <label for="group_id">Select Group *</label>
                <select name="group_id" id="group_id" required>
                    <option value="">-- Choose a group --</option>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?= $group['id'] ?>" 
                            <?= (isset($_SESSION['form_data']['group_id']) && $_SESSION['form_data']['group_id'] == $group['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($group['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text">Choose which group this thread belongs to</small>
            </div>

            <div class="form-group">
                <label for="title">Thread Title *</label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    maxlength="255" 
                    required 
                    placeholder="Enter a descriptive title for your thread"
                    value="<?= htmlspecialchars($_SESSION['form_data']['title'] ?? '') ?>"
                >
                <small class="form-text">Maximum 255 characters</small>
            </div>

            <div class="form-group">
                <label for="content">Content *</label>
                <textarea 
                    name="content" 
                    id="content" 
                    rows="10" 
                    required 
                    placeholder="Write your thread content here..."
                ><?= htmlspecialchars($_SESSION['form_data']['content'] ?? '') ?></textarea>
                <small class="form-text">Share your thoughts, ask questions, or start a discussion</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z"/>
                    </svg>
                    Create Thread
                </button>
                <a href="/dashboard" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php unset($_SESSION['form_data']); ?>

<style>
.create-thread-page {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.page-header h2 {
    margin: 0;
    color: #333;
}

.alert {
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert h4 {
    margin: 0 0 10px 0;
    font-size: 16px;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

.form-container {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 30px;
}

.thread-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.form-group select,
.form-group input[type="text"],
.form-group textarea {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    font-family: inherit;
    transition: border-color 0.3s;
}

.form-group select:focus,
.form-group input[type="text"]:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #007bff;
}

.form-group textarea {
    resize: vertical;
    min-height: 200px;
}

.form-text {
    font-size: 12px;
    color: #666;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
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
    border: none;
    cursor: pointer;
    font-size: 14px;
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

.btn-outline {
    background: white;
    color: #333;
    border: 1px solid #ddd;
}

.btn-outline:hover {
    background: #f8f9fa;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
        width: 100%;
    }
}
</style>
