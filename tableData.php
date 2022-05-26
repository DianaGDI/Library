<?php
//данные для таблицы поиска читателей
$dataReaders = mysqli_query($db, "select * from reader where reader_name like '$find_reader_name%' order by reader_name"); //запрос с поиском введенного значения
if (mysqli_num_rows($dataReaders) > 0) {
    $fields_dr = mysqli_fetch_fields($dataReaders); // массив объектов с полями таблицы
    for ($i = 0; $i < (count($fields_dr)); $i++) {
        $fields_dr1 = (array) $fields_dr[$i]; // преобразование в массив
        $fieldsDataReader[] = $fields_dr1['name']; //массив с полями таблицы
    }
    while ($row = mysqli_fetch_array($dataReaders)) {
        $data_readers[] = $row; // массив данных читателей
    }
} else if (mysqli_num_rows($dataReaders) == 0) {
    $info_find_reader = "Ничего не найдено";
}

// назначение выбранного фильтра по статусу из сесиии
if (isset($_SESSION['stat1'])) {
    $stat1 = $_SESSION['stat1'];
} else {
    $stat1 = null;
}
// данные для таблицы сдачи
$dataBooksReader = mysqli_query($db, "
    select book_id, book_name, give_out_id, give_date, take_date, stat, return_date from reader 
 join give_out on reader_id=give_out.reader_reader_id
 join book on book_id=give_out.book_book_id
 where reader_id='$reader_choice_id' $stat1 order by give_out_id
    "); //запрос на вывод данных выбранного читателя и выбранного фильтра
if (mysqli_num_rows($dataBooksReader) > 0) {

    $fields_br = mysqli_fetch_fields($dataBooksReader);// массив объектов с полями таблицы
    for ($i = 0; $i < (count($fields_br)); $i++) { 
        $fields_br1 = (array) $fields_br[$i];// преобразование в массив
        $fieldsBooksReader[] = $fields_br1['name'];//массив с полями таблицы
    }

    while ($row = mysqli_fetch_array($dataBooksReader)) {
        $data_books_reader[] = $row; // массив данных сдачи
        $data_stat_text[] = $row[5];// массив данных статуса сдачи
    }
}
// данные для таблицы выдачи книг
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
        $fields_fb = mysqli_fetch_fields($ResFindBooks);// массив объектов с полями таблицы
        for ($i = 0; $i < (count($fields_fb)); $i++) {
            $fields_fb1 = (array) $fields_fb[$i];// преобразование в массив
            $fieldsFindBooks[] = $fields_fb1['name'];//массив с полями таблицы
        }

        while ($row = mysqli_fetch_array($ResFindBooks)) {
            $data_find_books[] = $row;//массив с найденными книгами 
        }

        for ($i = 0; $i < (count($data_find_books)); $i++) {

            $all_books_find_id[] = $data_find_books[$i][0];//массив id найденных книг 
            $resFindBookAuthors = mysqli_query($db, "
select author_name from book
 join book_author on book_id=book_author.book_book_id
 join author on author_id=book_author.author_author_id
 where book_id='$all_books_find_id[$i]'
    "); //запрос на вывод всех авторов книги
            while ($row1 = mysqli_fetch_all($resFindBookAuthors)) {
                $data_find_books_aut[] = $row1; //массив всех авторов книги
            }
            for ($c = 0; $c < (count($data_find_books_aut[$i])); $c++) {
                $data_find_books_aut1[$i][$c] = $data_find_books_aut[$i][$c][0]; //добавление авторов в новый массив 
            }
        }

        for ($k = 0; $k < (count($data_find_books_aut1)); $k++) {
            $data_find_books_aut2[] = implode(', ', $data_find_books_aut1[$k]); //объединение элементов массива в строку
        }

        for ($i = 0; $i < (count($data_find_books)); $i++) {


            $resFindBookGenres = mysqli_query($db, "
select genre_name from book
 join book_genre on book_id=book_genre.book_book_id
 join genre on genre_id=book_genre.genre_genre_id
 where book_id='$all_books_find_id[$i]'
    "); //запрос на вывод всех жанров книги

            while ($row = mysqli_fetch_all($resFindBookGenres)) {
                $data_find_books_gen[] = $row; //массив всех жанров книги
            }
            for ($c = 0; $c < (count($data_find_books_gen[$i])); $c++) {
                $data_find_books_gen1[$i][$c] = $data_find_books_gen[$i][$c][0];//добавление жанров в новый массив 
            }
        }
        for ($k = 0; $k < (count($data_find_books_gen1)); $k++) {
            $data_find_books_gen2[] = implode(', ', $data_find_books_gen1[$k]);//объединение элементов массива в строку
        }
    } else if (mysqli_num_rows($ResFindBooks) == 0) {// если книги не найдены
        $info_find = "Ничего не найдено";
    }
}

// данные для проверки сдачи
if (isset($all_books_find_id)) { 
    for ($t = 0; $t < (count($all_books_find_id)); $t++) {
        $infoBooks = mysqli_query($db, "
    select book_id, book_name, stat from reader 
 join give_out on reader_id=give_out.reader_reader_id
 join book on book_id=give_out.book_book_id
 where reader_id='$reader_choice_id' and book_id='$all_books_find_id[$t]'
    ");  //запрос на вывод всех книг читателя

        if (mysqli_num_rows($infoBooks) > 0) {

            while ($row = mysqli_fetch_array($infoBooks)) {

                $data_info_books[] = $row; //массив всех книг читателя
            }
        }
    }
}

if (isset($data_info_books)) {
    for ($m = 0; $m < (count($data_info_books)); $m++) {
        if ($data_info_books[$m][2] == "Не сдана") { //если книга не сдана то ее id добавляется в массив
            $info_books_id[] = $data_info_books[$m]['book_id'];
        }
    }
}

// данные для таблицы поиска книг
if ($find_book_author1 != "") { // если введен автор, то в запрос добавляется поиск по автору
    $find_book_author22 = "and author_name like '$find_book_author1%'";
    $join1 = "join book_author on book_id=book_author.book_book_id join author on author_id=author_author_id";
}
if ($find_book_genre1 != "") {// если введен жанр, то в запрос добавляется поиск по жанру
    $find_book_genre22 = "and genre_name like '$find_book_genre1%'";
    $join22 = "join book_genre on book_id=book_genre.book_book_id join genre on genre_id=genre_genre_id";
}

$ResFindBooks1 = mysqli_query($db, "
 select book_id, book_name, publisher_name, publisher_city, book_year, book_pages, book_copies from book 
 join publisher on publisher_id=publisher_publisher_id
 $join1 $join22 where book_name like '$find_book_name1%' $find_book_author22 $find_book_genre22 order by book_name"); //запрос с поиском введенных значений

if ($ResFindBooks1 != false) {
    if (mysqli_num_rows($ResFindBooks1) > 0) {
        $fields_fb11 = mysqli_fetch_fields($ResFindBooks1);// массив объектов с полями таблицы
        for ($i = 0; $i < (count($fields_fb11)); $i++) {
            $fields_fb111 = (array) $fields_fb11[$i];// преобразование в массив
            $fieldsFindBooks1[] = $fields_fb111['name'];//массив с полями таблицы
        }

        while ($row = mysqli_fetch_array($ResFindBooks1)) {
            $data_find_books2[] = $row;//массив с найденными книгами 
        }

        for ($i = 0; $i < (count($data_find_books2)); $i++) {

            $all_books_find_id2[] = $data_find_books2[$i][0];//массив id найденных книг 
            $resFindBookAuthors1 = mysqli_query($db, "
select author_name from book
 join book_author on book_id=book_author.book_book_id
 join author on author_id=book_author.author_author_id
 where book_id='$all_books_find_id2[$i]'
    ");//запрос на вывод всех авторов книги
            while ($row1 = mysqli_fetch_all($resFindBookAuthors1)) {
                $data_find_books_aut11[] = $row1;//массив всех авторов книги
            }
            for ($c = 0; $c < (count($data_find_books_aut11[$i])); $c++) {
                $data_find_books_aut111[$i][$c] = $data_find_books_aut11[$i][$c][0];//добавление авторов в новый массив 
            }
        }

        for ($k = 0; $k < (count($data_find_books_aut111)); $k++) {
            $data_find_books_aut22[] = implode(', ', $data_find_books_aut111[$k]);//объединение элементов массива в строку
        }

        for ($i = 0; $i < (count($data_find_books2)); $i++) {

            $resFindBookGenres1 = mysqli_query($db, "
select genre_name from book
 join book_genre on book_id=book_genre.book_book_id
 join genre on genre_id=genre_genre_id
 where book_id='$all_books_find_id2[$i]'
    ");//запрос на вывод всех жанров книги

            while ($row = mysqli_fetch_all($resFindBookGenres1)) {
                $data_find_books_gen11[] = $row;//массив всех жанров книги
            }
            for ($c = 0; $c < (count($data_find_books_gen11[$i])); $c++) {
                $data_find_books_gen111[$i][$c] = $data_find_books_gen11[$i][$c][0];//добавление жанров в новый массив 
            }
        }
        for ($k = 0; $k < (count($data_find_books_gen111)); $k++) {
            $data_find_books_gen22[] = implode(', ', $data_find_books_gen111[$k]);//объединение элементов массива в строку
        }
    } else if (mysqli_num_rows($ResFindBooks1) == 0) {// если книги не найдены
        $info_find1 = "Ничего не найдено";
    }
}