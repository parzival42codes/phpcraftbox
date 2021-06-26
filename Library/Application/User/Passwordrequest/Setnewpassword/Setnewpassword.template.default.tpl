<div class="card-container card-container--shadow"
     style="flex: 1;">
    <div class="card-container-header">
        {insert/language class="ApplicationUserPasswordrequestSetnewpassword" path="/form/title"
        language-de_DE="Neues Password vergeben"
        language-en_US="Enter new Pasword"}
    </div>
    <div class="card-container-content">
        {$request}
        {$newPasswordHeader}
        {$newPassword}
        {$newPasswordFooter}
    </div>
</div>
