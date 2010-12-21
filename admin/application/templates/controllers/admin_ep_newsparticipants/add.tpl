{include file="common/contentVisualEdit.tpl" textarea="content"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objEventsList = Shelby_Backend.ListHelper.cloneObject('objEventsList');
objParticipantList = Shelby_Backend.ListHelper.cloneObject('objParticipantList');

objEventsList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'), new Array('organizer_name', 'Организатор'));
objEventsList.returnFieldId = 'events_id';
objEventsList.feedUrl = '{getUrl controller="admin_ep_brandsevents" action="list" feed="json"}';
objEventsList.writeForm();

objParticipantList.columns = new Array(new Array('id', 'Id'),  new Array('active', 'A'), new Array('name', 'Заголовок'));
objParticipantList.returnFieldId = 'events_pariticipants_id';
objParticipantList.feedUrl = '{getUrl controller="admin_ep_eventsparticipants" action="list" feed="json"}';
objParticipantList.writeForm();

</SCRIPT>

<h4>Добавляем новость участника</h4>

<FORM method="post" action="{getUrl add="1" action="insert"}">

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Событие:</TD>
  <TD><INPUT type="text" size="5" name="events_id" id="events_id" value=""> <INPUT type="button" onclick="objEventsList.showPopUp();" value="Выбрать"></TD>
 </TR>
 <TR>
  <TD>Участник выставки:</TD>
  <TD><INPUT type="text" size="5" name="events_participants_id" id="events_participants_id" value=""> <INPUT type="button" onclick="objParticipantList.showPopUp();" value="Выбрать"></TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="50" name="title" value=""></TD>
 </TR>
 <TR>
  <TD>Email:</TD>
  <TD><INPUT type="text" size="50" name="email"></TD>
 </TR>
 <TR>
  <TD>Email2:</TD>
  <TD><INPUT type="text" size="50" name="email2"></TD>
 </TR>
 <TR>
  <TD>Веб-сайт:</TD>
  <TD><INPUT type="text" size="50" name="web_address"></TD>
 </TR>
 <TR>
  <TD colspan="2">Полный текст:<BR />
   <TEXTAREA name="content" id="content" style="width:95%; height:500px;"></TEXTAREA>
  </TD>
 </TR>
 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Добавить"></TD></TR>
</TABLE>

</FORM>

<p><a href="{getUrl add="1" action="list"}">Вернуться к списку</a>