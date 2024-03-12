<?php

include_once './model/familiemodel.php';
include_once './model/familieledenmodel.php';
include_once './model/contributiesmodel.php';
include_once './model/groepenmodel.php';
include_once './model/Boekjaarmodel.php';

// Maak instanties van de controllers
$familieController = new FamilieController($conn);
$groupController = new GroupController();
$boekjaarController = new BoekjaarController();
$contributieController = new ContributieController();

// Kijk welke actie er wordt gevraagd
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Roep de juiste actie aan op basis van de queryparameter
switch ($action) {
    case 'addFamily':
        $familieController->addFamily();
        break;
    case 'addMember':
        $familieController->addMember();
        break;
    case 'editFamily':
        $familieController->editFamily();
        break;
    case 'editMember':
        $familieController->editMember();
        break;
    case 'members':
        $familieController->viewMembers();
        break;
    case 'deleteMember':
        $familieController->deleteMember();
        break;
    case 'deleteFamily':
        $familieController->deleteFamily();
        break;
    case 'viewGroups':
        $groupController->viewGroups();
        break;
    case 'addGroup':
        $groupController->addGroup();
        break;
    case 'editGroup':
        $groupController->editGroup();
        break;
    case 'deleteGroup':
        $groupController->deleteGroup();
        break;
    case 'contributions':
        $contributieController->viewContributions();
        break;
    case 'addcontributions':
        $contributieController->addContribution();
        break;
    case 'editcontributions':
        $contributieController->editContributions();
        break;
    case 'deletecontributions':
        $contributieController->deleteContribution();
        break;
    case 'yearoverview':
        $boekjaarController->showBoekjaarPage();
        break;
    case 'addYear':
        $boekjaarController->addBoekjaar();
        break;
    case 'editYear':
        $boekjaarController->editBoekjaar();
        break;
    case 'deleteYear':
        $boekjaarController->deleteBoekjaar();
        break;
    default:
        $familieController->index();
}

?>