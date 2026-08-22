{php}

   $cats = [

        [
            'link' => '/pages/obresnaya_doska/',
            'text' => 'Обрезная доска',
            'img' => '/img/obres.png',
            'img-alt' => 'Обрезная доска в Воронеже',
        ],
		
        [
            'link' => '/pages/doska_suhaya_strogannaja/',
            'text' => 'Доска сухая строганная',
            'img' => '/img/img05-1.jpg',
            'img-alt' => 'Доска сухая в Воронеже',
        ],

        [
            'link' => '/pages/spuntovannya_doska/',
            'text' => 'Шпунтованная доска',
            'img' => '/img/spuntov.png',
            'img-alt' => 'Шпунтованная доска',
        ],

		[
            'link' => '/pages/izgotovlenie_derevyannyh_konstrukcij/',
            'text' => 'Изготовление деревянных конструкций',
            'img' => '/img/izg.jpg',
            'img-alt' => 'Изготовление деревянных конструкций',
        ],

           [
             'link' => '/pages/armatura_stekloplastikovaya/',
            'text' => 'Арматура стеклопластиковая',
            'img' => '/img/armatura_stekloplastik.png',
            'img-alt' => 'Арматура стеклопластиковая в Воронеже',
        ],
                [
             'link' => '/pages/osb_orientirovanno_struzhechnaya_plita/',
            'text' => 'Ориентированно-стружечная плита',
            'img' => '/img/orientirovochno_struzhechnaya_plita.png',
            'img-alt' => 'Ориентированно-стружечная плита в Воронеже',
        ],

                [
             'link' => '/pages/doska_pola/ ',
            'text' => 'Доска пола',
            'img' => '/img/doska_pola22.png',
            'img-alt' => 'Доска пола плита в Воронеже',
        ],

                [
             'link' => '/pages/neobresnaya_doska/ ',
            'text' => 'Необрезная доска',
            'img' => '/img/neobresnaya_doska22.png',
            'img-alt' => 'Необрезная доска плита в Воронеже',
        ],
        
        [
             'link' => '/pages/brusok_suhoj_strogannyj/ ',
            'text' => 'Брусок строганный',
            'img' => '/img/brus.png',
            'img-alt' => 'Строганный брусок в Воронеже',
        ],

		
    ];
	

    $this->assign("cats", $cats);
{/php}

{foreach from=$cats item=item}
    <a class="good-cat" href="{$item.link}" class="good-cat">
        <img class="good-cat__img" src="{$item.img}" alt="{$item.img-alt}">
        <p class="good-cat__p">{$item.text}</p>
    </a>
{/foreach}