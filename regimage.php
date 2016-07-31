<?php
# KCAPTCHA PROJECT VERSION 1.2.4

# Automatic test to tell computers and humans apart

# Copyright by Kruglov Sergei, 2006
# www.captcha.ru, www.kruglov.ru

# System requirements: PHP 4.0.6+ w/ GD

# KCAPTCHA is a free software. You can freely use it for building own site or software.
# If you use this software as a part of own sofware, you must leave copyright notices intact or add KCAPTCHA copyright notices to own.
# As a default configuration, KCAPTCHA has a small credits text at bottom of CAPTCHA image.
# You can remove it, but I would be pleased if you left it. ;)

# See kcaptcha_config.php for customizing
session_start();
$captcha = new KCAPTCHA();
$_SESSION['captcha'] = $captcha->getKeyString();

class KCAPTCHA {

	// generates keystring and image
	public function __construct() {
		$alphabet = "0123456789abcdefghijklmnopqrstuvwxyz"; # do not change without changing font files!
		$allowed_symbols = "23456789abcdeghkmnpqsuvxyz"; #alphabet without similar symbols (o=0, 1=l, i=j, t=f)
		$length = 8;
		$width = 200;
		$height = 54;
		$fluctuation_amplitude = 2;
		$no_spaces = true;
		$show_credits = false; # set to false to remove credits line. Credits adds 12 pixels to image height
		$foreground_color = array( mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100) );
		$background_color = array( mt_rand(230, 255), mt_rand(230, 255), mt_rand(230, 255) );
		$jpeg_quality = 90;
		$font_file = "im/images/TimesNewRoman.png";
		$alphabet_length = strlen($alphabet);

		while (true) {
			// generating random keystring
			while (true) {
				$this->keystring = '';
				for ($i = 0; $i < $length; $i++) {
					$this->keystring .= $allowed_symbols{mt_rand(0, strlen($allowed_symbols) - 1)};
				}
				if (!preg_match('/cp|cb|ck|c6|c9|rn|rm|mm|co|do|cl|db|qp|qb|dp/', $this->keystring)) {
					break;
				}
			}

			$font = imagecreatefrompng($font_file);
			imagealphablending($font, true);
			$fontfile_width = imagesx($font);
			$fontfile_height = imagesy($font) - 1;
			$font_metrics = array();
			$symbol = 0;
			$reading_symbol = false;

			// loading font
			for ($i = 0; $i < $fontfile_width && $symbol < $alphabet_length; $i++) {

				$transparent = ( imagecolorat($font, $i, 0) >> 24 ) == 127;

				if (!$reading_symbol && !$transparent) {
					$font_metrics[$alphabet{$symbol}] = array( 'start' => $i );
					$reading_symbol = true;
					continue;
				}

				if ($reading_symbol && $transparent) {
					$font_metrics[$alphabet{$symbol}]['end'] = $i;
					$reading_symbol = false;
					$symbol++;
					continue;
				}
			}

			$img = imagecreatetruecolor($width, $height);
			imagealphablending($img, true);
			$white = imagecolorallocate($img, 255, 255, 255);
			$black = imagecolorallocate($img, 0, 0, 0);

			imagefilledrectangle($img, 0, 0, $width - 1, $height - 1, $white);

			// draw text
			$x = 1;
			for ($i = 0; $i < $length; $i++) {
				$m = $font_metrics[$this->keystring{$i}];

				$y = mt_rand(-$fluctuation_amplitude, $fluctuation_amplitude) + ( $height - $fontfile_height ) / 2;

				if ($no_spaces) {
					$shift = 0;
					if ($i > 0) {
						$shift = 1000;
						for ($sy = 7; $sy < $fontfile_height - 20; $sy += 1) {
							for ($sx = $m['start'] - 1; $sx < $m['end']; $sx += 1) {
								$rgb = imagecolorat($font, $sx, $sy);
								$opacity = $rgb >> 24;
								if ($opacity < 127) {
									$left = $sx - $m['start'] + $x;
									$py = $sy + $y;
									if ($py > $height) {
										break;
									}
									for ($px = min($left, $width - 1); $px > $left - 12 && $px >= 0; $px -= 1) {
										$color = imagecolorat($img, $px, $py) & 0xff;
										if ($color + $opacity < 190) {
											if ($shift > $left - $px) {
												$shift = $left - $px;
											}
											break;
										}
									}
									break;
								}
							}
						}
						if ($shift == 1000) {
							$shift = mt_rand(4, 6);
						}

					}
				}
				else {
					$shift = 1;
				}
				imagecopy($img, $font, $x - $shift, $y, $m['start'], 1, $m['end'] - $m['start'], $fontfile_height);
				//imagecopyresampled($img,$font,$x-$shift,$y,$m['start'],1, ($m['end']-$m['start'])/*1.5*/, $height, $m['end']-$m['start'], $fontfile_height);
				$x += mt_rand(0, 15) + ( $m['end'] - $m['start'] - $shift );//*1.2;
			}
			if ($x < $width - 10) {
				break;
			} // fit in canvas

		}
		for ($i = 0; $i <= 3; $i++) {
			$yl = mt_rand(15, 45);
			imageline($img, 0, $yl, $x - $shift, $yl, imagecolorallocate($img, 0, 0, 0));
		}

		$center = $x / 2;

		// credits. To remove, see configuration file

		$img2 = imagecreatetruecolor($width, $height);

		// periods
		$rand1 = mt_rand(750000, 1200000) / 10000000;
		$rand2 = mt_rand(750000, 1200000) / 10000000;
		$rand3 = mt_rand(750000, 1200000) / 10000000;
		$rand4 = mt_rand(750000, 1200000) / 10000000;
		// phases
		$rand5 = mt_rand(0, 3141592) / 500000;
		$rand6 = mt_rand(0, 3141592) / 500000;
		$rand7 = mt_rand(0, 3141592) / 500000;
		$rand8 = mt_rand(0, 3141592) / 500000;
		// amplitudes
		$rand9 = mt_rand(330, 420) / 110;
		$rand10 = mt_rand(330, 450) / 110;

		//wave distortion
		for ($x = 0; $x < $width; $x++) {
			for ($y = 0; $y < $height; $y++) {
				$sx = $x + ( sin($x * $rand1 + $rand5) + sin($y * $rand3 + $rand6) ) * $rand9 - $width / 2 + $center + 1;
				$sy = $y + ( sin($x * $rand2 + $rand7) + sin($y * $rand4 + $rand8) ) * $rand10;

				if ($sx < 0 || $sy < 0 || $sx >= $width - 1 || $sy >= $height - 1) {
					$color = 255;
					$color_x = 255;
					$color_y = 255;
					$color_xy = 255;
				}
				else {
					$color = imagecolorat($img, $sx, $sy) & 0xFF;
					$color_x = imagecolorat($img, $sx + 1, $sy) & 0xFF;
					$color_y = imagecolorat($img, $sx, $sy + 1) & 0xFF;
					$color_xy = imagecolorat($img, $sx + 1, $sy + 1) & 0xFF;
				}

				if ($color == 0 && $color_x == 0 && $color_y == 0 && $color_xy == 0) {
					$newred = $foreground_color[0];
					$newgreen = $foreground_color[1];
					$newblue = $foreground_color[2];
				}
				else {
					if ($color == 255 && $color_x == 255 && $color_y == 255 && $color_xy == 255) {
						$newred = $background_color[0];
						$newgreen = $background_color[1];
						$newblue = $background_color[2];
					}
					else {
						$frsx = $sx - floor($sx);
						$frsy = $sy - floor($sy);
						$frsx1 = 1 - $frsx;
						$frsy1 = 1 - $frsy;

						$newcolor = ( $color * $frsx1 * $frsy1 + $color_x * $frsx * $frsy1 + $color_y * $frsx1 * $frsy + $color_xy * $frsx * $frsy );

						if ($newcolor > 255) {
							$newcolor = 255;
						}
						$newcolor = $newcolor / 255;
						$newcolor0 = 1 - $newcolor;

						$newred = $newcolor0 * $foreground_color[0] + $newcolor * $background_color[0];
						$newgreen = $newcolor0 * $foreground_color[1] + $newcolor * $background_color[1];
						$newblue = $newcolor0 * $foreground_color[2] + $newcolor * $background_color[2];
					}
				}

				imagesetpixel($img2, $x, $y, imagecolorallocate($img2, $newred, $newgreen, $newblue));
			}
		}


		if (function_exists("imagejpeg")) {
			header("Content-Type: image/jpeg");
			imagejpeg($img2, null, $jpeg_quality);
		}
		else {
			if (function_exists("imagegif")) {
				header("Content-Type: image/gif");
				imagegif($img2);
			}
			else {
				if (function_exists("imagepng")) {
					header("Content-Type: image/x-png");
					imagepng($img2);
				}
			}
		}
	}

	// returns keystring
	function getKeyString() {
		return $this->keystring;
	}
}

?>