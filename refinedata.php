<?php
/* Read bikemap.raw (json encoded)
   Create fatality list
   Create Injury list
   Create Other list
*/
$list_dir = "./data/";

function open_files()
{
}
function print_file($json,$filename)
{
  $fh = fopen($filename,'w');
  fwrite($fh,json_encode($json));
  fclose($fh);
}

function main()
{
  $raw_data = "./data/bikemap.raw";
  $bike_data = json_decode(file_get_contents($raw_data),true);
  $pdo = Array();
  $fatal = Array();
  $severe = Array();
  $minor = Array();

  echo count($bike_data) ."\n";
  foreach($bike_data as $record)
  {
    $sev = $record["collision_severity"];
    switch($sev)
    {
      case 0:
        echo $record["case_id"]." ===> PDO\n";
        array_push($pdo,$record);
	break;
      case 1:
        echo "Fatal\n";
        array_push($fatal,$record);
	break;
      case 2:
        echo "Injury (Severe)\n";
        array_push($severe,$record);
	break;
      case 3:
        echo "Injury (Minor)\n";
        array_push($minor,$record);
	break;
      case 4:
        echo "In pain\n";
	break;
      default:
        echo "Don't know what this value is: ".$sev."\n";
    }
  }
  echo "========Final counts==========\n";
  echo "PDO             => ".count($pdo)."\n";
  echo "Fatal           => ".count($fatal)."\n";
  echo "Injury (severe) => ".count($severe)."\n";
  echo "Injury (minor)  => ".count($minor)."\n";
  
  print_file($fatal,"./data/fatal.json");
  print_file($severe,"./data/severe.json");
  print_file($minor,"./data/minor.json");
  print_file($pdo,"./data/pdo.json");
}

main()
?>
