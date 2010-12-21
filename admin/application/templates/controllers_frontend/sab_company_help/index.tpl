<SCRIPT type="text/javascript" src="{$document_root}js/adminFormValidator.js"></SCRIPT>

<SCRIPT type="text/javascript">
objValidator = Shelby_Backend.FormValidator.cloneObject('objValidator');

objValidator.headerMessage = "{#msgThereAreErrors#}:\n\n";
objValidator.addField('name_id', 'text', 3, "{#msgEnterName#}\n");
objValidator.addField('email_id', 'email', 0, "{#msgEnterEmail#}\n");
objValidator.addField('message_id', 'text', 10, "{#msgEnterMessage#}");
</SCRIPT>

<H4>Задайте Ваш вопрос</H4>

<FORM method="post" action="{getUrl add="1" action="insert"}" onsubmit="return objValidator.validate();">
{#captionName#}*: <INPUT type="text" name="name" id="name_id" size="20"><BR />
{#captionEmail#}*: <INPUT type="text" name="email" id="email_id" size="20"><BR />
{#captionMessage#}*:<BR />
<TEXTAREA name="message" style="width:100%; height:200px;" id="message_id"></TEXTAREA>

<p><CENTER><INPUT type="submit" value="{#sendAction#}"></CENTER></p>
</FORM>

<CENTER>
<div style="width:700px;" align="left">

{assign var="page" value=$HCms->getEntry(33)}
{$page.content}


</div>
</center>