<?php

/* 

BWD water quality data/map viewer: COMMON FUNCTIONS

21.3.2008; first version

*/

// f. to change 'not-allowed characters' into something else
function changeChars($input_string, $changeTo) {
  $charsToChange = array(" ",",",":",";","?",".");
  Return str_replace($charsToChange, $changeTo, $input_string);
}

// f. to change UTF8 chars (Č,Š,Ž,Ü,...) to (C,S,Z,U,...) - only for jpgraph, because default font is not UTF8
function replaceUTFChars($input_string) {
  $changeFrom  = array("Č","Š","Ž","Ü","Ä","Ö","ß","É","Õ","Ñ","Ű","Ø","\\'","Ş","Æ","Å","Ő","Ç","Ú","ľ","Ý","Á"); 
  $changeTo       = array("C","S","Z","UE","AE","OE","SS","E","O","N","U","OE","'","S","AE","A","O","C","U","L","Y","A"); 
  Return str_replace($changeFrom, $changeTo, $input_string);
}

// f. to change UTF8 chars (Š) into HTML (%C5%A0) - to pass arguments through JS (onclick) - problem only in IE
function convertUTFtoHTML($input_string) {
  $changeFrom  = array("Š","Ø","É","Ö","Õ","Ž","Ä","Ñ","'","Č","Ü","ß","Ű","Ş","Æ","Å","ľ","Ý"); 
  $changeTo    = array("%C5%A0","%C3%98","%C3%89","%C3%96","%C3%95","%C5%BD","%C3%84","%C3%91","%27","%C4%8C","%C3%9C","%C3%9F","%C5%B0","%C5%9E","%C3%86","%C3%85","%C4%BE","%C3%9D");
  Return str_replace($changeFrom, $changeTo, $input_string);
}

// f. to output compliance colors:
// 1=compliant to guide values = BLUE, 
// 2=prohibited throughout the season = GRAY, 
// 3=insufficiently sampled = ORANGE, 
// 4=not compliant = RED, 
// 5=compliant to mandatory values = GREEN, 
// 6=not sampled = ORANGE, 
// 0 = ORANGE
function complianceColor($value) {
  switch($value) {
      case 0: Return "#FFCB67"; break;
      case 1: Return "#B9E8F7"; break;
      case 2: Return "#CFCFCF"; break;
      case 3: Return "#FFCB67"; break;
      case 4: Return "#FF7F7F"; break;
      case 5: Return "#98FF97"; break;
      case 6: Return "#FFCB67"; break;
      default: Return "white"; break;
  }
}

// f. to ouput compliance symbol (nc) for compliance value
// 1=cg = BLUE, 
// 2=b = GRAY, 
// 3=nf (za nas ns) = ORANGE, 
// 4=nc = RED, 
// 5=ci = GREEN, 
// 6=ns = ORANGE, 
// 0=(ns) ORANGE
function complianceCharacter($value) {
  switch($value) {
      case 0: Return "ns"; break;
      case 1: Return "cg"; break;
      case 2: Return "b"; break;
      case 3: Return "nf"; break;
      case 4: Return "nc"; break;
      case 5: Return "ci"; break;
      case 6: Return "nc"; break;
      default: Return "ns"; break;
  }
}

// f. to output text for compliance value
function complianceText($value) {
  switch($value) {
      case 0: Return "Not sampled"; break;
      case 1: Return "Compliant to guide values (excellent)"; break;
      case 2: Return "Prohibited throughout the season (closed)"; break;
      case 3: Return "Insufficiently sampled"; break;
      case 4: Return "Not compliant with mandatory values (poor)"; break;
      case 5: Return "Compliant to mandatory values (good)"; break;
      case 6: Return "Not compliant"; break;
      default: Return "Not sampled"; break;
  }
}

?>