"use strict"

window.addEventListener('DOMContentLoaded', function() {
    let form = document.querySelector('form');

    function findTountInAddress(mass, block)
    {
        let flagReturn = false;        
        let touns = ['Москва', 'Санкт-Петербург', 'Нижний Новгород', 'Новосибирск', 'Самара', 'Казань', 'Екатеринбург'];
        
        mass.forEach(element => {
            if(touns.indexOf( element ) != -1) {
                block.innerHTML = '<span class="error">Кажется, вы ввели город в поле для адреса и решили меня запутать! Выберите город чуть выше!</span>'
                flagReturn = true;
            }
        });

        return flagReturn;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        let message = form.querySelector('.message-block');
        message.innerHTML = '<span class="loading">Загрузка...</span>';

        let address = form.querySelector('#address_field').value;
        let toun = form.querySelector('#toun_field').value;

        if(address.length == 0) {
            message.innerHTML = '<span class="error">Сначала введите адрес!</span>'
            return;  
        }

        // TODO проверка на ввод города в поле адреса
        let arrLocation = address.split(',');
        if(findTountInAddress(arrLocation, message)) return;
        arrLocation = address.split(' ');
        if(findTountInAddress(arrLocation, message)) return;
        
        postData('./getResult.php', JSON.stringify({
            address: address,
            toun: toun
        }))
            .then((data) => {
                console.log(data);
                message.innerHTML = `<span class="success">
                    <b>Станция: </b>${data.station}<br>
                    <b>Линия: </b>${data.line}
                </span>`
            })
            .catch(() => {
                message.innerHTML = '<span class="error">Капитан, плаванье нельзя продолжить! КомпАс сбился и что-то идет не так!</span>'
            })
    })

})