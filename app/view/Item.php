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
	public static function super($data)
	{
		$lis = '';
		$i = 1;
		foreach ($data as $row) {
			$obj = (object) $row;
			
			$no = $i % 4;
			$style = '';
			if (1 == $no) {
				$style = ' style="clear: left;"';
			}
			
			$li = <<<HEREDOC
			<li $style>
				<div>
					<a href="/item/{$row['excel_id']}">
						<p><img src="{$row['pic']}_400x400.jpg"></p>
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
