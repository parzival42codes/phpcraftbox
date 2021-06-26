{create/table set="header" table="CoreErrorhandlerDebug" key="level" title="{insert/language class="CoreErrorhandler" path="/template/level" import="template"}"}
{create/table set="header" table="CoreErrorhandlerDebug" key="message" title="{insert/language class="CoreErrorhandler" path="/template/message"  import="template"}"}
{create/table set="header" table="CoreErrorhandlerDebug" key="details" title="{insert/language class="CoreErrorhandler" path="/template/details"  import="template"}"}
{create/table set="header" table="CoreErrorhandlerDebug" key="file" title="{insert/language class="Language" path="/standard/template/file" import="template"}"}
{create/table set="header" table="CoreErrorhandlerDebug" key="line" title="{insert/language class="Language" path="/standard/template/row" import="template"}"}
{create/table set="header" table="CoreErrorhandlerDebug" key="backtrace" title="{insert/language class="Language" path="/standard/template/backtrace" import="template"}"}

{create/table set="row" table="CoreErrorhandlerDebug" key="level"}
{create/table set="row" table="CoreErrorhandlerDebug" key="message"}
{create/table set="row" table="CoreErrorhandlerDebug" key="details"}
{create/table set="row" table="CoreErrorhandlerDebug" key="file"}
{create/table set="row" table="CoreErrorhandlerDebug" key="line"}
{create/table set="row" table="CoreErrorhandlerDebug" key="backtrace" modification="Dialog:test"}

{create/table set="display" table="CoreErrorhandlerDebug" source="CoreErrorhandlerDebug" standard="1" uniqid="CoreErrorhandlerDebug"}