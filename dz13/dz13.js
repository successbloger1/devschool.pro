/*
 * Следующие задания требуется воспринимать как ТЗ (Техническое задание)
 * p.s. Разработчик, помни! 
 * Лучше уточнить ТЗ перед выполнением у заказчика, если ты что-то не понял, чем сделать, переделать, потерять время, деньги, нервы, репутацию.
 * Не забывай о навыках коммуникации :)
 * 
 * Задание 1
 * - Создайте переменную $name и присвойте ей значение содержащее ваше имя, например Игорь
 * - Создайте переменную $age и присвойте ей ваше количество лет, например 30
 * - Выведите на экран фразу по шаблону "Меня зовут Игорь"
 *                                      "Мне 30 лет"
 * Удалите переменные $name и $age
 * 
 * 
 * Задание 2
 * - Создайте константу и присвойте ей значение города в котором живете
 * - Прежде чем выводить константу на экран, проверьте, действительно ли она объявлена и существует
 * - Выведите значение объявленной константы
 * - Попытайтесь изменить значение созданной константы
 * 
 * Задание 3
 * - Создайте ассоциативный массив $book, ключами которого будут являться значения "title", "author", "pages"
 * - Заполните его по логике описания книг, укажите значения книги, которую недавно прочитали
 * - Выведите следующую строку на экран, следуя шаблону: "Недавно я прочитал книгу 'title', написанную автором author, я осилил все pages страниц, мне она очень понравилась"
 * 
 * Задание 4
 *  - Создайте индексный массив $books, который будет содержать в себе два массива $book1 и $book2, где будут записаны уже две последние прочитанные вами книги
 *  - Выведите следующую строку на экран, следуя шаблону: "Недавно я прочитал книги 'title1' и 'title2', 
 *  написанные соответственно авторами author1 и author2, я осилил в сумме pages1+pages2 страниц, не ожидал от себя подобного"
 */

//Задание 1
var $name = 'Павел';
var $age = 27;
console.log("Меня зовут "+$name);
console.log("Мне "+$age+" лет.");

// - Удаляем переменные $name и $age
$name = undefined; 
$age = undefined;

//Задание 2
var CITY = 'Харьков';

// - Проверяем существование константы
if (CITY !== undefined){ 
    console.log(CITY);
} else {
    console.log('Константа не объявлена');
}



//Задание 3
var $book = {'title' : 'Лайфхак на каждый день', 'author' : 'Фарид Каримов', 'pages' : 190};

var $text = 'Недавно я прочитал книгу '+$book['title']+', написанную автором '+$book['author']+', я осилил все '+$book['pages']+' страниц, мне она очень понравилась';
console.log($text);


//Задание 4
$book1 = {'title' : 'Лайфхак на каждый день', 'author' : 'Фарид Каримов', 'pages' : 190};
$book2 = {'title' : 'Важные годы. Почему не стоит откладывать жизнь на потом', 'author' : 'Мэг Джей', 'pages' : 270};

function Books ($book1, $book2){
    this.book1 = $book1;
    this.book2 = $book2;
}

Books.prototype.out = function () {
	var $text = 'Недавно я прочитал книги '+this.book1['title']+' и '+this.book2['title']+', написанные соответственно авторами '
    +this.book1['author']+' и '+this.book2['author']+', я осилил в сумме '+(function (a, b){ return a+b })(this.book1['pages'], this.book2['pages'])
    +' страниц, не ожидал от себя подобного.';
    
    console.log($text);
}

books = new Books($book1, $book2);

books.out();
