<!DOCTYPE html>

<html lang="ru">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>shpp-library</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="library Sh++">
    <link rel="stylesheet" href="../static/CSS/libs.min.css">
    <link rel="stylesheet" href="../static/CSS/style.css">

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

<?php require_once "header.html" ?>

<section id="main" class="main-wrapper">

    <div class="container">
        <div id="content" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php foreach ($booksList as $bookItem) :
                if (!isset($bookItem["id"])) {
                    continue;
                } ?>
                <div data-book-id="<?php echo $bookItem["id"] ?>" class="book_item col-xs-6 col-sm-3 col-md-2 col-lg-2">
                    <div class="book">
                        <a href="http://level3.local/book/<?php echo $bookItem['id'] ?>"><img
                                    src="<?php echo $bookItem['picture'] ?>"
                                    alt="<?php echo $bookItem['book_name'] ?>">
                            <div data-title="<?php echo $bookItem['book_name'] ?>" class="blockI" style="height: 50px;">
                                <div data-book-title="<?php echo $bookItem['book_name'] ?>"
                                     class="title size_text"><?php echo $bookItem['book_name'] ?>
                                </div>
                                <div data-book-author="<?php echo $bookItem['author'] ?>"
                                     class="author"><?php echo $bookItem['author'] ?></div>
                            </div>
                        </a>
                        <a href="http://level3.local/book/<?php echo $bookItem['id'] ?>">
                            <button type="button" class="details btn btn-success">Читать</button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <center>

        <nav aria-label="...">
            <ul class="pagination">
                <?php if ($booksList['shift'] > 0) { ?>
                    <li class="page-item active">
                        <a class="page-link"
                           href="http://level3.local/books/<?php echo($booksList['shift'] - 1) ?>" tabindex="-1">Previous</a>
                    </li>
                <?php } ?>
                <li class="page-item"><a class="page-link"
                                         href="#"><?php echo "всего книг " . ($booksList['count']) ?></a></li>
                <li class="page-item">
                </li>
                <?php if ((int)($booksList['count'] / OFFSET) > $booksList['shift']) { ?>
                    <li class="page-item active">
                        <a class="page-link"
                           href="http://level3.local/books/<?php echo($booksList['shift'] + 1) ?>">Next</a>
                    </li>
                <?php } ?>
            </ul>
        </nav>

    </center>


</section>
<?php require_once "footer.html" ?>
<div class="sweet-overlay" tabindex="-1" style="opacity: -0.02; display: none;"></div>
<div class="sweet-alert hideSweetAlert" data-custom-class="" data-has-cancel-button="false"
     data-has-confirm-button="true" data-allow-outside-click="false" data-has-done-function="false" data-animation="pop"
     data-timer="null" style="display: none; margin-top: -169px; opacity: -0.03;">
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
    <div class="sa-error-container">
        <div class="icon">!</div>
        <p>Not valid!</p>
    </div>
</div>
</body>

</html>