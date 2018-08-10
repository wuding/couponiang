<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<title>红券网</title>
	<link href="ui/mobi/style.css?v=10" rel="stylesheet" type="text/css">
</head>
<?php
$orderList = \app\view\Item::orderList();
$itemLists = \app\view\Item::dl($items, '', $query);
?>
<body>
<noscript>
Your browser does not support JavaScript!
</noscript>

<header>
	<form>
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
		<?=\app\view\Item::catNav($tree, $category_id, $query)?>
	</nav>
	<div class="tool">
		<blockquote>
			<a href="">筛选</a>
		</blockquote>
		<span>
			<?=\app\view\Item::orderTab($orderList[0], $sort)?>
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

<div class="goto" id="gotop" onclick="goto()">
</div>

<main>
	<ul><?=$itemLists[0]?></ul>
	<ul class="right"><?=$itemLists[1]?></ul>
</main>

<footer>
玩命加载中……
</footer>

<script>
<!--
window.onscroll = scroll

function displayMsg() {
    alert("Hello World!")
}

function order() {
	var cls = order_first.className
	var prefix = '_grey'
	if ('cur' == cls) {
		prefix = ''
	}

	if ('block' != order_list.style.display) {
		order_list.style.display = 'block'
		glass.style.display = 'block'
		order_first.style.backgroundImage = 'url(ui/mobi/img/uarr' + prefix + '.png)'
	} else {
		order_list.style.display = 'none'
		glass.style.display = 'none'
		order_first.style.backgroundImage = 'url(ui/mobi/img/arr' + prefix + '.png)'
	}
	return false
}

function goto() {
	var top = document.body.scrollTop	
	var per = Math.ceil(top / 10) + 1
	var i = 1
	for (; i < 11; i++) {
		var to = top - per * i
		setTimeout("window.scrollTo(0, " + to + ")", 100 * i)
	}
	return false
}

function scroll() {
	var top = document.body.scrollTop
	if (top > getViewPort().height) {
		gotop.style.display = 'block'
	} else {
		gotop.style.display = 'none'
	}
}

/*视口的大小，部分移动设备浏览器对innerWidth的兼容性不好，需要
 *document.documentElement.clientWidth或者document.body.clientWidth
 *来兼容（混杂模式下对document.documentElement.clientWidth不支持）。
 *使用方法 ： getViewPort().width;
 */
function getViewPort () {
    if(document.compatMode == "BackCompat") {   //浏览器嗅探，混杂模式
        return {
            width: document.body.clientWidth,
            height: document.body.clientHeight
        };
    } else {
        return {
            width: document.documentElement.clientWidth,
            height: document.documentElement.clientHeight
        };
    }
}
//-->
</script>
<?php if (!$stat) { $this->insert('stat'); } ?>
</body>
</html>
