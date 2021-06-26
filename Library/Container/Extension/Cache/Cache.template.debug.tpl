[data=DebugTableCache]

[_config]
cssClass = "template-table-standard"
table = "DebugTableCache"
source = "DebugTableCache"

[isCreated]
titleHeader = "{insert/language class="ContainerExtensionCache" path="/debug/table/isCreated" import="template"
language-de_DE="Wurde erstellt"
language-en_US="Is Created"}"
rowParameter[] = "yesno"
[cacheClassName]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/class" import="template"}"
[cacheName]
titleHeader = "{insert/language class="ContainerExtensionCache_abstract" path="/debug/table/cacheName" import="template"
language-de_DE="Cache"
language-en_US="Cache"}"

[microtimeDiff]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/debug/table/microtimeDiff" import="template"}"
[memoryDiff]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/debug/table/memoryDiff" import="template"}"
[debugBacktraceFile]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/file" import="template"}"
[debugBacktraceLine]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/line" import="template"}"

[/data]

{create/table data="DebugTableCache"}
