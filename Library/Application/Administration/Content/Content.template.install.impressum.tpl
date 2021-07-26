<div style="flex: 1;"
     class="card-container card-container--shadow">
    <div class="card-container-header">Impressum</div>
    <div class="card-container-content">
        <CMS function="_markdown">
            Anbieter:
            {insert/config class="ApplicationAdministrationContent" path="/address/firstname"} {insert/config class="ApplicationAdministrationContent" path="/address/lastname"}
            {insert/config class="ApplicationAdministrationContent" path="/address/street"}
            {insert/config class="ApplicationAdministrationContent" path="/address/zipcode"} {insert/config class="ApplicationAdministrationContent" path="/address/city"}

            Kontakt:
            Telefon: {insert/config class="ApplicationAdministrationContent" path="/address/phone"}
            Telefax: {insert/config class="ApplicationAdministrationContent" path="/address/telefax"}
            E-Mail: {insert/config class="ApplicationAdministrationContent" path="/address/email"}
            Website: {insert/config class="ApplicationAdministrationContent" path="/address/website"}



            Bei redaktionellen Inhalten:

            Verantwortlich nach § 55 Abs.2 RStV
            Moritz Schreiberling
            Musterstraße 2
            80999 München
        </CMS>
    </div>
</div>
