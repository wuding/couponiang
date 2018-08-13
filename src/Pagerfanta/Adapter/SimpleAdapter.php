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
 * SimpleAdapter.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class SimpleAdapter implements AdapterInterface
{
    private $count = 0;
	private	$data = [];

    /**
     * Constructor.
     *
     * @param array $array The array.
     */
    public function __construct($count, $data = null)
    {
		$this->count = $count;
		$this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
		return $this->data;
    }
}
