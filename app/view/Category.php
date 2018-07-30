<?php
namespace app\view;

class Category
{
	public function __construct()
	{
	}
	
	/**
	 * 堆叠条形图
	 *
	 *
	 */
	public static function barStacked($cat)
	{
		$div = "<div";
		$dls = '';
		$width = 0;
		foreach ($cat as $c) {
			$w = 0;
			$dd = '';
			foreach ($c->leaves as $key => $value) {
				$li = <<<HEREDOC
				<li>
					<a href="?category=$value->category_id#cat_$c->category_id" title="$value->title">$value->title</a>
				</li>
HEREDOC;

				$dd .= $li;
				$w += 46;
			}
			
			$dl = <<<HEREDOC
			<dl id="cat_$c->category_id">
				<dt>
					<a href="?category=$c->category_id#cat_$c->category_id">$c->title</a>
				</dt>
				<dd>
					$dd
				</dd>
			</dl>
HEREDOC;
			$dls .= $dl;
			$w = (74 < $w) ? $w : 75;
			$width += $w;
		}
		$div .= " style=\"width: {$width}px;\">";
		return $div . $dls . '</div>';
	}
}
