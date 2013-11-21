<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META NAME="Keywords" CONTENT="Vacature, Vacatures, Zelfstandig Agent, Havee meubelen, Nederland, België, Belgie, Duitsland, reageer, ">

<META NAME="Description" CONTENT="Vacature Zelfstandig Agent m/v voor de project en retailmarkt, Bouw verder op 56 jaar vakmanschap. Havee Meubelen BV is sinds 1956 één van de toonaangevende producenten van hoogwaardige, hedendaagse moderne zitmeubelen in Nederland." URL="http://www.haveemeubelen.nl/vacature.php">
<link rel="icon" href= "favicon.ico" type="image/ico">
<link rel="shortcut icon" href= "favicon.ico" type="image/ico">

<title>Vacature</title>
<style type="text/css">
<!--
body {
	
	
	background-color: #FFF;
	
}
#vak{
	color: #000;
	font-size: 12px;
	font-family: Verdana, Geneva, sans-serif;
	background: #FFF;
	border: 1px #fff solid;
	padding-left: 20px;
	padding-bottom: 20px;
	border-radius:7px 7px 7px 7px;
	-moz-border-radius:7px 7px 7px 7px;
	-webkit-border-radius:7px;
	-webkit-border-top-right-radius:7px;
	-webkit-border-bottom-right-radius:7px;
	-webkit-border-top-left-radius:7px;
	-webkit-border-bottom-left-radius:7px;
	opacity:0.9;
	filter:alpha(opacity=90); /* For IE8 and earlier */
	
}
-->
</style>
</head>

<body  leftmargin="0" topmargin="0" marginwidth="0">
<br />



<table width="90%" border="0" align="center" cellpadding="0" cellspacing="6" id="vak" style="border:1px #CCC solid;">
  <tr>
    <td valign="top"><h2>Bouw verder op 56 jaar vakmanschap<br /></h2></td>
    <td width="10%" align="right"><a href="http://www.haveemeubelen.nl" target="_blank"><img src="images/havee_vak.png" width="195" height="56" border="0" /></a></td>
  </tr>
  
 
  
  <tr>
    <td colspan="2"><p><strong>Havee  Meubelen BV is sinds 1956 &eacute;&eacute;n van de toonaangevende producenten van hoogwaardige,  hedendaagse moderne zitmeubelen in Nederland.</strong> <br />
      <br />
      Innovatie, diversificatie en transparantie liggen aan de basis van ons  nationaal succes. 
      Omdat  wij ook graag andere landen met ons kennis laten maken zoekt Havee Meubelen BV  nieuwe medewerkers die oog hebben voor kwaliteit, en dit weten over te brengen  op hun gesprekspartner.
      Wil  jij deel uitmaken van Havee Meubelen bv? zie onderstaande vacatures  (m/v);</p>
      <ul>
        <li>Zelfstandig  Agent m/v voor de project en retailmarkt Belgi&euml;&nbsp; </li>
        <li>Zelfstandig  Agent m/v voor de project en retailmarkt Duitsland</li>
      </ul>
      <p>Heb  je belangstelling, of wil je vrijblijvend meer informatie omtrent een  specifieke vacature ontvangen? <br />
  &nbsp;        <br />
        Stuur  dan je volledige cv, graag met pasfoto naar:<br />
        <br />
        Havee  Meubelen BV<br />
&nbsp;        <br />
        t.a.v.  Dhr. Herman Veldhuizen <br />
        Postbus  171<br />
        4100  AD Culemborg &ndash; Holland<br />
&nbsp;        <br />
        Tel.  &nbsp; 0031 345 520377<br />
        Fax.&nbsp;  0031 345 517374<br />
        E-mail: <a href="mailto:directie@haveemeubelen.nl">directie@haveemeubelen.nl</a> &nbsp;<br />
        <br />
      Je  kandidatuur wordt in alle discretie behandeld. </p>
      <p><br />
    </p></td>
  </tr>
 
</table>
</span>

</body>
</html>
<?
// verander dit bestand in jou eigen bestand
// maak hem aan als hij nog niet bestaat
$file = "counter.dat";

// open bestand en lees huidige nummer
if(!($fp = fopen($file, "r"))) die ("Kan het bestand niet openen...");
$count = (int) fread($fp, 30);
fclose($fp);

// tel het + 1
$count++;

echo '<span style="color:#FFF"><b>'.$count.'</b></span>';

// open bestand en schrijf het nieuwe getal weg
$fp = fopen($file, "w");
fwrite($fp, $count);
fclose($fp);
?> 