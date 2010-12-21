{config_load file="file:global.conf"}{if $config_file}{config_load file="file:`$config_file`.conf"}{/if}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <title>{$entry.name}</title>
</head>

<body>
  {*$entry.content*}
  {$smarty.config.$topic}
</body>
</html>