#!/usr/bin/php
<?

if(empty($_SERVER["argv"][1])) die("Usage: ".$_SERVER["argv"][0]." lettres\n");

$lettres = $_SERVER["argv"][1];

$wait=1;
if(isset($_SERVER["argv"][2]) && $_SERVER["argv"][2]=="-auto") $wait=0;

function envoie($mot)
{
	echo "-> $mot ";
	usleep(rand(100000,500000)); // attends entre 0.1s et 0.5s
	system('xvkbd -text "'.$mot.'\r" 2> /dev/null');
	echo "[OK]\n";
}

if($wait)
{
	echo "* ATTENTION *\n\nDéclanchement dans 3 secondes !\n";
	sleep(1);
	echo "Déclanchement dans 2 secondes...\n";
	sleep(1);
	echo "Déclanchement dans 1 seconde !\n";
	sleep(1);
	echo "Go ! Go ! Go !\n";
}


$tirage = str_split($lettres);
$files = array_unique($tirage);
foreach($files as $file)
{

	$f = file("out_ok/$file.txt");
	foreach($f as $m)
	{
		$m = trim($m);
		if(strlen($m)<3) continue;
		if(strlen($m)>6) continue;
		$ls = str_split($m);
		$ok=1;
		$test = $tirage;
		foreach($ls as $l)
		{
			$s = array_search($l,$test);
			if($s===false)
			{
				$ok=0;
				break;
			}
			unset($test[$s]);
		}
		if($ok)
		{
			envoie($m);
			if(!$wait && strlen($m)==6) system("touch delme.6");
		}
	}
}

echo "* OK *\n"

?>
