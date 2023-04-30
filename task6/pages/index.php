<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/header.php');
?>
<?php // для авторизоавнного выводим его список
if (isset($_SESSION['user'])) : ?>

<?php // для неавторизованного - все книжки 
// TODO сделать лимит и сортировку по дате добавления - свежие
else : ?>
    <div class="books">
        <?php foreach ($books as $book) : ?>
            <div class="books__item card">
                <img 
                    src="<?= $book->picture_path ?>" 
                    alt="<?= $book->name ?>" 
                    class="card__img"
                />

                <div class="card__caption">
                    <?= $book->name?>
                </div>

                <div class="card__info">
                    <div class="card__info-item author">
                        <b>Автор: </b><?= $authors[$book->author_id->id]->fio?>
                    </div>

                    <div class="card__info-item date">
                        <b>Дата прочтения: </b><?= $book->date->format('Y-m-d')?>
                    </div>

                    <div class="card__info-item user">
                        <b>Добавил: </b><?= $users[$book->user_id->id]->firstname?> <?= $users[$book->user_id->id]->lastname?>
                    </div>                    
                </div>

            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php');
