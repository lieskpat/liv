<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'BRNeueingang.php';
require_once 'SqlHelper.php';
require_once 'Config.php';
require_once 'WorkOnDB.php';

$simpleXmlElementTotal = simplexml_load_file('br_neueingaenge.xml');
//$simpleXmlElementUpdate = simplexml_load_file(Config::BRRSSXML);
$brNeueingangTotalObjectArray = xmlToBRNeueingangObject($simpleXmlElementTotal->channel->item);
//$brNeieingangUpdateObjectArray = xmlToBRNeueingangObject($simpleXmlElementUpdate->channel->item);

$sqlInsertCommand = 'Insert into tx_delegates_domain_model_topdok (pid'
//    . ', tstamp'
//    . ', crdate'
//    . ', cruser_id'
//    . ', t3ver_oid '
//    . ', t3ver_id'
//    . ', t3ver_wsid'
//    . ', t3ver_label'
//    . ', t3ver_state'
//    . ', t3ver_stage'
//    . ', t3ver_count'
//    . ', t3ver_tstamp'
//    . ', t3_origuid'
//    . ', sys_language_uid'
//    . ', l10n_parent'
//    . ', l10n_diffsource'
//    . ', deleted'
//    . ', hidden'
    . ', wp'
    . ', fileDate'
    . ', filename'
    . ', filenamedoc'
    . ', betreff'
    . ', kurzbez'
    . ', dokNumber'
    . ', dokNumberExtr'
    . ', dokType'
    . ', dokArt'
    . ', einreicher'
    . ', drucksachenArt'
    . ', color'
    . ', pages'
    . ', ffAusschuss'
    . ', mitAusschuss'
    . ', wahlkreis'
    . ', verfuegung'
    . ', rankDokType'
    . ', importid'
    . ', baseImportId'
    . ') '
    . 'values (:pid, :wp, :fileDate, :filename, :filenamedoc, :betreff, :kurzbez'
    . ', :dockNumber, :dockNumberExtr, :dockType, :dockArt, :einreicher, :drucksachenArt'
    . ', :color, :pages, :ffAusschuss, :mitAusschuss, :wahlkreis, :verfuegung, :rankDokType, :importid, :baseImportId)';

$sqlHelper = new SqlHelper(Config::DBSOURCE, Config::USERNAME, Config::PASSWORD);
//$sqlInsertCommand = 'Insert into br_neueingang (title, link, creation_date, pub_date, author, drs_number) '
//    . 'values (:title, :link, :creationDate, :pubDate, :author, :drsNumber)';
foreach ($brNeueingangTotalObjectArray as $brNeueingang) {
    $sqlHelper->execute($sqlInsertCommand, [
        ':pid' => '0',
        ':wp' => '7',
        ':fileDate' => $brNeueingang->getCreationDate(),
        ':filename' => $brNeueingang->getLink(),
        ':filenamedoc' => '',
        ':betreff' => $brNeueingang->getTitle(),
        ':kurzbez' => '',
        ':dockNumber' => $brNeueingang->getDrsNumber(),
        ':dockNumberExtr' => '0',
        ':dockType' => '6_0_BR_Neueingaenge',
        ':dockArt' => 'Informationen der Landesregierung',
        ':einreicher' => 'Staatskanzlei',
        ':drucksachenArt' => '',
        ':color' => '0',
        ':pages' => '0',
        ':ffAusschuss' => '',
        ':mitAusschuss' => '',
        ':wahlkreis' => '',
        ':verfuegung' => '',
        ':rankDokType' => '0',
        ':importid' => '',
        ':baseImportId' => '0',
        ]
        ,1
    );
}

//foreach ($brNeueingangTotalObjectArray as $brNeueingang) {
//    $sqlHelper->execute($sqlInsertCommand, [
//        ':title' => $brNeueingang->getTitle(),
//        ':link' => $brNeueingang->getLink(),
//        ':creationDate' => $brNeueingang->getCreationDate(),
//        ':pubDate' => $brNeueingang->getPubDate(),
//        ':author' => $brNeueingang->getAuthor(),
//        ':drsNumber' => $brNeueingang->getDrsNumber()
//        ]
//        ,1
//    );
//}

$paramPreparedStatementArray = [':title', ':link', ':creationDate', ':pubDate', ':author', ':drsNumber'];

function persistBrNeueingangObjectIntoDB($objectArray, $paramPreparedStatementArray, $sqlHelper, $sqlCommand) {
    foreach ($objectArray as $value) {
        foreach ($paramPreparedStatementArray as $param) {
            $sqlHelper->execute($sqlCommand, [
                $param => $value->getTitle(),
                ]
            );
        }
        for ($index = 0; $index < count($paramPreparedStatementArray); $index++) {
            $sqlHelper->execute($sqlCommand, [
                $paramPreparedStatementArray[$index] => $value->getTitle(),
                $paramPreparedStatementArray[$index] => $value->getLink(),
                $paramPreparedStatementArray[$index] => $value->getCreationDate(),
                $paramPreparedStatementArray[$index] => $value->getPubDate(),
                $paramPreparedStatementArray[$index] => $value->getAuthor(),
                $paramPreparedStatementArray[$index] => $value->getDrsNumber(),
                ]
            );
        }
        foreach ($paramPreparedStatementArray as $key => $param) {
            
        }
    }
}

/**
 * 
 * @param type $xmlArray
 * @return \BRNeueingang
 */
function xmlToBRNeueingangObject($xmlArray) {
    foreach ($xmlArray as $item) {
        $BRNeueingangObjectArray[] = BRNeueingang::create()
            ->setTitle($item->title)
            ->setLink($item->link)
            ->setCreationDateToDateTime($item->pubDate)
            ->setAuthor($item->author)
            ->setDrsNumberFromTitle()
            ->setPubDateFromTitle();
    }
    return $BRNeueingangObjectArray;
}

$sqlSelectCommand = 'select betreff, fileDate, fileName, dokNumber from tx_delegates_domain_model_topdok where dokType = :dokType';

//$paramArray = array (':creationDate' => '1526767200');

/**
 * 
 * @param type $sqlHelper
 * @param type $sqlCommand
 */
function topDokTableToBRNeueingangObject($sqlHelper, $sqlCommand) {
    $BRNeueingangObjectArray = array();
    $resultArray = $sqlHelper->execute($sqlCommand, [
        ':dokType' => '6_0_BR_Neueingaenge',
    ]
        );
    foreach ($resultArray as $value) {
        $BRNeueingangObjectArray[] = BRNeueingang::create()
            ->setTitle($value['betreff'])
            ->setCreationDate($value['fileDate'])
            ->setLink($value['fileName'])
            ->setDrsNumber($value['dokNumber']);
    }
    return $BRNeueingangObjectArray;
}

foreach (topDokTableToBRNeueingangObject($sqlHelper, $sqlSelectCommand) as $brNeueingang) {
    $brNeueingang->toString();
    echo '-------------------------------------------' . "\n";
}
//var_dump(topDokTableToBRNeueingangObject($sqlHelper, $sqlSelectCommand));

//read new rss.xml and convert to BRNeueingang object
//compare db with new rss.xml and update db



