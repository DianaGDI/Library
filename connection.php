<?php

session_start();
//соединение с БД
$db = mysqli_connect('localhost', 'root', 'hjk10mBi17', 'library_db1'); 
mysqli_set_charset($db, 'utf8');

$info_reader = null;

// кнопка добавления читателя
if (isset($_POST['new_reader_name_button'])) { 
    $new_reader_name = $_POST['new_reader_name']; //получение данных введенных пользователем
    $new_reader_email = $_POST['new_reader_email'];
    $new_reader_phone = $_POST['new_reader_phone'];
    if ($new_reader_name != "" && $new_reader_email != "" && $new_reader_phone != "") { // проверка на заполнение всех полей
        $new_reader_name = str_replace("'", "\'", $new_reader_name); // замена символа для коррекного выполнения запроса SQL
        $resultNewR = mysqli_query($db, "INSERT INTO reader (reader_name, reader_email, reader_phone) VALUES ('$new_reader_name', '$new_reader_email', '$new_reader_phone')"); //ввод новых данных в бд
        $info_reader = "Читатель успешно добавлен";
        $_POST["new_reader_name"] = ""; //очистка полей ввода
        $_POST["new_reader_email"] = "";
        $_POST["new_reader_phone"] = "";
    } else if ($new_reader_name == "" || $new_reader_email == "" || $new_reader_phone == "") { // если введены не все данные
        $info_reader = "Введите все данные";
    }
}


// удаление читателей
$delet = null;
$info_del_reader = null;
if (isset($_POST['deleteButton'])) { 
    if (isset($_POST['delet'])) { //выбранные строки вносятся в массив
        $delet = $_POST['delet'];
    } else {
        $info_del_reader = 'Выберите читателей для удаления';
    }
}
if ($delet != null) { // если есть хотя бы одна строка для удаления
    for ($i = 0; $i < (count($delet)); $i++) {
        $checkStat = mysqli_query($db, "SELECT * FROM give_out where reader_reader_id='$delet[$i]' and stat='Не сдана'"); //определение сдачи книг читателем через запрос
        if (mysqli_num_rows($checkStat) > 0) { // если есть незданные книги
            $info_del_reader .= '<p>Удаление не выполнено: Читатель \'' . $delet[$i] . '\' сдал не все книги</p>';
        } else { // если читатель сдал все книги выполняется его удаление
            $deleteReaders = mysqli_query($db, "DELETE FROM give_out WHERE reader_reader_id='$delet[$i]'");
            $deleteReaders1 = mysqli_query($db, "DELETE FROM reader WHERE reader_id='$delet[$i]'");
            $info_del_reader .= '<p>Читатель \'' . $delet[$i] . '\' успешно удален</p>';
            unset ($_SESSION['reader_choice_id']); //удаление переменных из сессии для установки по умолчанию
            unset ($_SESSION['reader_choice_name']);
            unset ($_SESSION['choice_reader_list']);
        }
    }
}

//все id и имена читателей
$allReaders = mysqli_query($db, "select reader_id, reader_name from reader order by reader_name"); //запрос на вывод данных в таблице читателей
if (mysqli_num_rows($allReaders) > 0) {

    while ($row = mysqli_fetch_array($allReaders)) { // сохранение данных в массивы
        $all_readers_id[] = $row[0]; // id читателей
        $all_readers_name[] = $row[1]; //имена читателей
    }
    $reader_choice_id = $all_readers_id[0]; //id читателя по умолчанию 
    $reader_choice_name = $all_readers_name[0];//имя читателя по умолчанию
}
else if (mysqli_num_rows($allReaders) == 0) {//если читателей нет
    $reader_choice_id = null;
    $reader_choice_name = "Читателей нет";
}

// значения по умолчанию
$find_book_author2 = null; 
$find_book_genre2 = null;
$find_book_author22 = null;
$find_book_genre22 = null;
$join = null;
$join2 = null;
$join1 = null;
$join22 = null;
$info_find = null;
$info_find1 = null;
$info_find_reader = null;
$info_del_book = null;
$info_aut = null;
$info_publ = null;
$info_genre = null;
$info_book = null;
$stat1 = null;
$count_authors = 1;
$count_genres = 1;
$find_book_name = "";
$find_book_author = "";
$find_book_genre = "";
$find_reader_name = "";
$find_book_name1 = "";
$find_book_author1 = "";
$find_book_genre1 = "";
$fullA = 0;
$fullG = 0;
$delet1 = null;
$choice_table_list = 0;


// выбранная таблица для поиска
if (isset($_POST['choice_table_list'])) {
    $choice_table_list = $_POST['choice_table_list'];
}

if (isset($_POST['choiceButton'])) { // если нажата кнопка выбора читателя
    $_SESSION['reader_choice_id'] = $all_readers_id[$_POST['choice_reader_list']]; //сохранение id читателя в сессии
    $_SESSION['reader_choice_name'] = $all_readers_name[$_POST['choice_reader_list']];  // сохранение имени читателя в сессии
    $_SESSION['choice_reader_list'] = $_POST['choice_reader_list']; //сохранение значения выпадающего списка в сессии
}

if (isset($_SESSION['reader_choice_id'])&&isset($_SESSION['reader_choice_name'])) { // если переменные есть в сессии
$reader_choice_id=$_SESSION['reader_choice_id']; //присвоение переменным значений из сессии
$reader_choice_name=$_SESSION['reader_choice_name'];
}

//данные для вывода таблицы сдачи
    if (isset($_SESSION['stat1'])) { //назначение фильтра по статусу из сессии
        $stat1 = $_SESSION['stat1'];
    } else {
        $stat1 = null;
    }
    $dataBooksReader = mysqli_query($db, "
    select book_id, book_name, give_out_id, give_date, take_date, stat, return_date from reader
 join give_out on reader_id=give_out.reader_reader_id
 join book on book_id=give_out.book_book_id
 where reader_id='$reader_choice_id' $stat1 order by give_out_id
    "); //запрос на вывод данных выбранного читателя и выбранного фильтра
    if (mysqli_num_rows($dataBooksReader) > 0) {

        while ($row = mysqli_fetch_array($dataBooksReader)) {
            $data_books_id[] = $row[0]; //id книги
            $data_stat_id[] = $row[2]; //id выдачи
        }
    }


//получение данных введенных пользователем для поиска читателя
if (isset($_POST['find_reader_name'])) { 
    $find_reader_name = $_POST['find_reader_name'];
}


// удаление книг
if (isset($_POST['deleteButton1'])) {
    if (isset($_POST['delet1'])) { //выбранные строки вносятся в массив
        $delet1 = $_POST['delet1'];
    } else {
        $info_del_book = 'Выберите книги для удаления';
    }
}
if ($delet1 != null) { // если есть хотя бы одна строка для удаления
    for ($i = 0; $i < (count($delet1)); $i++) {
        $checkStat2 = mysqli_query($db, "SELECT * FROM give_out where book_book_id='$delet1[$i]'"); //определение выдачи книги через запрос
        if (mysqli_num_rows($checkStat2) > 0) { //если книга была выдана
            $info_del_book .= '<p>Удаление не выполнено: Книга \'' . $delet1[$i] . '\' была выдана</p>';
        } else { //если книга еще никому не выдавалась выполняется ее удаление
            $deleteBooks1 = mysqli_query($db, "DELETE FROM book_author WHERE book_book_id='$delet1[$i]'");
            $deleteBooks2 = mysqli_query($db, "DELETE FROM book_genre WHERE book_book_id='$delet1[$i]'");
            $deleteBooks3 = mysqli_query($db, "DELETE FROM book WHERE book_id='$delet1[$i]'");
            $info_del_book .= '<p>Книга \'' . $delet1[$i] . '\' успешно удалена</p>';
            
        }
    }
}

//данные для поиска книг
if (isset($_POST['find_book_name1']) || isset($_POST['find_book_author1']) || isset($_POST['find_book_genre1'])) {
    $find_book_name1 = $_POST['find_book_name1']; //получение данных введенных пользователем
    $find_book_author1 = $_POST['find_book_author1'];
    $find_book_genre1 = $_POST['find_book_genre1'];
}

//данные для поиска книг для выдачи
if (isset($_POST['find_book_name']) || isset($_POST['find_book_author']) || isset($_POST['find_book_genre'])) {
    $find_book_name = $_POST['find_book_name']; //получение данных введенных пользователем
    $find_book_author = $_POST['find_book_author'];
    $find_book_genre = $_POST['find_book_genre'];

    if ($find_book_author != "") { // если введен автор, то в запрос добавляется поиск по автору
        $find_book_author2 = "and author_name like '$find_book_author%'";
        $join = "join book_author on book_id=book_author.book_book_id join author on author_id=author_author_id";
    }
    if ($find_book_genre != "") { // если введен жанр, то в запрос добавляется поиск по жанру
        $find_book_genre2 = "and genre_name like '$find_book_genre%'";
        $join2 = "join book_genre on book_id=book_genre.book_book_id join genre on genre_id=genre_genre_id";
    }
    $ResFindBooks = mysqli_query($db, "
 select book_id, book_name, publisher_name, publisher_city, book_year, book_pages, book_copies from book 
 join publisher on publisher_id=publisher_publisher_id
 $join $join2 where book_name like '$find_book_name%' $find_book_author2 $find_book_genre2 order by book_name"); //запрос с поиском введенных значений

    if ($ResFindBooks != false) {
        if (mysqli_num_rows($ResFindBooks) > 0) {


            while ($row = mysqli_fetch_array($ResFindBooks)) {
                $data_find_books1[] = $row; //массив с найденными книгами 
            }
            for ($i = 0; $i < (count($data_find_books1)); $i++) {


                $all_books_find_id1[] = $data_find_books1[$i][0]; //массив id найденных книг 
            }
        }
    }
}
// кнопка выдачи
if (isset($all_books_find_id1)) {

    for ($n = 0; $n < count($all_books_find_id1); $n++) {
        if (isset($_POST["give$n"])) { // если нажата кнопка Выдать
            //регистрация новой выдачи посредством ввода новых значений в таблицу выдачи
            $resultGive = mysqli_query($db, "INSERT INTO give_out (give_date, take_date, book_book_id, reader_reader_id) VALUES (current_date(), current_date()+interval 14 day, '$all_books_find_id1[$n]', '$reader_choice_id')");
            if ($resultGive != false) { //если первый запрос выполнен успешно то количество копий уменьшается на единицу
                $resultGive2 = mysqli_query($db, "UPDATE book SET book_copies = book_copies - 1 WHERE book_id = '$all_books_find_id1[$n]'");
            }
            
        }
    }
}

// кнопка сдачи
if (isset($data_stat_id)) {

    for ($g = 0; $g < count($data_stat_id); $g++) {
        if (isset($_POST["ret$g"])) { //если нажата кнопка Сдать
            //добавление даты возврата и изменение статуса выдачи на Сдана
            $resultRet = mysqli_query($db, "UPDATE give_out SET stat = 'Сдана', return_date = current_date() WHERE give_out_id = '$data_stat_id[$g]'");
            if ($resultRet != false) { //если первый запрос выполнен успешно то количество копий увеличивается на единицу
                $resultRet2 = mysqli_query($db, "UPDATE book SET book_copies = book_copies + 1 WHERE book_id = '$data_books_id[$g]'");
            }
        }
    }
}
// кнопка добавления автора
if (isset($_POST['new_author_name_button'])) { 
    $new_author_name = $_POST['new_author_name']; //получение данных введенных пользователем
    if ($new_author_name != "") { //если данные введены
        $new_author_name = str_replace("'", "\'", $new_author_name); // замена символа для коррекного выполнения запроса SQL
        $resultNewA = mysqli_query($db, "INSERT INTO author (author_name) VALUES ('$new_author_name')");
        
        $info_aut = "Автор успешно добавлен";
    }
}
//все id и имена авторов
$allAuthors = mysqli_query($db, "select author_id, author_name from author order by author_name"); //запрос на вывод данных авторов
if (mysqli_num_rows($allAuthors) > 0) {

    while ($row = mysqli_fetch_array($allAuthors)) { // сохранение данных в массивы
        $all_author_id[] = $row[0]; 
        $all_author_name[] = $row[1];
    }
}
//кнопка добавления издательства
if (isset($_POST['new_publ_name_button'])) { 
    $new_publ_name = $_POST['new_publ_name'];//получение данных введенных пользователем
    $new_publ_city = $_POST['new_publ_city'];
    if ($new_publ_name != "" && $new_publ_city != "") { //проверка на заполнение всех полей
        $new_publ_name = str_replace("'", "\'", $new_publ_name); // замена символов для коррекного выполнения запроса SQL
        $new_publ_name = str_replace("\\", "\\\\", $new_publ_name);
        $new_publ_city = str_replace("'", "\'", $new_publ_city);
        $resultNewP = mysqli_query($db, "INSERT INTO publisher (publisher_name, publisher_city) VALUES ('$new_publ_name', '$new_publ_city')"); //ввод новых значений
        
        $info_publ = "Издательство успешно добавлено";
    } else if ($new_publ_name == "" || $new_publ_city == "") {
        $info_publ = "Введите все данные";
    }
}
//все id и названия издательств
$allPubl = mysqli_query($db, "select publisher_id, publisher_name from publisher order by publisher_name");  //запрос на вывод данных издательств
if (mysqli_num_rows($allPubl) > 0) {

    while ($row = mysqli_fetch_array($allPubl)) { // сохранение данных в массивы
        $all_publ_id[] = $row[0];
        $all_publ_name[] = $row[1];
    }
}
//кнопка добавления жанра
if (isset($_POST['new_genre_name_button'])) { 
    $new_genre_name = $_POST['new_genre_name'];//получение данных введенных пользователем
    if ($new_genre_name != "") {
        $resultNewG = mysqli_query($db, "INSERT INTO genre (genre_name) VALUES ('$new_genre_name')"); //ввод новых значений
        
        $info_genre = "Жанр успешно добавлен";
    }
}
//все id и названия жанров
$allGenres = mysqli_query($db, "select genre_id, genre_name from genre order by genre_name"); 
if (mysqli_num_rows($allGenres) > 0) {

    while ($row = mysqli_fetch_array($allGenres)) {
        $all_genre_id[] = $row[0];
        $all_genre_name[] = $row[1];
    }
}

//количество авторов
if (isset($_POST["count_authorsButton"])) { //сохранение количества авторов в сессии
    $count_authors = $_POST['count_authors'];
    $_SESSION['count_authors'] = $count_authors;
}
//количество жанров
if (isset($_POST["count_genresButton"])) {  //сохранение количества жанров в сессии
    $count_genres = $_POST['count_genres'];
    $_SESSION['count_genres'] = $count_genres;
}
//кнопка добавления книги
if (isset($_POST["addBookButton"])) { 
    $new_book_name = $_POST['new_book_name']; //получение данных введенных пользователем
    $new_book_year = $_POST['new_book_year'];
    $new_book_pages = $_POST['new_book_pages'];
    $new_book_copies = $_POST['new_book_copies'];

    if (isset($_SESSION['count_authors'])) { // получение количества авторов и жанров из сесиии
        $count_authors = $_SESSION['count_authors'];
    }
    if (isset($_SESSION['count_genres'])) {
        $count_genres = $_SESSION['count_genres'];
    }


    if ($_POST["choice_publ_list"] != -1) { // проверка выбора издательства
        $choice_publ_list = $all_publ_id[$_POST["choice_publ_list"]]; 
    } else {
        $choice_publ_list = null;
    }

    for ($h = 1; $h <= $count_authors; $h++) {
        if ($_POST["choice_author_list$h"] != -1) { // проверка выбора автора
            ${"author_choice_id" . $h} = $all_author_id[$_POST["choice_author_list$h"]];
            $fullA++; //переменная для дальнейшегго сравнения количества авторов с количеством выбранных авторов
        } else {
            ${"author_choice_id" . $h} = null;
        }
    }
    for ($g = 1; $g <= $count_genres; $g++) {
        if ($_POST["choice_genre_list$g"] != -1) { // проверка выбора жанра
            ${"genre_choice_id" . $g} = $all_genre_id[$_POST["choice_genre_list$g"]];
            $fullG++; //переменная для дальнейшегго сравнения количества жанров с количеством выбранных жанров
        } else {
            ${"genre_choice_id" . $g} = null;
        }
    }
    if ($fullA == $count_authors && $fullG == $count_genres && $new_book_name != "" && $new_book_year != "" && $new_book_pages != "" && $new_book_copies != "" && $choice_publ_list != null) { // если все данные заполнены
        $new_book_name = str_replace("'", "\'", $new_book_name); // замена символов для коррекного выполнения запроса SQL
        $new_book_name = str_replace("\\", "\\\\", $new_book_name);
        $addBook = mysqli_query($db, "INSERT INTO book (book_name, book_year, book_pages, book_copies, publisher_publisher_id) VALUES ('$new_book_name', $new_book_year, '$new_book_pages', '$new_book_copies', '$choice_publ_list')"); //ввод новых значений
        $idNewBook = mysqli_insert_id($db); // получение id книги из последнего запроса
        



        for ($h = 1; $h <= $count_authors; $h++) { // добавление авторов для книги 
            $addAuthors = mysqli_query($db, "INSERT INTO book_author (book_book_id, author_author_id) VALUES ('$idNewBook', '${"author_choice_id" . $h}')");
            
        }


        for ($g = 1; $g <= $count_genres; $g++) { // добавление жанров для книги
            $addGenres = mysqli_query($db, "INSERT INTO book_genre (book_book_id, genre_genre_id) VALUES ('$idNewBook', '${"genre_choice_id" . $g}')");
            
        }
        //привоение значений по умолчанию
        $_SESSION['count_authors'] = 1; 
        $_SESSION['count_genres'] = 1;
        $_POST["count_authors"] = 1;
        $_POST["count_genres"] = 1;
        $_POST["choice_author_list1"] = null;
        $_POST["choice_genre_list1"] = null;
        $_POST["choice_publ_list"] = null;
        $_POST["new_book_name"] = "";
        $_POST["new_book_year"] = "";
        $_POST["new_book_pages"] = "";
        $_POST["new_book_copies"] = "";
        $info_book = "Книга успешно добавлена";
    } else if ($fullA != $count_authors || $fullG != $count_genres || $new_book_name == "" || $new_book_year == "" || $new_book_pages == "" || $new_book_copies == "" || $choice_publ_list != null) { // если введены не все данные
        $info_book = "Введите все данные";
    }
}

//фильтрация по статусу
if (isset($_POST["all"])) { //добавление в переменную stat1 части запроса для дальнейшего вывода таблицы в зависимости от нажатой кнопки
    $stat1 = null;
    $_SESSION['stat1'] = $stat1;
}
if (isset($_POST["ret"])) {
    $stat1 = "and stat='Сдана'";
    $_SESSION['stat1'] = $stat1;
}
if (isset($_POST["not_ret"])) {
    $stat1 = "and stat='Не сдана'";
    $_SESSION['stat1'] = $stat1;
}

