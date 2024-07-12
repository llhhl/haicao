function no(){
	alert('感谢您对 微享CMS 的支持！');
	window.close();
}
function showmsg(msg,tyle){
	var html = '<p class="'+tyle+'">'+msg+'</p>';
	$('.m-log').append(html);
}
function insok(homeUrl,adminUrl){
	var html = '\
	<a href="'+homeUrl+'" class="submit twosep_la">返回首页</a>\
	<a href="'+adminUrl+'" class="submit twosep_ra">进入后台</a>\
	';
	$('.twostep_bottom').html(html);
}

$(function(){
	var minhgt = $(".two_main");
	var allhg = $(document).height();
	minhgt.css("min-height",allhg);
})
