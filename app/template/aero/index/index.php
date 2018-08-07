<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>红券网￥优惠券折扣返利全网比价购物搜索</title>
<link href="http://www.loc.urlnk.com/perfect/GUI/GinsengFruitTree/css/new-ui.css?v=14" rel="stylesheet" type="text/css">
</head>

<body>
<header class="top">
	<div class="top-in">
		<div class="logo">
			<h1>
				<a href="">红券网</a>
			</h1>
			<address>
				<a href="">cpn.red</a>
			</address>
		</div>
		
		<div class="account">
			<a href="">登录</a>
			<a href="">设置</a>
		</div>
		
		<div class="search">
			<form>
				<blockquote>
					<button type="submit">&crarr;</button>					
				</blockquote>
				<div>
					<input name="q" value="<?=htmlspecialchars($query)?>">
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
				</span>
			</form>
		</div>
	</div>
</header>

<header class="pick">
	<div class="category">
		<blockquote>
			<dl>
				<dt>
					<a href="">全部</a>
				</dt>
				<dd>
					<li>
						<a href="" title="分类">分类</a>
					</li>
				</dd>
			</dl>
		</blockquote>
		<nav>
			<?=\app\view\Category::barStacked($tree)?>
		</nav>
	</div>
</header>

<header class="toolbar">
	<div class="toolbar-in">
		<div class="toolbar-left">
			<blockquote class="site">
				<form>
					<?php
					$property = ['name' => 'subclass', 'style' => 'max-width: 75px;'];
					if (!$category_id) {
						$property []= 'disabled';
					}
					
					$sites = [
						'' => '网站',
						1 => '淘宝',
						2 => '天猫',
						3 => '聚划算',
					];
					
					echo \app\view\Category::select($tree, $category_id, ['name' => 'category', 'style' => 'max-width: 75px;']);
					echo \app\view\Category::select($subclass, $subclass_id, $property, ['' => '分类']);
					echo \app\view\Form::select($sites, $site_id, ['name' => 'site']);
					?>
				</form>
			</blockquote>
			
			<blockquote class="filter">
				<h5>筛选</h5>
				<form>
					<?php
					$prices = [
						'' => '价格',
						'9.9' => '9块9',
						'20' => '20元',
						'50' => '50元',
						'100' => '100',
					];
					
					$saves = [
						'' => '省钱',
						'10' => '10元',
						'20' => '20元',
						'50' => '50元',
						'100' => '100',
					];
					
					$starts = [
						'' => '开始',
						'today' => '今天',
						'yesterday' => '昨天',
						'DBY' => '前天',
						'last_week' => '上周',
					];
					
					$ends = [
						'' => '结束',
						'today' => '今天',
						'tomorrow' => '明天',
						'DAT' => '后天',
						'weekend' => '周末',
					];
					
					$sales = [
						'' => '月销',
						'100' => '100',
						'500' => '500',
						'1000' => '1千',
						'5000' => '5千',
					];
					
					echo \app\view\Form::select($prices, $price, ['name' => 'price']);
					echo \app\view\Form::select($saves, $save, ['name' => 'save']);
					echo \app\view\Form::select($starts, $start_time, ['name' => 'start']);
					echo \app\view\Form::select($ends, $end_time, ['name' => 'end']);
					echo \app\view\Form::select($sales, $sold, ['name' => 'sale']);
					?>
				</form>
			</blockquote>
		</div>
		
		<div class="toolbar-right">
			<blockquote class="order">
				<h5>排序</h5>
				<form>
					<?php
					$sorts = [
						'' => '默认',
						'price' => '价格',
						'save' => '省钱',
						'start' => '开始',
						'end' => '结束',
						'sale' => '月销',
					];
					
					$orders = [
						'' => '方式',
						'asc' => '升序',
						'desc' => '降序',
					];
					
					echo \app\view\Form::select($sorts, $sort, ['name' => 'sort']);
					echo \app\view\Form::select($orders, $order, ['name' => 'order']);
					?>
				</form>
			</blockquote>
		</div>
	</div>
</header>

<main class="large">
	<div class="main-in">
		<article class="view-huge">
			<section>
				<ol>
					<?=\app\view\Item::huge($items)?>
				</ol>
			</section>
			
			<!--section>
				<header>
					<h2>看过</h2>
				</header>
			</section-->		
		</article>
		<!--hr-->
	</div>
</main>

<!-- 脚本 -->
<script src="js/jquery-3.3.1.js"></script>
<script>
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
	$('.search form').submit();
});
</script>
</body>
</html>
