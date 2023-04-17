'use strict'

window.addEventListener('DOMContentLoaded', () => {
    let form = document.querySelector('form');
    let messBlock = document.querySelector('.message-form');

    form.addEventListener('submit', function(e){
        e.preventDefault();

        messBlock.classList.remove('error');
        
        const fData = new FormData(this);

        let flag = false;
        for(let field of fData.entries())
        {
            let f = this.querySelector('[name=' + field[0] +']').closest('.form-field');
            f.classList.remove('error');

            // TODO сделать нормальное условие - сейчас не хватает интеллектуальных способностей
            if(field[0] == 'email'
                && !/^[\w\d\.]+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/.test(field[1])
            ) {
                f.classList.add('error');
                flag = true;
            } else if(field[0] == 'phone'
                && !/\+([\d\-\(\) ][^\w]?){11,}\d/g.test(field[1])
            ) {
                f.classList.add('error');
                flag = true; 
            } else if(field[1] == null || field[1] == "") {
                f.classList.add('error');
                flag = true; 
            }
        }

        if(flag) {
            messBlock.innerHTML = 'Ошибка в заполнении данных формы.';
            messBlock.classList.add('error');
            return;
        }


        postData('/admin/api/form', fData, {})
            .then((data) => {
                form.remove();
                messBlock.classList.add('sucsess');
                messBlock.innerHTML = data.message;
            })
            .catch(() => {
                let errorMess = getCookieError();
                messBlock.classList.add('error');
                messBlock.innerHTML = errorMess;
            })
    })
})