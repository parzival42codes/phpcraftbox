<div id="CMSFooter">
    <div class="card-container card-container--shadow">
        <div class="card-container-content"
             style="display: flex;">
            <div style="flex: 1;">
                {insert/positions position="/Content/Footer/Left"}
            </div>
            <div style="flex: 1;">
                {insert/positions position="/Content/Footer/Middle"}
            </div>
            <div style="flex: 1;">
                {insert/positions position="/Content/Footer/Right"}
                <p>
                    <a href="{insert/positions position="/_/base/url"}/impressum">{insert/language class="ContainerIndexPageBox" path="/footer/link/impressum"
                        language-de_DE="Impressum"
                        language-en_US="Impressum"}</a>
                </p>
                <p>
                    <a href="{insert/positions position="/_/base/url"}/privacy">{insert/language class="ContainerIndexPageBox" path="/footer/link/privacy"
                        language-de_DE="Datenschutz"
                        language-en_US="Privacy"}</a>
                </p>
            </div>
        </div>
    </div>
</div>
