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

    </div>
</div>

{insert/positions position="/ContainerIndexPage/Template/Positions/Footer/Include"}

<script type="text/javascript">
    {$javascriptFooter}
</script>

</body>
</html>
