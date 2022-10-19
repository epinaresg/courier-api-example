<?php

namespace App\Models;

final class VOAddress
{

	public $lineOne;
	public $lineTwo;
	public $lineThree;

	public function __construct(string $lineOne, string $lineTwo, string $lineThree)
	{
		$this->lineOne = $lineOne;
		$this->lineTwo = $lineTwo;
		$this->lineThree = $lineThree;
	}

}
