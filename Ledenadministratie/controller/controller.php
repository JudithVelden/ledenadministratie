<?php
// Inclusie van vereiste modellen en klassedefinities
include_once './model/familiemodel.php';
include_once './model/familieledenmodel.php';
include_once './model/contributiesmodel.php';
include_once './model/Boekjaarmodel.php';
include_once './model/LoginModel.php';

// Definitie van de FamilieController klasse
class FamilieController
{
    private $familieModel;
    private $familieledenModel;
    private $contributiesModel;
    private $loginModel;
    private $conn;

    // Constructor om de modellen in te stellen
    public function __construct($conn)
    {
        $this->familieModel = new Familie();
        $this->familieledenModel = new Familieleden();
        $this->contributiesModel = new ContributiesModel();
        $this->loginModel = new LoginModel();
        $this->conn = $conn;
    }

    // Methode voor het inloggen van gebruikers
    public function login() {
        if(isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            // Roep de methode aan om de gebruiker in te loggen
            $loginResult = $this->loginModel->loginUser($username, $password, $this->conn);

            // Controleer of het inloggen succesvol was
            if($loginResult) {
                // Inloggen gelukt, zet een sessievariabele om dit te onthouden
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                // Herlaad de pagina om de header bij te werken
                header('Location: ./index.php');
                exit();
            } else {
                // Inloggen mislukt, stel een sessievariabele in voor de foutmelding
                $_SESSION['login_error'] = "Gebruikersnaam of wachtwoord is onjuist.";
                // Herlaad de pagina om de foutmelding weer te geven
                header('Location: ./index.php');
                exit();
            }
        }
    }

    // Methode voor het uitloggen van gebruikers
    public function logout() {
        if(isset($_POST['logout'])) {
            // Vernietig de sessie om de gebruiker uit te loggen
            session_destroy();
            // Herlaad de pagina om de header bij te werken
            header('Location: ./index.php');
            exit();
        }
    }

    // Methode voor het weergeven van de hoofdpagina
    public function index()
    {
        $this->login();
        $families = $this->familieModel->getFamilies();
        $familiesMetFamilieleden = [];
        while ($row = $families->fetch(PDO::FETCH_ASSOC)) {
            $familieID = $row['ID'];
            $familieleden = $this->familieledenModel->getFamilieleden($familieID);
            $row['familieleden'] = $familieleden->fetchAll(PDO::FETCH_ASSOC);
            $familiesMetFamilieleden[] = $row;
        }
        $familiesMetFamilieleden = $this->calculateTotalContribution($familiesMetFamilieleden);
        include './view/families.php';
    }

    // Methode voor het weergeven van de ledenpagina
    public function viewMembers()
    {
        // Haal alle families op
        $families = $this->familieModel->getFamilies();

        // Maak een array om families en hun leden op te slaan
        $familiesMetFamilieleden = [];

        // Loop door elke familie
        while ($row = $families->fetch(PDO::FETCH_ASSOC)) {
            // Haal de ID van de familie op
            $familieID = $row['ID'];

            // Maak een nieuw object van de klasse Familieleden om de familieleden op te halen
            $familieledenModel = new Familieleden();
            $familieleden = $familieledenModel->getFamilieleden($familieID);

            // Maak een array om de familiegegevens en hun leden op te slaan
            $familieData = [];

            // Voeg de familiegegevens toe aan de array
            $familieData['ID'] = $row['ID'];
            $familieData['Naam'] = $row['Naam'];
            $familieData['Adres'] = $row['Adres'];

            // Voeg de familieleden toe aan de array
            $familieData['Familieleden'] = $familieleden->fetchAll(PDO::FETCH_ASSOC);

            // Voeg de familiegegevens en hun leden toe aan de array met alle families
            $familiesMetFamilieleden[] = $familieData;
        }

        // Laad de view met de juiste gegevens
        include './view/familieleden.php';
    }

    // Methode voor het toevoegen van een familie
    public function addFamily()
    {
        if (isset($_POST['submit'])) {
            $naam = $_POST['naam'];
            $adres = $_POST['adres'];
            if ($this->familieModel->addFamilie($naam, $adres)) {
                header('Location: ./index.php');
                exit();
            }
        }
        include './view/add_familie.php';
    }

    // Methode voor het toevoegen van een familielid
    public function addMember()
    {
        $families = $this->familieModel->getFamilies();

        if (isset($_POST['submit'])) {
            $familieID = $_POST['familieID']; // Ophalen van de geselecteerde familie ID uit het formulier
            $voornaam = $_POST['voornaam'];
            $geboortedatum = $_POST['geboortedatum'];

            // Bepaal het soort lid op basis van de geboortedatum
            $soortLid = $this->bepaalSoortLid($geboortedatum);

            // Voeg het lid toe met het bepaalde soort lid
            if ($this->familieledenModel->addMember($familieID, $voornaam, $geboortedatum, $soortLid)) {
                header('Location: ./index.php');
                exit();
            } else {
                echo "Er is een fout opgetreden bij het toevoegen van het familielid.";
            }
        }
        include './view/add_familielid.php';
    }
    
    // Methode voor het bewerken van een familie
    public function editFamily()
    {
        if (isset($_POST['submit'])) {
            $familyID = $_POST['familyID'];
            $newName = $_POST['newName'];
            $newAddress = $_POST['newAddress'];

            if ($this->familieModel->editFamily($familyID, $newName, $newAddress)) {
                // Als de bewerking succesvol is, doorverwijzen naar de pagina met de tabel met families
                header('Location: ./index.php');
                exit();
            } else {
                echo "Er is een fout opgetreden bij het aanpassen van de familie.";
            }
        }

        // Haal de lijst met families op
        $familiesMetFamilieleden = $this->familieModel->getFamilies();

        // Laad de view voor het bewerken van een familie
        include './view/edit_familie.php';
    }

    // Methode voor het bewerken van een lid
    public function editMember()
    {
        if (isset($_POST['submit'])) {
            $memberID = $_POST['memberID'];
            $newName = $_POST['newName'];
            $familyID = $_POST['familyID'];
            $newBirthdate = $_POST['newBirthdate'];

            if ($this->familieledenModel->editMember($memberID, $newName, $familyID, $newBirthdate)) {
                header('Location: ./index.php?action=members'); // Redirect naar de ledenpagina na het bewerken
                exit();
            } else {
                echo "Er is een fout opgetreden bij het bewerken van het lid.";
            }
        }

        // Haal de lijst met families op
        $families = $this->familieModel->getFamilies();

        // Laad de view voor het bewerken van een lid
        include './view/edit_familielid.php';
    }

    // Methode voor het verwijderen van een familielid
    public function deleteMember()
    {
        if (isset($_POST['delete'])) {
            $memberID = $_POST['member'];

            // Haal de voornaam en achternaam op van het geselecteerde familielid
            $memberInfo = $this->familieledenModel->getMemberInfo($memberID);
            $voornaam = $memberInfo['voornaam'];
            $achternaam = $memberInfo['achternaam'];

            // Als het lid succesvol is verwijderd, doorverwijzen naar de ledenpagina
            if ($this->familieledenModel->deleteMember($memberID)) {
                header('Location: ./index.php?action=members');
                exit();
            } else {
                echo "Er is een fout opgetreden bij het verwijderen van het familielid.";
            }
        }

        // Haal alle familielid ID's en namen op om weer te geven in het dropdownmenu
        $familielidData = $this->familieledenModel->getAllMemberData();

        // Laad de view voor het verwijderen van een familielid
        include './view/delete_familielid.php';
    }

    // Methode voor het verwijderen van een familie
    public function deleteFamily()
    {
        if (isset($_POST['delete'])) {
            $familyID = $_POST['family'];

            if ($this->familieModel->deleteFamily($familyID)) {
                header('Location: ./index.php');
                exit();
            } else {
                echo "Er is een fout opgetreden bij het verwijderen van de familie.";
            }
        }

        // Haal alle families op
        $families = $this->familieModel->getFamilies();

        // Laad de view voor het verwijderen van een familie
        include './view/delete_familie.php';
    }

    // Methode voor het bepalen van het soort lid op basis van geboortedatum
    private function bepaalSoortLid($geboortedatum)
    {
        $geboortedatum = new DateTime($geboortedatum);
        $leeftijd = $geboortedatum->diff(new DateTime())->y;
        if ($leeftijd < 8) {
            return 1;
        } elseif ($leeftijd >= 8 && $leeftijd < 13) {
            return 2;
        } elseif ($leeftijd >= 13 && $leeftijd < 18) {
            return 3;
        } elseif ($leeftijd >= 18 && $leeftijd < 51) {
            return 4;
        } else {
            return 5;
        }
    }

    // Methode voor het berekenen van de leeftijd
    private function calculateAge($geboortedatum)
    {
        $today = new DateTime();
        $diff = $today->diff(new DateTime($geboortedatum));
        return $diff->y;
    }

    // Methode voor het berekenen van de contributie op basis van leeftijd en soort lid
    private function calculateContribution($leeftijd, $soortLid)
    {
        $basisbedrag = 100; // Basisbedrag contributie is €100,00 per jaar

        switch ($soortLid) {
            case 1: // Jeugd
                if ($leeftijd < 8) {
                    return $basisbedrag * 0.5; // 50% korting
                }
                break;
            case 2: // Aspirant
                if ($leeftijd >= 8 && $leeftijd < 13) {
                    return $basisbedrag * 0.6; // 40% korting
                }
                break;
            case 3: // Junior
                if ($leeftijd >= 13 && $leeftijd < 18) {
                    return $basisbedrag * 0.75; // 25% korting
                }
                break;
            case 4: // Senior
                if ($leeftijd >= 18 && $leeftijd < 51) {
                    return $basisbedrag; // Geen korting
                }
                break;
            case 5: // Oudere
                if ($leeftijd >= 51) {
                    return $basisbedrag * 0.55; // 45% korting
                }
                break;
            default:
                return $basisbedrag; // Geen korting standaard
        }

        return $basisbedrag; // Geen korting standaard
    }

    // Methode voor het berekenen van de totale contributie voor alle families
    public function calculateTotalContribution($familiesMetFamilieleden)
    {
        foreach ($familiesMetFamilieleden as &$familie) {
            $totalContribution = 0;
            foreach ($familie['familieleden'] as $familielid) {
                $leeftijd = $this->calculateAge($familielid['Geboortedatum']);
                $soortLid = $familielid['Soort_lid_id'];
                $contribution = $this->calculateContribution($leeftijd, $soortLid);
                $totalContribution += $contribution;
            }
            $familie['totalContribution'] = $totalContribution;
        }
        return $familiesMetFamilieleden;
    }
}

// Controller voor contributies
class ContributieController
{
    private $contributiesModel;
    private $familieledenModel;

    private $boekjaarModel;

    public function __construct()
    {
        $this->contributiesModel = new ContributiesModel();
        $this->familieledenModel = new Familieleden();
        $this->boekjaarModel = new Boekjaarmodel();
    }

    // Methode voor het bekijken van contributies
    public function viewContributions()
    {
        // Haal alle contributies op uit het model
        $contributions = $this->contributiesModel->getContributions();

        // Laad de view om de contributies weer te geven
        include './view/contributies.php';
    }

    // Methode voor het toevoegen van een contributie
    public function addContribution()
    {
        // Haal alle leden op
        $familielidData = $this->familieledenModel->getAllMemberData();

        if (isset($_POST['submit'])) {
            // Haal de ingevulde gegevens op
            $boekjaarID = $_POST['selectedYear'];
            $lidID = $_POST['lidID']; // Lidnummer ophalen uit het formulier

            // Roep de methode addContributionForFamilyMember aan vanuit het model
            $this->contributiesModel->addContributionForFamilyMember($boekjaarID, $lidID);

            // Na het toevoegen, doorverwijzen naar de pagina met de contributies
            header('Location: ./index.php?action=contributions');
            exit();
        }

        // Genereer een lijst met boekjaren voor het dropdownmenu
        $years = array();
        $currentYear = date("Y");
        for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
            $years[] = array('year_id' => $i);
        }

        // Laad de view voor het toevoegen van contributies met de leden en jaren
        include './view/add_contributies.php';
    }

    // Methode voor het verwijderen van een contributie
    public function deleteContribution()
    {
        // Haal alle contributies op
        $contributions = $this->contributiesModel->getContributions();

        if (isset($_POST['submit'])) {
            $selectedContributionId = $_POST['selectedContributionId'];

            // Roep de methode deleteContributionById aan vanuit het model
            $this->contributiesModel->deleteContributionById($selectedContributionId);

            // Na het verwijderen, doorverwijzen naar de pagina met de contributies
            header('Location: ./index.php?action=contributions');
            exit();
        }

        // Laad de view voor het verwijderen van contributies met de dropdown
        include './view/delete_contributies.php';
    }

    // Methode voor het bewerken van contributies
    public function editContributions()
    {
        // Initializeer errorMessage als lege string
        $errorMessage = '';
        
        if (isset($_POST['submit'])) {
            // Haal de geselecteerde contributie ID, lidnummer en boekjaar op uit het formulier
            $selectedContributionId = $_POST['selectedContributionId'];
            $lidnummer = $_POST['lidnummer'];
            $boekjaarId = $_POST['boekjaarId'];
        
            // Voer de bewerking van de contributie uit via de modelmethode
            $success = $this->contributiesModel->editContribution($lidnummer, $boekjaarId, $selectedContributionId);
        
            if ($success) {
                // Als de bewerking succesvol is uitgevoerd, doorverwijzen naar de pagina met de contributies
                header("Location: ./index.php?action=contributions");
                exit();
            } else {
                // Als er een fout is opgetreden, geef een foutmelding weer
                $errorMessage = "Deze combinatie van lidnummer en boekjaar bestaat al.";
            }
        }
        
        // Haal alle contributies op
        $contributions = $this->contributiesModel->getContributions();
        
        // Haal alle lidnummers op
        $lidnummers = $this->contributiesModel->getAllMemberNumbers();
        
        // Haal alle boekjaren op
        $boekjaren = $this->contributiesModel->getAllBoekjaren();
        
        // Laad de view voor het bewerken van contributies
        include './view/edit_contributies.php';
    }
    

}




class GroupController
{
    private $groupsModel;

    public function __construct()
    {
        $this->groupsModel = new GroepenModel(); // Instantie van het GroepenModel in de constructor
    }

    // Methode om de groepen weer te geven
    public function viewGroups()
    {
        // Haal alle groepen op uit het model
        $groups = $this->groupsModel->getGroups();

        // Laad de view om de groepen weer te geven
        include './view/groepen.php';
    }

    public function addGroup()
    {
        // Controleer of er een formulier is verzonden
        if (isset($_POST['submit'])) {
            // Haal de ingevulde gegevens op uit het formulier
            $soortnaam = $_POST['soortnaam'];
            $omschrijving = $_POST['omschrijving'];
            $korting = $_POST['korting'];
            if ($this->groupsModel->addGroup($soortnaam, $omschrijving, $korting)) {
                header('Location: ./index.php?action=viewGroups');
                exit();
            } else {
                // Als er een fout optreedt bij het toevoegen van de groep, toon een foutmelding
                echo "Er is een fout opgetreden bij het toevoegen van de groep.";
            }
        } else {
            // Als er geen formulier is verzonden, laad dan het formulier om een groep toe te voegen
            include './view/add_groep.php';
        }
    }

    public function editGroup()
    {
        if (isset($_POST['submit'])) {
            $groupId = $_POST['groupId'];
            $newName = $_POST['soortnaam'];
            $newDescription = $_POST['omschrijving'];
            $newDiscount = $_POST['korting'];

            if ($this->groupsModel->editGroup($groupId, $newName, $newDescription, $newDiscount)) {
                // Als de bewerking succesvol is, doorverwijzen naar de pagina met de groepen
                header('Location: ./index.php?action=viewGroups');
                exit();
            } else {
                echo "Er is een fout opgetreden bij het aanpassen van de groep.";
            }
        }

        // Haal alle groepen op
        $groups = $this->groupsModel->getGroups();

        // Laad de view voor het bewerken van een groep
        include './view/edit_groep.php';
    }

    // Methode om een groep te verwijderen
    public function deleteGroup()
    {
        if (isset($_POST['submit'])) {
            $groupId = $_POST['groupId'];

            // Roep de methode aan in het GroepenModel om de groep te verwijderen
            if ($this->groupsModel->deleteGroup($groupId)) {
                // Als het verwijderen succesvol is, doorverwijzen naar de pagina met de groepen
                header('Location: ./index.php?action=viewGroups');
                exit(); // Zorg ervoor dat het script hier stopt na de doorverwijzing
            } else {
                // Als er een fout optreedt bij het verwijderen van de groep, toon een foutmelding
                echo "Er is een fout opgetreden bij het verwijderen van de groep.";
            }
        } else {
            // Haal alle groepen op uit het model als het formulier niet is verzonden
            $groups = $this->groupsModel->getGroups();

            // Laad de view om de groepen te verwijderen
            include './view/delete_groep.php';
        }
    }



}


class BoekjaarController
{
    private $boekjaarModel;

    public function __construct()
    {
        $this->boekjaarModel = new Boekjaarmodel();
        $action = isset($_GET['action']) ? $_GET['action'] : '';
    }

    public function showBoekjaarPage()
    {
        $boekjaren = $this->boekjaarModel->getBoekjaren();
        include_once './view/boekjaar.php';
    }

    public function addBoekjaar()
    {
        $errorMessage = '';

        if (isset($_POST['submit'])) {
            // Hier controleren we of het formulier is ingediend
            $jaar = $_POST['jaar'];

            // Controleer of het jaar al bestaat
            if ($this->boekjaarModel->boekjaarExists($jaar)) {
                $errorMessage = "Dit boekjaar bestaat al! Voeg een ander jaar toe.";
            } else {
                // Voeg het boekjaar toe
                $this->boekjaarModel->addBoekjaar($jaar);
                header("Location: index.php?action=yearoverview"); // Stuur de gebruiker terug naar de hoofdpagina
                exit();
            }
        }

        // Laad de pagina inclusief het formulier
        include_once './view/add_boekjaar.php';
    }

    public function editBoekjaar()
    {
        $error = '';

        if (isset($_POST['submit'])) {
            $boekjaar_id = $_POST['jaar'];
            $nieuw_jaar = $_POST['nieuw_jaar'];

            // Controleer of het nieuwe jaar al bestaat
            if ($this->boekjaarModel->boekjaarExists($nieuw_jaar)) {
                // Als het nieuwe jaar al bestaat, stel de foutmelding in
                $error = "Het jaar dat je wil toevoegen heeft al een ID.";
            } else {
                // Als het nieuwe jaar niet bestaat, voer de update uit
                $this->boekjaarModel->updateBoekjaar($boekjaar_id, $nieuw_jaar);
                header("Location: index.php?action=yearoverview");
                exit();
            }
        }

        $boekjaren = $this->boekjaarModel->getBoekjaren();
        include_once './view/edit_boekjaar.php';

    }

    public function deleteBoekjaar()
    {
        if (isset($_POST['submit'])) {
            $boekjaar_id = $_POST['jaar'];

            // Roep de methode aan om het boekjaar te verwijderen
            $this->boekjaarModel->deleteBoekjaar($boekjaar_id);

            // Stuur de gebruiker door naar de jaaroverzichtspagina na het verwijderen
            header("Location: index.php?action=yearoverview");
            exit();
        }

        // Haal de lijst met boekjaren op om weer te geven in de dropdown
        $boekjaren = $this->boekjaarModel->getBoekjaren();

        // Laad de weergave van de pagina
        include_once './view/delete_boekjaar.php';
    }



}





?>