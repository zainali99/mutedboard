/**
 * MutedBoard Frontend Framework
 * Main JavaScript class to manage AJAX requests and frontend interactions
 */

class MutedBoard {
    constructor() {
        this.baseURL = window.location.origin;
        this.ajaxURL = this.baseURL + '/ajax';
        this.currentFilters = {
            group_id: '',
            search: '',
            sort: 'recent',
            page: 1
        };
        this.init();
    }

    /**
     * Initialize the framework
     */
    init() {
        this.setupEventListeners();
        console.log('MutedBoard Framework initialized');
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Search functionality
        const searchBtn = document.getElementById('searchBtn');
        const searchInput = document.getElementById('searchInput');
        
        if (searchBtn) {
            searchBtn.addEventListener('click', () => this.handleSearch());
        }
        
        if (searchInput) {
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.handleSearch();
                }
            });
        }

        // Filter changes
        const groupFilter = document.getElementById('groupFilter');
        const sortFilter = document.getElementById('sortFilter');
        
        if (groupFilter) {
            groupFilter.addEventListener('change', (e) => {
                this.currentFilters.group_id = e.target.value;
                this.currentFilters.page = 1;
                this.loadThreads();
            });
        }
        
        if (sortFilter) {
            sortFilter.addEventListener('change', (e) => {
                this.currentFilters.sort = e.target.value;
                this.currentFilters.page = 1;
                this.loadThreads();
            });
        }

        // Clear filters
        const clearBtn = document.getElementById('clearFilters');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => this.clearFilters());
        }
    }

    /**
     * Handle search
     */
    handleSearch() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            this.currentFilters.search = searchInput.value.trim();
            this.currentFilters.page = 1;
            this.loadThreads();
        }
    }

    /**
     * Clear all filters
     */
    clearFilters() {
        this.currentFilters = {
            group_id: '',
            search: '',
            sort: 'recent',
            page: 1
        };
        
        const searchInput = document.getElementById('searchInput');
        const groupFilter = document.getElementById('groupFilter');
        const sortFilter = document.getElementById('sortFilter');
        
        if (searchInput) searchInput.value = '';
        if (groupFilter) groupFilter.value = '';
        if (sortFilter) sortFilter.value = 'recent';
        
        this.loadThreads();
    }

    /**
     * Load threads with current filters
     */
    async loadThreads() {
        const container = document.getElementById('threadsContainer');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const emptyState = document.getElementById('emptyState');
        
        if (!container) return;

        // Show loading
        if (loadingIndicator) loadingIndicator.style.display = 'block';
        if (emptyState) emptyState.style.display = 'none';
        container.style.opacity = '0.5';

        try {
            const params = new URLSearchParams(this.currentFilters);
            const response = await this.ajax('GET', `/get-threads?${params}`);
            
            if (response.success) {
                this.renderThreads(response.data.threads);
                this.renderPagination(response.data.pagination);
            } else {
                this.showError(response.error || 'Failed to load threads');
            }
        } catch (error) {
            this.showError(error.message);
        } finally {
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            container.style.opacity = '1';
        }
    }

    /**
     * Render threads
     */
    renderThreads(threads) {
        const container = document.getElementById('threadsContainer');
        const emptyState = document.getElementById('emptyState');
        
        if (!container) return;

        if (!threads || threads.length === 0) {
            container.innerHTML = '';
            if (emptyState) emptyState.style.display = 'block';
            return;
        }

        if (emptyState) emptyState.style.display = 'none';

        container.innerHTML = threads.map(thread => this.createThreadCard(thread)).join('');
    }

    /**
     * Create thread card HTML
     */
    createThreadCard(thread) {
        const excerpt = thread.content.substring(0, 180) + (thread.content.length > 180 ? '...' : '');
        const badges = [];
        
        if (thread.is_pinned) {
            badges.push('<span class="badge badge-pinned">📌 Pinned</span>');
        }
        if (thread.is_locked) {
            badges.push('<span class="badge badge-locked">🔒 Locked</span>');
        }
        badges.push(`<span class="badge badge-group">${this.escapeHtml(thread.group_name)}</span>`);

        return `
            <article class="blog-card" data-thread-id="${thread.id}">
                <div class="blog-card-header">
                    ${badges.join('')}
                </div>
                <h2 class="blog-card-title">
                    <a href="/dashboard/thread/${thread.id}">
                        ${this.escapeHtml(thread.title)}
                    </a>
                </h2>
                <div class="blog-card-excerpt">
                    ${this.escapeHtml(excerpt)}
                </div>
                <div class="blog-card-meta">
                    <span class="meta-author">
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                        </svg>
                        ${this.escapeHtml(thread.author_username)}
                    </span>
                    <span class="meta-views">
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                        ${thread.views}
                    </span>
                    <span class="meta-date">
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                        </svg>
                        ${this.formatDate(thread.created_at)}
                    </span>
                </div>
                <a href="/dashboard/thread/${thread.id}" class="blog-card-link">Read More →</a>
            </article>
        `;
    }

    /**
     * Render pagination
     */
    renderPagination(pagination) {
        const container = document.getElementById('paginationContainer');
        if (!container) return;

        if (pagination.total_pages <= 1) {
            container.innerHTML = '';
            return;
        }

        const buttons = [];
        
        // Previous button
        buttons.push(`
            <button class="pagination-btn" ${pagination.current_page === 1 ? 'disabled' : ''} 
                    onclick="mutedBoard.changePage(${pagination.current_page - 1})">
                ← Previous
            </button>
        `);

        // Page numbers
        for (let i = 1; i <= pagination.total_pages; i++) {
            if (
                i === 1 ||
                i === pagination.total_pages ||
                (i >= pagination.current_page - 2 && i <= pagination.current_page + 2)
            ) {
                buttons.push(`
                    <button class="pagination-btn ${i === pagination.current_page ? 'active' : ''}"
                            onclick="mutedBoard.changePage(${i})">
                        ${i}
                    </button>
                `);
            } else if (
                i === pagination.current_page - 3 ||
                i === pagination.current_page + 3
            ) {
                buttons.push('<span class="pagination-dots">...</span>');
            }
        }

        // Next button
        buttons.push(`
            <button class="pagination-btn" ${pagination.current_page === pagination.total_pages ? 'disabled' : ''}
                    onclick="mutedBoard.changePage(${pagination.current_page + 1})">
                Next →
            </button>
        `);

        container.innerHTML = buttons.join('');
    }

    /**
     * Change page
     */
    changePage(page) {
        this.currentFilters.page = page;
        this.loadThreads();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /**
     * AJAX Request
     */
    async ajax(method, url, data = null) {
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        if (data && method !== 'GET') {
            options.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(this.ajaxURL + url, options);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('AJAX Error:', error);
            throw error;
        }
    }

    /**
     * Show error message
     */
    showError(message) {
        console.error('Error:', message);
        // You can implement a toast notification system here
        alert('Error: ' + message);
    }

    /**
     * Escape HTML to prevent XSS
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Format date
     */
    formatDate(dateString) {
        const date = new Date(dateString);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return `${months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
    }

    /**
     * Get thread by ID
     */
    async getThread(id) {
        return await this.ajax('GET', `/get-thread?id=${id}`);
    }

    /**
     * Get all groups
     */
    async getGroups() {
        return await this.ajax('GET', '/get-groups');
    }

    /**
     * Increment thread view
     */
    async incrementView(id) {
        return await this.ajax('POST', '/increment-view', { id: id });
    }

    /**
     * Search threads
     */
    async search(query) {
        return await this.ajax('GET', `/search?q=${encodeURIComponent(query)}`);
    }
}

// Initialize the framework when DOM is ready
let mutedBoard;
document.addEventListener('DOMContentLoaded', function() {
    mutedBoard = new MutedBoard();
});
