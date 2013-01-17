<?php
/*

0	Case ID
4	Collision Date
5	Collision Time
18	Primary Rd
19	Secondary Rd
20 	Distance from intersection
21 	Direction from intersection
23	Weather condition A
36	Collision Severity
37	Number of ppl killed  (could be a ped dying as a result of a cyclist crashing into them)
38	Number of ppl injured
42	Primary Collision Factor Category
43 	Vehicle Code Violation (as a result of the PCF)
44 	PCF Subsection in the vehicle code
45	Hit and Run (either Y or N)
46	Type of Collision
47	Motor Vehicle Involved With (usually G == Bicycle in our case), but we will do pedestrian (B) as well
49	Road surface
52	Lighting
66	Count Ped Killed
68	Count Bicyclist Killed

*/




function readHeader($header){

  return array_map('strtolower',array_map('trim', explode(',',$header)));
  
}

function geocode($street, $crossstreet, $offset){


  return $coords
}

function readRecord($list,$contents){
  
  $records = Array(); 
  $record_count = 0;
  for ($i=1; $i<count($contents); $i++)
  { 
    $index = 0;
    $data_record = Array();
    foreach(explode(',',$contents[$i]) as $data)
    {
        $data_record[$list[$index]] = trim($data,'"\n'); 
        $index++;
    }
    $records[$record_count] = $data_record;
    $record_count++;
  } 
  // geocode here?
  return $records;
}

function getNecessary($list,$contents){

  $selection = Array(0,4,5,18,19,20,21,23,36,37,38,42,43,44,45,46,47,49,54,66,68);
  $records = Array();
  $record_count = 0;
  for ($i=1; $i<count($contents); $i++)
  {
    $record = explode(',',$contents[$i]);
    foreach($selection as $index)
    {
        $data_record[$list[$index]] = trim($record[$index],'"\n');
    }
    $records[$record_count] = $data_record;
    $record_count++;
  }
  // geocode here?
  return $records;

}

function main(){
 $s_contents = file('CollisionRecords2012.csv');
 $field_list = readHeader($s_contents[0]);
 //print_r($field_list);
 $records = readRecord($field_list,$s_contents);
 $necessary = getNecessary($field_list,$s_contents);
 print json_encode($necessary);
 //print json_encode($records);
}

main()
?>
