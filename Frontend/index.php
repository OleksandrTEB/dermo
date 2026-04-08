<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "kalendarz2";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Błąd połączenia: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'])) {
    $date = $_POST['date'];
    $text = $_POST['value'];
    $time = $_POST['time'] ?? null;
    $type = $_POST['type'];

    if ($type === 'zadanie') {
        $stmt = $pdo->prepare("INSERT INTO term (data, text) VALUES (:date, :text)");
        $stmt->execute([
            'date' => $date,
            'text' => $text,
        ]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO term (data, time, text) VALUES (:date, :time, :text)");
        $stmt->execute([
            'date' => $date,
            'time' => $time,
            'text' => $text,
        ]);
    }

    header("Location: index.php?year=" . date('Y', strtotime($date)) . "&month=" . date('n', strtotime($date)));
    exit;
}



if (isset($_GET['year'])) {
    $year = (int)$_GET['year'];
} else {
    $year = (int)date('Y');
}

if(isset($_GET['month'])) {
    $month = (int)$_GET['month'];
} else {
    $month = (int)date('n');
}

$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
}

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}

$startDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
$endDate = date("Y-m-t", strtotime($startDate));

$stmt = $pdo->prepare("SELECT * FROM term WHERE data BETWEEN :start AND :end");
$stmt->execute([
    'start' => $startDate,
    'end' => $endDate,
    ]);
$allTerms = $stmt->fetchAll();
$termsByDay = [];
foreach ($allTerms as $t) {
    $d = (int)date('j', strtotime($t['data']));
    $termsByDay[$d][] = $t;
}


$monthsNames = ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"];
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$firstDayIndex = date('N', strtotime($startDate));
?>

<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Kalendarz</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="icon" href="./img/calendar.png">
</head>
<body>
<main>
    <header>
        <span class="title">Witamy w naszym systemie kalendarza</span>
    </header>

    <div class="content">
        <div class="base-interface">
            <div class="options-container">
                <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>" class="btn-nav">
                    <img src="./img/back.png" alt="Poprzedni">
                </a>

                <div class="information">
                    <div class="year">Rok: <?= $year ?></div>
                    <div class="mouth">Miesiąc: <?= $monthsNames[$month-1] ?></div>
                </div>

                <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>" class="btn-nav">
                    <img src="./img/next.png" alt="Następny">
                </a>
            </div>

            <div class="wrapper-days-container">
                <div class="week-days-container">
                    <div class="red">Poniedziałek</div>
                    <div class="red">Wtorek</div>
                    <div class="red">Środa</div>
                    <div class="red">Czwartek</div>
                    <div class="red">Piątek</div>
                    <div class="green">Sobota</div>
                    <div class="green">Niedziela</div>
                </div>

                <div class="days-container">
                    <?php
                    for ($i = 1; $i < $firstDayIndex; $i++): ?>
                        <div class="empty"></div>
                    <?php endfor; ?>

                    <?php for ($d = 1; $d <= $daysInMonth; $d++):
                        $isToday = ($d == date('j') && $month == date('n') && $year == date('Y'));
                        $hasTerms = isset($termsByDay[$d]);
                        ?>
                        <div class="day-container <?= $isToday ? 'to-day' : '' ?>"
                            data-date="<?= $year ?>-<?= str_pad($month, 2, '0', STR_PAD_LEFT) ?>-<?= str_pad($d, 2, '0', STR_PAD_LEFT) ?>"
                            data-terms='<?= json_encode($termsByDay[$d] ?? []) ?>'>
                            <span class="day"><?= $d ?></span>
                            <div class="msg-container">
                                <?php if ($hasTerms): ?>
                                    <div class="msg"></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-window" id="modal">
        <div class="container">
            <div class="back-button">
                <button type="button" data-role="exit">
                    <img src="./img/exit.png" alt="Wstecz">
                </button>
            </div>

            <form class="second-form" method="POST" id="taskForm">
                <input type="hidden" name="action" value="add_task">
                <input type="hidden" name="date" id="modalDateInput">

                <select class="sel" name="type" id="type">
                    <option class="op" value="zadanie">Zadanie</option>
                    <option class="op" value="spotkanie">Spotkanie</option>
                </select>

                <div class="czas">
                    <label for="time">Czas:</label>
                    <input type="time" name="time" id="time">
                </div>

                <div class="text">
                    <label for="value">Treść <span id="selectedDateLabel"></span>:</label>
                    <textarea name="value" id="value" cols="60" rows="4" required></textarea>
                </div>

                <div class="submit-task">
                    <button type="submit">Zapisz</button>
                </div>
            </form>

            <div class="tasks-info" id="tasksInfo">
                <div class="header">
                    <div class="title">Wydażenia:</div>
                    <div class="add-button">
                        <button type="button" data-role="add">
                            <img src="./img/plus.png" alt="Dodaj">
                        </button>
                    </div>
                </div>
                <div id="taskListContent" class="list-tasks">
                </div>
            </div>
        </div>
    </div>
</main>

<script src="./main.js"></script>
</body>
</html>