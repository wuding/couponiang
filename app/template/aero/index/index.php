<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>红券网￥优惠券折扣返利全网比价购物搜索</title>
<link href="http://www.loc.urlnk.com/perfect/GUI/GinsengFruitTree/css/new-ui.css?v=11" rel="stylesheet" type="text/css">
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
					<input>
				</div>
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
			<?=\app\view\Category::barStacked($cat)?>
		</nav>
	</div>
</header>

<header class="toolbar">
	<div class="toolbar-in">
	<div class="toolbar-left">
		<blockquote class="nav">
			<a href="" title="导航">[=]</a>
		</blockquote>
		
		<blockquote class="site">
			<form>
				<?=\app\view\Category::select($cat, $category_id, ['name' => 'category', 'style' => 'max-width: 75px;'])?>
				<?php
				$property = ['name' => 'subclass', 'style' => 'max-width: 75px;'];
				if (!$category_id) {
					$property []= 'disabled';
				}
				echo $subclasses = \app\view\Category::select($subclass, $subclass_id, $property, ['' => '分类']);
				
				$sites = [
					'' => '网站',
					1 => '淘宝',
					2 => '天猫',
					3 => '聚划算',
				];
				
				echo \app\view\Form::select($sites, $site_id, ['name' => 'site']);
				?><button type="submit">&crarr;</button>
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
					'2018-7-31' => '今天',
					'2018-7-30' => '昨天',
					'2018-7-29' => '前天',
					'2018-7-23_2018-7-29' => '上周',
				];
				
				$ends = [
					'' => '结束',
					'2018-7-31 23:59:59' => '今天',
					'2018-8-1_2018-8-1 23:59:59' => '明天',
					'2018-8-2' => '后天',
					'2018-8-3_2018-8-5 23:59:59' => '周末',
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
				?><button type="submit">&crarr;</button>
			</form>
		</blockquote>
	</div>
	
	<div class="toolbar-right">
		<blockquote class="view">
			<h5>查看</h5>
			<form>
				<?php
				$views = [
					'' => '默认',
					'huge' => '超大图',
					'large' => '大图',
					'medium' => '中等图',
					'small' => '小图',
					'list' => '列表',
					'detail' => '详细',
					'tile' => '平铺',
					'content' => '内容',
				];
				echo \app\view\Form::select($views, $view, ['name' => 'view']);
				?><button type="submit">&crarr;</button>
			</form>
		</blockquote>
		
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
					'sale' => '销量',
				];
				
				$orders = [
					'' => '方式',
					'asc' => '升序',
					'desc' => '降序',
				];
				
				echo \app\view\Form::select($sorts, $sort, ['name' => 'sort']);
				echo \app\view\Form::select($orders, $order, ['name' => 'order']);
				?><button type="submit">&crarr;</button>
			</form>
		</blockquote>
	
		
		
		
		<blockquote class="group">
			<h5>分组</h5>
			<form>
				<select>
					<option>
						自动
					</option>
					<option>
						禁用
					</option>
					<option>
						分类
					</option>
					<option>
						网站
					</option>
					<option>
						价格
					</option>
					<option>
						省钱
					</option>
					<option>
						开始
					</option>
					<option>
						结束
					</option>
					<option>
						销量
					</option>
					<option>
						人气
					</option>
				</select><button type="submit">&crarr;</button>
			</form>
		</blockquote>
		
		<blockquote class="tool">
			<a href="" title="工具" class="hide">[V]</a>
			<a href="" title="预览">[P]</a>
			<a href="" title="帮助">[?]</a>
		</blockquote>	
		
	</div>
	</div>
</header>

<main>
	<div class="main-in">
		<article class="view-huge">
			<section>
				<ol>
					<?=\app\view\Item::huge($items)?>
				</ol>
			</section>
			
			<section>
				<header>
					<h2>看过</h2>
				</header>
				<ol>
					<li>
						<div>
							<a href="">
								<img src="http://www.loc.urlnk.com/perfect/GUI/GinsengFruitTree/img/545216303635.jpg">
								<b>商品</b>
							</a>
						</div>
					</li>
					<li>
						<div>
							<a href="">
								<img src="http://www.loc.urlnk.com/perfect/GUI/GinsengFruitTree/img/545216303635.jpg">
								<b>商品</b>
							</a>
						</div>
					</li>
					<li>
						<div>
							<a href="">
								<img src="http://www.loc.urlnk.com/perfect/GUI/GinsengFruitTree/img/545216303635.jpg">
								<b>商品</b>
							</a>
						</div>
					</li>
					<li>
						<div>
							<a href="">
								<img src="http://www.loc.urlnk.com/perfect/GUI/GinsengFruitTree/img/545216303635.jpg">
								<b>商品</b>
							</a>
						</div>
					</li>
					<li>
						<div>
							<a href="">
								<img src="http://www.loc.urlnk.com/perfect/GUI/GinsengFruitTree/img/545216303635.jpg">
								<b>商品</b>
							</a>
						</div>
					</li>
					<li>
						<div>
							<a href="">
								<img src="http://www.loc.urlnk.com/perfect/GUI/GinsengFruitTree/img/545216303635.jpg">
								<b>商品</b>
							</a>
						</div>
					</li>
					<li>
						<div>
							<a href="">
								<img src="http://www.loc.urlnk.com/perfect/GUI/GinsengFruitTree/img/545216303635.jpg">
								<b>商品</b>
							</a>
						</div>
					</li>
					<li>
						<div>
							<a href="">
								<img src="http://www.loc.urlnk.com/perfect/GUI/GinsengFruitTree/img/545216303635.jpg">
								<b>商品</b>
							</a>
						</div>
					</li>
				</ol>
			</section>
		
		</article>

		<!--hr-->
		
		<article class="view-large">	
			<section>
				<header>
					<h2>女装男装</h2>
				</header>
				<div>
					<ol>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
					</ol>
				</div>
			</section>
			
			<section>
				<header>
					<h2>聚划算</h2>
				</header>
				<div>
					<ol>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
					</ol>
				</div>
			</section>
		</article>

		<article class="view-medium">	
			<section>
				<header>
					<h2>9块9</h2>
				</header>
				<div>
					<ol>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
					</ol>
				</div>
			</section>
		</article>
			
		
			
		<article class="view-small">
			<section>
				<header>
					<h2>今天结束</h2>
				</header>
				<div>
					<ol>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
					</ol>
				</div>
			</section>
		</article>
			
		<article class="view-list">	
			<section>
				<header>
					<h2>热销</h2>
				</header>
				<div>
					<ol>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
					</ol>
				</div>
			</section>
		</article>
			
		<article class="view-detail">	
			<section>
				<header>
					<h2>超高人气</h2>
				</header>
				<div>
					<ol>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
					</ol>
				</div>
			</section>
		</article>
		
		<article class="view-tile">	
			<section>
				<header>
					<h2>省10元</h2>
				</header>
				<div>
					<ol>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
					</ol>
				</div>
			</section>
		</article>
			
		<article class="view-content">	
			<section>
				<header>
					<h2>今天开始</h2>
				</header>
				<div>
					<ol>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
						<li>商品</li>
					</ol>
				</div>
			</section>
		</article>
	</div>
</main>
</body>
</html>
