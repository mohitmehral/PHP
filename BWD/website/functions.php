<?php

// f. za spremenit 'nedovoljene znake'
function changeChars($input_string, $changeTo) {
  $charsToChange = array(" ",",",":",";","?");
  Return str_replace($charsToChange, $changeTo, $input_string);
}

// f. spremeni UTF8 znake (Č,Š,Ž,Ü,...) v (C,S,Z,U,...) - SAMO ZA PRIKAZ V GRAFIH jpgraph, ker ne podpira UTF8
function replaceUTFChars($input_string) {
  $changeFrom  = array("Č","Š","Ž","Ü","Ä","Ö","ß","É","Õ","Ñ","Ű","Ø","\\'","Ş","Æ","Å","Ő","Ç","Ú","ľ","Ý"); 
  $changeTo       = array("C","S","Z","UE","AE","OE","SS","E","O","N","U","OE","'","S","AE","A","O","C","U","L","Y"); 
  Return str_replace($changeFrom, $changeTo, $input_string);
}

// VEľKÝ DRAŽDIAK
// f. spremeni UTF8 znake (Š) v HTML (%C5%A0) - za prenos preko JAVASCRIPTA (onclick) - problem samo v IE
function convertUTFtoHTML($input_string) {
  $changeFrom  = array("Š","Ø","É","Ö","Õ","Ž","Ä","Ñ","'","Č","Ü","ß","Ű","Ş","Æ","Å","ľ","Ý"); 
  $changeTo    = array("%C5%A0","%C3%98","%C3%89","%C3%96","%C3%95","%C5%BD","%C3%84","%C3%91","%27","%C4%8C","%C3%9C","%C3%9F","%C5%B0","%C5%9E","%C3%86","%C3%85","%C4%BE","%C3%9D");
  Return str_replace($changeFrom, $changeTo, $input_string);
}

// f. obratna kot convertUTFtoHTML - za dobljeno preko GET (SE MI ZDI DA SPLOH NE RABIŠ !)
/*
function convertHTMLtoUTF($input_string) {
  $changeFrom  = array("%C5%A0","%C3%98","%C3%89","%C3%96","%C3%95","%C5%BD","%C3%84","%C3%91","%27"); 
  $changeTo       = array("Š","Ø","É","Ö","Õ","Ž","Ä","Ñ","'");
  Return str_replace($changeFrom, $changeTo, $input_string);
}
*/
// f. za pobarvat glede na compliance:
// 1=compliant to guide values = MODRA, 
// 2=prohibited throughout the season = SIVA, 
// 3=insufficiently sampled = ORANŽNA, 
// 4=not compliant = RDEČA, 
// 5=compliant to mandatory values = ZELENA, 
// 6=not sampled = ORANŽNA, 
// 0 = ORANŽNA
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

// f. za izpis simbola (nc) za compliance value
// 1=cg = MODRA, 
// 2=b = SIVA, 
// 3=nf (za nas ns) = ORANŽNA, 
// 4=nc = RDEČA, 
// 5=ci = ZELENA, 
// 6=ns = ORANŽNA, 
// 0= (za nas ns) ORANŽNA
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

// f. za izpis teksta za compliance value
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