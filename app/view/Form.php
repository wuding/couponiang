<?php
namespace app\view;

class Form
{
	public function __construct()
	{
	}
	
	/**
	 * 选择
	 *
	 */
	public static function select($option = [], $selected = null, $property = [])
	{
		# print_r([$property]); 
		
		$attr = [];
		foreach ($property as $key => $value) {
			if (is_numeric($key)) {
				$attr []= "$value";
			} else {
				$value = htmlspecialchars($value);
				$attr []= "$key=\"$value\"";
			}
		}
		$attr = implode(' ', $attr);
		$sel = "<select $attr>";
		foreach ($option as $key => $value) {
			$s = '';
			if ($selected == $key) {
				$s = ' selected="selected"';
			}
			# print_r([$cat_id, $c->category_id]);
			$opt = "<option value=\"$key\" $s>$value</option>";
			$sel .= $opt;
		}
		$sel .= '</select>';
		# exit;
		return $sel;
	}
}
