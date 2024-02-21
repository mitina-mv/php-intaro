<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/header.php');
?>
<div class="books">
    <?php foreach ($books as $book) : ?>
        <div class="books__item card">
            <img 
                src="<?= $book->picture_path ?: $_ENV['DEFAULT_IMG'] ?>" 
                alt="<?= $book->name ?>" 
                class="card__img"
            />

            <div class="card__caption">
                <?= $book->name?>
            </div>

            <div class="card__info">
                <div class="card__info-item author">
                    <b>Автор: </b><?= $book->getAuthor()->fio?>
                </div>

                <div class="card__info-item date">
                    <b>Дата прочтения: </b><?= $book->date->format('Y-m-d')?>
                </div>

                <div class="card__info-item user">
                    <b>Добавил(a): </b>
                    <a href="<?='/user/' . $book->getUser()->id?>"> 
                        <?= $book->getUser()->firstname?> <?= $book->getUser()->lastname?>
                    </a>
                </div>                   
            </div>

            <div class="buttons">
                <?php if($book->isdownload):?>
                    <a href="<?= $book->file_path?>" class="btn btn_primary" download>Скачать</a>
                <?php endif;?>
            </div>


        </div>
    <?php endforeach; ?>
</div>

<div class="pagination">
    <?php foreach ($links as $link) : ?>
        <?=$link?>
    <?php endforeach; ?>
</div>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php');
