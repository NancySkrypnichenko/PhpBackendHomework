<?php
// Объявляем нужные константы
require_once '../config/constants.php';


// Подключаемся к базе данных
function connectDB() {
    $db_connect= null;
    try{
    $db_connect = new PDO('mysql:host=localhost;dbname=library_base;charset=utf8', DB_USER, DB_PASSWORD);
    $db_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo json_encode(array("error" => $e->getMessage()));
    }
    return $db_connect;
}


// Получаем список файлов для миграций
function getMigrationFiles($db_connect) {
    // Находим папку с миграциями
    $sqlFolder = str_replace('\\', '/', realpath(dirname(__FILE__)) . '/');
    // Получаем список всех sql-файлов
    $allFiles = glob($sqlFolder . '*.sql');

    print_r($allFiles);

    // Проверяем, есть ли таблица versions
    // Так как versions создается первой, то это равносильно тому, что база не пустая
    $query = sprintf('show tables from `%s` like "%s"', DB_NAME, DB_TABLE_VERSIONS);
    $statement = $db_connect->prepare($query);
    $statement->execute();

    //Fetch the rows from our statement
    $tables = $statement->fetchAll(PDO::FETCH_NUM);

    // Если первая миграция, возвращаются все файлы из папки sql
    if (!$tables) {
        return $allFiles;
    }

    // Ищем уже существующие миграции
    $versionsFiles = array();

    // Выбираем из таблицы versions все названия файлов
    $query = sprintf('select `name` from `%s`', DB_TABLE_VERSIONS);
    $statement = $db_connect->prepare($query);
    $statement->execute();
    $tables = $statement->fetchAll(PDO::FETCH_NUM);

    // Загоняем названия в массив $versionsFiles
    // Не забываем добавлять полный путь к файлу

    for ($i=0; $i < count ($tables); $i++){
        array_push($versionsFiles, $sqlFolder . $tables[$i][0]);
    }

    // Возвращаем файлы, которых еще нет в таблице versions
    return array_diff($allFiles, $versionsFiles);
}

// Накатываем миграцию файла
function migrate($db_connect, $file) {
    // Формируем команду выполнения mysql-запроса из внешнего файла
    $command = sprintf('mysql -u%s -p%s -h %s -D %s < %s', DB_USER, DB_PASSWORD, DB_HOST, DB_NAME, $file);
    // Выполняем shell-скрипт
    shell_exec($command);

    // Вытаскиваем имя файла, отбросив путь
    $baseName = basename($file);
    // Формируем запрос для добавления миграции в таблицу versions
    $query = sprintf('insert into `%s` (`name`) values("%s")', DB_TABLE_VERSIONS, $baseName);
    // Выполняем запрос
    $statement = $db_connect->prepare($query);
    $statement->execute();
}


// Стартуем

// Подключаемся к базе
$db_connect = connectDB();

// Получаем список файлов для миграций за исключением тех, которые уже есть в таблице versions
$files = getMigrationFiles($db_connect);

// Проверяем, есть ли новые миграции
if (empty($files)) {
    echo 'Ваша база данных в актуальном состоянии.';
} else {
    echo 'Начинаем миграцию...<br><br>';

    // Накатываем миграцию для каждого файла
    foreach ($files as $file) {
        migrate($db_connect, $file);
        // Выводим название выполненного файла
        echo basename($file) . '<br>';
    }

    echo '<br>Миграция завершена.';
}