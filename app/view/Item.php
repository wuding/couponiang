<?php
namespace app\view;

class Item
{
	public function __construct()
	{
	}
	
	/**
	 * 超大图
	 *
	 *
	 */
	public static function huge($data, $view = '')
	{
		$cols = [
			'' => 4,
			'huge' => 4,
			'large' => 5,
			'medium' => 10,
			'small' => 5,
		];
		
		$col = isset($cols[$view]) ? $cols[$view] : 4;
		
		
		$lis = '';
		$i = 1;
		foreach ($data as $row) {
			$obj = (object) $row;
			
			$no = $i % $col;
			$style = '';
			if (1 == $no) {
				$style = ' style="clear: left;"';
			}
			
			$li = <<<HEREDOC
			<li $style>
				<div>
					<a href="/item/{$row['excel_id']}">
						<p><img src="{$row['pic']}_400x400.jpg"></p>
						<var>￥{$row['cost']}</var>
						<b>{$row['name']}</b>
					</a>
				</div>
			</li>
HEREDOC;

			$lis .= $li;
			$i++;
		}
		return $lis;
	}
}
