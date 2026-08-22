$(document).ready(function(){
	get_menu_classic();
});

$.History.bind(function(state){
	if (state.length > 0){
		get_link(state);
	}
});
function load_events_menu(){
	$("a").click(function () { 
		if($(this).attr("rel") == 'menuitem' && !$(this).hasClass("menu_item_non_childs")){
			if ($(this).hasClass("menu_item_yes_childs")){
				//Открываем подкатегории
				$(this).removeClass("menu_item_yes_childs");
				$(this).addClass("menu_item_yes_childs_open");
				var id = $(this).parents().parents().attr("id");
				$("#"+id+" > ul").css("display","block");
				if ($(this).attr("open")!='' && !$("#"+id+" > ul").html()){
					curr_load_menu = id;
					load_menu($(this).attr("openlink"));
				}
				
			} else if ($(this).hasClass("menu_item_yes_childs_open")){
				//Закрываем подкатегории
				$(this).removeClass("menu_item_yes_childs_open");
				$(this).addClass("menu_item_yes_childs");
				var id = $(this).parents().parents().attr("id");
				$("#"+id+" > ul").css("display","none");
			}
		}/* else {
			link = $(this).attr("href");
			if (link[0] == "#" && link.length > 1){
				var reg=/^#/
				link=link.replace(reg, "?")
				
			}
		}*/
    });
}
var curr_load_menu = false;
function load_menu (link) {
	$("#"+curr_load_menu+"").append("<div class='load_m'>загрузка...</div>");
	$.get(admin_path+"?"+link, function(data){
		$("#"+curr_load_menu+" > div.load_m").remove();
		$("#"+curr_load_menu+"").append(data);
		$("#"+curr_load_menu+" > ul").css("display","block");
	});
}

//Загружаем модуль по ссылке
function get_link(link){
	loading(true);
	$.get(admin_path+"?"+link, function(data){
		$("#main_content").html(data);
		loading(false);
	});
}

//Загружаем меню 
function get_menu_classic(){
	loading(true);
	$.get(admin_path+"?mod=admin_menu&act=init", function(data){
		$("#menu_main").html(data);
		loading(false);
		load_events_menu();
	});
}

//Управление меню
function show_childs (){//Показать подкатегории
	
}
function hide_childs (){//Скрыть подкатегории
	
}