{*Шаблон письмы для обратной связи*}

<div>С сайта поступил новый заказ:</div>
<br />
<div><b>Тип продукции:</b> {$smarty.post.r1}</div>
<div><b>Ширина:</b> {$smarty.post.r2} мм</div>
<div><b>Длина:</b> {$smarty.post.r3} м</div>
<div><b>Толщина:</b> {$smarty.post.r4} мм</div>
<div><b>Кол-во:</b> {$smarty.post.r5} шт</div>
<div><b>Общая стоимость:</b> {$smarty.post.r6} руб</div>
<br />
<div><b>Имя:</b> {$smarty.post.name}</div>
<div><b>Компания:</b> {$smarty.post.company}</div>
<div><b>E-mail:</b> {$smarty.post.mail}</div>
<div><b>Телефон:</b> {$smarty.post.phone}</div>
<br />
<font color="gray"><br />
<br />-- 
<br />IP: {$smarty.server.REMOTE_ADDR}
</font>