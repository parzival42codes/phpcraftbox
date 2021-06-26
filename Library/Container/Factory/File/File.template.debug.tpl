[data=DebugTableFile]

[_config]
cssClass = "template-table-standard"
table = "DebugTableFile"
source = "DebugTableFile"

[direction]
titleHeader = "{insert/language class="ContainerFactoryFile" path="/debug/table/direction" import="template"}"
[filenameTarget]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/file" import="template"}"
[debugBacktraceFile]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/file" import="template"}"
[debugBacktraceLine]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/line" import="template"}"
[backtrace]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/backtrace" import="template"}"
[/data]

{create/table data="DebugTableFile"}
