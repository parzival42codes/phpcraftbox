[data=DebugTableQuery]

[_config]
cssClass = "template-table-standard"
table = "DebugTableQuery"
source = "DebugTableQuery"

[direction]
titleHeader = "{insert/language class="ContainerFactoryDatabaseQuery" path="/debug/table/direction" import="template"
language-de_DE="Richtung"
language-en_US="Direction"}"
[query]
titleHeader = "{insert/language class="ContainerFactoryDatabaseQuery" path="/debug/table/query" import="template"}"
[selectExplainData]
titleHeader = "{insert/language class="ContainerFactoryDatabaseQuery" path="/debug/table/query" import="template"}"
[data]
titleHeader = "{insert/language class="ContainerFactoryDatabaseQuery" path="/debug/table/query" import="template"}"
[databaseConnection]
titleHeader = "{insert/language class="ContainerFactoryDatabaseQuery" path="/debug/table/databaseConnection" import="template"}"
[table]
titleHeader = "{insert/language class="ContainerFactoryDatabaseQuery" path="/debug/table/table" import="template"}"
[rowCount]
titleHeader = "{insert/language class="ContainerFactoryDatabaseQuery" path="/debug/table/rowCount" import="template"}"
[microtimeDiff]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/debug/table/microtimeDiff" import="template"}"
[memoryDiff]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/debug/table/memoryDiff" import="template"}"
[debugBacktraceFile]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/file" import="template"}"
[debugBacktraceLine]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/line" import="template"}"
[backtrace]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/backtrace" import="template"}"
[/data]

{create/table data="DebugTableQuery"}
