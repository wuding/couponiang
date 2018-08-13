<?php

/*
 * This file is part of the Pagerfanta package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pagerfanta\Adapter;

/**
 * MedooAdapter.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class MedooAdapter implements AdapterInterface
{
    private $db;
	private	$table = '';
	private	$columns = '';
	private	$where = [];

    /**
     * Constructor.
     *
     * @param array $array The array.
     */
    public function __construct($db, $table = null, $columns = null, $where = null)
    {
		$table = (null === $table) ? $db->table_name: $table;
		$columns = (null === $columns) ? $db->columns: $columns;
		$where = (null === $where) ? $db->getWhere() : $where;
        $this->db = $db;
		$this->table = $table;
		$this->columns = $columns;
		$this->where = $where;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        return $this->db->count($this->table, $this->where);
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
		$where = $this->where;
		$where['LIMIT'] = [$offset, $length];
        return $this->db->getAdapter()->getInstance()->select($this->table, $this->columns, $where);
    }
}
