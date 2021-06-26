<li class="subdir--close">
    <span class="subdir--folder-icon-text"
          style="{$menuActive};">
        <span class="subdir--folder-icon">
            <span class="subdir--folder-icon-open">{insert/positions position="/ContainerFactoryMenu/vertical/sub/folder/open" default="&#x1F4C2;"}</span>
            <span class="subdir--folder-icon-close"
                  style="display:block;">{insert/positions position="/ContainerFactoryMenu/vertical/sub/folder/close" default="&#x1F4C1;"}</span>
        </span>
        <span class="subdir--folder-text">
            &nbsp;{$title}
        </span>
    </span>
    <ul class="subDir">
        {$subDir}
    </ul>
</li>
