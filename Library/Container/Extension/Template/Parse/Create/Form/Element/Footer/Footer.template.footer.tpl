<input
        form="{$formId}"
        type="hidden"
        name="_ident"
        value="{$formId}"/>
<input
        form="{$formId}"
        type="hidden"
        name="_modify"
        value="{$modifyEncrypt}"/>
<div class="footer-button-container">
    <input type="reset"
           class="init footer-button-container-reset"
           value="{insert/language class="ContainerExtensionTemplateParseCreateForm" path="/form/reset" import="form"
           language-de_DE="Zurücksetzen"
           language-en_US="Reset"}">
    <input
            type="submit"
            class="reset footer-button-container-submit"
            value="{insert/language class="ContainerExtensionTemplateParseCreateForm" path="/form/submit" import="form"
            language-de_DE="Absenden"
            language-en_US="Submit"}">

</div>
</form>
