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
    return match ($weekday) {
        1 => "Mgu",
        2 => "Sen",
        3 => "Sel",
        4 => "Rab",
        5 => "Kam",
        6 => "Jum",
        default => "Sab",
    };
}

function SafeInput($text)
{
    $text = str_replace("'", "`", (string) $text);
    $text = str_replace("<", "&lt;", $text);
    $text = str_replace(">", "&gt;", $text);
    
    return $text;
}

function ChangeNewLine($text)
{
    
    //return str_replace(chr(13), "<br>", $text);
    return str_replace("[{*@NL#$]}", "<br>", (string) $text);

}

function RecodeNewLine($text)
{
    return str_replace("[{*@NL#$]}", chr(13) . chr(10), (string) $text);
}

function ChangeSingleQuote($text)
{
    return str_replace("'", "'", (string) $text);
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
    $eset[] = ["Devil.png", [">:)", ">:-)"]];
    $eset[] = ["Laughing.png", [":))"]];
    $eset[] = ["Sacred.png", ["O:)", "O:-)"]];
    $eset[] = ["Smile.png", [":-)", ":)", ":]", "=)"]];
    
    $eset[] = ["Crying-Loud.png", [":(("]];
    $eset[] = ["Broken-Heart.png", ["=(("]];
    $eset[] = ["Angry.png", [">:(", ">:-(", "x("]];
    $eset[] = ["Crying.png", ["T_T"]];   
    $eset[] = ["Sad.png", [":-(", ":(", ":[", "=("]];
    
    $eset[] = ["Winking.png", [";-)", ";)"]];
    $eset[] = ["Big-Grin.png", [":-D", ":D", "=D"]];
    $eset[] = ["Smug.png", ["8-|", "8|", "B-|", "B|", "8-)", "8)", "B-)", "B)"]];
    
    $eset[] = ["Scared.png", [":-O", ":O", ":-o", ":o"]];
    $eset[] = ["Cool.png", ["(Y)", "(y)"]];
    $eset[] = ["Love.png", ["<3", ":x", ":X"]];
    $eset[] = ["Thinking.png", [":?", ":-?"]];
    $eset[] = ["Whew.png", [":<", ":-<"]];
    $eset[] = ["Confused.png", [":-/", ":/", ":-\\", ":\\"]];
    $eset[] = ["Sick.png", [":&", ":-&"]];
    $eset[] = ["Sleepy.png", ["i-)", "i)"]];
    $eset[] = ["Kiss.png", [":*", ":-*"]];
    $eset[] = ["Nerd.png", [":-b", ":b"]];
    $eset[] = ["Straight-Face.png", [":-|", ":|"]];
    $eset[] = ["Silly.png", ["8-}", "8}", "B-}", "B}"]];
    $eset[] = ["Hug.png", [">:D<"]];
    
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
        $text = str_replace($symbol, $icon, (string) $text);
    }
    
    return $text;
}

function FormattedText($text)
{
    $text = FormatEmoticon($text);
    $text = strip_tags((string) $text, "<img>");
    $text = ChangeNewLine($text);
    //$text = MySqlSafe($text);
        
    return $text;
}

function MySqlSafe($text)
{
    $search = ["\\", "\0", "\n", "\r", "\x1a", "'", '"'];
    $replace = ["\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'];
    
    return str_replace($search, $replace, (string) $text);
}

function FormattedPreviewText($text, $length)
{
    $text = trim((string) $text);
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