<div class="blog-container">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Welcome to MutedBoard</h1>
            <p class="hero-subtitle">Community discussions and knowledge sharing</p>
            <div class="hero-stats">
                <span class="stat-item">
                    <strong><?= $total_threads ?></strong> Threads
                </span>
                <span class="stat-item">
                    <strong><?= count($groups) ?></strong> Groups
                </span>
            </div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/dashboard/create-thread" class="btn btn-hero">Create New Thread</a>
            <?php else: ?>
                <a href="/login" class="btn btn-hero">Join the Discussion</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="filters-section">
        <div class="filters-container">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search threads..." class="search-input">
                <button id="searchBtn" class="btn-search">
                    <svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </button>
            </div>

            <div class="filter-group">
                <label for="groupFilter">Group:</label>
                <select id="groupFilter" class="filter-select">
                    <option value="">All Groups</option>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?= $group['id'] ?>"><?= htmlspecialchars($group['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="sortFilter">Sort by:</label>
                <select id="sortFilter" class="filter-select">
                    <option value="recent">Most Recent</option>
                    <option value="oldest">Oldest First</option>
                    <option value="most_viewed">Most Viewed</option>
                    <option value="title">Title (A-Z)</option>
                </select>
            </div>

            <button id="clearFilters" class="btn-clear">Clear Filters</button>
        </div>
    </section>

    <!-- Threads Grid -->
    <section class="threads-section">
        <div id="threadsContainer" class="threads-grid">
            <?php foreach ($threads as $thread): ?>
                <article class="blog-card" data-thread-id="<?= $thread['id'] ?>">
                    <div class="blog-card-header">
                        <?php if ($thread['is_pinned']): ?>
                            <span class="badge badge-pinned">📌 Pinned</span>
                        <?php endif; ?>
                        <?php if ($thread['is_locked']): ?>
                            <span class="badge badge-locked">🔒 Locked</span>
                        <?php endif; ?>
                        <span class="badge badge-group"><?= htmlspecialchars($thread['group_name']) ?></span>
                    </div>

                    <h2 class="blog-card-title">
                        <a href="/dashboard/thread/<?= $thread['id'] ?>">
                            <?= htmlspecialchars($thread['title']) ?>
                        </a>
                    </h2>

                    <div class="blog-card-excerpt">
                        <?= htmlspecialchars(substr($thread['content'], 0, 180)) ?>
                        <?= strlen($thread['content']) > 180 ? '...' : '' ?>
                    </div>

                    <div class="blog-card-meta">
                        <span class="meta-author">
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                            </svg>
                            <?= htmlspecialchars($thread['author_username']) ?>
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

                    <a href="/dashboard/thread/<?= $thread['id'] ?>" class="blog-card-link">Read More →</a>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="loading-indicator" style="display: none;">
            <div class="spinner"></div>
            <p>Loading threads...</p>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="empty-state" style="display: none;">
            <svg width="64" height="64" viewBox="0 0 16 16" fill="currentColor">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
            </svg>
            <h3>No threads found</h3>
            <p>Try adjusting your filters or search terms</p>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer" class="pagination-container"></div>
    </section>
</div>

<style>
.blog-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--primary-color, #27651A) 0%, var(--primary-hover, #7CB342) 100%);
    color: white;
    padding: 60px 20px;
    text-align: center;
    margin-bottom: 40px;
}

.hero-content h1 {
    font-size: 3em;
    margin: 0 0 15px 0;
    font-weight: 700;
}

.hero-subtitle {
    font-size: 1.3em;
    margin: 0 0 25px 0;
    opacity: 0.95;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin: 25px 0;
    font-size: 1.1em;
}

.stat-item strong {
    display: block;
    font-size: 1.8em;
}

.btn-hero {
    background: white;
    color: var(--primary-color, #27651A);
    padding: 12px 30px;
    border-radius: 6px;
    font-weight: 600;
    display: inline-block;
    margin-top: 10px;
    transition: transform 0.3s;
}

.btn-hero:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Filters Section */
.filters-section {
    background: white;
    padding: 20px;
    margin: 0 20px 30px 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.filters-container {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: flex-end;
}

.search-box {
    display: flex;
    flex: 1;
    min-width: 250px;
}

.search-input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 4px 0 0 4px;
    font-size: 14px;
}

.btn-search {
    padding: 10px 15px;
    background: var(--primary-color, #27651A);
    color: white;
    border: none;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-search:hover {
    background: var(--primary-hover, #7CB342);
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.filter-group label {
    font-size: 12px;
    font-weight: 600;
    color: #666;
}

.filter-select {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    background: white;
    cursor: pointer;
}

.btn-clear {
    padding: 10px 20px;
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.3s;
}

.btn-clear:hover {
    background: #545b62;
}

/* Threads Grid */
.threads-section {
    padding: 0 20px 40px 20px;
}

.threads-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.blog-card {
    background: white;
    border-radius: 8px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
}

.blog-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.blog-card-header {
    display: flex;
    gap: 8px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.badge-pinned {
    background: #ffc107;
    color: #000;
}

.badge-locked {
    background: #dc3545;
    color: white;
}

.badge-group {
    background: #e9ecef;
    color: #495057;
}

.blog-card-title {
    margin: 0 0 15px 0;
    font-size: 1.5em;
    line-height: 1.3;
}

.blog-card-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s;
}

.blog-card-title a:hover {
    color: var(--primary-color, #27651A);
}

.blog-card-excerpt {
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
    flex-grow: 1;
}

.blog-card-meta {
    display: flex;
    gap: 15px;
    font-size: 13px;
    color: #999;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.blog-card-meta > span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.blog-card-link {
    color: var(--primary-color, #27651A);
    font-weight: 600;
    text-decoration: none;
    transition: transform 0.3s;
    display: inline-block;
}

.blog-card-link:hover {
    transform: translateX(5px);
}

/* Loading & Empty States */
.loading-indicator {
    text-align: center;
    padding: 40px;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid var(--primary-color, #27651A);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 15px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 8px;
}

.empty-state svg {
    color: #ccc;
    margin: 0 auto 20px;
}

.empty-state h3 {
    margin: 0 0 10px 0;
    color: #333;
}

.empty-state p {
    color: #666;
    margin: 0;
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 30px;
}

.pagination-btn {
    padding: 8px 15px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
}

.pagination-btn:hover {
    background: var(--primary-color, #27651A);
    color: white;
    border-color: var(--primary-color, #27651A);
}

.pagination-btn.active {
    background: var(--primary-color, #27651A);
    color: white;
    border-color: var(--primary-color, #27651A);
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-dots {
    padding: 8px;
    color: #999;
}

@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2em;
    }
    
    .hero-stats {
        flex-direction: column;
        gap: 20px;
    }
    
    .threads-grid {
        grid-template-columns: 1fr;
    }
    
    .filters-container {
        flex-direction: column;
    }
    
    .search-box {
        width: 100%;
    }
    
    .filter-group {
        width: 100%;
    }
}
</style>
