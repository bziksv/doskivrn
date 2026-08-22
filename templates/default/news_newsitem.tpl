<div class="box">
	<div class="date-hold">
		<span class="date">{$news->dataNews.0}</span>
		<strong>{$news->dataNews.1}</strong>
		<em>{$news->dataNews.2}</em>
	</div>
	<div class="text-hold">
		<p><a href="/news/view/{$news->id}/">{$news->newsTitle}</a></p>
		{$news->newsPreview}
	</div>
</div>