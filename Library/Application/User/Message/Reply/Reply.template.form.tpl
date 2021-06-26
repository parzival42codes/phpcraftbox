<div class="card-container card-container--shadow"
     style="flex: 1;">
    <div class="card-container-header">
        {insert/language class="ApplicationUserMessageReply" path="/table/reply/title"
        language-de_DE="Nachricht beantworten"
        language-en_US="Reply Message"}
    </div>
    <div class="card-container-content">
        {$replyMessageHeader}
        {$replyMessageUser}
        {$replyMessage}
        {$replyMessageFooter}
    </div>
</div>
