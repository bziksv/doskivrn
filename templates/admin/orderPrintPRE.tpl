<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
  <META HTTP-EQUIV="Content-Language" content="ru">
  <META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
  <link href="/admin/css/xajax.css" type="text/css" rel="stylesheet" />
  <title>Бланк заказа №{$order->id} от {$order->order_date}</title>
  <link href="/admin/css/print.css" type="text/css" rel="stylesheet">
  <!--[if IE]><link rel="stylesheet" type="text/css" href="http://{$smarty.server.HTTP_HOST}/styles/ie.css"/><![endif]-->
  <script type="text/javascript" src="http://{$smarty.server.HTTP_HOST}/js/jquery.js"></script>
  <script type="text/javascript" src="http://{$smarty.server.HTTP_HOST}/js/jquery.tablesorter.js"></script>
  <script type="text/javascript" src="http://{$smarty.server.HTTP_HOST}/js/main.js"></script>
</head>
<body><div style="width:600px;margin:auto;">
<div class="ordermaind"></div>
<br /><br /><h2>Бланк заказа №{$order->id} от {$order->order_date}</h2><br /><br />
{$content}
<br /><br /><div class="ordermaind"><b><i>Заказ оформил _______________________________________________/ _________________ /</i></b></div><br />
</body>
</html>