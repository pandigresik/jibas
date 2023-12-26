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
function DayName($weekday)
{
    switch($weekday)
    {
        case 1:
            return "Mgu";
        case 2:
            return "Sen";
        case 3:
            return "Sel";
        case 4:
            return "Rab";
        case 5:
            return "Kam";
        case 6:
            return "Jum";
        default:
            return "Sab";
    }
}

function SafeInput($text)
{
    $text = str_replace("'", "`", $text);
    $text = str_replace("<", "&lt;", $text);
    $text = str_replace(">", "&gt;", $text);
    
    return $text;
}

function ChangeNewLine($text)
{
    
    //return str_replace(chr(13), "<br>", $text);
    return str_replace("[{*@NL#$]}", "<br>", $text);

}

function RecodeNewLine($text)
{
    return str_replace("[{*@NL#$]}", chr(13) . chr(10), $text);
}

function ChangeSingleQuote($text)
{
    return str_replace("'", "'", $text);
}

function SecToAge($secdiff)
{
    if ($secdiff >= 86400)
        return ceil($secdiff / 86400) . " hari yang lalu";
    
    if ($secdiff >= 3600)
        return ceil($secdiff / 3600) . " jam yang lalu";
    
    if ($secdiff >= 60)
        return ceil($secdiff / 60) . " menit yang lalu";
    
    return $secdiff . " detik yang lalu";
}

function SecToAgeDate($secdiff, $date)
{
    if ($secdiff >= 86400)
    {
        $nday = ceil($secdiff / 86400);
        return $nday <= 14 ? "$nday hari yang lalu" : $date;
    }
    
    if ($secdiff >= 3600)
        return ceil($secdiff / 3600) . " jam yang lalu";
    
    if ($secdiff >= 60)
        return ceil($secdiff / 60) . " menit yang lalu";
    
    return $secdiff . " detik yang lalu";
}

function GetEmoticonSet()
{
    $eset[] = array("Devil.png", array(">:)", ">:-)"));
    $eset[] = array("Laughing.png", array(":))"));
    $eset[] = array("Sacred.png", array("O:)", "O:-)"));
    $eset[] = array("Smile.png", array(":-)", ":)", ":]", "=)"));
    
    $eset[] = array("Crying-Loud.png", array(":(("));
    $eset[] = array("Broken-Heart.png", array("=(("));
    $eset[] = array("Angry.png", array(">:(", ">:-(",  "x("));
    $eset[] = array("Crying.png", array("T_T"));   
    $eset[] = array("Sad.png", array(":-(", ":(", ":[", "=("));
    
    $eset[] = array("Winking.png", array(";-)", ";)"));
    $eset[] = array("Big-Grin.png", array(":-D", ":D", "=D"));
    $eset[] = array("Smug.png", array("8-|", "8|", "B-|", "B|", "8-)", "8)", "B-)", "B)"));
    
    $eset[] = array("Scared.png", array(":-O", ":O",  ":-o",  ":o"));
    $eset[] = array("Cool.png", array("(Y)", "(y)"));
    $eset[] = array("Love.png", array("<3", ":x", ":X"));
    $eset[] = array("Thinking.png", array(":?", ":-?"));
    $eset[] = array("Whew.png", array(":<", ":-<"));
    $eset[] = array("Confused.png", array(":-/", ":/",  ":-\\", ":\\"));
    $eset[] = array("Sick.png", array(":&", ":-&"));
    $eset[] = array("Sleepy.png", array("i-)", "i)"));
    $eset[] = array("Kiss.png", array(":*", ":-*"));
    $eset[] = array("Nerd.png", array(":-b",  ":b"));
    $eset[] = array("Straight-Face.png", array(":-|",  ":|"));
    $eset[] = array("Silly.png", array("8-}", "8}", "B-}", "B}"));
    $eset[] = array("Hug.png", array(">:D<"));
    
    return $eset;
}

function FormatEmoticon($text)
{
    $eset = GetEmoticonSet();
    for($i = 0; $i < count($eset); $i++)
    {
        $icon = $eset[$i][0];
        $symbol = $eset[$i][1];
        
        $icon = "<img src=\'../images/emoticons/$icon\' class=\'EmoticonSmall\'>";
        $text = str_replace($symbol, $icon, $text);
    }
    
    return $text;
}

function FormattedText($text)
{
    $text = FormatEmoticon($text);
    $text = strip_tags($text, "<img>");
    $text = ChangeNewLine($text);
    //$text = MySqlSafe($text);
        
    return $text;
}

function MySqlSafe($text)
{
    $search = array("\\", "\0", "\n", "\r", "\x1a", "'", '"');
    $replace = array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"');
    
    return str_replace($search, $replace, $text);
}

function FormattedPreviewText($text, $length)
{
    $text = trim($text);
    if (strlen($text) <= $length)
        return FormattedText($text);
    
    $search = "\n";
    $replace = "                                                                             \n";
    $text = str_replace($search, $replace, $text);
    
    $trimchar = substr($text, 0, $length);
    if ($trimchar != "")
    {
        $posspace = strpos($text, " ", $length);
        $previewtext = substr($text, 0, $posspace);
    }
    else
    {
        $previewtext = substr($text, 0, $length);
    }
    $previewtext = trim($previewtext);
    
    return FormattedText($previewtext) . "<span class=\'MoreLink\'> .. selengkapnya</span>";
}
?>