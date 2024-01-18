<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 29.0 (Sept 20, 2023)
 * @notes: JIBAS Education Community will be managed by Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 *
 * Copyright (C) 2009 Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?php
class ColorFactory
{
    private $baseMinColor;
    private $baseMaxColor;

    private array $zeroColor = [209, 209, 209];  // gray

    private array $homogenColor = [0, 166, 255];

    public function __construct(private $minValue, private $maxValue, private $weight = 1, private $reverseColor = false)
    {
        $this->baseMinColor = $this->rgb2hsv([183, 13, 13]);
        $this->baseMaxColor = $this->rgb2hsv([35, 166, 46]);

        //$this->baseMinColor = $this->rgb2hsv(array(18, 12, 9));
        //$this->baseMaxColor = $this->rgb2hsv(array(105, 195, 18));

        if ($reverseColor)
        {
            $temp = $this->baseMinColor;
            $this->baseMinColor = $this->baseMaxColor;
            $this->baseMaxColor = $temp;
        }
    }

    private function rgb2hsv($c)
    {
        [$r, $g, $b] = $c;

        $v = max($r, $g, $b);
        $t = min($r, $g, $b);
        $s = ($v == 0) ? 0 : ($v - $t) / $v;
        if ($s == 0)
        {
            $h = -1;
        }
        else
        {
            $a = $v - $t;
            $cr = ($v - $r) / $a;
            $cg = ($v - $g) / $a;
            $cb = ($v - $b) / $a;
            $h = ($r == $v) ? $cb - $cg : (($g == $v) ? 2 + $cr - $cb : (($b == $v) ? $h = 4 + $cg - $cr : 0));
            $h = 60 * $h;
            $h = ($h < 0) ? $h + 360 : $h;
        }

        return [$h, $s, $v];
    }

    private function hsv2rgb($c)
    {
        [$h, $s, $v] = $c;

        if ($s == 0)
        {
            return [$v, $v, $v];
        }
        else
        {
            $h = ($h %= 360) / 60;
            $i = floor($h);
            $f = $h - $i;
            $q[0] = $q[1] = $v * (1 - $s);
            $q[2] = $v * (1 - $s * (1 - $f));
            $q[3] = $q[4] = $v;
            $q[5] = $v * (1 - $s * $f);

            return([$q[($i + 4) % 6], $q[($i + 2) % 6], $q[$i % 6]]); //[1]
        }
    }

    private function transition($value, $startValue, $endValue)
    {
        if ($value < $this->minValue)
            $value = $this->minValue;
        elseif ($value > $this->maxValue)
            $value = $this->maxValue;

        //echo "$value $this->minValue $this->maxValue => ";
        $value = $value - $this->minValue;
        $maxValue = $this->maxValue - $this->minValue;
        //echo "$value 0 $maxValue<br>";

        return $startValue + ($endValue - $startValue) * $value / $maxValue;
    }

    private function transition3($value)
    {
        $r1 = $this->transition($value, $this->baseMinColor[0], $this->baseMaxColor[0]);
        $r2 = $this->transition($value, $this->baseMinColor[1], $this->baseMaxColor[1]);
        $r3 = $this->transition($value, $this->baseMinColor[2], $this->baseMaxColor[2]);

        return [$r1, $r2, $r3];
    }

    private function rgb2html($r, $g =- 1, $b =- 1)
    {
        if (is_array($r) && sizeof($r) == 3)
            [$r, $g, $b] = $r;

        $r = intval($r);
        $g = intval($g);
        $b = intval($b);

        $r = dechex($r < 0 ? 0 : ($r > 255 ? 255 : $r));
        $g = dechex($g < 0 ? 0 : ($g > 255 ? 255 : $g));
        $b = dechex($b < 0 ? 0 : ($b > 255 ? 255 : $b));

        $color  = (strlen($r) < 2 ? '0' : '') . $r;
        $color .= (strlen($g) < 2 ? '0' : '') . $g;
        $color .= (strlen($b) < 2 ? '0' : '') . $b;

        return '#' . $color;
    }

    public function GetColorCode($value)
    {
        $value = $this->weight * $value;

        /*
        if ($value < $this->minValue)
            $value = $this->minValue;
        else if ($value > $this->maxValue)
            $value = $this->maxValue;
        */

        //if ($value <= 0)
//            $resultRGB = $this->baseMinColor;
//        else if ($value >= $this->maxValue)
//            $resultRGB = $this->baseMaxColor;
//        else
        $resultRGB = $this->hsv2rgb($this->transition3($value));

        return $this->rgb2html($resultRGB[0], $resultRGB[1], $resultRGB[2]);
    }

    public function GetHomogenColorRGB($dividen, $divisor, $value)
    {
        if ($divisor == 0 && $dividen == 0)
            return $this->zeroColor;
        else
            return $this->homogenColor;
    }

    public function GetHomogenColorHTML($dividen, $divisor, $value)
    {
        if ($divisor == 0 && $dividen == 0)
            return $this->rgb2html($this->zeroColor);
        else
            return $this->rgb2html($this->homogenColor[0],
                $this->homogenColor[1],
                $this->homogenColor[2]);
    }

    public function GetColorCodeHTML($dividen, $divisor, $value)
    {
        $value = $this->weight * $value;

        /*
        if ($value < $this->minValue)
            $value = $this->minValue;
        else if ($value > $this->maxValue)
            $value = $this->maxValue;
        */

        if ($divisor == 0 && $dividen == 0)
            $resultRGB = $this->zeroColor;
        else
            $resultRGB = $this->hsv2rgb($this->transition3($value));

        return $this->rgb2html($resultRGB[0], $resultRGB[1], $resultRGB[2]);
    }

    public function GetColorCodeRGB($dividen, $divisor, $value)
    {
        $value = $this->weight * $value;

        /*
        if ($value < $this->minValue)
            $value = $this->minValue;
        else if ($value > $this->maxValue)
            $value = $this->maxValue;
        */

        if ($divisor == 0 && $deviden == 0)
            $resultRGB = $this->zeroColor;
        else
            $resultRGB = $this->hsv2rgb($this->transition3($value));

        return [floor($resultRGB[0]), floor($resultRGB[1]), floor($resultRGB[2])];
    }
}
?>