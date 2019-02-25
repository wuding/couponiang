<?php
/* 后置调试 */
// 结束调试问题
if ($_DEBUG['last']) {
	eval($_DEBUG['last']);
}
