<form>
<span class="ApplicationAdministrationModul_button ApplicationAdministrationModul_button_{$button}"
      data-status="{$status}"
      data-action="{$action}"
      data-modul="{$class}">
        <label class="template--form--icon-switch"
               for="icon-switch{$hash}">
            <input type="checkbox"
                   {$disabled}
                   id="icon-switch{$hash}"
                   {$checked}>
            <span class="slider round {$disabledClass}"></span>
        </label>
</span>
</form>

