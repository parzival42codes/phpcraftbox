[data=DebugTableAutoload]

[_config]
cssClass = "template-table-standard"
table = "CoreAutoloadDebugTable"
source = "CoreAutoloadDebugTable"

[class]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/class" import="template"}"
[function]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/function" import="template"}"
[file]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/file" import="template"}"
[line]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/line" import="template"}"
[load]
titleHeader = "{insert/language class="CoreAutoload" path="/debug/table/autoload" import="template"}"
rowParameter[] = "yesno"
[/data]

{create/table data="DebugTableAutoload"}
