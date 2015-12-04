<?php

require ('vendor/autoload.php');
require ('config.php');
$client =
    new Redmine\Client(
        Config::address,
        Config::username,
        Config::password
    );

$params = array_merge($_POST, $_GET);

if (isset($params['date'])) {
    $currentDate = $params['date'];
} else {
    $currentDate = date('Y-m-d', time());
}

$totalTime = $client->time_entry->all(
    [
        'user_id' => Config::userId,
        'spent_on' => $currentDate
    ]
);

$entries = $totalTime['time_entries'];

$totalHours = 0;
$projects = [];
$issues = [];

foreach ($entries as $entry) {
    $totalHours += $entry['hours'];
    $projects[] = $entry['project']['name'];
    $issues[] = $entry['issue']['id'];
}

$projects = array_unique($projects);

echo "Total time logged for $currentDate : $totalHours hours<br>";
echo "In " . count($projects) . " projects<br>";

?>

<form method="get">
    <input type="text" value="<?php echo $currentDate ?>" name="date">
    <input type="submit" value="refresh">
</form>