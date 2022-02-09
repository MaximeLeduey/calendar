<?php 

$months = [1=>"Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

// on defini la date par défaut

$defaultMonth = date('n');
$defaultYear = date('Y');



// on recupère les données des selects via l'url s'il existe, sinon, on prend la date d'aujourd'hui

$monthNbr;
$currentYear;

extract($_GET);

if (isset($month)) {
    $monthNbr = $_GET['month'];
    $currentYear = $_GET['year'];
}
elseif (isset($month) && !isset($year)) {
    $monthNbr = $_GET['month'];
    $currentYear = $defaultYear;
}
elseif (!isset($month) && isset($year)) {
    $monthNbr = $defaultMonth;
    $currentYear = $_GET['year'];
}
else {
    $monthNbr = $defaultMonth;
    $currentYear = $defaultYear;
}



// mois précédent

$lastMonth = "$currentYear-$monthNbr";
$movingMonth = date('n', strtotime("$lastMonth -1 months"));
$movingYear = date('Y', strtotime("$lastMonth -1 months"));
$daysNumberLastMonth = cal_days_in_month(CAL_GREGORIAN, $movingMonth, $movingYear);


// mois suivant

$nextMonth = date('n', strtotime("$lastMonth +1 months"));
$nextYear = date('Y', strtotime("$lastMonth +1 months"));


// mois actuel 

$aDate = new DateTime($currentYear.'-'.$monthNbr);
$start = $aDate->format('N');
$daysNumber = cal_days_in_month(CAL_GREGORIAN, $monthNbr, $currentYear);
$endDate = new DateTime($currentYear.'-'.$monthNbr.'-'.$daysNumber);
$end = $endDate->format('N');
$iterationNbr = ($daysNumber + $start - 1) + ( 7 - $end);

$prevUrl = "calendar.php/?month=$movingMonth&year=$movingYear";
$nextUrl = "calendar.php/?month=$nextMonth&year=$nextYear";


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- on fait un reset css -->
    <link rel="stylesheet" href="assets/css/reset.css">
    <!-- on lie le css -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Calendrier</title>
</head>
<body>
    <a href=<?php echo $prevUrl ?>><img class="arrow-left" src="https://img.icons8.com/external-dreamstale-lineal-dreamstale/32/000000/external-right-arrow-arrows-dreamstale-lineal-dreamstale-15.png"/></a>
    <h2><?php 
        
        echo ($months[$monthNbr]." ".$currentYear);

        

    ?></h2>
    <a href=<?php echo $nextUrl ?>><img class="arrow-right" src="https://img.icons8.com/external-dreamstale-lineal-dreamstale/32/000000/external-right-arrow-arrows-dreamstale-lineal-dreamstale-15.png"/></a>
    <form action="" method="GET">
        <select name="month" id="month">
            <?php 

             // boucle pour créer les select pour les mois

                foreach ($months as $counter=>$month) {
                    if ( $counter == $monthNbr) {
                        echo "<option value=\"$counter\" selected>$month</option>";
                    }
                    else {
                        echo "<option value=\"$counter\">$month</option>";
                    }
                }
            
            ?>
        </select>
        <select name="year" id="year">
            <?php 

            // boucle pour créer les select pour les années

                for ($i = 1970; $i< 2031; $i++) {
                    if ($i == $currentYear) {
                        echo "<option value=\"$i\" selected>$i</option>";
                    }
                    else {
                        echo "<option value=\"$i\">$i</option>";
                    }
                }
            ?>
        </select>
        <button type="submit">Valider</button>
    </form>
    <table>
        <thead>
            <tr>
                <?php 

                    // boucle pour créer les abreviations des jours

                    $daysText = ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"];
                    foreach($daysText as $day) {
                        echo "<th>$day</th>";
                    }
                
                ?>
            </tr>
        </thead>
        <tbody>

            <?php 

                    // boucle qui utilise les variables pour le mois actuel et le mois précédent
                    $rowOpen = "<tr>";
                    $rowClose = "</tr>";
                    echo $rowOpen; 
                    $p = $daysNumberLastMonth - $start + 2;
                    for ( $i = 1; $i <= $iterationNbr; $i++) {
                        if ($i < $start) {
                            echo "<td class='hidden'>$p</td>";
                            $p++;
                        }
                        if ($i == $start) {
                            $d = 1;
                        }
                        if ( $i >= $start ) {
                            echo "<td class=\"visible\">$d</td>";
                            $d++;
                            if ( $d > $daysNumber ) {
                                $d = 1;
                            }
                        } 
                        if ( $i%7 == 0 ) {
                            echo $rowClose;
                            echo $rowOpen;
                        }   
                    }       
                    
            ?>
        </tbody>
    </table>
</body>
</html>