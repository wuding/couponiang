<?php
namespace app\view;

class Category
{
	/**
	 * 堆叠条形图
	 *
	 *
	 */
	public static function barStacked($cat, $category_id = null, $subclass_id = null)
	{
		$dls = '';
		$width = 0;
		foreach ($cat as $c) {
			$anchor = 'cat_' . $c->category_id;
			
			/* 子类 */
			$w = 0;
			$dd = '';
			foreach ($c->leaves as $key => $value) {
				$cls = '';
				if ($subclass_id == $value->category_id) {
					$cls = 'class="current-cat"';
				}
				$li = <<<HEREDOC
				<li $cls>
					<a href="?category=$value->category_id#$anchor" title="$value->title">$value->title</a>
				</li>
HEREDOC;

				$dd .= $li;
				$w += 50;
			}
			
			/* 主类 */
			$class = '';
			$cls = '';
			if ($category_id == $c->category_id) {
				$class = 'class="current-tab"';
				if (!$subclass_id) {
					$cls = 'class="current-cat"';
				}
			}
			
			$dl = <<<HEREDOC
			<dl $class>
				<dt $cls>
					<a href="?category=$c->category_id#$anchor">$c->title</a>
				</dt>
				<dd>
					$dd
				</dd>
			</dl>
			<ol><a href="#$anchor" id="$anchor">&nbsp;</a></ol>
HEREDOC;
			$dls .= $dl;
			$w = (74 < $w) ? $w : 75;
			$width += $w + 5;
		}
		return $div = "<div style=\"width: {$width}px;\"><ol><a href=\"#cat_\" id=\"cat_\">&nbsp;</a></ol>" . $dls . '</div>';
	}
	
	/**
	 * 
	 *
	 */
	
}
