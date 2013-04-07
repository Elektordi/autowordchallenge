#!/usr/bin/php
<?

$lettres = str_split("abcdefghijklmnopqrstuvwxyz");

$start = "<p class=\"liste-mots\">";
$stop = "</p>";

$ls = strlen($start);
@mkdir("out");

foreach($lettres as $l)
{
	echo "* ".$l." *\n";
	$p = "";
	$i=1;

	$f = fopen('out/'.$l.'.txt', 'w');
	while($i<255) // Evite les boucles sans fin
	{
		$url = "http://www.dictionnaire-scrabble.fr/tous-les-mots-par-lettre/".$l."/".$i."/";
		echo $i."-> ";
		$p = trim(@file_get_contents($url));
		if($p=="<p>Erreur 404</p>" || $p=="")
		{
			echo "[END]\n";
			break;
		}
		echo "[OK]\n";
		
		$p1 = strpos($p,$start);
		$p2 = strpos($p,$stop,$p1);
		if(!$p1 || !$p2) die("Erreur de parsing");
		$z = trim(substr($p,$p1+$ls,$p2-$p1-$ls));

		$mots = explode(",",$z);
		foreach($mots as $m)
		{
			fwrite($f, trim($m)."\n");
		}
		$i++;

	}
	fclose($f);
}

?>
