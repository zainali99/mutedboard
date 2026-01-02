<div class="text-center">
    <h2>Welcome to <?= htmlspecialchars($framework) ?></h2>
    <p class="mb-2">Version <?= htmlspecialchars($version) ?></p>
    
    <div class="mt-2 mb-2">
        <h3>Framework Features:</h3>
        <ul style="list-style: none; padding: 0;">
            <?php foreach ($features as $feature): ?>
                <li style="padding: 0.5rem; margin: 0.5rem 0; background: #f8f9fa; border-radius: 4px;">
                    ✓ <?= htmlspecialchars($feature) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="mt-2">
        <h3>Getting Started</h3>
        <p>Edit the controller at <code>app/Controllers/Home.php</code></p>
        <p>Views are located in <code>app/views/</code></p>
        <p>Configure routes in <code>config/routes.php</code></p>
    </div>

    <div class="mt-2">
        <a href="/about" class="btn">Learn More</a>
    </div>
</div>
