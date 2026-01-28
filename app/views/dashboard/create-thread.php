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

