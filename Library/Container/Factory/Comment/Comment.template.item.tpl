<div
     class="card-container card-container--shadow">
    <div class="card-container-header">
        <div style="display: flex;">
            <div style="flex: 1;text-align: left;">
                {$title}
            </div>
            <div style="flex: 1;text-align: right;">
                <div>{$category}</div>
                <div>{$date}</div>
            </div>
        </div>
    </div>
    <div class="card-container-content flex-container">
        <div style="flex: 4;">
            {$content}
        </div>
        <div style="flex: 1;">
            <div>{insert/language class="ApplicationBlog" path="/item/viewCount"
                language-de_DE="Ansichten"
                language-en_US="Views"}: {$viewCount}
            </div>
            <div>{insert/language class="ApplicationBlog" path="/item/commentCount"
                language-de_DE="Kommentare"
                language-en_US="Comments"}: {$commentCount}</div>
        </div>
    </div>
    <div class="card-container-footer">
        <a href="index.php?application=ApplicationBlogView&id={$id}&title={$titleUrl}"
           class="btn">{insert/language class="ApplicationBlog" path="/item/continueReading"
            language-de_DE="Weiterlesen"
            language-en_US="Continue reading"}</a>
    </div>
</div>
