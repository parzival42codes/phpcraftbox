<div class="card-container card-container--shadow">
    <div class="card-container-header">
        {insert/language class="ApplicationUserEdit" path="/form/information/title"
        language-de_DE="Regestrieren"
        language-en_US="Registration"}
    </div>
    <div class="card-container-content flex-container">
        <div class="flex-container-item" style="flex: 3;">
            {$registerHeader}
            {$register}
            {$registerPassword}
            {$registerActivate}
            {$registerFooter}
        </div>
        <div class="flex-container-item">
            <div class="flex-container flex-container-item" style="flex-direction: row;">
                <div class="flex-container-item">
                    {insert/language class="ApplicationUserEdit" path="/infoUserId"
                    language-de_DE="User ID"
                    language-en_US="User ID"}
                </div>
                <div class="flex-container-item">
                    {$infoUserId}
                </div>
            </div>
            <div class="flex-container flex-container-item" style="flex-direction: row;">
                <div class="flex-container-item">
                    {insert/language class="ApplicationUserEdit" path="/infoRegisterDate"
                    language-de_DE="Registrierungsdatum"
                    language-en_US="Register Date"}
                </div>
                <div class="flex-container-item">
                    {$infoRegisterDate}
                </div>
            </div>
        </div>
    </div>
</div>
