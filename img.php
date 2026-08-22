<?
class genrandomimage
{
  var $lenght = 20;
  var $string = '';
  var $code = '';

  function genstring()
    {
      $num1 = mt_rand(1, 9);
      $num2 = mt_rand(1, 9);
      $op = (mt_rand(0, 1) === 0) ? '+' : '-';

      if ($op === '-') {
        if ($num1 < $num2) {
          $tmp = $num1;
          $num1 = $num2;
          $num2 = $tmp;
        }
      }

      $this -> string = $num1 . ' ' . $op . ' ' . $num2;
      $this -> code = $num1 . $op . $num2;
    }

  function getFontPath()
    {
      $candidates = array(
        __DIR__ . '/arial.ttf',
        __DIR__ . '/images/fonts/acaderom-webfont.ttf',
      );
      foreach ($candidates as $font) {
        if (is_readable($font)) {
          return $font;
        }
      }
      return '';
    }

  function genimage()
    {
      $font = $this->getFontPath();
      $fontSize = 14;
      $padding = 10;
      $height = 28;
      $text = $this -> string;

      if ($font !== '') {
        $box = imagettfbbox($fontSize, 0, $font, $text);
        $textWidth = $box[2] - $box[0];
        $textHeight = $box[1] - $box[7];
        $width = $textWidth + $padding * 2;
        if ($width < 72) {
          $width = 72;
        }
      } else {
        $width = 140;
        $textHeight = 12;
      }

      $im = imagecreatetruecolor($width, $height);
      $white = imagecolorallocate($im, 255, 255, 255);
      $black = imagecolorallocate($im, 40, 40, 40);
      imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $white);

      if ($font !== '') {
        $textX = (int) (($width - $textWidth) / 2);
        $textY = (int) (($height - $textHeight) / 2 - $box[7]);
        imagettftext($im, $fontSize, 0, $textX, $textY, $black, $font, $text);
      } else {
        imagestring($im, 3, $padding, (int) (($height - 12) / 2), $text, $black);
      }

      header('Content-type: image/png');
      imagepng($im);
      imagedestroy($im);
    }

}

$im = new genrandomimage ();
$im->genstring();
$nm=$im->code;

if(isset($_GET["type"]) && $_GET["type"]=='reg'){
  if (isset($_GET["sid"])) {
    session_id($_GET["sid"]);
  } elseif (isset($_COOKIE["_sid"])) {
    session_id($_COOKIE["_sid"]);
  }
  session_start();
  $_SESSION["rndCodeImg"] = $nm;
	$random_reg_id=$nm;
}

$im->genimage();

?>
