<div class="text-center">{* это чтобы ссылки субменю не были слишком далеко от открывшей их «вкладки». если не нужно, тогда просто без этого дива *}
	<a href="{getUrl add=1 controller="sab_partner_info" action="description"}" class="link{if $user_params.action=='description'} current{/if}">{#menuDescription#}</a>

	<a href="{getUrl add=1 controller="sab_partner_info" action="payments"}" class="link{if $user_params.action=='payments'} current{/if}">{#menuPayments#}</a>

	<a href="{getUrl add=1 controller="sab_partner_info" action="becomepartner"}" class="link{if $user_params.action=='becomepartner'} current{/if}">{#menuBecomePartner#}</a>
</div>