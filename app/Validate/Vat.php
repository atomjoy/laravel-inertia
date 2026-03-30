<?php

namespace App\Validate;

class Vat
{
	public $tax = 17.734956;

	/**
	 * Round vat tax (cut below 0.5 cent round to 1 cent equals and above 0.5 cent).
	 *
	 * @param float $tax
	 * @return float
	 */
	function round($tax)
	{
		return number_format($tax, 2);
	}

	/**
	 * Round vat tax (cut below 0.5 cent round to 1 cent equals and above 0.5 cent).
	 *
	 * @param float $tax
	 * @return float
	 */
	function roundStr($tax)
	{
		return round(explode('.', "" . ($tax * 1000))[0] / 1000, 2);
	}

	/**
	 * Round vat tax (invalid method +1 cent).
	 *
	 * @param float $tax
	 * @return float
	 */
	function roundFake($tax)
	{
		// $tax = 11.0349;
		return round(round($tax, 3), 2);
	}
}
