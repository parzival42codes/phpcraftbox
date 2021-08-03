{$registerHeader}

<div class="card-container card-container--shadow">
    <div class="card-container-header">
        {insert/language class="ApplicationSearch" path="/form/search/search"
        language-de_DE="Suche"
        language-en_US="Search"}
    </div>
    <div class="card-container-content">
        {$search}
    </div>
</div>

{$content}

{$registerFooter}
