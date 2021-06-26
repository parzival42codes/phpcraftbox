[data=BottleneckTableQuery]

[_config]
cssClass = "template-table-standard"
table = "BottleneckTableQuery"
source = "BottleneckTableQuery"

[direction]

[bottleneck]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/class" import="template"}"
[bottleneckMeter]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/class" import="template"}"
[arguments]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/arguments" import="template"}"
[class]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/class" import="template"}"
[method]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/method" import="template"}"
[differenceMicrotime]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/debug/table/microtimeDiff" import="template"}"
[differenceMemory]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/debug/table/memoryDiff" import="template"}"
[file]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/file" import="template"}"
[line]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/backtrace" import="template"}"
[backtrace]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/line" import="template"}"
[/data]

{create/table data="BottleneckTableQuery"}
