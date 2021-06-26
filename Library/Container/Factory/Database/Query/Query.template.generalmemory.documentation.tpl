<h2>{insert/language class="ContainerExtensionTemplateLoad" path="/documentation/loadFromCacheAndTemplate/descriptiion" import="documentation"
    language-de_DE="Select"
    language-en_US="Select"}</h2>

<div class="card-container card-container--shadow">
    <div class="card-container-content">
        <div class="btn copyToClipboard"
             data-id="{$copyToClipboard}">{insert/language class="ContainerFactoryLanguage" path="/standard/template/copyToClipboard" import="template"}</div>
        <div class="code" id="{$copyToClipboard}">
            /** @var ContainerFactoryDatabaseQuery $query */<br/>
            $query = Container::get('ContainerFactoryDatabaseQuery',<br/>
            __METHOD__ . '#select',<br/>
            true,<br/>
            ContainerFactoryDatabaseQuery::MODE_SELECT);<br/>
            $query->setTable('');<br/>
            $query->select('');<br/>
            <br/>
            $query->construct();<br/>
            $smtp = $query->execute();<br/>
            <br/>
            while ($smtpData = $smtp->fetch()) {<br />
            }<br/>
        </div>
    </div>
</div>

