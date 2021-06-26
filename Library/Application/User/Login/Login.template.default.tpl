<div class="flex-container">
    <div class="flex-container"
         style="flex: 2;flex-direction: column;">
        <div class="card-container card-container--shadow"
             style="flex: 1;">
            <div class="card-container-header">
                {insert/language class="ApplicationUserLogin" path="/form/title"
                language-de_DE="Einloggen"
                language-en_US="Login"}
            </div>
            <div class="card-container-content">
                {create/form form="Login" name="Header"}

                <div class="flex-container"
                     style="flex-direction: column;">
                    <div class="flex-container-sub">
                        <div class="flex-container-sub-item label">{create/form form="Login" name="email" get="label"}</div>
                        <div class="flex-container-sub-item"
                             style="flex: 2;">{create/form form="Login" name="email"}</div>
                        <div class="flex-container-sub-item info">{create/form form="Login" name="email" get="info"}</div>
                        <div class="flex-container-sub-item error">{create/form form="Login" name="email" get="error"}</div>
                    </div>

                    <div class="flex-container-sub">
                        <div class="flex-container-sub-item label">{create/form form="Login" name="password" get="label"}</div>
                        <div class="flex-container-sub-item"
                             style="flex: 2;">{create/form form="Login" name="password"}</div>
                        <div class="flex-container-sub-item info">{create/form form="Login" name="password" get="info"}</div>
                        <div class="flex-container-sub-item error">{create/form form="Login" name="password" get="error"}</div>
                    </div>

                </div>

                {create/form form="Login" name="Footer"}

            </div>
        </div>

    </div>
</div>
