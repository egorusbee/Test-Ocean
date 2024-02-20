let source = document.getElementById('car_number');
const error_text = document.getElementById('error_text');

const regex = new RegExp('^[АВЕКМНОРСТУХ]\\d{3}(?<!000)[АВЕКМНОРСТУХ]{2}\\d{2,3}$', 'ui')

console.log(source)
const inputHandler = function(e) 
{
    console.log(source.value)
    if (!regex.test(source.value)) {
        error_text.style.color = 'red'
        error_text.innerHTML = 'номер не соответсвует заданному формату (Пример: С775СС74)'
    }
    else
    {
        error_text.style.color = 'green'
        error_text.innerHTML = 'Формат номера верный'
    }
}

source.addEventListener('input', inputHandler);
source.addEventListener('propertychange', inputHandler); 