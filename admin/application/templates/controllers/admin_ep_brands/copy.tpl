{capture name="Url" assign="formActionUrl"}{getUrl add="1" action="insert" del="id"}{/capture}

{include file="controllers/admin_ep_brands/edit.tpl" formActionUrl=$formActionUrl formSubmitName="Копировать" formTitle="Копируем бренд"}