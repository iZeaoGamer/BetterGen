<?php
/**
 *  ____             __     __                    ____                       
 * /\  _`\          /\ \__ /\ \__                /\  _`\                     
 * \ \ \L\ \     __ \ \ ,_\\ \ ,_\     __   _ __ \ \ \L\_\     __     ___    
 *  \ \  _ <'  /'__`\\ \ \/ \ \ \/   /'__`\/\`'__\\ \ \L_L   /'__`\ /' _ `\  
 *   \ \ \L\ \/\  __/ \ \ \_ \ \ \_ /\  __/\ \ \/  \ \ \/, \/\  __/ /\ \/\ \ 
 *    \ \____/\ \____\ \ \__\ \ \__\\ \____\\ \_\   \ \____/\ \____\\ \_\ \_\
 *     \/___/  \/____/  \/__/  \/__/ \/____/ \/_/    \/___/  \/____/ \/_/\/_/
 * Tomorrow's pocketmine generator.
 * @author Ad5001 <mail@ad5001.eu>, XenialDan <https://github.com/thebigsmileXD>
 * @link https://github.com/Ad5001/BetterGen
 * @category World Generator
 * @api 3.0.0
 * @version 1.1
 */


namespace Ad5001\BetterGen\populator;

use Ad5001\BetterGen\structure\Cactus;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\utils\Random;

class CactusPopulator extends AmountPopulator {
	/** @var ChunkManager */
	protected $level;
	/**
	 * Constructs the class
	 */
	public function __construct() {
		$this->setBaseAmount(1);
		$this->setRandomAmount(2);
	}
	
	/**
	 * Populates the chunk
	 *
	 * @param ChunkManager $level
	 * @param int $chunkX
	 * @param int $chunkZ
	 * @param Random $random
	 * @return void
	 */
	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $this->getAmount($random);
		$cactus = new Cactus ();
		for($i = 0; $i < $amount; $i++) {
			$x = $random->nextRange($chunkX * 16, $chunkX * 16 + 15);
			$z = $random->nextRange($chunkZ * 16, $chunkZ * 16 + 15);
			$y = $this->getHighestWorkableBlock($x, $z);
			if ($y !== -1 and $cactus->canPlaceObject($level, $x, $y, $z, $random)) {
				$cactus->placeObject($level, $x, $y, $z);
			}
		}
	}

	/**
	 * Gets the highest workable block
	 *
	 * @param int $x
	 * @param int $z
	 *
	 * @return int
	 */
	protected function getHighestWorkableBlock($x, $z) {
		for($y = Level::Y_MAX - 1; $y >= 0; -- $y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b !== Block::AIR and $b !== Block::LEAVES and $b !== Block::LEAVES2) {
				break;
			}
		}
		return $y === 0 ? - 1 : ++$y;
	}
}