

<?php if(isset($_SESSION['user'])):?>
                    <div class="buttons__action">
                        <a href="/book/<?= $book->id?>" class="btn btn_success btn_edit">Редактировать</a>
                        <a href="/book/<?= $book->id?>/delete" class="btn btn_danger btn_delete">Удалить</a>
                    </div>
                <?php endif;?>