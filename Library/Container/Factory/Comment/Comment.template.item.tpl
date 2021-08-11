<div
        class="card-container card-container--shadow">
    <div class="card-container-header">
        <div style="display: flex;">
            <div style="flex: 1;text-align: left;">
                {$user} ({$userGroup})
            </div>
            <div style="flex: 1;text-align: right;">
                <div>{$date}</div>
            </div>
        </div>
    </div>
    <div class="card-container-content flex-container">
        <div style="flex: 4;">
            {$content}
        </div>
    </div>
    <div class="card-container-footer flex-container">
        <div style="flex: 4;">&nbsp;</div>
        <div style="flex: 1; text-align: right;"><a href="#"
                                 class="btn">{insert/language class="ContainerFactoryComment" path="/item/redButton"
                language-de_DE="Beitrag melden"
                language-en_US="Report / block post"}</a></div>
    </div>
</div>
