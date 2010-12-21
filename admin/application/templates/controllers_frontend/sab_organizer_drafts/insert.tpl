{if $last_action_result==1}

  {#msgRecordAdded#}
  <script type="text/javascript">
    setTimeout("redir()", 1000);
    function redir() {ldelim}
      document.location.href="{getUrl controller="sab_organizer_brandsevents" del="id" action="list"}";
    {rdelim}
  </script>

{elseif $last_action_result==0}

  {#msgRecordAddError#}: {$last_action_result}

{else}

  {#msgRecordAddError#}: {$last_action_result}

{/if}

<p><a href="{getUrl controller="sab_organizer_brandsevents" del="id" action="list"}">{#linkBackToList#}</a></p>
