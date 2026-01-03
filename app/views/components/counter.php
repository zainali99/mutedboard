<div 
    class="counter-component" 
    data-component="Counter" 
    data-component-id="<?= htmlspecialchars($componentId) ?>"
    data-props='<?= json_encode($props) ?>'
>
    <div class="counter-display">
        <h2>Count: <span class="count-value"><?= $count ?></span></h2>
    </div>

    <div class="counter-controls">
        <button class="btn btn-danger" data-action="decrement">-</button>
        <button class="btn btn-secondary" data-action="reset">Reset</button>
        <button class="btn btn-success" data-action="increment">+</button>
    </div>

    <div class="counter-set">
        <form data-component-form data-action="setCount">
            <input 
                type="number" 
                name="value" 
                placeholder="Set count..." 
                class="form-control"
            >
            <button type="submit" class="btn btn-primary">Set</button>
        </form>
    </div>
</div>

<style>
.counter-component {
    max-width: 400px;
    margin: 20px auto;
    padding: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    text-align: center;
}

.counter-display h2 {
    color: white;
    margin: 0 0 20px 0;
    font-size: 32px;
}

.count-value {
    display: inline-block;
    min-width: 60px;
    padding: 10px 20px;
    background: rgba(255,255,255,0.2);
    border-radius: 8px;
    font-weight: bold;
}

.counter-controls {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-bottom: 20px;
}

.counter-controls .btn {
    flex: 1;
    padding: 15px;
    font-size: 24px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.counter-controls .btn:hover {
    transform: scale(1.05);
}

.counter-controls .btn:active {
    transform: scale(0.95);
}

.counter-controls .btn.loading {
    opacity: 0.6;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.counter-set form {
    display: flex;
    gap: 10px;
}

.counter-set input {
    flex: 1;
    padding: 10px;
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 6px;
    background: rgba(255,255,255,0.9);
    font-size: 16px;
}

.counter-set .btn-primary {
    padding: 10px 20px;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.counter-set .btn-primary:hover {
    background: #2563eb;
}
</style>
