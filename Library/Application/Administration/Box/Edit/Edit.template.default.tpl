<div class="flex-container">
    <div style="flex: 1;text-align: left;">
        {$widgets}
    </div>
    <div style="flex:1;text-align: right;">
        <a href="index.php?application=ApplicationAdministrationBoxEdit&route=edit&id={$id}&action=new"
           class="btn"
        >{insert/language class="ApplicationAdministrationBoxEdit" path="/button/new"
            language-de_DE="Neue Box Erstellen"
            language-en_US="Create new Box"}</a>
    </div>
</div>
<hr/>
<div id="ApplicationAdministrationBoxEdit-Box">
    {$formHeader}
    {$content}
    {$formFooter}
</div>
