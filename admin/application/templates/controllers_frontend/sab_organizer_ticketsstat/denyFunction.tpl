<script type="text/javascript">
    var denyUrl = "{getUrl add='1' registrationId='DENYID' reason='REASON' action='update'}";
    var stateReason = "{#stateReason#}";
    
{literal}

    deny = function(id)
    {
        if (reason = prompt(stateReason))
            location = denyUrl.replace("DENYID", id).replace("REASON", encodeURIComponent(reason));
    }

{/literal}    
</script>