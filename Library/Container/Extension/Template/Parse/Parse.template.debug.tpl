[data=DebugTableTemplateParse]

[_config]
cssClass = "template-table-standard"
table = "DebugTableTemplateParse"
source = "DebugTableTemplateParse"

[parseClass]
titleHeader =  "{insert/language class="ContainerFactoryLanguage" path="/standard/template/class" import="template"}"
[parseString]
titleHeader = "{insert/language class="ContainerExtensionTemplateParse" path="/debug/table/parseString" import="template"
language-de_DE="Der zu parsende String"
language-en_US="The string to be parsed"}"
[differenceMicrotime]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/debug/table/microtimeDiff" import="template"}"
[differenceMemory]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/debug/table/memoryDiff" import="template"}"
[file]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/file" import="template"}"
[line]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/line" import="template"}"
[/data]

{create/table data="DebugTableTemplateParse"}
