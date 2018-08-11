<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<title>红券网</title>
	<link href="ui/mobi/style.css?v=22" rel="stylesheet" type="text/css">
</head>
<?php
$orderList = \app\view\Item::orderList($category_id);
$itemLists = \app\view\Item::dl($items, '', $query);
?>
<body>
<noscript>
	<div>
		<p>您正在使用的设备不支持 JavaScript</p>
		<p>无法正常体验本页提供的功能！</p>
	</div>
</noscript>

<header>
	<form id="search_form" action="" onsubmit="return search()">
		<h1>
			<a href="/">红券网</a>
		</h1>
		<div>			
			<blockquote>
				<button type="submit" title="搜索">&nbsp;</button>
			</blockquote>
			<span>
				<input name="q" value="<?=htmlspecialchars($query)?>" placeholder="请输入关键词">
			</span>
		</div>
		<span style="display:none">
			<input name="category" value="<?=$subclass_id ? : $category_id?>">
			<input name="site" value="<?=$site_id?>">
			<input name="price" value="<?=$price?>">
			<input name="save" value="<?=$save?>">
			<input name="start" value="<?=$start_time?>">
			<input name="end" value="<?=$end_time?>">
			<input name="sale" value="<?=$sold?>">
			<input name="sort" value="<?=$sort?>">
			<input name="order" value="<?=$order?>">
			<?=isset($_GET['debug']) ? '<input name="debug" value="0">' : ''?>
		</span>
	</form>
	<nav>
		<?=\app\view\Item::catNav($tree, $category_id, $query, $sort, $order)?>
	</nav>
	<div class="tool">
		<blockquote>
			<a href="">筛选</a>
		</blockquote>
		<span>
			<?=\app\view\Item::orderTab($orderList[0], $sort, $category_id)?>
		</span>
	</div>
	<div class="order" id="order_list">
		<ol>
			<?=$orderList[1]?>
		</ol>
	</div>
</header>

<div class="glass" id="glass" onclick="order()">
</div>

<div class="goto" id="got_top" onclick="goTop()">
</div>

<main>
	<ul id="items_left"><?=$itemLists[0]?></ul>
	<ul class="right" id="items_right"><?=$itemLists[1]?></ul>
</main>

<footer id="load_info">
<?=$count ? "共 $count 条" : '无'?>结果
</footer>

<script>
<!--
/*---- 定义 ------*/
query = {
	category_id : <?=$category_id ? : '""'?>,
	page: <?=$page ? : 1?>
	
}

config = {
	api_host: ''
}

page = query.page + 1
overflow = <?=(int) $overflow?>

count = <?=$count?>

XHR = []
AJAX = []
RESP = []

window.onscroll = scroll

/*---- polyfill ------*/

if ( 'undefined' == typeof URLSearchParams ) {
	var URLSearchParams = function ( init ) {
		obj = new Object
		obj.data = {}
		
		obj.append = function ( key, value ) {
			obj.data[ key ] = value
		}
		
		obj.toString = function () {
			arr = []
			for ( key in obj.data ) {
				arr.push( key + '=' + encodeURIComponent( obj.data[ key ] ) )
			}
			return arr.join( '&' )
		}
		return obj
	}
}

/*---- 类库 ------*/
/**
 * 类库 - 主函数对象
 */
var _ = function () {
}

_.form = function () {
}

/**
 * 接口 - 调用
 */
_.api = function ( uri, formData, method, query, arg ) {
	method = method || 'get'
	method = method.toUpperCase()
	arg = arg || {}	
	
	if ( 'GET' == method && !query && formData ) {
		params = new URLSearchParams
		for ( pair in formData ) {
		   params.append( pair, formData[ pair ] )
		}
		query = params.toString()		
	}
	
	url = config.api_host + '/api/' + uri
	if ( query ) {
		url += '?' + query
	}
	
	uri = uri.replace( /\//, '_' )
	req = XHR[ uri ] = new XMLHttpRequest
	req.onreadystatechange = function () {
		if ( 4 == req.readyState ) {
			if ( 200 == req.status ) {
				eval( "json = " + req.responseText + "; _.api.run( json, '" + uri + "', '" + encodeURI(JSON.stringify(arg)) + "' )" )
			} else {
				alert( 'Problem retrieving data: ' + req.statusText )
			}
		}
	}
	req.open( method, url, true )
	if ( 'POST' == method ) {
		req.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' )
	}
	req.send( formData )
}

/**
 * 接口 - 运行
 */
_.api.run = function (json, func, arg) {
	RESP[func] = json
	if (json) {
		if (3 < json.code) {
			alert(json.msg)
			
		} else {			
			eval("api_" + func + "('" + arg + "')")
		}
		
	} else {
		str = JSON.stringify([json, func, arg])
		alert('_.api.run() ERROR: ' + str)
	}
}

/**
 * DOM - 获取视口和文档尺寸及滚动条信息
 *
 */
_.viewport = function () {
	doc = document.body
	if ('BackCompat' == document.compatMode) {
		return {
			width: doc.clientWidth,
			height: doc.clientHeight,
			left: doc.scrollLeft,
			top: doc.scrollTop,
			scrollWidth: doc.scrollWidth,
			scrollHeight: doc.scrollHeight
		}
	}
	el = document.documentElement
	return {
		width: el.clientWidth,
		height: el.clientHeight,
		left: el.scrollLeft || doc.scrollLeft,
		top: el.scrollTop || doc.scrollTop,
		scrollWidth: el.scrollWidth,
		scrollHeight: el.scrollHeight
	}
}

/**
 * 表单 - 删除空值元素
 *
 */
_.form.removeNull = function (obj) {
	npt = obj.getElementsByTagName('input')
	len = npt.length
	i = 0
	for (; i < len; i++) {
		el = npt[i]
		if ('' === el.value) {
			el.removeAttribute('name')
		}
	}
	return true
}

/*---- 自定义接口 ------*/
/**
 * API - 追加商品列表
 */
function api_item_list(arg) {
	position = 'beforeEnd'
	load_msg = ''
	json = RESP['item_list']
	code = json.code
	msg = json.msg	
	switch (code) {
		case 0:
			break
		case 1:
		case 2:
			load_msg = msg
			overflow = 1
			break
		case 3:
			alert(msg)
			return
		default:
			alert(code + ': ' + msg)
			return
	}
		
	data = json.data
	len = data.length
	i = 0
	list = [items_left, items_right]
	for (; i < len; i++) {
		odd = i % 2
		row = data[i]
		html = '<dl>'
				+ '<a href="/item/' + row.list_id + '" target="_blank">'
					+ '<img src="' + row.pic + '_200x200.jpg">'
					+ '<span>'						
						+ '<h4>' + row.title + '</h4>'
					+ '</span>'
					+ '<data>'
						+ '<p>￥' + row.price + '</p>'
						+ '<s>月销' + row.sold + '笔</s>'
					+ '</data>'
				+ '</a>'
			+ '</dl>'
		
		list[odd].insertAdjacentHTML(position, html)
	}
	load_info.innerHTML = load_msg
	page++
}

/**
 * AJAX - 加载数据
 *
 */
function loadData() {	
	uri = 'item/list'
	key = uri + ':' + page
	if ( count && !overflow && !AJAX[ key ] ) {
		AJAX[ key ] = 1		
		load_info.innerHTML = '玩命加载中……'
		
		formData = {}
		npt = search_form.getElementsByTagName( 'input' )
		len = npt.length
		i = 0
		for ( ; i < len; i++ ) {
			el = npt[ i ]
			if ( el.name && '' !== el.value ) {
				formData[ el.name ] = el.value
			}
		}
		if ( 1 < page ) {
			formData.page = page
		}
		_.api( uri, formData )
	}
}

/*---- 页面函数 ------*/
/**
 * UI - 切换排序下拉菜单
 */
function order() {
	cls = order_first.className
	color = '_grey'
	if ('cur' == cls) {
		color = ''
	}

	display = 'none'
	url = 'ui/mobi/img/arr' + color + '.png'
	if ('block' != order_list.style.display) {
		display = 'block'
		url = 'ui/mobi/img/uarr' + color + '.png'
	}
	
	order_list.style.display = glass.style.display = display
	order_first.style.backgroundImage = 'url(' + url + ')'
	return false
}

/**
 * UE - 滚动到顶部
 */
function goTop(step) {
	step = step || 3
	scrollTop = _.viewport().top	
	per = Math.ceil(scrollTop / step) + 1
	i = 1
	len = step + 1
	for (; i < len; i++) {
		y = scrollTop - per * i
		setTimeout("window.scrollTo(0, " + y + ")", 100 * i)
	}
	return false
}

/**
 * 窗口事件 - 滚动条
 */
function scroll() {
	viewport = _.viewport()
	scrollTop = viewport.top	
	clientHeight = viewport.height
	if (scrollTop > clientHeight) {
		got_top.style.display = 'block'
	} else {
		got_top.style.display = 'none'
	}
	
	height = viewport.scrollHeight - 44
	if (height <= clientHeight + scrollTop) {
		loadData()
	}
}

/**
 * onsubmit - 搜索
 */
function search() {
	_.form.removeNull(search_form)
	if (query.category_id) {
		hash = '#cat_' + query.category_id
		if (hash != location.hash) {
			search_form.action = hash
		}
	}
	return true
}
//-->
</script>
<?php if (!$stat) { $this->insert('stat'); } ?>
</body>
</html>
