<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/header.php');
?>
<?php if(isset($_SESSION['user'])):?>
    <form action="/book/create" method="post" enctype='multipart/form-data'>    
        <div class="errors">
            <?= isset($_GET['error']) ? $_GET['error'] : ''?>
        </div>
        
        <div class="field">
            <label for="field-name">Название</label>
            <input 
                type="text" 
                name="name" 
                id="field-name" 
                <?php if(isset($book)):?>
                    value="<?= $book->name?>"
                <?php endif?>
                required
            >
        </div>
        
        <div class="field">
            <label for="field-author">Автор</label>
            <select name="author_id" id="field-author" required>
                <?php foreach($authors as $author):?>
                    <option 
                        value="<?= $author->id?>"
                        <?= isset($book) && $book->author_id == $author->id ? "selected" : ""?>
                    >
                        <?= $author->fio?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>
        
        <div class="field">
            <label for="field-picture">Обложка</label>
            <input 
                type="file" 
                name="picture" 
                id="field-picture" 
                required
            >
        </div>
        
        <div class="field">
            <label for="field-book">Книга</label>
            <input type="file" name="book" id="field-book" required>
        </div>
        
        <div class="field">
            <label for="field-date">Дата прочтения</label>
            <input 
                type="date" 
                name="date" 
                id="field-date" 
                <?php if(isset($book) && $book->isdownload):?>
                    checked
                <?php endif?>
                required
            >
        </div>
        
        <div class="field">
            <label for="field-isdownload">Разрешить скачивание</label>
            <input type="checkbox" name="isdownload" id="field-isdownload">
        </div>

        <input type="submit" value="Сохранить">

        <p>* все поля обязательные</p>
        <p>* максимальный размер файла книги - 5Мб</p>
    </form>
<?php else:?>
    <div class="error">
        Вы не авторизованы, добавление или редактирование книги недоступно :(
    </div>
<?php endif?>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php');