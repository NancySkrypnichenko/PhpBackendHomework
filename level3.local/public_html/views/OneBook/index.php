<!DOCTYPE html>
<html lang="ru">

<head>
    <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous">

    </script>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>shpp-library</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="library Sh++">

    <link rel="stylesheet" href="../../static/CSS/libs.min.css">
    ">
    <link rel="stylesheet" href="../../static/CSS/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"
          crossorigin="anonymous"/>

    <link rel="shortcut icon" href="http://localhost:3000/book/favicon.ico">
</head>

<body data-gr-c-s-loaded="true" class="">
<section id="header" class="header-wrapper">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="col-xs-5 col-sm-2 col-md-2 col-lg-2">
                <div class="logo"><a href="http://localhost:3000/" class="navbar-brand"><span class="sh">Ш</span><span
                                class="plus">++</span></a></div>
            </div>
            <div class="col-xs-12 col-sm-7 col-md-8 col-lg-8">
                <div class="main-menu">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <form class="navbar-form navbar-right">
                            <div class="form-group">
                                <input id="search" type="text" placeholder="Найти книгу" class="form-control">
                                <script>
                                    $("#search").bind("keypress", function (e) {
                                        if (e.keyCode == 13) {
                                            e.preventDefault();
                                            alert("а вот это придется сделать самому. Ваш @rshmelev");
                                        }
                                    })
                                </script>
                                <div class="loader"><img src="./book-page_files/loading.gif"></div>
                                <div id="list" size="" class="bAutoComplete mSearchAutoComplete"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xs-2 col-sm-3 col-md-2 col-lg-2 hidden-xs">
                <div class="social"><a href="https://www.facebook.com/shpp.kr/" target="_blank"><span
                                class="fa-stack fa-sm"><i class="fa fa-facebook fa-stack-1x"></i></span></a><a
                            href="http://programming.kr.ua/ru/courses#faq" target="_blank"><span class="fa-stack fa-sm"><i
                                    class="fa fa-book fa-stack-1x"></i></span></a></div>
            </div>
        </div>
    </nav>
</section>
<section id="main" class="main-wrapper">
    <div class="container">
        <div id="content" class="book_block col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <script id="pattern" type="text/template">
                <div data-book-id="{id}" class="book_item col-xs-6 col-sm-3 col-md-2 col-lg-2">
                    <div class="book">
                        <a href="/book/{id}"><img src="img/books/{id}.jpg" alt="{title}">
                            <div data-title="{title}" class="blockI">
                                <div data-book-title="{title}" class="title size_text">{title}</div>
                                <div data-book-author="{author}" class="author">{author}</div>
                            </div>
                        </a>
                        <a href="/book/{id}">
                            <button type="button" class="details btn btn-success">Читать</button>
                        </a>
                    </div>
                </div>
            </script>
            <div id="id" book-id="<?php echo $book['id'] ?>">
                <div id="bookImg" class="col-xs-12 col-sm-3 col-md-3 item" style="
    margin:;
"><img src="<?php echo $book['picture'] ?>" alt="Responsive image" class="img-responsive">

                    <hr>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 info">
                    <div class="bookInfo col-md-12">
                        <div id="title" class="titleBook"><?php echo $book['book_name'] ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="bookLastInfo">
                            <div class="bookRow"><span class="properties">автор:</span><span
                                        id="author"><?php echo $book['author_name'] ?></span></div>
                            <div class="bookRow"><span class="properties">год:</span><span
                                        id="year"><?php echo $book['year'] ?></span></div>
                            <div class="bookRow"><span class="properties">страниц:</span><span id="pages">351</span>
                            </div>
                            <div class="bookRow"><span class="properties">isbn:</span><span
                                        id="<?php echo $book['id'] ?>"></span></div>
                        </div>
                    </div>
                    <div class="btnBlock col-xs-12 col-sm-12 col-md-12">
                        <button type="button" id="WantRead" class="btnBookID btn-lg btn btn-success">Хочу читать!
                        </button>

                        <script>
                            $(document).ready(function () {
                                $('button#WantRead').on('click', function () {
                                    $.ajax({
                                        method: "GET",
                                        url: "want/<?php echo $book['id'] ?>"
                                    })
                                        .done(function (msg) {
                                            alert(
                                                "Книга свободна и ты можешь прийти за ней." +
                                                " Наш адрес: г. Кропивницкий, переулок Васильевский 10, 5 этаж." +
                                                " Лучше предварительно прозвонить и предупредить нас, чтоб " +
                                                " не попасть в неловкую ситуацию. Тел. 099 196 24 69" +
                                                " \n\n" +
                                                "******************\n"
                                            );
                                        });
                                });

                            });
                        </script>
                    </div>
                    <div class="bookDescription col-xs-12 col-sm-12 col-md-12 hidden-xs hidden-sm">
                        <h4>О книге</h4>
                        <hr>
                        <p id="description"><?php echo $book['book_name'] ?></p>
                    </div>
                </div>
                <div class="bookDescription col-xs-12 col-sm-12 col-md-12 hidden-md hidden-lg">
                    <h4>О книге</h4>
                    <hr>
                    <p class="description"><?php echo $book['book_name'] ?></p>
                </div>
            </div>
            <script src="../../static/JS/book.js" defer=""></script>
        </div>
    </div>
</section>
<section id="footer" class="footer-wrapper">
    <div class="navbar-bottom row-fluid">
        <div class="navbar-inner">
            <div class="container-fuild">
                <div class="content_footer"> Made with<a href="http://programming.kr.ua/" class="heart"><i
                                aria-hidden="true" class="fa fa-heart"></i></a>by HolaTeam
                </div>
            </div>
        </div>
    </div>
</section>
<div class="sweet-overlay" tabindex="-1" style="opacity: -0.04; display: none;"></div>
<div class="sweet-alert hideSweetAlert" data-custom-class="" data-has-cancel-button="false"
     data-has-confirm-button="true" data-allow-outside-click="false" data-has-done-function="false" data-animation="pop"
     data-timer="null" style="display: none; margin-top: -169px; opacity: -0.04;">
    <div class="sa-icon sa-error" style="display: block;">
            <span class="sa-x-mark">
        <span class="sa-line sa-left"></span>
            <span class="sa-line sa-right"></span>
            </span>
    </div>
    <div class="sa-icon sa-warning" style="display: none;">
        <span class="sa-body"></span>
        <span class="sa-dot"></span>
    </div>
    <div class="sa-icon sa-info" style="display: none;"></div>
    <div class="sa-icon sa-success" style="display: none;">
        <span class="sa-line sa-tip"></span>
        <span class="sa-line sa-long"></span>

        <div class="sa-placeholder"></div>
        <div class="sa-fix"></div>
    </div>
    <div class="sa-icon sa-custom" style="display: none;"></div>
    <h2>Ооопс!</h2>
    <p style="display: block;">Ошибка error</p>
    <fieldset>
        <input type="text" tabindex="3" placeholder="">
        <div class="sa-input-error"></div>
    </fieldset>
    <div class="sa-button-container">
        <button class="cancel" tabindex="2" style="display: none; box-shadow: none;">Cancel</button>
        <div class="sa-confirm-button-container">
            <button class="confirm" tabindex="1"
                    style="display: inline-block; background-color: rgb(140, 212, 245); box-shadow: rgba(140, 212, 245, 0.8) 0px 0px 2px, rgba(0, 0, 0, 0.05) 0px 0px 0px 1px inset;">
                OK
            </button>
            <div class="la-ball-fall">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
</div>
</body>

</html>