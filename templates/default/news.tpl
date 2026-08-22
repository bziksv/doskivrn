<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" >
<!--

	Mystery Group Design Studio (c)
	info@mygr.ru
	www.mystery-group.ru
	 
-->
<head>
	{include file='head.tpl'}
</head>
<body>
	<div id="wrapper">
		{include file='header.tpl'}
		<div id="main">
			<div class="container">
				<div class="chold">
					<div class="twocol">
						<div class="m1">
							{include file='sidebar.tpl'}
							<div class="cont-hold">
								
									<div class="title">
										<div class="heading">
																			
												<h2>Статьи</h2>
										
									
									</div>
									<div class="twocol">
										<div class="m1">
											<div class="bside">
												<span class="ad-eco-i"><a href="/pages/econom_fuel/">Экономия топлива на предприятии до 30%</a></span>
											</div>
											<div class="colhold">
												<div class="c1">
													<div class="news-cont">
														<div class="news-hold">
															{$content}
														</div>
														{if isset($listing) && $listing!=''}
														<div class="pager">
															<ul>
																{$listing}
															</ul>
														</div>
														{/if}
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		{include file='footer.tpl'}
	</div>
</body>
</html>