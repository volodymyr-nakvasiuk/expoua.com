{strip}
{if !$action}{assign var="action" value="index"}{/if}
{if !empty($session_user_allow[$controller][$action]) &&  $controller == 'admin_ep_requests'}
  <li>
  {if $controller == $user_params.controller}
    <a class="leftMenuSubElement" href="{getUrl controller=$controller action="index"}"><strong>{$text}</strong></a>
  {else}
    <a class="leftMenuSubElement" href="{getUrl controller=$controller action="index"}">{$text}</a>
  {/if}
  </li>
{elseif !empty($session_user_allow[$controller]) &&  $controller == 'admin_ep_drafts'}
  <li>
  {if $controller == $user_params.controller}
    <a class="leftMenuSubElement" href="{getUrl controller=$controller action="list"}"><strong>{$text}</strong></a>
  {else}
    <a class="leftMenuSubElement" href="{getUrl controller=$controller action="list"}">{$text}</a>
  {/if}
  </li>
{elseif !empty($session_user_allow[$controller]) &&  $controller == 'admin_ep_analyze'}
  <li>
  {if $controller == $user_params.controller}
    <a class="leftMenuSubElement" href="{getUrl controller=$controller}"><strong>{$text}</strong></a>
  {else}
    <a class="leftMenuSubElement" href="{getUrl controller=$controller}">{$text}</a>
  {/if}
  </li>
{elseif !empty($session_user_allow[$controller][$action])}
  <li>
  {if $controller == $user_params.controller}
    <a class="leftMenuSubElement" href="{getUrl controller=$controller action="list"}"{if $count} style="color:#900"{/if}><strong>{$text}{if $count}&nbsp;({$count}){/if}</strong></a>
  {else}
    <a class="leftMenuSubElement" href="{getUrl controller=$controller action="list"}"{if $count} style="color:#900"{/if}>{$text}{if $count}&nbsp;({$count}){/if}</a>
  {/if}
  </li>
{/if}
{/strip}