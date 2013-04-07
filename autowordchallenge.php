#!/usr/bin/php
<?

echo "* ATTENTION *\n\nDéclanchement dans 3 secondes !\n";
sleep(1);
echo "Déclanchement dans 2 secondes...\n";
sleep(1);
echo "Déclanchement dans 1 seconde !\n";
sleep(1);
echo "Go ! Go ! Go !\n";

$defi=0;
if(isset($_SERVER["argv"][1]) && $_SERVER["argv"][1]=="-d")
{
	$defi=1;
	echo "*** MODE DEFI ***\n";
}
else	echo "*** MODE SOLO *** ( -d pour le mode défi )";

while(1)
{

	system("scrot screen.png");

	$ocr = "";
	system('xvkbd -text "\r" 2> /dev/null'); // pour pas avoir de lettres coincees
	for($i=0;$i<6;$i++)
	{
		//system("convert -extract 38x38+".(720+54*$i)."+495 -depth 8 screen.png zone".$i.".pgm");
		//$ocr .= system("gocr -a 50 -p ./db/ -C AZERTYUIOPQSDFGHJKLMWXCVBN zone".$i.".pgm");

		system("convert -extract 38x38+".(720+54*$i)."+495 -depth 8 screen.png zone".$i.".png");
		system("convert zone".$i.".png zone".$i.".tif");
		system("tesseract zone".$i.".tif ocr batch tesseract.conf");
		$l = trim(file_get_contents("ocr.txt"));
		echo "-> $l\n";
		if(strlen($l)==1) $ocr.=$l;
	}
	$ocr = trim(strtolower($ocr));
	echo "*".$ocr."*\n";
	if(strlen($ocr)<4 || strlen($ocr)>6) die("OCR FAILED\n");
	if(file_exists("delme.6")) unlink("delme.6");
	system("php wordchallenge.php ".$ocr." -auto");
	system('xvkbd -text "\C " 2> /dev/null');
	
	if(!$defi && file_exists("delme.6"))
	{
		echo "Superbonus! Waiting 20 secs... ";
		sleep(20);
	}
	else
	{
		sleep(3);
	}
	echo "[DONE]\n";
}


?>
