<?php
/* 后置调试 */
// 结束调试问题
if (!empty($_DEBUG['code'])) {
	$_DEBUG['end'] = $_DEBUG['end'] ? : (is_array($_DEBUG['code']) ? '' : $_DEBUG['code']);
	if ($_DEBUG['end']) {
		eval($_DEBUG['end']);
	}
}
