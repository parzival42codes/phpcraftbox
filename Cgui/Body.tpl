<!doctype html>
<html lang="{$lang}">
<head>
    <title>cGUI</title>
    <meta charset="UTF-8"/>
    <meta name="title"
          content="{$headerTitle}"/>
    <meta name="description"
          content="{$headerMetaDescription}"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
    <meta name="generator"
          content="CMS2000"/>

    <link rel="shortcut icon"
          href="data:image/x-icon;base64,{$headerLinkFavicon}"
          type="image/x-icon"/>
    <link rel="canonical"
          href="{$headerLinkRelCanonical}"
          id="pageCanonical"/>

    <script type="text/javascript">
        {$javascriptHeader}
    </script>

    <style type="text/css">
        {$headerCss}
    </style>

</head>
<body>
<div id="Application">

    <form id="cguiInterface"
          method="post">

        <div style="display: flex; align-items: stretch;">
            <div class="card-container"
                 style="flex: 1;">
                <div class="card-container-header"><label for="cguiButtons">cGUI, Graphic Console Interface</label>
                </div>
                <div class="card-container-content">
                    <div class="btn"
                         id="cguiButtonsGo">Start
                    </div>
                </div>

            </div>
        </div>

        <div style="display: flex; align-items: stretch;">
            <div style="flex: 1;">
                <div style="display: flex;">
                    <div class="card-container"
                         style="flex: 1;">
                        <div class="card-container-header"><label for="cguiConsoleClass">Console Class</label></div>
                        <div class="card-container-content">
                            <input id="modul"
                                   name="modul"
                                   type="text"
                                   style="width: 100%;"
                                   value="ContainerFactoryModulInstall"/>
                        </div>
                    </div>
                </div>
                <div style="display: flex;">
                    <div class="card-container"
                         style="flex: 1;">
                        <div class="card-container-header"><label for="cguiConsoleClass">Command</label></div>
                        <div class="card-container-content">
                            <input id="command"
                                   name="command"cguiSeccommandureKey
                                   type="text"
                                   style="width: 100%;"
                                   value="Install"/>
                        </div>
                    </div>
                </div>
                <div class="card-container">
                    <div class="card-container-header"><label for="cguiSecureKey">Secure Key</label></div>
                    <div class="card-container-content">
                        <input id="securekey"
                               name="securekey"
                               type="text"
                               style="width: 100%;"
                               value="6L#ZLq2fYdBdPuhrocDgspnEbPw&okn3"
                        />
                    </div>
                </div>
                <div class="card-container">
                    <div class="card-container-header"><label for="cguiParameter">Parameter</label></div>
                    <div class="card-container-content">
                        <textarea id="parameter"
                                  name="parameter"
                                  style="width: 100%;
"
                        ></textarea>
                    </div>
                </div>
                <div class="card-container">
                    <div class="card-container-header"><label for="cguiParameter">Links</label></div>
                    <div class="card-container-content">
                        <ul>
                            <li>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div style="flex: 4;">
                <div class="card-container">
                    <div class="card-container-header">Messages</div>
                    <div class="card-container-content">
                        <div id="cguiMessages"
                             style="height: 20em;overflow: auto;"></div>
                    </div>
                </div>
            </div>

        </div>

    </form>
</div>

</body>
</html>
