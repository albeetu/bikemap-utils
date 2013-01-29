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
function make_marker($record,$lat,$long)
{
  $marker["case_id"] = $record["case_id"];
  $marker["collision_date"] = $record["collision_date"];
  $marker["lat"] = $lat;
  $marker["long"] = $long;
  return $marker;
}

function geocode($case_id, $street, $crosstreet, $offset)
{
  $ch = curl_init();

  // set URL and other appropriate options
  $address = urlencode($street. " and ".$crosstreet." Los Angeles,CA");
  curl_setopt($ch, CURLOPT_URL, "http://maps.googleapis.com/maps/api/geocode/json?address=" . $address. "&sensor=false");
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // grab URL and pass it to the browser
  $output  = curl_exec($ch);
  $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
  // close cURL resource, and free up system resources
  // echo "HTTP code -> ". $http_code ." for ID :: ".$case_id."\n";
  if ($http_code == '200')
  {
    $feed = json_decode( $output, true);
    //print_r($feed);
    // Check API status code
    if ($feed['status'] == "OK")
    {
      // check to see if location is valid.
      if (isset($feed['results']['0']['geometry']['location']))
      {
        $retString = $feed['results'][0]['geometry']['location']['lat'] . ',' . $feed['results'][0]['geometry']['location']['lng'];
        $retString = explode(",",$retString);
      }
      else
      {
        echo "   Empty coordinate set for ID :: ".$case_id."\n";
        // place case_id into blacklist???
        $retString = false;
      }
    }
    else
    {
      // map status is not OK
      echo "   Geocode response not OK -> ". $feed['status'] ."\n";
      $retString = false;
    }
   }
  else
  // HTTP response is not 200
  {
     echo $http_code." -> ".$case_id."\n";
     $retString = "0,0";
  }
  curl_close($ch);
  return $retString;
}

function main()
{
  $raw_data = "./data/bikemap.raw";
  $bike_data = json_decode(file_get_contents($raw_data),true);
  $pdo = Array();
  $fatal = Array();
  $severe = Array();
  $minor = Array();
  $coords = Array();

  echo count($bike_data) ."\n";
  foreach($bike_data as $record)
  {
    $sev = $record["collision_severity"];
    switch($sev)
    {
      case 0:
        echo $record["case_id"]." ===> PDO\n";
        array_push($pdo,make_marker($record,0,0));
	break;
      case 1:
        echo "Fatal\n";
        $coords = geocode($record["case_id"],$record["primary_rd"],$record["secondary_rd"],0);
        //print_r($coords);
        array_push($fatal,make_marker($record,$coords[0],$coords[1]));
	break;
      case 2:
        echo "Injury (Severe)\n";
        //geocode
        array_push($severe,make_marker($record,0,0));
	break;
      case 3:
        echo "Injury (Minor)\n";
        array_push($minor,make_marker($record,0,0));
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
