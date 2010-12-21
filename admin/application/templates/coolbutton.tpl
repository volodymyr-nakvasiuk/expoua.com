{*
	<!-- Шаблон для кнопок
		notlink - задайте true, если нужно, чтобы шаблон вернул div, а не ссылку
		class - дополнительные CSS-классы
		href - адрес для перехода
		title - всплывающая подсказка
		other - строка с другими параметрами
		content - то, что будет текстом кнопки
		onclick
		
		пример вызова:
		{ include file="coolbutton.php" content="Ничего не делать" notlink=1 class="hello world" other='id="exampleID" onclick="void(0);"'}
	-->
*}
{ if $title }
{ assign var=titleAttribute value=" title=$title"}
{ /if }
{ if $class }
{ assign var=classStr value=" $class"}
{ /if }
{ if $other }
{ assign var=otherStr value=" $other"}
{ /if }
{ if $onclick }
{ assign var=onclickStr value=" onclick=$onclick"}
{ /if }

{ if $notlink }
<div class="cool-button{$classStr}"{$titleAttribute}{$otherAttr}{$onclickStr}>
  <div class="wrapper bg">
	<div class="content-wrapper bg">
	 <span class="value bg">{$content}</span>
	</div>
	<div class="bottom bg"><!-- --></div>
  </div>
</div>
{ else }
<a class="cool-button{$classStr}" href="{$href}"{$titleAttribute}{$otherAttr}{$onclickStr}>
  <div class="wrapper bg">
	<div class="content-wrapper bg">
	 <span class="value bg">{$content}</span>
	</div>
	<div class="bottom bg"><!-- --></div>
  </div>
</a>
{ /if }