<!doctype html>
<html>
<head>
	<!--meta http-equiv="Content-Type" content="text/html; charset=UTF-8"-->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<title>红券网￥优惠券折扣返利全网比价购物搜索</title>
	<meta http-equiv="default-style" content="default">
	<link rel="stylesheet" href="https://urlnk.host/css/new-ui.css?v=34" type="text/css" title="default">
	<link rel="shortcut icon" href="/img/favicon-32x32.ico?v=" type="image/x-icon">
	<link rel="icon" href="/img/favicon.gif?v=" type="image/gif">
	<link rel="bookmark" href="/img/bookmark.ico?v=" type="image/x-icon">
</head>

<body>
<noscript>
	<div>
		<p>您正在使用的设备不支持 JavaScript，无法正常体验本页提供的功能！</p>
	</div>
</noscript>

<header class="top">
	<div class="top-in">
		<div class="logo">
			<h1>
				<a href="/">红券网</a>
			</h1>
			<address>
				<a href="/">cpn.red</a>
			</address>
		</div>
		
		<div class="account">
			<!--a href="">登录</a>
			<a href="">设置</a-->
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
					<?=isset($_GET['debug']) ? '<input name="debug" value="0">' : ''?>
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
					<a href="/">全部</a>
				</dt>
				<dd>
					<li>
						<a href="/" title="分类">分类</a>
					</li>
				</dd>
			</dl>
		</blockquote>
		<nav>
			<?=\app\view\Category::barStacked($tree, $category_id, $subclass_id)?>
		</nav>
	</div>
</header>

<header class="toolbar">
	<div class="toolbar-in">
		<div class="toolbar-left">
			<blockquote class="site">
				<form>
					<?php
					$prop = ['name' => 'category', 'style' => 'max-width: 80px;'];
					$property = ['name' => 'subclass', 'style' => 'max-width: 80px;'];
					if (!$category_id) {
						$property []= 'disabled';
					}

					echo \app\view\Item::selectCategory($tree, $category_id, $prop);
					echo \app\view\Item::selectCategory($subclass, $subclass_id, $property, ['' => '分类']);
					echo \app\view\Item::selectSite($site_id);
					?>
				</form>
			</blockquote>
			
			<blockquote class="filter">
				<h5>筛选</h5>
				<form>
					<?php					
					echo \app\view\Item::selectPrice($price);
					echo \app\view\Item::selectSave($save);
					echo \app\view\Item::selectStart($start_time);
					echo \app\view\Item::selectEnd($end_time);
					echo \app\view\Item::selectSale($sold);
					?>
				</form>
			</blockquote>
		</div>
		
		<div class="toolbar-right">
			<blockquote class="order">
				<h5>排序</h5>
				<form>
					<?php
					echo \app\view\Item::selectSort($sort);
					echo \app\view\Item::selectOrder($order);
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
				<?php
				if (!$items) {
					$tip = [];
					if ($query) {
						$tip[0] = '修改<a href="javascript:keyword()">关键词</a>';
					}
					if ($filter || $category_id || $site_id) {
						$tip[2] = '清除<a href="javascript:filter()">筛选与分类</a>';
					}
					$tips = count($tip);
					if (1 < $tips) {
						$tip[1] = '或';
					}
					ksort($tip);
					$tip = implode('', $tip);
					echo '<div style="text-align:center">
						<h3>无结果</h3>
						<blockquote>请' . $tip . '</blockquote>
					</div>';
				}
				?>
				<ol>
					<?=\app\view\Item::huge($items, '', $query)?>
				</ol>
			</section>			
			
			<!--section>
				<header>
					<h2></h2>
				</header>
			</section-->
		</article>
		<!--hr-->
	</div>
	<div style="text-align:center">
		<!--?=\app\view\Item::pagination($page, $pages)?-->
		<div class="pagerfanta">
			<?=$pagination?>
		</div>
	</div>
</main>

<!-- 脚本 -->
<script>
/* 预定义 */
var interval = <?=$items ? 'self.setInterval("update()", 1000)' : 'null'?>;
var end = '<?=isset($items[0]) ? $items[0]['end'] : 'null'?>';
</script>
<!--script src="js/jquery-3.3.1.js"></script-->
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<script src="https://urlnk.host/js/search.js?v=15"></script>
<?php if (!$stat) { $this->insert('stat'); } ?>
</body>
</html>
