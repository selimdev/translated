<?php

$dataSet = [
       ['weight'=>'91','age'=>'29','sex'=>'M','state'=>'New Jersey','height'=>'180','cholesterol'=>null,'painType'=>null,'defectType'=>null,'diagnosis'=>null],
       ['weight'=>'83','age'=>'38','sex'=>'M','state'=>'Ohio','height'=>'174','cholesterol'=>null,'painType'=>'2','defectType'=>null,'diagnosis'=>'present'],
       ['weight'=>'91','age'=>'29','sex'=>'M','state'=>'New Jersey','height'=>'180','cholesterol'=>'331','painType'=>null,'defectType'=>'normal','diagnosis'=>null],
       ['weight'=>'58','age'=>'25','sex'=>'F','state'=>'Washington','height'=>'160','cholesterol'=>null,'painType'=>null,'defectType'=>null,'diagnosis'=>null],
       ['weight'=>'91','age'=>'29','sex'=>'M','state'=>'New Jersey','height'=>'180','cholesterol'=>null,'painType'=>'2','defectType'=>null,'diagnosis'=>'absent'],
       ['weight'=>'83','age'=>'38','sex'=>'M','state'=>'Ohio','height'=>'174','cholesterol'=>'340','painType'=>'2','defectType'=>'normal','diagnosis'=>'present'],
       ['weight'=>'58','age'=>'25','sex'=>'F','state'=>'Washington','height'=>'160','cholesterol'=>null,'painType'=>null,'defectType'=>null,'diagnosis'=>'absent'],
];


// inizio definendo un po' di funzioni con compiti specifici, per semplificare le cose


/**
 * Restituisce la distanza tra due oggetti (numero di campi aventi valori differenti)
 * @param array $object1
 * @param array $object2
 * @return int
 */
function calculateDistance($object1, $object2)
{
    $distance = 0;

    foreach ($object1 as $key => $value) {
        // se il valore non e' uguale aumenta la distanza
        if ($object1[$key] !== $object2[$key]) {
            $distance++;
        }
    }

    return $distance;
}

/**
 * Trova gli index delle righe nel dataSet con distanza piu' breve dall'oggetto con index passato come parametro
 * @param int $index
 * @param array $dataSet
 * @return array
 */
function findShortestDistanceIndexes($index, $dataSet)
{
    $shortestDistance = null;
    $shortestDistanceIndexes = [];
    $currentObject = $dataSet[$index];

    foreach ($dataSet as $key => $object) {
        // salta si tratta dello stesso oggetto
        if ($index === $key) {
            continue;
        }

        $distance = calculateDistance($currentObject, $object);

        // ignorare righe a distanza superiore a 5
        if ($distance > 5) {
            continue;
        }

        if ($shortestDistance === null || $shortestDistance >= $distance) {
            $shortestDistance = $distance;
            $shortestDistanceIndexes[] = $key;
        }
    }

    return $shortestDistanceIndexes;
}

/**
 * Restituisce true se l'oggetto passato come parametro ha campi con valori mancanti (null)
 * @param array $object
 * @return bool
 */
function hasNullValues($object)
{
    foreach ($object as $value) {
        if ($value === null) {
            return true;
        }
    }
    return false;
}

/**
 * Riempie i campi con valore null dell'object1 con il valore dello stesso campo dell'object2
 * @param array $object1
 * @param array $object2
 */
function fillNullValues(&$object1, $object2)
{
    foreach ($object1 as $key => &$value) {
        if ($value === null) {
            $value = $object2[$key];
        }
    }
}


// ed ecco l'algoritmo finale!

foreach ($dataSet as $key => &$object) {
    // se l'oggetto non ha campi con valori mancanti possiamo saltarlo
    if (!hasNullValues($object)) {
        continue;
    }

    // devo trovare gli oggetti nel dataSet che abbiano la minore distanza da quello attuale
    // a quanto pare possono essere piu' di uno...
    $shortestDistanceIndexes = findShortestDistanceIndexes($key, $dataSet);

    // nel caso in cui non sia stato possibile trovarli, si va avanti
    if (empty($shortestDistanceIndexes)) {
        continue;
    }

    // Riempio i valori dei campi nulli copiandoli da quelli degli oggetti simili.
    // passa all'oggetto successivo solamente se non sono stati riempiti tutti i campi nulli
    foreach ($shortestDistanceIndexes as $index) {

        if (hasNullValues($object)) {
            $shortestDistanceObject = $dataSet[$index];
            fillNullValues($object, $shortestDistanceObject);
        }
    }

}

// di seguito una stampa a video dell'expected dataset
?>

<html>
    <head>

        <style>
            table {
                border: 1px solid #ccc;
            }
            th, td {
                border: 1px solid #ccc;
            }
        </style>

    </head>

    <body>
        <table>
            <thead>
                <tr>
                    <th>weight</th>
                    <th>age</th>
                    <th>sex</th>
                    <th>state</th>
                    <th>height</th>
                    <th>cholesterol</th>
                    <th>painType</th>
                    <th>defectType</th>
                    <th>diagnosis</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataSet as $row) { ?>
                <tr>
                    <?php foreach ($row as $value) { ?>
                        <td><?=$value?></td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    </body>
</html>
