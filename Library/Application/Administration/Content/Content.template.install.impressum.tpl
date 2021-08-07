<div style="flex: 1;"
     class="card-container card-container--shadow">
    <div class="card-container-header">Impressum</div>
    <div class="card-container-content">
        <CMS function="_markdown">
            ## {insert/language class="ApplicationAdministrationContent" path="/header/provider"
            language-de_DE="Anbieter"
            language-en_US="Provider"}:
            {insert/config class="ApplicationAdministrationContent" path="/address/firstname"} {insert/config class="ApplicationAdministrationContent" path="/address/lastname"}
            {insert/config class="ApplicationAdministrationContent" path="/address/street"}
            {insert/config class="ApplicationAdministrationContent" path="/address/zipcode"} {insert/config class="ApplicationAdministrationContent" path="/address/city"}
            ## {insert/language class="ApplicationAdministrationContent" path="/address/contact"
            language-de_DE="Kontakt"
            language-en_US="Contact"}
            {insert/language class="ApplicationAdministrationContent" path="/address/phone"
            language-de_DE="Telefon"
            language-en_US="Phone"}: {insert/config class="ApplicationAdministrationContent" path="/address/phone"}
            {insert/language class="ApplicationAdministrationContent" path="/address/telefax"
            language-de_DE="Fax"
            language-en_US="Telefax"}: {insert/config class="ApplicationAdministrationContent" path="/address/telefax"}
            {insert/language class="ApplicationAdministrationContent" path="/address/email"
            language-de_DE="E-Mail"
            language-en_US="E-Mail"}: {insert/config class="ApplicationAdministrationContent" path="/address/email"}
            {insert/language class="ApplicationAdministrationContent" path="/address/website"
            language-de_DE="Internetseite"
            language-en_US="Website"}: <a href="{insert/config class="ApplicationAdministrationContent" path="/address/website"}">{insert/config class="ApplicationAdministrationContent" path="/address/website"}</a>
            ## {insert/language class="ApplicationAdministrationContent" path="/header/editorial"
            language-de_DE="Bei redaktionellen Inhalten"
            language-en_US="For editorial content"}
            {insert/language class="ApplicationAdministrationContent" path="/header/editorial/text"
            language-de_DE="Verantwortlich nach"
            language-en_US="Responsible after"} § 55 Abs.2 RStV
            {insert/config class="ApplicationAdministrationContent" path="/editorial/firstname"} {insert/config class="ApplicationAdministrationContent" path="/editorial/lastname"}
            {insert/config class="ApplicationAdministrationContent" path="/editorial/street"}
            {insert/config class="ApplicationAdministrationContent" path="/editorial/zipcode"} {insert/config class="ApplicationAdministrationContent" path="/editorial/city"}
        </CMS>
    </div>
</div>
