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

	public static function debug()
	{
		$debug = isset($_GET['debug']) ? $_GET['debug'] : null;
		if (null === $debug) {
			return false;
		} elseif (is_array($debug)) {
			$pieces = [];
			foreach ($debug as $key => $value) {
				$value = htmlspecialchars($value);
				$pieces[] = "<input name=\"debug[$key]\" value=\"$value\">";
			}
			return $html = implode(PHP_EOL, $pieces);
		}
		$debug = $debug ? htmlspecialchars($debug) : 0;
		return $html = "<input name=\"debug\" value=\"$debug\">";
	}
}
