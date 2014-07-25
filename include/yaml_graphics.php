<?php
//YAML - graphics related functions
include_once('spyc/Spyc.php');
include_once('yaml_common.php');

/**
 * Updates typeID information from typeIDs.yaml file
 * 
 * @global $LM_EVEDB - EVE Static Data db name
 */
function updateYamlTypeIDs($silent=true) {
    global $LM_EVEDB;
    
    $file="../data/$LM_EVEDB/typeIDs.yaml";
    
    if (!file_exists($file)) {
        echo("File $file does not exist. Make sure YAML files from EVE SDE are in appropriate directories.");
        return FALSE;
    }
    
    $createyamlTypeIDs="CREATE TABLE IF NOT EXISTS `$LM_EVEDB`.`yamlTypeIDs` (
      `typeID` int(11) NOT NULL,
      `graphicID` int(11) NULL,
      `iconID` int(11) NULL,
      `radius` decimal(30,2) NULL,
      `soundID` int(11) NULL,
      PRIMARY KEY (`typeID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
    db_uquery($createyamlTypeIDs);
    
    $createyamlInvTraits="CREATE TABLE IF NOT EXISTS `$LM_EVEDB`.`yamlInvTraits` (
      `typeID` int(11) DEFAULT NULL,
      `skillID` int(11) DEFAULT NULL,
      `bonus` double DEFAULT NULL,
      `bonusText` text,
      `unitID` int(11) DEFAULT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
    db_uquery($createyamlInvTraits);
    
    if (!$silent) echo('Converting YAML compact in-line notation...');
    $fileraw=file_get_contents($file);
    $fileraw=str_replace('bonusText: ', "bonusText: ingore\r\n                    ", $fileraw);
    
    if (!$silent) echo('loading YAML...');
    $typeIDs = Spyc::YAMLLoadString($fileraw);
    
    //$typeIDs = Spyc::YAMLLoad($file);
    if (!empty($typeIDs)) {
        db_uquery("TRUNCATE TABLE `$LM_EVEDB`.`yamlTypeIDs`;");
        db_uquery("TRUNCATE TABLE `$LM_EVEDB`.`yamlInvTraits`;");
    } else return false;
    
    $biginsertTypeIDs="INSERT INTO `$LM_EVEDB`.`yamlTypeIDs` VALUES ";
    $biginsertTraits="INSERT INTO `$LM_EVEDB`.`yamlInvTraits` VALUES ";
    foreach($typeIDs as $typeID => $row) {
        $graphicID=yaml_prepare($row['graphicID']);
        $iconID=yaml_prepare($row['iconID']);
        $radius=yaml_prepare($row['radius']);
        $soundID=yaml_prepare($row['soundID']);
        $biginsertTypeIDs.="($typeID, $graphicID, $iconID, $radius, $soundID),";
        if (is_array($row['traits'])) { //if there are traits
            foreach ($row['traits'] as $skillID => $traits) {
                foreach ($traits as $trait) {
                    $bonus=yaml_prepare($trait['bonus']);
                    $bonusText=yaml_prepare($trait['bonusText']);
                    $unitID=yaml_prepare($trait['unitID']);
                    $biginsertTraits.="($typeID, $skillID, $bonus, '$bonusText', $unitID),";
                }
            }
        }
    }
    
    $biginsertTypeIDs=rtrim($biginsertTypeIDs,',').";";
    $biginsertTraits=rtrim($biginsertTraits,',').";";
    
    if (!$silent) echo('insert to DB...');
    
    db_uquery($biginsertTypeIDs);
    db_uquery($biginsertTraits);
    
    return true;
}

/**
 * Updates typeID information from typeIDs.yaml file
 * 
 * @global $LM_EVEDB - EVE Static Data db name
 */
function updateYamlGraphicIDs($silent=true) {
    global $LM_EVEDB;
    
    $file="../data/$LM_EVEDB/graphicIDs.yaml";
    
    if (!file_exists($file)) {
        echo("File $file does not exist. Make sure YAML files from EVE SDE are in appropriate directories.");
        return FALSE;
    }
    
    $create="CREATE TABLE IF NOT EXISTS `$LM_EVEDB`.`yamlGraphicIDs` (
      `graphicID` int(11) NULL,
      `colorScheme` varchar(256) NULL,
      `description` varchar(256) NULL,
      `graphicFile` varchar(512) NULL,
      `graphicName` varchar(256) NULL,
      `graphicType` varchar(256) NULL,
      `gfxRaceID` varchar(64) NULL,
      `collidable` boolean NULL,
      `directoryID` int(11) NULL,
      PRIMARY KEY (`graphicID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
    db_uquery($create);
    
    if (!$silent) echo('loading YAML...');
    $graphicIDs = Spyc::YAMLLoad($file);
    
    if (!empty($graphicIDs)) {
        db_uquery("TRUNCATE TABLE `$LM_EVEDB`.`yamlGraphicIDs`;");
    } else return false;
    $biginsert="INSERT INTO `$LM_EVEDB`.`yamlGraphicIDs` VALUES ";
    foreach($graphicIDs as $graphicID => $row) {
        if (!isset($row['colorScheme'])) $colorScheme='NULL'; else $colorScheme="'".addslashes($row['colorScheme'])."'";
        if (!isset($row['description'])) $description='NULL'; else $description="'".addslashes($row['description'])."'";
        if (!isset($row['graphicFile'])) $graphicFile='NULL'; else $graphicFile="'".addslashes($row['graphicFile'])."'";
        if (!isset($row['graphicName'])) $graphicName='NULL'; else $graphicName="'".addslashes($row['graphicName'])."'";
        if (!isset($row['graphicType'])) $graphicType='NULL'; else $graphicType="'".addslashes($row['graphicType'])."'";
        if (!isset($row['gfxRaceID'])) $gfxRaceID='NULL'; else $gfxRaceID="'".addslashes($row['gfxRaceID'])."'";
        if (!isset($row['collidable'])) $collidable='NULL'; else $collidable=$row['collidable'];
        if (!isset($row['directoryID'])) $directoryID='NULL'; else $directoryID=$row['directoryID'];
        $biginsert.="($graphicID, $colorScheme, $description, $graphicFile, $graphicName, $graphicType, $gfxRaceID, $collidable, $directoryID),";
    }
    $biginsert=rtrim($biginsert,',').";";
    if (!$silent) echo('insert to DB...');
    db_uquery($biginsert);
    return true;
}

/**
 * Fetches resource file names for CCP WebGL from manually created `ccpwglmapping` table
 * 
 * @deprecated
 * @param type $typeID
 * @return array typeID, shipModel, background, thrusters or false if not found
 */
function getResourceFromMapping($typeID) {
    $modelinfo=db_asocquery("SELECT * FROM `ccpwglmapping` WHERE `typeID`=$typeID;");
    if (count($modelinfo)==1) {
        return $modelinfo[0];
    } else return false;
}

/**
 * Fetches resource file names for CCP WebGL from YAML imported data
 * 
 * @param type $typeID
 * @return array typeID, shipModel, background, thrusters or false if not found
 */
function getResourceFromYaml($typeID) {
    global $LM_EVEDB;
    $modelinfo=db_asocquery("SELECT * FROM `$LM_EVEDB`.`yamlTypeIDs` yti JOIN `$LM_EVEDB`.`yamlGraphicIDs` ygi ON yti.`graphicID`=ygi.`graphicID` WHERE yti.`typeID`=$typeID;");
    if (count($modelinfo)==1) {
        $model=$modelinfo[0];
        $returns['typeID']=$typeID;
        $returns['shipModel']=$model['graphicFile'];
        /*
        NULL
        Caldari
        Minmatar
        Amarr
        Gallente
        Jove
        Angel
        Sansha
        ORE
        Concord
        RogueDrone
        SOCT
        Generic
        Sleeper
        Talocan
         * (28661, 'res:/dx9/model/ship/gallente/battleship/gb2/duvolle/gb2_t2_duvolle.red', 'res:/dx9/scene/universe/g04_cube.red', 'res:/dx9/model/ship/booster/booster_gallente.red'),
(28659, 'res:/dx9/model/ship/amarr/battleship/ab1/sarum/ab1_t2_sarum.red', 'res:/dx9/scene/universe/a04_cube.red', 'res:/dx9/model/ship/booster/booster_amarr.red'),
(28710, 'res:/dx9/model/ship/caldari/battleship/cb1/laidai/cb1_t2_laidai.red', 'res:/dx9/scene/universe/c03_cube.red', 'res:/dx9/model/ship/booster/booster_caldari.red'),
(28665, 'res:/dx9/model/ship/minmatar/battleship/mb2/brutor/mb2_t2_brutor.red', 'res:/dx9/scene/universe/m01_cube.red', 'res:/dx9/model/ship/booster/booster_minmatar.red');
        */
        switch($model['gfxRaceID']) {
            case 'Caldari':
                $returns['background']='res:/dx9/scene/universe/c03_cube.red';
                $returns['thrusters']='res:/dx9/model/ship/booster/booster_caldari.red';
                break;
            case 'Minmatar':
                $returns['background']='res:/dx9/scene/universe/m01_cube.red';
                $returns['thrusters']='res:/dx9/model/ship/booster/booster_minmatar.red';
                break;
            case 'Amarr':
                $returns['background']='res:/dx9/scene/universe/a04_cube.red';
                $returns['thrusters']='res:/dx9/model/ship/booster/booster_amarr.red';
                break;
            case 'Gallente':
                $returns['background']='res:/dx9/scene/universe/g04_cube.red';
                $returns['thrusters']='res:/dx9/model/ship/booster/booster_gallente.red';
                break;
            case 'Angel':
                $returns['background']='res:/dx9/scene/universe/m01_cube.red';
                $returns['thrusters']='res:/dx9/model/ship/booster/booster_minmatar.red';
                break;
            case 'Sansha':
                $returns['background']='res:/dx9/scene/universe/a04_cube.red';
                $returns['thrusters']='res:/dx9/model/ship/booster/booster_amarr.red';
                break;
            case 'ORE':
                $returns['background']='res:/dx9/scene/universe/g04_cube.red';
                $returns['thrusters']='res:/dx9/model/ship/booster/booster_gallente.red';
                break;
            default:
                $returns['background']='res:/dx9/scene/universe/g04_cube.red';
                $returns['thrusters']='res:/dx9/model/ship/booster/booster_gallente.red';
        }
        return $returns;
    } else return false;
}

?>