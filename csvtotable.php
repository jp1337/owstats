<?php

$csvfile = $argv[1];
$mode=substr($csvfile,strpos($csvfile,"_")+1,-4);


$f = fopen($csvfile,"r");

$headers = fgetcsv($f,2000);

function formatting($value,$field,$fieldbefore)
{
  $pos="";
  if (!$field)
  {
	$value = explode("|",$value);
	$value = $value[count($value)-1];
	if (abs($value)<0.005) return "";
	$field=$fieldbefore;
	$pos="+";
  }
  if (in_array($field,array("K/D Ratio","Win Ratio","Kills")))
  {
	return ($value>0?$pos:"").number_format($value,2,",",".");
  }
  if (in_array($field,array("Games","Wins","Heal","Dmg","Block","Time played","Rank","Level")))
  {
	return ($value>0?$pos:"").number_format(ceil($value),0,",",".");
  }
  return ($value>0?$pos:"").$value;
  
}

function colorclass($value)
{
  if ($value>0.1) return "green";
  if ($value<-0.1) return "red";
  return "";
}

?>
<div id="<?=$mode;?>" class="tabcontent">
<table class="sortable">
<thead>
<tr>
<?php
foreach ($headers as $head) {
?>
	<th class="header"><?=$head;?></th>
<?php
}
?>
</tr>
</thead>
<tbody>
<?php
 while ($row = fgetcsv($f))
 {
?>
<tr>
<?php
	foreach ($row as $fnum=> $field)
	{
		$addclass = "";
		if (!$headers[$fnum]) {
			$chartvals = $field;
			$field = substr($field,strrpos($field,"|")+1);
			$addclass = "small ".colorclass($field);
		}
		
?>
	<td
		data-td="<?=$headers[$fnum];?>" data-value="<?=$field;?>" class="nowrap <?=$addclass;?>">
		<?php /* if ($headers[$fnum]) */ echo formatting($field,$headers[$fnum],$headers[abs($fnum-1)]);?><br/>
<?php
	if (!$headers[$fnum] && $fnum>6) {
		$chartvalsa = explode("|",$chartvals);
		$noval = true;
		foreach ($chartvalsa as $value)
		{
			if ($value!=0) $noval = false;
		}
		if (!$noval) {
?>
		<span class="chart" title="<?=$field;?>"><?=str_replace("|",",",$chartvals);?></span>
<?php
		}
	}
?>
	</td>
<?php
	}
?>
</tr>
<?php
 }
?>
</tbody>
</table>
</div>
