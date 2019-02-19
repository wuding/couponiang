<?php
namespace app\model;

use PDO;

class SearchTerms extends DbShopping
{
	public $table_name = 'search_terms';
	public $primary_key = 'term_id';
	public $return = ['select'];

	public $columns = [
		'all' => ['excel_id', 'title', 'pic', 'price', 'save', 'sold', 'end', 'site', 'tao_token'],
		'row' => ['term_id', 'query', 'results', 'modified', 'updated', 'updates'],
	];

	public function exist($arr, $lockTime = 'modified', $sort = null, $limit = 1, $offset = 0)
	{
		$primary_key = $this->primary_key;
		$time = time();

		/* 参数 */
		if (is_string($arr)) {
			$arr = [
				'query' => $arr,
			];
		}
		
		/* 查询 */
		$where = [
			'query' => $arr['query'],
		];
		$option = [$sort, $limit, $offset];
		# $row = $this->sel($where, '*');
		$row = $this->sel($where, $this->columns['row'], $option);
		# print_r($row);print_r($this);exit;

		/* 新建 */
		if (!$row) {
			$data = [
				'created' => $time,
				$lockTime => $time,
			];
			$data += $arr;
			return $this->insert($data);
		}
		$arr['updated'] = $time;
		$arr['updates'] = $row->updates + 1;

		/* 比较 */
		$diff = array_diff_kv($arr, (array) $row);
		if ($diff) {
			$data = [];
			foreach ($diff as $key => $value) {
				$data[$key] = $value[0];
			}			
			$data[$lockTime] = $time;
			return $result = $this->update($data, $row->{$primary_key});
		}
		
		return $row->{$primary_key};
	}

	public function view($search_terms = null)
	{
		$query = "SELECT * 
FROM `search_terms` A 
LEFT JOIN `search_suggestions` B ON B.term_id = A.term_id 
WHERE `query` = '$search_terms' 
ORDER BY `seq` 
LIMIT 50";
		$map = ['床'];
		$exec = $this->query($query)->fetchAll(PDO::FETCH_ASSOC);
		return $this->logs($this->inst->sql, 'view') ? : $exec;
	}
}
