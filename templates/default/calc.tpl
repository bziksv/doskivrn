<div class="calc-hold">
	<form action="#">
		<fieldset>
			<div class="calc-box">
				<div class="box prod">
					<label for="baseprice">Тип продукции</label>
					<select id="baseprice" class="sel" onchange="$('#res1').html($('#baseprice :selected').html())">
						<option value=" 21500">Брус</option>
                                                <option value="20000">Доска обрезная</option>
					</select>
					<div class="squer">
                                                <br>
                                                <span> Брус 1м<sup>3</sup> = </span> <strong id="info_price"> 21500</strong><br>
						<span> Доска обрезная 1м<sup>3</sup> = </span> <strong id="info_price">20000</strong>

					</div>
				</div>
				<div class="box">
					<div class="row">
						<div class="col">
							<label for="width">Ширина</label>
							<select id="width" class="sel2" onchange="_width=this.value/1000;$('#res2').html($(this).val())">
								<option value="100">100</option>
								<option value="120">120</option>
								<option value="150">150</option>
								<option value="200">200</option>
							</select>
							<span class="metr">мм</span>
						</div>
						<div class="col">
							<label for="length">Длина</label>
							<select id="length" class="sel2" onchange="_length=this.value;$('#res3').html($(this).val())">
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
							</select>
							<span class="metr">м</span>
						</div>
						<div class="col alt">
							<label for="thickness">Толщина</label>
							<select id="width" class="sel2" onchange="_thickness=this.value/1000;$('#res4').html($(this).val())">
								<option value="25">25</option>
								<option value="30">30</option>
								<option value="40">40</option>
								<option value="50">50</option>
								<option value="100">100</option>
								<option value="120">120</option>
								<option value="150">150</option>
								<option value="200">200</option>
							</select>
							<span class="metr">мм</span>
						</div>
					</div>
				</div>
				<div class="box col-sh">
					<div class="row">
						<div class="col">
							<label for="cnt">Кол-во</label>
							<div class="text-inp">
								<input id="cnt" class="text" type="text" value="1" onchange="$('#res5').html($(this).val())" />
							</div>
							<span class="metr">шт</span>
						</div>
					</div>
				</div>
				<div class="sum-box">
					<span>Общая стоимость:</span>
					<strong id="results">0</strong>
				</div>
			</div>
			<ul class="buttons">
				<li><a onclick="yaCounter11319412.reachGoal('CALC1'); return true;" href="#" id="cgo">Рассчитать</a></li>
				<li><a onclick="yaCounter11319412.reachGoal('ORDER1'); return true;" href="#popup1" class="open-popup" onclick="calculation ();">Заказать</a></li>
			</ul>
		</fieldset>
	</form>
</div>
<p>Продукция компании «Велес» (Воронеж): <strong>пиломатериалы, доска обрезная, <a href='http://www.doskivrn.ru'>доска пола</a>, доска необрезная, доска шпунтованная</p>