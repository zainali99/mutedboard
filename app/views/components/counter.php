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

