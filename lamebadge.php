<?php
	require "functions.php";
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

	$image = imagecreatetruecolor(240, 80);
	$FILL_ALPHA       = imagecolorallocatealpha($image, 0, 0, 0, 50);
	$FILL             = imagecolorallocatealpha($image, 255,255,255, 127);
	$FILL_TEXT        = imagecolorallocatealpha($image, 255,255,255,0);
	$FILL_TEXT_SHADOW = imagecolorallocatealpha($image, 0, 0, 0, 50);

	header("content-type: image/png");
	dither_fill($image, $FILL);
	shadow_text($image, 1, 1, 4, $repo, $FILL_TEXT, $FILL_TEXT_SHADOW);

	// show only the first six characters of the commit id
	if ($_GET["graph"] == 1)
	shadow_text($image, 8, 13, 2, "Master [".substr($arr['commit']['id'], 0, 6)."]", $FILL_TEXT, $FILL_TEXT_SHADOW);
	shadow_text($image, 16, 24, 1, $arr['commit']['message'], $FILL_TEXT, $FILL_TEXT_SHADOW);
	imagepng($image);
	imagedestroy($image);
?>
