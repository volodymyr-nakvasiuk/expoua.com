{*
	<!-- ������ ��� ������
		notlink - ������� true, ���� �����, ����� ������ ������ div, � �� ������
		class - �������������� CSS-������
		href - ����� ��� ��������
		title - ����������� ���������
		other - ������ � ������� �����������
		content - ��, ��� ����� ������� ������
		onclick
		
		������ ������:
		{ include file="coolbutton.php" content="������ �� ������" notlink=1 class="hello world" other='id="exampleID" onclick="void(0);"'}
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