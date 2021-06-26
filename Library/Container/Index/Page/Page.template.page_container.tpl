<div id="{|class}-container-header" class="{|class}-container">
    {$pageContainerHeader}
</div>
<div id="{|class}-container-content" class="flex-container {|class}-container">
    <div style="flex:{insert/config class="ContainerIndexPage" path="/flexLeft"}">
        {$pageContainerContentLeft}
    </div>

    <div style="flex:{insert/config class="ContainerIndexPage" path="/flexMain"}">
        {$pageContainerContentMain}
    </div>
</div>
<div id="{|class}-container-footer" class="{|class}-container">
    {$pageContainerFooter}
</div>