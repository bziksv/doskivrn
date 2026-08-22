{*Шаблон письмы для обратной связи*}


<div><b>Имя:</b> {$smarty.post.name}</div>
<div><b>Компания:</b> {$smarty.post.company}</div>
<div><b>E-mail:</b> {$smarty.post.mail}</div>
<div><b>Телефон:</b> {$smarty.post.phone}</div>
<div><b>Сообщение:</b></div>
<div>{$smarty.post.msg}</div>

<font color="gray"><br />
<br />-- 
<br />IP: {$smarty.server.REMOTE_ADDR}
</font>