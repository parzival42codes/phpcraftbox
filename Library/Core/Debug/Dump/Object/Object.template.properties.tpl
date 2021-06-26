[data=DebugObjectDataProperties]

[_config]
cssClass = "template-table-standard"
table = "DebugObjectDataProperties"
source = "DebugObjectDataProperties"

[getProperty]
titleHeader = "{insert/language class="CoreDebugDumpObject" path="/table/properties/getProperty" import="template"
language-de_DE="Eigenschaft"
language-en_US="Property"}"
[type]
titleHeader = "{insert/language class="CoreDebugDumpObject" path="/table/properties/type" import="template"
language-de_DE="Typ"
language-en_US="Type"}"
[isStatic]
titleHeader = "{insert/language class="CoreDebugDumpObject" path="/table/properties/isStatic" import="template"
language-de_DE="Statisch ?"
language-en_US="Static"}"
[value]
titleHeader = "{insert/language class="CoreDebugDumpObject" path="/table/properties/value" import="template"
language-de_DE="Inhalt"
language-en_US="Value"}"
[getDocComment]
titleHeader = "{insert/language class="CoreDebugDumpObject" path="/table/properties/getDocComment" import="template"
language-de_DE="Document"
language-en_US="Document"}"
[/data]

{create/table data="DebugObjectDataProperties"}
