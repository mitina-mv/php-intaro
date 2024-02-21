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

                <?php if($_SESSION && $_SESSION['user']->id != $book->getUser()->id):?>
                <div class="card__info-item user">
                    <b>Добавил(a): </b>
                    <a href="<?='/user/' . $book->getUser()->id?>"> 
                        <?= $book->getUser()->firstname?> <?= $book->getUser()->lastname?>
                    </a>
                </div>  
                <?php endif;?>                 
            </div>

            <div class="buttons">
                <?php if($_SESSION && $_SESSION['user']->id == $book->getUser()->id):?>
                    <div class="buttons__action">
                        <a href="/book/<?= $book->id?>" class="btn btn_success btn_edit">Редактировать</a>
                        <a href="/book/<?= $book->id?>/delete" class="btn btn_danger btn_delete">Удалить</a>
                    </div>
                <?php endif;?>
                
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
