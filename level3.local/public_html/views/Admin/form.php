<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>shpp-library</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="library Sh++">
    <link rel="stylesheet" href="../../static/CSS/libs.min.css">
    <link rel="stylesheet" href="../../static/CSS/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"
          crossorigin="anonymous"/>

    <link rel="shortcut icon" href="http://localhost:3000/favicon.ico">
    <style>
        .details {
            display: none;
        }
    </style>
</head>
<body>

<body data-gr-c-s-loaded="true" class="">
<section id="header" class="header-wrapper">

    <div class="logout">
        <a href="http://level3.local/logout/" style="margin-right: 200px; margin-top: 20px; margin-block: 20px;"
           type="button" class="page-link pull-right">Выход</a>
    </div>

</section>

<table width="100%" cellpadding="5">
    <tr>
        <td valign="top" width="10%"
        ">
        <td valign="top" width="30%" bgcolor="#f0f0f0">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название книги</th>
                    <th scope="col">Авторы</th>
                    <th scope="col">год</th>
                    <th scope="col">действия</th>
                    <th scope="col">кликов</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 0;
                foreach ($booksList as $bookItem) :
                    ?>
                    <tr>
                        <th scope="row"><?php echo ++$i ?></th>
                        <td><?php echo $bookItem['book_name'] ?></td>
                        <td><?php echo $bookItem['author_name'] ?></td>
                        <td><?php echo $bookItem['year'] ?></td>


                        <td>
                            <form id="<?php echo $bookItem['id'] ?>" action="http://level3.local/adm/form"
                                  method="POST">
                                <input type="hidden" name="id" value="<?php echo $bookItem['id'] ?>">
                                <!--<a type="submit" >Delete</a>--><?php echo $bookItem['id'] ?>
                                <a href="javascript:{}" onclick="this.parentNode.submit(); return false;">Delete</a>
                            </form>
                        </td>


                        <td><?php echo $bookItem['number_of_clicks'] ?></td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <center>
                <nav aria-label="...">
                    <ul class="pagination pagination-sm">

                        <?php for ($i = 0; $i < (int)($booksList[0]['count'] / OFFSET) + 1; $i++) { ?>
                            <li>
                                <a class="page-link" href="http://level3.local/adm/form/<?php echo $i ?>"
                                   tabindex="-1"><?php echo $i + 1 ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
                <center>
        </td>

        <!-- в style.css нужно добавить вот это. Остальное регулирует себя само, по крайней мере
        у меня это таки выглядит прилично

        .row{
        margin: 20px;
        } -->

        <td width="2%"></td>
        <td valign="top" width="25%" bgcolor="#f0f0f0">
            <form action="http://level3.local/adm/form" method="POST">
                <fieldset>
                    <legend>Добавить новую книгу</legend>
                    <div class="row">
                        <div class="col">
                            <input type="text" name="book_name" class="form-control" placeholder="Название книги">
                        </div>
                        <div class="col">
                            <input type="text" name="author_1" class="form-control" placeholder="автор 1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="text" name="year" class="form-control" placeholder="год издания">
                        </div>
                        <div class="col">
                            <input type="text" name="author_2" class="form-control" placeholder="автор 2">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="input-group mb-3">
                                <input type="text" name="picture" class="form-control" id="user" style="width: 200px;"
                                       placeholder="Загрузите изображение"
                                       aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">Button</button>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <input type="text" name="author_3" class="form-control" placeholder="автор 3">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <!-- а вот этого тут не должно быть... тут превью картинки  -->
                            <input type="text" class="form-control" placeholder="Имя">
                        </div>
                        <div class="col">
                            <p><textarea name="comment" style="width: 200px;" id="comment"></textarea></p>
                        </div>
                    </div>
                </fieldset>
                <center>
                    <button type="submit" class="btn btn-success">Добавить книгу ></button>
                </center>
            </form>
        </td>
        <td valign="top" width="10%"
        ">
    </tr>
</table>


<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>
</html>