/**
 * MutedBoard Component System
 * Automatic AJAX handling, state management, and component updates
 */

class ComponentSystem {
    constructor() {
        this.components = new Map();
        this.init();
    }

    /**
     * Initialize component system
     */
    init() {
        // Auto-discover and register all components on page
        this.discoverComponents();
        
        // Setup event delegation for component actions
        this.setupEventListeners();
    }

    /**
     * Discover all components in the DOM
     */
    discoverComponents() {
        const elements = document.querySelectorAll('[data-component]');
        elements.forEach(el => {
            const componentId = el.getAttribute('data-component-id');
            const componentName = el.getAttribute('data-component');
            
            if (componentId && componentName) {
                this.registerComponent(componentId, componentName, el);
            }
        });
    }

    /**
     * Register a component
     */
    registerComponent(componentId, componentName, element) {
        this.components.set(componentId, {
            name: componentName,
            element: element,
            state: {}
        });
    }

    /**
     * Get component by ID
     */
    getComponent(componentId) {
        return this.components.get(componentId);
    }

    /**
     * Setup event listeners for component actions
     */
    setupEventListeners() {
        // Handle form submissions FIRST (before click handlers)
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.hasAttribute('data-component-form')) {
                e.preventDefault();
                this.handleFormSubmit(form);
            }
        });

        // Handle clicks on elements with data-action
        document.addEventListener('click', (e) => {
            const actionEl = e.target.closest('[data-action]');
            if (actionEl) {
                // Don't handle if it's a submit button inside a component form
                const form = actionEl.closest('form[data-component-form]');
                if (form && actionEl.type === 'submit') {
                    return; // Let the form submit event handle this
                }
                
                e.preventDefault();
                console.log(actionEl);
                this.handleAction(actionEl);
            }
        });

        // Handle input changes with data-model
        document.addEventListener('input', (e) => {
            const input = e.target;
            if (input.hasAttribute('data-model')) {
                this.handleModelChange(input);
            }
        });
    }

    /**
     * Handle action button/link clicks
     */
    async handleAction(element) {
        const componentId = this.findComponentId(element);
        if (!componentId) return;

        const action = element.getAttribute('data-action');
        const params = this.extractParams(element);

        // Add loading state
        element.classList.add('loading');
        element.disabled = true;

        try {
            const result = await this.callComponentMethod(componentId, action, params);
            
            if (result.success) {
                // Update component if HTML returned
                if (result.html) {
                    this.updateComponent(componentId, result.html);
                }
                
                // Update state if provided
                if (result.state) {
                    this.updateState(componentId, result.state);
                }

                // Trigger custom event
                this.dispatchComponentEvent(componentId, 'action:' + action, result);
            } else {
                console.error('Component action failed:', result.error);
                alert(result.error || 'Action failed');
            }
        } catch (error) {
            console.error('Error calling component action:', error);
            alert('An error occurred');
        } finally {
            element.classList.remove('loading');
            element.disabled = false;
        }
    }

    /**
     * Handle form submission
     */
    async handleFormSubmit(form) {
        const componentId = this.findComponentId(form);
        if (!componentId) return;

        const action = form.querySelector('[data-action]')?.getAttribute('data-action') || 'submit';
        console.log('Form action:', action);
        
        const formData = new FormData(form);
        const params = {};
        
        // Debug: log all FormData entries
        console.log('FormData entries:');
        for (const [key, value] of formData.entries()) {
            console.log(`  ${key}: ${value}`);
            params[key] = value;
        }
        console.log('Final params:', params);

        // Add loading state
        const submitBtn = form.querySelector('[type="submit"]');
        if (submitBtn) {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        }

        try {
            const result = await this.callComponentMethod(componentId, action, params);
            
            if (result.success) {
                // Reset form if specified
                if (form.hasAttribute('data-reset-on-success')) {
                    form.reset();
                }

                // Update component
                if (result.html) {
                    this.updateComponent(componentId, result.html);
                }

                // Update state
                if (result.state) {
                    this.updateState(componentId, result.state);
                }

                // Show success message if provided
                if (result.message) {
                    this.showMessage(result.message, 'success');
                }

                // Trigger custom event
                this.dispatchComponentEvent(componentId, 'submit:' + action, result);
            } else {
                console.error('Form submission failed:', result.error);
                this.showMessage(result.error || 'Submission failed', 'error');
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            this.showMessage('An error occurred', 'error');
        } finally {
            if (submitBtn) {
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            }
        }
    }

    /**
     * Handle model binding (two-way data binding)
     */
    handleModelChange(input) {
        const componentId = this.findComponentId(input);
        if (!componentId) return;

        const component = this.getComponent(componentId);
        if (!component) return;

        const modelName = input.getAttribute('data-model');
        const value = input.value;

        // Update local state
        component.state[modelName] = value;
    }

    /**
     * Call a component method via AJAX
     */
    async callComponentMethod(componentId, method, params = {}) {
        const component = this.getComponent(componentId);
        if (!component) {
            throw new Error('Component not found: ' + componentId);
        }

        const payload = {
            component_id: componentId,
            component_name: component.name,
            method: method,
            params: params,
            props: this.extractPropsFromElement(component.element)
        };

        console.log('Component AJAX payload:', payload);

        const response = await fetch('/ajax/component', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        return await response.json();
    }

    /**
     * Update component HTML
     */
    updateComponent(componentId, html) {
        const component = this.getComponent(componentId);
        if (!component) return;

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        const newElement = tempDiv.firstElementChild;

        if (newElement) {
            component.element.replaceWith(newElement);
            
            // Re-register component with new element
            this.registerComponent(componentId, component.name, newElement);
        }
    }

    /**
     * Update component state
     */
    updateState(componentId, newState) {
        const component = this.getComponent(componentId);
        if (component) {
            component.state = { ...component.state, ...newState };
        }
    }

    /**
     * Find component ID from element or its parents
     */
    findComponentId(element) {
        const componentEl = element.closest('[data-component-id]');
        return componentEl ? componentEl.getAttribute('data-component-id') : null;
    }

    /**
     * Extract parameters from element's data attributes
     */
    extractParams(element) {
        const params = {};
        
        for (const [key, value] of Object.entries(element.dataset)) {
            if (key !== 'action' && key !== 'component' && key !== 'componentId') {
                params[key] = value;
            }
        }
        
        return params;
    }

    /**
     * Extract props from component element
     */
    extractPropsFromElement(element) {
        const propsAttr = element.getAttribute('data-props');
        if (propsAttr) {
            try {
                return JSON.parse(propsAttr);
            } catch (e) {
                console.error('Failed to parse component props:', e);
            }
        }
        return {};
    }

    /**
     * Dispatch custom event on component
     */
    dispatchComponentEvent(componentId, eventName, detail = {}) {
        const component = this.getComponent(componentId);
        if (component && component.element) {
            const event = new CustomEvent(eventName, { 
                detail: { componentId, ...detail },
                bubbles: true 
            });
            component.element.dispatchEvent(event);
        }
    }

    /**
     * Show message to user
     */
    showMessage(message, type = 'info') {
        // Simple alert for now - can be replaced with better UI
        if (type === 'error') {
            alert('Error: ' + message);
        } else {
            // You can implement a toast notification here
            console.log(type + ':', message);
        }
    }

    /**
     * Manually call a component method
     */
    async call(componentId, method, params = {}) {
        return await this.callComponentMethod(componentId, method, params);
    }

    /**
     * Manually refresh a component
     */
    async refresh(componentId) {
        return await this.callComponentMethod(componentId, 'refresh', {});
    }
}

// Initialize component system when DOM is ready
let componentSystem;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        componentSystem = new ComponentSystem();
        window.componentSystem = componentSystem;
    });
} else {
    componentSystem = new ComponentSystem();
    window.componentSystem = componentSystem;
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ComponentSystem;
}
