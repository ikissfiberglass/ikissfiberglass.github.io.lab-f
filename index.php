<?php
//phpinfo();

//echo "hello world";

require_once __DIR__ . '/lib/Encoder/EncoderInterface.php';
require_once __DIR__ . '/lib/Encoder/CsvEncoder.php';
require_once __DIR__ . '/lib/Encoder/JsonEncoder.php';
require_once __DIR__ . '/lib/Encoder/YamlEncoder.php';
require_once __DIR__ . '/lib/Serializer.php';

use Lib\Encoder\CsvEncoder;
use Lib\Encoder\JsonEncoder;
use Lib\Encoder\YamlEncoder;
use Lib\Serializer;

$serializer = new Serializer([
    new CsvEncoder(),
    new JsonEncoder(),
    new YamlEncoder()
]);

$inputData = $_COOKIE['inputData'] ?? '';
$inputFormat = $_COOKIE['inputFormat'] ?? 'csv';
$outputFormat = $_COOKIE['outputFormat'] ?? 'json';
$outputData = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = $_POST['inputData'] ?? '';
    $inputFormat = $_POST['inputFormat'] ?? 'csv';
    $outputFormat = $_POST['outputFormat'] ?? 'json';

    setcookie('inputData', $inputData);
    setcookie('inputFormat', $inputFormat);
    setcookie('outputFormat', $outputFormat);

    $outputData = $serializer->convert($inputData, $inputFormat, $outputFormat);
}

ob_start();
?>
<h1>Konwerter danych</h1>
<form method="POST">
    <label>Dane wejściowe:</label><br>
    <textarea name="inputData" rows="10" cols="60"><?= htmlspecialchars($inputData) ?></textarea><br><br>

    <label>Format wejściowy:</label>
    <select name="inputFormat">
        <?php foreach (['csv','ssv','tsv','json','yaml'] as $f): ?>
            <option value="<?= $f ?>" <?= $inputFormat === $f ? 'selected' : '' ?>><?= strtoupper($f) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Format wyjściowy:</label>
    <select name="outputFormat">
        <?php foreach (['csv','ssv','tsv','json','yaml'] as $f): ?>
            <option value="<?= $f ?>" <?= $outputFormat === $f ? 'selected' : '' ?>><?= strtoupper($f) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Konwertuj</button>
</form>
<h2>Wynik:</h2>
<pre><?= htmlspecialchars($outputData) ?></pre>
<?php
$content = ob_get_clean();
include __DIR__ . '/templates/layout.php';


?>
