{create/table set="header" table="DebugTableProfiler" key="id" title="{insert/language class="ContainerFactoryLanguage" path="/standard/template/id" import="template"}"}
{create/table set="header" table="DebugTableProfiler" key="level" title="{insert/language class="ContainerFactoryLanguage" path="/standard/template/level" import="template"}"}
{create/table set="header" table="DebugTableProfiler" key="microtime" title="{insert/language class="ContainerFactoryLanguage" path="/standard/template/mircrotime"  import="template"}"}
{create/table set="header" table="DebugTableProfiler" key="memory" title="{insert/language class="ContainerFactoryLanguage" path="/standard/template/memory"  import="template"}"}
{create/table set="header" table="DebugTableProfiler" key="backtrace" title="{insert/language class="ContainerFactoryLanguage" path="/standard/template/backtrace" import="template"}"}

{create/table set="row" table="DebugTableProfiler" key="id"}
{create/table set="row" table="DebugTableProfiler" key="level"}
{create/table set="row" table="DebugTableProfiler" key="microtime"}
{create/table set="row" table="DebugTableProfiler" key="memory"}
{create/table set="row" table="DebugTableProfiler" key="backtrace"}

{create/table set="display" table="DebugTableProfiler" source="DebugTableProfiler" standard="1" uniqid="DebugTableProfiler"}