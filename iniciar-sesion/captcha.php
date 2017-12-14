<?php 

function generar_caracteres(){
    $chars=array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','h','x','z');
    $length=4;
    $imagen = imagecreate(90, 50);
    $color_fondo = imagecolorallocate($imagen, 59, 66, 255);
    $color_texto = imagecolorallocate($imagen, 255, 255, 255);

    $captcha = null;
    for($i=0;$i<$length;$i++){
        $rand = rand(0, count($chars)-1);
        $captcha .= $chars[$rand];
    }
    // $img_number = imagecreate(325,25);
    // $backcolor = imagecolorallocate($img_number,0,153,255);
    // $textcolor = imagecolorallocate($img_number,255,255,255);
    // imagefill($img_number,0,0,$backcolor);
    // $texCaptch = $captcha;
    // Imagestring($img_number,10,5,5,$texCaptch,$textcolor);
    // header("Content-type: image/jpeg");
    // $captcha = imagejpeg($img_number);

    return $captcha;
}

?>