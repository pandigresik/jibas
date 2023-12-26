<?php
require_once("../library/datearith.php");

DateArith::TimeDiff("19:30", "15:07", $h, $m, $s);
echo "Diff: $h:$m:$s";
?>