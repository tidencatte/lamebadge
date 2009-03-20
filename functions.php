<?php
	function dither_fill($image, $color) {
		$_ix = imagesx($image);
		$_iy = imagesy($image);
		$offset = 0;
		for ($y = 0; $y < $_iy; $y+=2) {
			for ($x = 0; $x < $_ix; $x+=2) {
				if (($x + $offset) % 4 == 0) {
					imagefilledrectangle($image, $x, $y, $x+1,$y+1,$color);
				}
			}
			$offset ^= 0x02;
		}
	}

	function shadow_text($image, $x, $y, $font, $string, $color, $shadow_color) {
		imagestring($image,$font,$x+1,$y+1, $string, $shadow_color);
		imagestring($image,$font,$x,$y, $string, $color);
	}
?>
