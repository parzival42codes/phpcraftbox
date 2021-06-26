[data=DebugTableCrypt]

[_config]
cssClass = "template-table-standard"
table = "DebugTableCrypt"
source = "DebugTableCrypt"

[action]
titleHeader = "{insert/language class="ContainerFactoryCrypt" path="/debug/table/action" import="template"
language-de_DE="Verschlüsseln / Entschlüsseln"
language-en_US="Encrypt / Decrypt"}"
[length]
titleHeader = "{insert/language class="ContainerFactoryCrypt" path="/debug/table/length" import="template"
language-de_DE="Länge"
language-en_US="Lenght"}"
[cipher]
titleHeader = "{insert/language class="ContainerFactoryCrypt" path="/debug/table/cipher" import="template"
language-de_DE="Methode"
language-en_US="Method"}"
[microtimeDiff]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/debug/table/microtimeDiff"}"
[memoryDiff]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/debug/table/memoryDiff" import="template"}"
[debugBacktraceFile]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/file" import="template"}"
[debugBacktraceLine]
titleHeader = "{insert/language class="ContainerFactoryLanguage" path="/standard/template/line" import="template"}"
[/data]

{create/table data="DebugTableCrypt"}
