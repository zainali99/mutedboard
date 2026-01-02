<div class="text-center">
    <h2>About MutedBoard Framework</h2>
    
    <div class="mt-2" style="text-align: left; max-width: 800px; margin: 2rem auto;">
        <p class="mb-1"><?= htmlspecialchars($description) ?></p>
        
        <h3 class="mt-2">Architecture</h3>
        <p class="mb-1">
            MutedBoard follows the Model-View-Controller (MVC) pattern, separating your application logic
            into three distinct layers for better organization and maintainability.
        </p>

        <h3 class="mt-2">Core Components</h3>
        <ul>
            <li><strong>Router:</strong> Handles URL routing and dispatching requests</li>
            <li><strong>Controller:</strong> Processes requests and coordinates between models and views</li>
            <li><strong>Model:</strong> Manages data and business logic with PDO database support</li>
            <li><strong>View:</strong> Renders templates with multiple rendering options</li>
            <li><strong>Template Engine:</strong> Custom template syntax for clean view files</li>
        </ul>

        <h3 class="mt-2">Directory Structure</h3>
        <ul>
            <li><code>/app</code> - Application code (Controllers, Models, Views)</li>
            <li><code>/core</code> - Framework core classes</li>
            <li><code>/config</code> - Configuration files</li>
            <li><code>/public</code> - Web root (CSS, JS, Images)</li>
        </ul>
    </div>

    <div class="mt-2">
        <a href="/" class="btn btn-success">Back to Home</a>
    </div>
</div>
