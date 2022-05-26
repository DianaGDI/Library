<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Библиотека</title>
        <link rel="stylesheet" href="styles.css"/>
        <link rel="shortcut icon" href="logo3.svg"/> 
<!--        предотвращение повторной отправки формы при перезагрузке страницы-->
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </head>
    <body>
        <?php
        include 'connection.php';
        include 'tableData.php';
        ?>
        <div class="main_div">
            <!--логотип сайта-->
            <div class="logo">
                <p><img src='logo2.svg'></p>
            </div>
            <div class="logo">
                <h1>Библиотека</h1>
            </div>

            <form name="new" method="POST">
                <!--главные кнопки переключения-->
                <input name="vis" type="radio" id="dr_b" value="dr" <?php echo isset($_POST["vis"]) && $_POST["vis"] == 'dr' ? "checked" : "checked" ?>/>  
                <input name="vis" type="radio" id="f_b" value="f" <?php echo isset($_POST["vis"]) && $_POST["vis"] == 'f' ? "checked" : "" ?>/>
                <input name="vis" type="radio" id="abr_b" value="abr" <?php echo isset($_POST["vis"]) && $_POST["vis"] == 'abr' ? "checked" : "" ?>/>
                <input name="vis" type="radio" id="apg_b" value="apg" <?php echo isset($_POST["vis"]) && $_POST["vis"] == 'apg' ? "checked" : "" ?>/>    
                <input name="vis" type="radio" id="addb_b" value="addb" <?php echo isset($_POST["vis"]) && $_POST["vis"] == 'addb' ? "checked" : "" ?>/>

                <label for="dr_b" class="lab" id="dr_b1">Поиск</label>
                <label for="f_b" class="lab" id="f_b1">Выдача книг</label>
                <label for="abr_b" class="lab" id="abr_b1">Сдача книг</label>
                <label for="apg_b" class="lab" id="apg_b1">Добавить читателя</label>
                <label for="addb_b" class="lab" id="addb_b1">Добавить книгу</label>
                
<!-- Выбор читателя -->
                <div class="chReader">
                    <p>Выберите читателя</p>
                    <select name="choice_reader_list">
                        <?php foreach ($all_readers_name as $key => $value) { ?> <!--вывод всех читателей -->
                            <option value="<?php echo $key ?>" <?php echo isset($_SESSION['choice_reader_list']) && $_SESSION['choice_reader_list'] == $key ? "selected" : "" ?>><?php echo $value ?></option>;
                        <?php } ?>
                    </select>
                    <p><input type="submit" name="choiceButton" value="Выбрать"></p>
                    <p><?php print_r("Выбран читатель: ". $reader_choice_name) ?></p> <!--вывод имени выбранного читателя -->
                </div>
<!-- Сдача -->
                <div class="allBooksReader">
             <!-- кнопки фильтрации таблицы по статусу -->       
                    <p><input type="submit" name="all" value="Все книги">
                        <input type="submit" name="ret" value="Сданы">
                        <input type="submit" name="not_ret" value="Не сданы"></p>
 <!-- таблица для сдачи -->                    
                    <table class="t_stat">
                        <thead>
                            <tr>
                                <?php
                                if (isset($data_books_reader) == true) { // имена столбцов таблицы
                                    echo '<th>Код книги</th>'
                                    . '<th>Название</th>'
                                    . '<th class="small">Код выдачи</th>'
                                    . '<th>Дата выдачи</th>'
                                    . '<th>Дата сдачи</th>'
                                    . '<th>Статус</th>'
                                    . '<th>Дата возврата</th>';
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    $out_stat = "";
    for ($i = 0; $i < count($data_books_reader); $i++) {
        $out_stat .= "<tr>";
        for ($j = 0; $j <= count($fieldsBooksReader); $j++) {
            if ($j == count($fieldsBooksReader)) { //добавление кнопки Сдать в конец таблицы
                if ($data_books_reader[$i][5] == 'Сдана') { //определение сдачи книги, если сдана, то кнопка становится неактивной
                    $out_stat .= '<td class="tdBut"><input type="submit" name="ret' . $i . '" value="Сдать" disabled></td>';
                } else if ($data_books_reader[$i][5] == 'Не сдана') {
                    $out_stat .= '<td class="tdBut"><input type="submit" name="ret' . $i . '" value="Сдать"></td>';
                }
            }
            if ($j < count($fieldsBooksReader)) { //вывод данных из массива выдачи
                $out_stat .= '<td>' . $data_books_reader[$i][$j] . '</td>';
            }
        }
        $out_stat .= "</tr>";
    }
    echo $out_stat;
} else if (isset($data_books_reader) == false) { // если в массиве нет данных
    echo "Книги не найдены";
}
?>

                        </tbody>
                    </table>  


                </div>
<!-- Выдача -->
                <div class="findBooksForReader">
                    <!--поля ввода для поиска книг-->
                    <div class="fbInput">
                        <p>Введите название книги</p>
                        <p><input id="fbn" type="text" name="find_book_name" value="<?php
                            if (isset($_POST['find_book_name']))
                                echo $_POST['find_book_name'];
                            else
                                ""
    ?>"></p> </div>
                    <div class="fbInput">
                        <p>Введите фамилию автора</p>
                        <p><input id="fba" type="text" name="find_book_author" value="<?php
                        if (isset($_POST['find_book_author']))
                            echo $_POST['find_book_author'];
                        else
                            ""
    ?>"></p> </div>
                    <div class="fbInput">
                        <p>Введите жанр книги</p>
                        <p><input id="fbg" type="text" name="find_book_genre" value="<?php
                        if (isset($_POST['find_book_genre']))
                            echo $_POST['find_book_genre'];
                        else
                            ""
    ?>"></p></div>
                    <p><input type="submit" name="find_book_button" value="Найти"></p>
<!--таблица для вывода найденных книг-->
                    <table class="t_find">
                        <thead>
                            <tr>
                                <?php
                                $m = 0;
                                if (isset($data_find_books) == true) { // имена столбцов таблицы
                                    echo '<th>Код книги</th>'
                                    . '<th>Название</th>'
                                    . '<th>Издательство</th>'
                                    . '<th>Город</th>'
                                    . '<th class="small">Год издания</th>'
                                    . '<th class="small">Кол-во страниц</th>'
                                    . '<th class="small">Кол-во копий</th>'
                                    . '<th>Авторы</th>'
                                    . '<th>Жанры</th>';
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $out_find = "";
                                for ($i = 0; $i < count($data_find_books); $i++) {  
                                    $out_find .= "<tr>";
                                    for ($j = 0; $j <= count($fieldsFindBooks) + 2; $j++) {
                                        if ($j == count($fieldsFindBooks)) { // вывод авторов
                                            $out_find .= '<td>' . $data_find_books_aut2[$i] . '</td>';
                                        }
                                        if ($j == count($fieldsFindBooks) + 1) { //вывод жанров
                                            $out_find .= '<td>' . $data_find_books_gen2[$i] . '</td>';
                                        }
                                        if ($j == count($fieldsFindBooks) + 2) { //добавление кнопки Выдать
                                            if (isset($info_books_id)) { // если читатель брал книги в библиотеке
                                                if ($info_books_id[$m] != $data_find_books[$i][0] && $data_find_books[$i][6] != 0) { //если книга сдана и есть в наличии

                                                    $out_find .= '<td class="tdBut"><input type="submit" name="give' . $i . '" value="Выдать"></td>';
                                                } else if ($info_books_id[$m] != $data_find_books[$i][0] && $data_find_books[$i][6] == 0) { //если книга сдана и ее нет в наличии, кнопка становится неактивной

                                                    $out_find .= '<td class="tdBut"><input type="submit" name="give' . $i . '" value="Выдать" disabled></td>';
                                                } else if ($info_books_id[$m] == $data_find_books[$i][0] && $data_find_books[$i][6] != 0) { //если книга не сдана и есть в наличии, кнопка становится неактивной

                                                    $out_find .= '<td class="tdBut"><input type="submit" name="give' . $i . '" value="Выдать" disabled></td>';
                                                    if ($m == count($info_books_id) - 1) { // назначение последнего индекса, чтобы не выходить за пределы массива
                                                        $m = count($info_books_id) - 1;
                                                    } else { // увеличение индекса на единицу
                                                        $m++;
                                                    }
                                                } else if ($info_books_id[$m] == $data_find_books[$i][0] && $data_find_books[$i][6] == 0) { //если книга не сдана и ее нет в наличии, кнопка становится неактивной

                                                    $out_find .= '<td class="tdBut"><input type="submit" name="give' . $i . '" value="Выдать" disabled></td>';
                                                    if ($m == count($info_books_id) - 1) {
                                                        $m = count($info_books_id) - 1;
                                                    } else {
                                                        $m++;
                                                    }
                                                }
                                            } else {
                                                if ($data_find_books[$i][6] != 0 && mysqli_num_rows($allReaders) > 0) { // если книга есть в наличии и есть хотя бы один читатель

                                                    $out_find .= '<td class="tdBut"><input type="submit" name="give' . $i . '" value="Выдать"></td>';
                                                } else if ($data_find_books[$i][6] == 0 && mysqli_num_rows($allReaders) > 0) { // если книги нет в наличии и есть хотя бы один читатель, кнопка становится неактивной

                                                    $out_find .= '<td class="tdBut"><input type="submit" name="give' . $i . '" value="Выдать" disabled></td>';
                                                } else if (mysqli_num_rows($allReaders) == 0) { // если читателей нет, то кнопка не выводится

                                                    $out_find .= '';
                                                }
                                            }
                                        }
                                        if ($j < count($fieldsFindBooks)) { //вывод данных из массива книг
                                            $out_find .= '<td>' . $data_find_books[$i][$j] . '</td>';
                                        }
                                    }
                                    $out_find .= "</tr>";
                                }
                                echo $out_find;
                            }
                            ?>

                        </tbody>
                    </table> 
                            <?php print_r($info_find); ?>
                </div>

<!-- Добавление читателя -->
                <div class="addAPG">

<!--поля для ввода значений-->
                    <p>ФИО читателя</p>
                    <p><input type="text" name="new_reader_name" value="<?php echo isset($_POST["new_reader_name"]) == true ? $_POST["new_reader_name"] : "" ?>" pattern="[А-Яа-яЁё'\-\s]+" title='Русские буквы без цифр'></p>
                    <p>Электронная почта</p>
                    <p><input type="email" name="new_reader_email" value="<?php echo isset($_POST["new_reader_email"]) == true ? $_POST["new_reader_email"] : "" ?>" pattern="[a-z0-9\._\-]+[^\.]@[a-z0-9\.\-]+\.[a-z]+" title='Пример: mymail@mail.ru'></p>
                    <p>Номер телефона в формате 7(xxx)xxx-xx-xx</p>
                    <p><input type="tel" name="new_reader_phone" value="<?php echo isset($_POST["new_reader_phone"]) == true ? $_POST["new_reader_phone"] : "" ?>" pattern="7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}" title='Пример: 7(999)888-77-66'></p>
                    <p><input type="submit" name="new_reader_name_button" value="Добавить читателя"></p>
<?php print_r($info_reader); ?>
                </div>
<!-- Добавление книги -->
                <div class="addBooks">   
<!--поля для ввода значений-->
                    <div class="fbInput">    
                        <p>Название книги</p>
                        <p><input type="text" name="new_book_name" value="<?php echo isset($_POST["new_book_name"]) == true ? $_POST["new_book_name"] : "" ?>"></p>     
                    </div>
                    <div class="fbInput">    
                        <p>Год издания</p>
                        <p><input name="new_book_year"  type="number" min="1901" max="2099" step="1" value="<?php echo isset($_POST["new_book_year"]) == true ? $_POST["new_book_year"] : "" ?>"></p>
                    </div>
                    <div class="fbInput">    
                        <p>Количество страниц</p>
                        <p><input type="number" min="1" name="new_book_pages" value="<?php echo isset($_POST["new_book_pages"]) == true ? $_POST["new_book_pages"] : "" ?>"></p>
                    </div>
                    <div class="fbInput">    
                        <p>Количество копий</p>
                        <p><input type="number" min="1" name="new_book_copies" value="<?php echo isset($_POST["new_book_copies"]) == true ? $_POST["new_book_copies"] : "" ?>"></p>
                    </div>
                    <!--выбор количества авторов -->
                    <p>Количество авторов</p>
                    <p><input type="number" min="1" max="10" name="count_authors" value="<?php echo isset($_POST["count_authors"]) == true ? $_POST["count_authors"] : "1" ?>">
                        <input type="submit" name="count_authorsButton" value="принять"></p> 

<?php
//назначение количества авторов и жанров из сессии
if (isset($_SESSION['count_authors'])) { 
    $count_authors = $_SESSION['count_authors'];
}
if (isset($_SESSION['count_genres'])) {
    $count_genres = $_SESSION['count_genres'];
}
?>

                    <?php echo '<p>Выберите автора(ов)</p>';
                    for ($c = 1; $c <= $count_authors; $c++) { // вывод выпадающего списка авторов в соответствии с выбранным количеством
                        ?>
                        <select name="<?php echo ("choice_author_list" . $c) ?>">
                            <option value="-1"></option>;
                        <?php foreach ($all_author_name as $key => $value) { ?>  <!--вывод всех авторов -->

                                <option value="<?php echo $key ?>" <?php echo isset($_POST["choice_author_list$c"]) && $_POST["choice_author_list$c"] == $key ? "selected" : "" ?> ><?php echo $value ?></option>;
                        <?php } ?>
                        </select>
    <?php
}
?>
                    <!--добавление нового автора-->
                    <p>Добавьте нового автора, если нужного нет в списке</p>
                    <p><input type="text" name="new_author_name" value="" pattern="[А-Яа-яЁё'\-\s]+" title='Русские буквы без цифр'>
                        <input type="submit" name="new_author_name_button" value="добавить">
                    <?php print_r($info_aut); ?></p>
                    
                    
                   <!--выбор количества жанров -->
                    <p>Количество жанров</p>
                    <p><input type="number" min="1" max="10" name="count_genres" value="<?php echo isset($_POST["count_genres"]) == true ? $_POST["count_genres"] : "1" ?>">
                        <input type="submit" name="count_genresButton" value="принять"></p> 

<?php
echo '<p>Выберите жанр(ы)</p>';
for ($c = 1; $c <= $count_genres; $c++) { // вывод выпадающего списка жанров в соответствии с выбранным количеством
    ?>
                        <select name="<?php echo ("choice_genre_list" . $c) ?>">
                            <option value="-1"></option>;
    <?php foreach ($all_genre_name as $key => $value) { ?> <!--вывод всех жанров -->

                                <option value="<?php echo $key ?>" <?php echo isset($_POST["choice_genre_list$c"]) && $_POST["choice_genre_list$c"] == $key ? "selected" : "" ?>><?php echo $value ?></option>;
                        <?php } ?>
                        </select>
<?php } ?>
                    <!--добавление нового жанра-->
                    <p>Добавьте новый жанр, если нужного нет в списке</p>
                    <p><input type="text" name="new_genre_name" value="" pattern="[А-Яа-яЁё\-\s]+" title='Русские буквы без цифр'>
                        <input type="submit" name="new_genre_name_button" value="добавить">
<?php print_r($info_genre); ?></p>
                    <p>Выберите издательство</p>
                    <select name="choice_publ_list">
                        <option value="-1"></option>;
                    <?php foreach ($all_publ_name as $key => $value) { ?> <!--вывод всех издательств-->

                            <option value="<?php echo $key ?>" <?php echo isset($_POST["choice_publ_list"]) && $_POST["choice_publ_list"] == $key ? "selected" : "" ?> ><?php echo $value ?></option>;
                        <?php } ?>
                    </select>
                    <!--добавление нового издательства-->
                    <p>Добавьте новое издательство, если нужного нет в списке</p>
                    <div class="fbInput">
                        <p>Наименование издательства</p>
                        <p><input type="text" name="new_publ_name" value=""></p>
                    </div>
                    <div class="fbInput" id="lastButPubl">
                        <p>Город издательсва</p>
                        <p><input type="text" name="new_publ_city" value="" pattern="[А-Яа-яЁё'\-\s]+" title='Русские буквы без цифр'></p>

                    </div>

                    <div class="fbInput">
                        <p><input type="submit" name="new_publ_name_button" value="добавить"></p>

                    </div>
<?php print_r($info_publ); ?>
                    <p><input type="submit" name="addBookButton" value="Добавить книгу"></p>
<?php print_r($info_book); ?>

                </div>      
<!-- Поиск -->
                <div class="dataFind">

                    <div>
                        <!--выбор данных для поиска-->
                        <p>Выберите данные для поиска</p>
                        <select name="choice_table_list">
                            <option value="0" <?php echo isset($_POST["choice_table_list"]) && $_POST["choice_table_list"] == 0 ? "selected" : "" ?>>Читатели</option>
                            <option value="1" <?php echo isset($_POST["choice_table_list"]) && $_POST["choice_table_list"] == 1 ? "selected" : "" ?>>Книги</option>
                        </select>
                        <p><input type="submit" name="choiceTableButton" value="Выбрать"></p> 
                    </div>
<!-- Поиск читателей -->
<?php if ($choice_table_list == 0) { ?>
                        <p>Введите фамилию читателя</p>
                        <p><input id="frn" type="text" name="find_reader_name" value="<?php
    if (isset($_POST['find_reader_name']))
        echo $_POST['find_reader_name'];
    else
        ""
        ?>"></p>
                        <p><input type="submit" name="find_reader_button" value="Найти"></p>
                        
                        <!--таблица найденных читателей-->
                        <table class="t_dataReaders">
                            <thead>
                                <tr>
    <?php
    if (isset($data_readers) == true) { //название столбцов таблицы
        echo '<th>Код читателя</th>'
        . '<th>ФИО читателя</th>'
        . '<th>Электронная почта</th>'
        . '<th>Номер телефона</th>';
        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php
                                        $out_readers = "";
                                        for ($i = 0; $i < count($data_readers); $i++) {
                                            $out_readers .= "<tr>";
                                            for ($j = 0; $j < count($fieldsDataReader) + 1; $j++) {
                                                if ($j == count($fieldsDataReader)) { // добавление в конец таблицы флажка для удаления строки 
                                                    $out_readers .= '<td class="tdBut"><input id="tr' . $i . '" type="checkbox" name="delet[]" value="' . $data_readers[$i][0] . '"><label for="tr' . $i . '" class="ltr"></label></td>';
                                                }
                                                if ($j < count($fieldsDataReader)) {  //вывод данных из массива читателей
                                                    $out_readers .= '<td>' . $data_readers[$i][$j] . '</td>';
                                                }
                                            }

                                            $out_readers .= "</tr>";
                                        }
                                        echo $out_readers;
                                    }
                                    ?>

                            </tbody>
                        </table>  
                                <?php
                                print_r($info_find_reader);
                                if (mysqli_num_rows($dataReaders) > 0) { // если читатели есть то выводится кнопка для удаления
                                    ?>

                            <p><input type="submit" name="deleteButton" value="Удалить"></p>
                                    <?php print_r($info_del_reader); ?>
                                <?php }
                            } ?>
<!-- Поиск книг -->
<?php if ($choice_table_list == 1) { ?>
                    <!-- поля для ввода значений -->
                        <div class="fbInput">
                            <p>Введите название книги</p>
                            <p><input id="fbn" type="text" name="find_book_name1" value="<?php
                        if (isset($_POST['find_book_name1']))
                            echo $_POST['find_book_name1'];
                        else
                            ""
                            ?>"></p> </div>
                        <div class="fbInput">
                            <p>Введите фамилию автора</p>
                            <p><input id="fba" type="text" name="find_book_author1" value="<?php
                if (isset($_POST['find_book_author1']))
                    echo $_POST['find_book_author1'];
                else
                    ""
                            ?>"></p> </div>
                        <div class="fbInput">
                            <p>Введите жанр книги</p>
                            <p><input id="fbg" type="text" name="find_book_genre1" value="<?php
                        if (isset($_POST['find_book_genre1']))
                            echo $_POST['find_book_genre1'];
                        else
                            ""
                            ?>"></p></div>
                        <p><input type="submit" name="find_book_button1" value="Найти"></p>
                    <!-- таблица с найденными книгами -->
                        <table>
                            <thead>
                                <tr>
                                      <?php
                                      $m = 0;
                                      if (isset($data_find_books2) == true) { // названия столбцов таблицы
                                          echo '<th>Код книги</th>'
                                          . '<th>Название</th>'
                                          . '<th>Издательство</th>'
                                          . '<th>Город</th>'
                                          . '<th class="small">Год издания</th>'
                                          . '<th class="small">Кол-во страниц</th>'
                                          . '<th class="small">Кол-во копий</th>'
                                          . '<th>Авторы</th>'
                                          . '<th>Жанры</th>';
                                          ?>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php
                                        $out_find1 = "";
                                        for ($i = 0; $i < count($data_find_books2); $i++) {
                                            $out_find1 .= "<tr>";
                                            for ($j = 0; $j <= count($fieldsFindBooks1) + 2; $j++) {
                                                if ($j == count($fieldsFindBooks1)) { // вывод авторов
                                                    $out_find1 .= '<td>' . $data_find_books_aut22[$i] . '</td>';
                                                }
                                                if ($j == count($fieldsFindBooks1) + 1) { // вывод жанров
                                                    $out_find1 .= '<td>' . $data_find_books_gen22[$i] . '</td>';
                                                }
                                                if ($j == count($fieldsFindBooks1) + 2) { // добавление в конец таблицы флажка для удаления строки 
                                                    $out_find1 .= '<td class="tdBut"><input id="tbb' . $i . '" type="checkbox" name="delet1[]" value="' . $data_find_books2[$i][0] . '"><label for="tbb' . $i . '" class="ltr"></label></td>';
                                                }
                                                if ($j < count($fieldsFindBooks1)) { //вывод данных из массива книг
                                                    $out_find1 .= '<td>' . $data_find_books2[$i][$j] . '</td>';
                                                }
                                            }
                                            $out_find1 .= "</tr>";
                                        }
                                        echo $out_find1;
                                    }
                                    ?>

                            </tbody>
                        </table> 
                                <?php
                                print_r($info_find1);
                                if (mysqli_num_rows($ResFindBooks1) > 0) { // если книги есть то выводится кнопка для удаления
                                    ?>

                            <p><input type="submit" name="deleteButton1" value="Удалить"></p>
        <?php print_r($info_del_book); ?>
                        <?php }
                    } ?>



                </div>



            </form>
        </div>




    </body>
</html>
