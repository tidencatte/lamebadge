<?php
	$repo     = $_GET["repo"] or null;
	$username = $_GET["username"] or null;

	if (($repo === null) or ($username === null)) {
		die("UH OH!");
	}

	$url   = curl_init("http://github.com/api/v1/json/$username/$repo/commit/master");
	curl_setopt($url, CURLOPT_HEADER, false);
	curl_setopt($url, CURLOPT_RETURNTRANSFER, true);

	$json  = curl_exec($url);
	curl_close($url);

	$arr = json_decode($json,true);
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

	$image = imagecreatetruecolor(240, 40);
	$FILL_ALPHA       = imagecolorallocatealpha($image, 0, 0, 0, 50);
	$FILL             = imagecolorallocatealpha($image, 255,255,255, 127);
	$FILL_TEXT        = imagecolorallocatealpha($image, 255,255,255,0);
	$FILL_TEXT_SHADOW = imagecolorallocatealpha($image, 0, 0, 0, 50);

	header("content-type: image/png");
	dither_fill($image, $FILL);
	shadow_text($image, 1, 1, 5, $repo, $FILL_TEXT, $FILL_TEXT_SHADOW);

	// show only the first six characters of the commit id
	shadow_text($image, 8, 13, 3, "Master [".substr($arr['commit']['id'], 0, 6)."]", $FILL_TEXT, $FILL_TEXT_SHADOW);
	shadow_text($image, 16, 24, 2, $arr['commit']['message'], $FILL_TEXT, $FILL_TEXT_SHADOW);
	imagepng($image);
	imagedestroy($image);
?>
