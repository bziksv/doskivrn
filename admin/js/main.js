var files_img = '/admin/img/';
var admin_path = '/admin/';

function showmessage(text){
	$("#messageBoxe").css("display", "");
	//$("#messageBoxin").css("display", "none");
	$("#messageBox").html("Вы не заполнили логин/пароль. Для авторизации данные поля являются обязательными!");
	//$("#messageBoxin").show("slow");
}
var timeout = 0;
function hidemessage(){
	$("#messageBoxe").css("display", "none");
	$("#messageBox").html("");
}

//Анимация загрузки
function loading(fl){
	if (fl){
		$("body").prepend("<div id='loading_p'></div>");
		$("#loading_p").append ("<div><div class='loading_p_v'><img src='"+files_img+"loading.gif' width='16' height='16' alt='Идет загрузка. Пожалуйста подождите...' title='Идет загрузка. Пожалуйста подождите...'><br />Идет загрузка.<br />Пожалуйста подождите...</div></div>")
	} else {
		$("#loading_p").remove();
	}
	
}
//Настройки для сохранения формы
var options = { 
    //target:        '#output2',   // target element(s) to be updated with server response 
    //beforeSubmit:  showRequest,  // pre-submit callback 
    success:showResponse  // post-submit callback 
   // dataType: 'json',
        // other available options: 
    //url:       url_f         // override for form's 'action' attribute 
    //type:      type        // 'get' or 'post', override for form's 'method' attribute 
    //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
    //clearForm: true        // clear all form fields after successful submit 
    //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
    //timeout:   3000 
};
//Сохранение формы
f_name = '';
var editor = false;
var edit=0;
var editors = Array();
var send_data = false;

function f_save (id_f, url_f){
if (send_data)
	return false;
	
	f_name = id_f;
    //
	if (editor){
		for (i=0;i<editors.length;i++){
			$("#"+editors[i].name).val(editors[i].getData());
		}
	}
	$('#'+id_f).ajaxSubmit(options);
	send_data = true;
	loading(true);
	
}

var import_data = false;
var load_data = false;
function f_import (){
	if (load_data)return;
	import_data = true;
	load_data = true;
	$('#f_import').ajaxSubmit(options);
	loading(true);
}

function f_search(action){
	if ($("#f_search_word").val() == ''){
		showerror("Необходимо задать параметры поиска!");
		return false;
	}
	action += "&search=true&field="+$("#f_search_field").val()+"&word="+$("#f_search_word").val();
	document.location.href=action;
	
}

function f_sort(action){
	action += "&sort="+$("#f_sort_field").val()+"&type="+$("#f_sort_type").val();
	document.location.href=action;
	
}

function f_view(action){
	action += "&view_select=true&view_uid="+$("#f_view_field").val();
	document.location.href=action;
	
}

//Ответ при сохранении формы
function showResponse(responseText, statusText)  {
	load_data = false;
	send_data = false;
	if (import_data == true){
		$("#step").html(responseText);
		loading(false);
		return ;
	}
	
	if (responseText!='' && responseText!='success'){
		showerror(responseText);
	} else {
		showsave("Информация обновлена");
		if (edit==0){
			document.getElementById(f_name).reset();
		}
	}
	loading(false);
} 

//Вопрос при удалении или иных действия
function question (question){
	if(confirm(question)){
		return true;
	} else {
		return false;
	}
}