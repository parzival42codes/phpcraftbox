[data=DebugTableTemplate]

[_config]
cssClass = "template-table-standard"
table = "DebugTableTemplate"
source = "DebugTableTemplate"

[differenceMicrotime]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/debug/table/microtimeDiff" import="template"}"
[differenceMemory]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/debug/table/memoryDiff" import="template"}"
[file]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/file" import="template"}"
[line]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/line" import="template"}"
[/data]

{create/table data="DebugTableTemplate"}
