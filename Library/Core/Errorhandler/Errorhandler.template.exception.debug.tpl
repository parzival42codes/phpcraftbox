<div class="card-container card-container--shadow">
    <div class="card-container-header">
        Debug
    </div>
    <div class="card-container-content">
        Line & Row: {$lineAndRow}
        <hr/>
        {$debug}
    </div>
</div>


<div class="card-container card-container--shadow">
    <div class="card-container-header">
        Dump
    </div>
    <div class="card-container-content">
        {$dump}
    </div>
</div>

<div class="card-container card-container--shadow">
    <div class="card-container-header">
        Exception Data
    </div>
    <div class="card-container-content">
        get_class($exception): {$getClassException}
        <hr/>
        DetailedException From: {$exceptionClass}
    </div>
</div>

<div class="card-container card-container--shadow">
    <div class="card-container-header">
        Original Message
    </div>
    <div class="card-container-content">
        {$originalMessage}
    </div>
</div>


<div class="card-container card-container--shadow">
    <div class="card-container-header">
        Backtrace
    </div>
    <div class="card-container-content">
        {$outputBacktrace}
    </div>
</div>
