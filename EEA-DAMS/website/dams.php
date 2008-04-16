<?php
/**
 * EEA-DAMS dams.php
 *
 * The contents of this file are subject to the Mozilla Public
 * License Version 1.1 (the "License"); you may not use this file
 * except in compliance with the License. You may obtain a copy of
 * the License at http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS
 * IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or
 * implied. See the License for the specific language governing
 * rights and limitations under the License.
 *
 * The Original Code is "EEA-DAMS version 1.0".
 *
 * The Initial Owner of the Original Code is European Environment
 * Agency.  Portions created by I.O.Water are
 * Copyright (C) European Environment Agency.  All
 * Rights Reserved.
 *
 * Contributor(s):
 *  Original Code: François-Xavier Prunayre, I.O.Water <fx.prunayre@oieau.fr>
 *
 *
 * @abstract	 Manage dams.
 * @copyright    2005
 * @version    	 1.0
 *
 *
 */

require_once 'commons/config.php';
require_once 'DataObjects/Public_dams.php';
require_once 'DataObjects/DamObject.php';
require_once 'DataObjects/CountryDamObject.php';
require_once 'DataObjects/Public_user_dams_assigned.php';
require_once 'DataObjects/stat_country_dams_valid_user.php';
require_once 'DataObjects/stat_country_dams_valid.php';
require_once 'google.php';

if ($a->getAuth()) {
  if (isset($_REQUEST["action"]) && isset($_REQUEST["cd"])) {
    $dam = new DataObjects_Public_dams();
    $dam->whereAdd ("NOEEA = '".$_REQUEST["cd"]."'");
    $dam->find(true);
    $dam->x_val = $_REQUEST["x"];
    $dam->y_val = $_REQUEST["y"];
    $dam->x_icold = $_REQUEST["xini"];
    $dam->y_icold = $_REQUEST["yini"];
    $dam->comments = $_REQUEST["comment"];

    if ($_REQUEST["x"] != null)					// Unvalid if null
    $dam->valid = 1;
    else
    $dam->valid = 0;

    if ($_REQUEST["is_oncanal"] != null)
    $dam->is_oncanal = 1;
    else
    $dam->is_oncanal = 0;

    if ($_REQUEST["is_dyke"] != null)
    $dam->is_dyke = 1;
    else
    $dam->is_dyke = 0;

    $dam->update(DB_DATAOBJECT_WHEREADD_ONLY);
    $dam->free();

    $file->log('Dams: '.$_REQUEST["cd"].' validated by '.$_SESSION["LOGIN"]);
  }

  $i18nPage = 'dams';
  $smarty = iniI18n ($i18nPage, $smarty, $i18n);

  $dummy = new DataObjects_Public_dams();
  // Set the outOfRange values on N/A reset button
  $smarty->assign( "outOfRangeX", $dummy->outOfRange );
  $smarty->assign( "outOfRangeY", $dummy->outOfRange );
  //DB_DataObject::debugLevel(5);
  // Process the request
  $urlFilter = "";
  $daml = new DataObjects_Public_dams();
  $smarty->assign( 'damCountryFilter', $daml->getCountryList() );

  $userid = $_SESSION["ID"];
  $template = "dams_list.tpl";
  $i = 0;
  $isSingleDam = false;
  $singleDam = null;
  
  $damIdsOrdered = array();
  // cd parameter passed -> one or more dams
  if( isset( $_REQUEST[ "cd" ] ) && $_REQUEST[ "cd" ] != '' ) {
    $code = $_REQUEST[ "cd" ];
    if ( $_SESSION[ "ADM" ] == 't' ) {
      $daml = new DataObjects_Public_dams();
      $daml->query( "SELECT * FROM $daml->__table WHERE noeea ILIKE '%$code%' ORDER BY name" );
      if( $daml->N > 1 || $daml->N == 0 ) {
        $dt = array();
        while ($daml->fetch()) {
          $aDam = new DamObject();
          $aDam->name = $daml->name;
          $aDam->code = $daml->noeea;
          $aDam->valid = $daml->valid == 't' ? true : false;
          $aDam->country = $daml->country;
          $dt[ $i ] = $aDam;
          $damIdsOrdered[ $i ] = $daml->noeea;
          $i++;
        }
        $_SESSION[ "damIdsOrdered" ] = $damIdsOrdered;
        $smarty->assign('dt', $dt);
        $smarty->display('dams_list.tpl');
        
        return;
      } else {
        $daml->fetch();
        $singleDam = $daml->noeea;
        $isSingleDam = true;
      }
    } else {
      $daml = new DataObjects_Public_user_dams_assigned();
      $whereAdd .= "";
      $daml->query( "SELECT * FROM $daml->__table WHERE cd_dam ILIKE '%$code%' AND cd_user = $userid ORDER BY name" ); 
      if( $daml->N > 1 || $daml->N == 0 ) {
        $dt = array();
        while ($daml->fetch()) {
          $aDam = new DamObject();
          $aDam->name = $daml->name;
          $aDam->code = $daml->cd_dam;
          $aDam->valid = $daml->valid == 't' ? true : false;
          $aDam->country = $daml->country;
          $dt[ $i ] = $aDam;
          $damIdsOrdered[ $i ] = $daml->cd_dam;
          $i++;
        }
        $_SESSION[ "damIdsOrdered" ] = $damIdsOrdered;
        $smarty->assign_by_ref( 'dt', $dt );
        $smarty->display('dams_list.tpl');
        
        return;
      } else {
        $daml->fetch();
        $singleDam = $daml->cd_dam;
        $isSingleDam = true;
      }
    }
    $urlFilter .= "&amp;cd=$code";
  }
  // srcName passed -> one or more dams
  else if( isset( $_REQUEST[ "srcName" ] ) && $_REQUEST[ "srcName" ] != '' ) {
    $name = $_REQUEST[ "srcName" ];
    if ( $_SESSION[ "ADM" ] == 't' ) {
      $daml = new DataObjects_Public_dams();
      $whereAdd .= "";  
      $daml->query( "SELECT * FROM $daml->__table WHERE name ILIKE '%$name%' ORDER BY name" );
      if( $daml->N > 1 || $daml->N == 0 ) {
        $dt = array();
        while ($daml->fetch()) {
          $aDam = new DamObject();
          $aDam->name = $daml->name;
          $aDam->code = $daml->noeea;
          $aDam->valid = $daml->valid == 't' ? true : false;
          $aDam->country = $daml->country;
          $dt[ $i ] = $aDam;
          $damIdsOrdered[ $i ] = $daml->noeea;
          $i++;
        }
        $_SESSION[ "damIdsOrdered" ] = $damIdsOrdered;
        $smarty->assign('dt', $dt);
        $smarty->display('dams_list.tpl');
        
        return;
      } else {
        $daml->fetch();
        $singleDam = $daml->noeea;
        $isSingleDam = true;
      }
    } else {
      $daml = new DataObjects_Public_user_dams_assigned();
      $whereAdd .= " ";
      $daml->query( "SELECT * FROM $daml->__table WHERE name ILIKE '%$name%' AND cd_user=$userid ORDER BY name" ); 
      if( $daml->N > 1 || $daml->N == 0 ) {
        $dt = array();
        while ($daml->fetch()) {
          $aDam = new DamObject();
          $aDam->name = $daml->name;
          $aDam->code = $daml->cd_dam;
          $aDam->valid = $daml->valid == 't' ? true : false;
          $aDam->country = $daml->country;
          $dt[ $i ] = $aDam;
          $damIdsOrdered[ $i ] = $daml->cd_dam;
          $i++;
        }
        $_SESSION[ "damIdsOrdered" ] = $damIdsOrdered;
        $smarty->assign_by_ref( 'dt', $dt );
        $smarty->display('dams_list.tpl');
        
        return;
      } else {
        $daml->fetch();
        $singleDam = $daml->cd_dam;
        $isSingleDam = true;
      }
    }
    $urlFilter .= "&amp;srcName=$name";
  }
  // srcCountry passed -> Multiple dams
  else if( isset( $_REQUEST[ "srcCountry" ] ) && $_REQUEST[ "srcCountry" ] != '' ) {
    $country = $_REQUEST[ "srcCountry" ];
    if( $country == "all" ) {
      if( $_SESSION[ "ADM" ] == 't' ) {
        $daml = new DataObjects_stat_country_dams_valid();
        $daml->query( "SELECT * FROM $daml->__table ORDER BY country_code" );
        $dt = array();
        $aCDO = null;
        $added = false;
        $i = 0;
        $prevCDO = null;
        while ($daml->fetch()) {
          $prevMatch = false;
          if( $prevCDO != null && $prevCDO->country_code == $daml->country_code )
          {
            $prevMatch = true;
            $aCDO = $prevCDO;
          } else {
            $aCDO = new CountryDamObject();
            $aCDO->country_code = $daml->country_code;
            $dt[ $i++ ] = $aCDO;
            $prevCDO = $aCDO;
          }
          if( $daml->valid == 't' )
          { 
            $aCDO->validatedDams = $daml->count;
          }
          else
          {
            $aCDO->invalidatedDams = $daml->count;
          }
        }
        $smarty->assign_by_ref( 'dt', $dt );
        $smarty->display('dams_country.tpl');
        
        return;
      } else {
        $daml = new DataObjects_stat_country_dams_valid_user();
        $daml->query( "SELECT * FROM $daml->__table WHERE cd_user=$userid ORDER BY country_code" );
        $dt = array();
        $aCDO = null;
        $added = false;
        $i = 0;
        $prevCDO = null;
        while ($daml->fetch()) {
          $prevMatch = false;
          if( $prevCDO != null && $prevCDO->country_code == $daml->country_code )
          {
            $prevMatch = true;
            $aCDO = $prevCDO;
          } else {
            $aCDO = new CountryDamObject();
            $aCDO->country_code = $daml->country_code;
            $dt[ $i++ ] = $aCDO;
            $prevCDO = $aCDO;
          }
          if( $daml->valid == 't' )
          { 
            $aCDO->validatedDams = $daml->count;
          }
          else
          {
            $aCDO->invalidatedDams = $daml->count;
          }
        }
        $smarty->assign_by_ref( 'dt', $dt );
        $smarty->display('dams_country.tpl');
        
        return;
      }
    } else {
      if( $_SESSION[ "ADM" ] == 't' ) {
        $daml = new DataObjects_Public_dams();
        $daml->query( "SELECT * FROM $daml->__table WHERE country='$country' ORDER BY name" );
        $dt = array();
        while ($daml->fetch()) {
          
          $aDam = new DamObject();
          $aDam->name = $daml->name;
          $aDam->code = $daml->noeea;
          $aDam->valid = $daml->valid == 't' ? true : false;
          $aDam->country = $daml->country;
          $dt[ $i ] = $aDam;
          $damIdsOrdered[ $i ] = $daml->noeea;
          $i++;
        }
        $_SESSION[ "damIdsOrdered" ] = $damIdsOrdered;
        $smarty->assign('dt', $dt);
        $smarty->display('dams_list.tpl');
        
        return;
      } else {
        $daml = new DataObjects_Public_user_dams_assigned();
        $daml->query( "SELECT * FROM $daml->__table WHERE cd_user=$userid AND country='$country' ORDER BY name"  );
        $dt = array();
        while ($daml->fetch()) {
          $aDam = new DamObject();
          $aDam->name = $daml->name;
          $aDam->code = $daml->cd_dam;
          $aDam->valid = $daml->valid == 't' ? true : false;
          $aDam->country = $daml->country;
          $dt[ $i ] = $aDam;
          $damIdsOrdered[ $i ] = $daml->cd_dam;
          $i++;
        }
        $_SESSION[ "damIdsOrdered" ] = $damIdsOrdered;
        $smarty->assign('dt', $dt);
        $smarty->display('dams_list.tpl');
        
        return;
      }
    }
  }
  
  
  if ( $isSingleDam ) {
    $sessionDamIdsOrdered = $_SESSION[ "damIdsOrdered" ];
    if( count( $sessionDamIdsOrdered ) > 0 ) {
      $smarty->assign( 'first', $sessionDamIdsOrdered[ 0 ] );
      $idx = array_search( $singleDam, $sessionDamIdsOrdered );
  
      if( $idx > 0 && $idx < count( $sessionDamIdsOrdered ) - 1 ) {
        $smarty->assign( 'next',  $sessionDamIdsOrdered[ $idx + 1 ] );
        $smarty->assign( 'previous', $sessionDamIdsOrdered[ $idx - 1 ] );
      } elseif( $idx == 0 ) {
        $smarty->assign( 'next',  $sessionDamIdsOrdered[ $idx + 1] );
        $smarty->assign( 'previous', $singleDam );
      } elseif( $idx == sizeof( $sessionDamIdsOrdered ) - 1 ) {
        $smarty->assign( 'previous', $sessionDamIdsOrdered[ $idx - 1 ] );
        $smarty->assign( 'next', $singleDam );
      } else {
        $smarty->assign( 'next', $singleDam );
        $smarty->assign( 'previous', $singleDam );
      }
      $smarty->assign('last', $sessionDamIdsOrdered[ sizeof( $sessionDamIdsOrdered ) - 1 ] );
    } 
    $daml = new DataObjects_Public_dams();
    $daml->query( "SELECT * FROM $daml->__table WHERE noeea='$singleDam'" );
    $daml->fetch();
    // One dam go to validation and process
    $smarty->assign('urlFilter', $_SESSION["urlFilter"]);
    
    $daml->get($_REQUEST["cd"]);
    $smarty->assign('dam', 				$daml);
    $smarty->assign('x_val', 			$daml->x_val);
    $smarty->assign('y_val', 			$daml->y_val);
    $smarty->assign('valid', 			$daml->valid);
    $i18nPage = 'dam';
    
    $smarty = iniI18n ($i18nPage, $smarty, $i18n);
    // Set the outOfRange values on N/A reset button
    $dummy = new DataObjects_Public_dams();
    $smarty->assign( "outOfRangeX", $dummy->outOfRange );
    $smarty->assign( "outOfRangeY", $dummy->outOfRange );
    
    // File with images ...
    if (file_exists (BASEDIR.TOPOPATH.''.strtoupper($daml->noeea).'.png') || file_exists (BASEDIR.TOPOPATH.''.strtolower($daml->noeea).'.png'))
      $smarty->assign('imgTopook',true);
    else
      $smarty->assign('imgTopook',false);
    $smarty->assign('imgTopo', 	WWWDIR.TOPOPATH.''.$daml->noeea.'.png');

    if (file_exists (BASEDIR.SPUDPATH.'clip_'.strtoupper($daml->noeea).'.jpg') || file_exists (BASEDIR.SPUDPATH.'clip_'.strtolower($daml->noeea).'.jpg'))
      $smarty->assign('imgSpudok',true);
    else
      $smarty->assign('imgSpudok',false);
    $smarty->assign('imgSpud', 	WWWDIR.SPUDPATH.'clip_'.strtolower($daml->noeea).'.jpg');

    if (file_exists (BASEDIR.SPANPATH.'clip_'.strtoupper($daml->noeea).'.jpg') || file_exists (BASEDIR.SPANPATH.'clip_'.strtolower($daml->noeea).'.jpg'))
      $smarty->assign('imgSpanok',true);
    else
      $smarty->assign('imgSpanok',false);
    $smarty->assign('imgSpan', 	WWWDIR.SPANPATH.'clip_'.strtolower($daml->noeea).'.jpg');

    if ($googleOn == true)
    {
      $center = $daml->getDamMapCenter();
      $exclude0x = "0"; $exclude0y = "0"; $exclude1x = "0"; $exclude1y = "0";
      if( $daml->isValidPosition( $daml->x_icold, $daml->y_icold ) )
      {
        $exclude0x = $daml->x_icold;
        $exclude0y = $daml->y_icold;
      }
      if( $daml->isValidPosition( $daml->x_val, $daml->y_val ) )
      {
        $exclude1x = $daml->x_val;
        $exclude1y = $daml->y_val;
      }
      
      $gmap = startGoogleViewport( $center[ 0 ], $center[ 1 ], $center[ 2 ], "damMapClickListener", true, $exclude0x, $exclude0y, $exclude1x, $exclude1y );
      if( $daml->isValidPosition( $daml->x_icold, $daml->y_icold ) )
      {
        $gmap .= createCrossMarker( "ICOLD", "ICOLD position", $daml->x_icold, $daml->y_icold, ICOLDICON, 1 );
        #$gmap .= createCrossMarker( "ICOLD", "ICOLD position", $center[ 0 ], $center[ 1 ], ICOLDICON, 1 );
      }
/* See https://svn.eionet.europa.eu/projects/Zope/ticket/1300      
      if( $daml->isValidPosition( $daml->x_prop, $daml->y_prop ) )
      {
        $gmap .= createCrossMarker( "EEA", "EEA proposed position", $daml->x_prop, $daml->y_prop, EEAICON, 2 );
      }
*/      
      if( $daml->isValidPosition( $daml->x_val, $daml->y_val ) )
      {
        $gmap .= createCrossMarker( "VAL", "Validated position", $daml->x_val, $daml->y_val, VALIDICON, 3 );
      }
      $gmap .= endGoogleViewport();
      $smarty->assign('googleMap', 	$gmap);
    }
    else
    {
      $smarty->assign('googleMap', 	null);
    }
    	
    $smarty->display('dam.tpl');
  }
} else {
  $smarty->display('index.tpl');
}
?>
