[data=DebugTableErrorhandler]

[_config]
cssClass = "template-table-standard"
table = "DebugTableErrorhandler"
source = "DebugTableErrorhandler"

[level]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/level" import="template"}"
[message]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/message" import="template"}"
[file]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/file" import="template"}"
[line]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/line" import="template"}"
[backtrace]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/backtrace" import="template"}"
[/data]

{create/table data="DebugTableErrorhandler"}
