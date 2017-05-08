<?php

/*
 * FallenTree from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
 */

namespace Ad5001\BetterGen\structure;

use pocketmine\block\Leaves;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use pocketmine\math\Vector3;
use pocketmine\level\generator\object\Tree;
use pocketmine\level\generator\normal\object\Tree as Tree2;
use pocketmine\level\generator\object\Object;
use Ad5001\BetterGen\utils\BuildingUtils;


class FallenTree extends Object {
	public $overridable = [ 
			Block::AIR => true,
			6 => true,
			17 => true,
			18 => true,
			Block::DANDELION => true,
			Block::POPPY => true,
			Block::SNOW_LAYER => true,
			Block::LOG2 => true,
			Block::LEAVES2 => true,
			Block::CACTUS => true 
	];
	protected $tree;
	protected $direction;
	
	/*
	 * Constructs the class
	 * @param 	$tree Tree
	 * @throws 	Exeption
	 */
	public function __construct($tree) {
		if(!is_subclass_of($tree, Tree::class) && !is_subclass_of($tree, Tree2::class)) {
			throw new Exception("Argument 1 passed to \\Ad5001\\BetterGen\\structure\\FallenTree must be an instance of pocketmine\\level\\generator\\normal\\object\\Tree or pocketmine\\level\\generator\\object\\Tree. Instance of " . get_class($tree) . " given.");
		}
		$this->tree = $tree;
	}
	
	/*
	 * Places a fallen tree
	 * @param $level pocketmine\level\ChunkManager
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $random pocketmine\utils\Random
	 */
	public function canPlaceObject(ChunkManager $level, $x, $y, $z, Random $random) {
		$randomHeight = round($random->nextBoundedInt(6) - 3);
		$this->length = $this->tree->trunkHeight + $randomHeight;
		$this->direction = $random->nextBoundedInt(4);
		switch($this->direction) {
			case 0:
			case 1:// Z+
			if(in_array(false, BuildingUtils::fillCallback(new Vector3($x, $y, $z), new Vector3($x, $y, $z + $this->length), function($v3, $level) {
				if(!in_array($level->getBlockIdAt($v3->x, $v3->y, $v3->z), \Ad5001\BetterGen\structure\FallenTree::$overridable)) return false;
			}, $level))) {
				return false;
			}
			break;
			case 2:
			case 3:
			if(in_array(false, BuildingUtils::fillCallback(new Vector3($x, $y, $z), new Vector3($x + $this->length, $y, $z), function($v3, $level) {
				if(!in_array($level->getBlockIdAt($v3->x, $v3->y, $v3->z), \Ad5001\BetterGen\structure\FallenTree::$overridable)) return false;
			}, $level))) {
				return false;
			}
			break;
		}
		return true;
	}
	
	/*
	 * Places a fallen tree
	 * @param $level pocketmine\level\ChunkManager
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 */
	public function placeObject(ChunkManager $level, $x, $y, $z) {
		switch($this->direction) {
			case 0:
			$level->setBlockIdAt($x, $y, $z, $this->tree->trunkBlock);
			$level->setBlockDataAt($x, $y, $z, $this->tree->type);
			$z += 2;
			case 1:// Z+
			BuildingUtils::fill($level, new Vector3($x, $y, $z), new Vector3($x, $y, $z + $this->length), Block::get($this->tree->trunkBlock, $this->tree->type + 4));
			break;
			case 2:
			$level->setBlockIdAt($x, $y, $z, $this->tree->trunkBlock);
			$level->setBlockDataAt($x, $y, $z, $this->tree->type);
			$x += 2;
			case 3:
			BuildingUtils::fill($level, new Vector3($x, $y, $z), new Vector3($x, $y, $z + $this->length), Block::get($this->tree->trunkBlock, $this->tree->type + 4));
			break;
		}
		// Second call to build the last wood block
		switch($this->direction) {
			case 1:
			$level->setBlockIdAt($x, $y, $z + $this->length + 2, $this->tree->trunkBlock);
			break;
			case 3:
			$level->setBlockIdAt($x + $this->length + 2, $y, $z, $this->tree->trunkBlock);
			break;
		}
	}
	
	/*
	 * Places a Block
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $level pocketmine\level\ChunkManager
	 */
	public function placeBlock($x, $y, $z, ChunkManager $level) {
		if (isset($this->overridable [$level->getBlockIdAt($x, $y, $z )] ) && ! isset($this->overridable [$level->getBlockIdAt($x, $y - 1, $z )] )) {
			$level->setBlockIdAt($x, $y, $z, $this->trunk [0]);
			$level->setBlockDataAt($x, $y, $z, $this->trunk [1]);
		}
	}
}