<form method="post" action="{getUrl add='1' action='update'}">
<table>
    <tr>
        <th>{#Event#}</th>
        <th>{#Dates#}</th>
        <th>{#Stake#}</th>
        <th>{#NotificationLanguage#}</th>
        <th>{#NotificationMailsCSV#}</th>
    </tr>
    <tr>
        <td>{$event.brand_name|escape:"html"}</td>
        <td>{$event.date_from} - {$event.date_to}</td>
        <td>{$buyer.money}</td>
        <td>
            <select name="notification_language">
                <option value="en"{if $buyer.notification_language=='en'} selected="selected"{/if}>English</option>
                <option value="ru"{if $buyer.notification_language=='ru'} selected="selected"{/if}>Русский</option>
            </select>
        </td>
        <td><textarea name="notification_emails_csv">{$buyer.notification_emails_csv}</textarea></td>
    </tr>
    <tr>
        <td colspan="10" align="right"><input type="button" value="{#Cancel#}" onClick="document.location.href='{getUrl add="1" del="id" action="list"}';"><input type="submit" value="{#Update#}"></td>
    </tr>
</table>
</form>