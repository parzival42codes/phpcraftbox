<!doctype html>
<html lang="{$lang}">
<head>
    <title>{$headerTitle}</title>
    <meta charset="UTF-8"/>
    <meta name="title"
          content="{$headerTitle}"/>
    <meta name="description"
          content="{$headerMetaDescription}"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
    <meta name="generator"
          content="CMS2000"/>


    {$headerCss}

    <script type="text/javascript">
        var _CMSUrl = "{$CMSUrl}";
        var _parseOnLoadExecuteCounterSet = {$hashLoadJSCounter};
        var _parseOnLoadExecuteCounter = 0;
        var _globalFunctions = [];
        var _globalDataTrigger = [];
        var _globalDataTriggerIndex = [];

        function callGloballFunction(...args) {
            try {
                var func = args.shift();
                var returnValue = _globalFunctions[func].apply(this, args);
            } catch (e) {
                console.warn(e);
            }
            return returnValue;
        }

        function pushDataTrigger(data, code) {
            var dataName = 'data-' + data;

            _globalDataTriggerIndex.push(dataName);

            if (typeof _globalDataTrigger[dataName] == 'undefined') {
                _globalDataTrigger[dataName] = [];
            }

            _globalDataTrigger[dataName].push(code);
        }

        function removeDataTrigger() {

        }

        function doDataTrigger(source) {
            _globalDataTriggerIndex.forEach(function (triggerKey) {

                var triggerElement = jQuery(source).find('.' + triggerKey);

                _globalDataTrigger[triggerKey].forEach(function (triggerValue) {
                    triggerValue.call(this, triggerElement);
                });

            });

        }

        {insert/positions position="/Page/header/javascript"}

        {$javascriptHeader}

        //<save>
        function parseOnLoad() {
            _parseOnLoadExecuteCounter++;
            if (_parseOnLoadExecuteCounter === _parseOnLoadExecuteCounterSet) {
                //</save>
                {$javascript}
                //<save>
                doDataTrigger(document);
            }
        }

        //</save>
    </script>

    {$coreLoad}

    <link rel="shortcut icon"
          href="data:image/x-icon;base64,{$headerLinkFavicon}"
          type="image/x-icon"/>
    <link rel="canonical"
          href="{$headerLinkRelCanonical}"
          id="pageCanonical"/>

    {$headerInclude}
</head>
<body {$pageData}>
<div id="Application">
    <div id="{$applicationID}">

        {$pageContent}
        {$pageContentAdditional}
        {$footerInclude}

        <CMS function="_ifthen"
             ifthen="assigned"
             wanted="0"
             assigned="cookieBanner"
        >
            <div style="width: 100%;position: fixed;bottom: 0;">
                <div id="cookieBanner"
                     class="flex-container">
                    <div class="flex-container-item">
                        {insert/language class="ContainerIndexPage" path="/cookie/banner"
                        language-de_DE="
Sie haben an dieser Stelle die Möglichkeit, die technisch nicht notwendigen Cookies abzulehnen oder zuzulassen.<br/>
Weitere Informationen hierzu finden Sie in unserer <a href='{insert/positions position="/_/base/url"}/privacy' target='_blank' rel='nofollow'>Datenschutzerklärung</a>"
                        language-en_US="This website uses cookies and Matomo for analysis and statistics. Cookies help us to improve the user-friendliness of our website. By continuing to use the website, you consent to its use. You can find further information on this in our <a href='{insert/positions position="/_/base/url"}/privacy' target='_blank' rel='nofollow'> data protection declaration </a>"}
                    </div>
                    <div class="flex-container-item">
                        Diese Internetseite verwendet außer den technisch notwendigen Cookies noch:<br/>
                        <ul>
                            {insert/positions position="/Page/CookieBanner/list"}
                        </ul>
                    </div>
                    <div class="flex-container-item"
                         style="text-align: right;">
                        <div class="cookieBannerButton btn"
                             data-value="1"
                        >
                            {insert/language class="ContainerIndexPage" path="/cookie/banner/consent/yes"
                            language-de_DE="Einverstanden"
                            language-en_US="Consent"}
                        </div>

                        <div class="cookieBannerButton btn"
                        data-value="0"
                        >
                            {insert/language class="ContainerIndexPage" path="/cookie/banner/consent/no"
                            language-de_DE="Abgelehnt"
                            language-en_US="No Consent"}
                        </div>
                    </div>
                </div>
            </div>
        </CMS>

        {insert/positions position="/ContainerIndexPage/Template/Positions/Footer/Include"}

        <script type="text/javascript">
            {$javascriptFooter}
        </script>

</body>
</html>
