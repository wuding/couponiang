/* 给下拉菜单绑定事件 */
var idx = {
	'category': 0,
	'subclass': 0,
	'site': 1,
	'price': 2,
	'save': 3,
	'start': 4,
	'end': 5,
	'sale': 6,
	'sort': 7,
	'order': 8,
};
var npt = $('.search span input');
$('.toolbar select').on('change', function(event){
	var name = $(this).attr('name');
	var val = $(this).val();
	if ('subclass' == name && !val) {
		var sel = $('.toolbar select')[0];
		val = $(sel).val();
	}
	var index = idx[name];
	var input = npt[index];
	$(input).val(val);
	if (!val) {
		$(input).removeAttr('name');
	}
	if ('category' == name) {
		var _anchor = 'cat_' + val;
		document.getElementById(_anchor).click();
	}
	submit()
});



/* 全局变量定义 */
var no = 1
var main = document.getElementsByTagName('main')
var li = main[0].getElementsByTagName('li')

/**
 * 改变属性变量
 * @return {[type]} [description]
 */
function change() {
	end = this.getAttribute('data-end')
	no = this.getAttribute('data-no')
}

/**
 *  更新倒计时
 * @return {[type]} [description]
 */
function update() {
	var d = new Date();
	var t = d.getTime();
	
    var sp = end.split(' ');
	var s = sp[0].split('-');
	var tm = sp[1].split(':');
	d.setFullYear(parseInt(s[0]), parseInt(s[1]) - 1, parseInt(s[2]));
	d.setHours(tm[0], tm[1], tm[2]);
	var t2 = d.getTime() - t;
	
	//计算出相差天数
	var day = Math.floor(t2/(24*3600*1000))
	 
	//计算出小时数

	var leave1 = t2%(24*3600*1000)    //计算天数后剩余的毫秒数
	var h = Math.floor(leave1/(3600*1000))
	//计算相差分钟数
	var leave2=leave1%(3600*1000)        //计算小时数后剩余的毫秒数
	var m = Math.floor(leave2/(60*1000))
	 

	//计算相差秒数
	var leave3=leave2%(60*1000)      //计算分钟数后剩余的毫秒数
	var sec = Math.round(leave3/1000)
	
	var key = no -1;
	var a = li[key].getElementsByTagName('a');
	var time = a[0].getElementsByTagName('time');
	day = day ? day +'天' : '';
	
	time[0].innerHTML = '还剩 '+ day + h +'时'+ m +'分'+ sec +'秒 结束';
}

/**
 * 添加事件
 * @param  {[type]} len [description]
 * @return {[type]}     [description]
 */
function add(len) {
	for (var i = 0; i < len; i++) {
		var a = li[i].getElementsByTagName('a')
		a[0].addEventListener("mouseover", change)
	}
}

/**
 * 清除关键词
 *
 */
function keyword() {
	$('.search div input').val('')
	submit()
}

/**
 * 清除筛选与分类
 *
 */
function filter() {
	document.getElementById('cat_').click();
	for (var i = 0; i < npt.length; i++) {
		var input = npt[i]
		$(input).removeAttr('name')
	}
	$('.search form').submit()
}

/**
 * 清除无用输入后提交
 *
 */
function submit() {
	for (var i = 0; i < npt.length; i++) {
		var input = npt[i]
		if (! $(input).val() ) {
			$(input).removeAttr('name')
		}
	}
	$('.search form').submit()
}

add(li.length);