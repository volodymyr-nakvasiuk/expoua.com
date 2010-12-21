<h4>Просмотр записи</h4>

<table>
    {foreach from=$entry.namedValues item=value key=name}
    <tr>
        <td>{$name}</td>
        <td>{$value}</td>
    </tr>
    {/foreach}
    {if $entry.reason}
        <tr>
            <td>Статус</td>
            <td>
                {if $ticket.approve_status>0}
                    Отказ принят
                {elseif $ticket.approve_status<0}
                    Отказ отклонен
                {else}
                    Новый
                {/if}

            </td>
        </tr>
        <tr>
            <td>Причина отклонения регистрации, указанная Вами</td>
            <td>{$entry.reason}</td>
        </tr>
    {/if}
</table>

<p>
<a href="{getUrl add='1' del='id' action='list'}">Вернуться к списку</a>
</p>