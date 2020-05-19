# Тестовое задание для компании WineStyle

### Описание
Демо: https://winestyle.strilchuk.ru  
Проект представляет собой веб-приложение с MVC архитектурой.  
Точка входа в приложение находится в файле public/index.php.  
Маршрутизация задается в файле App/Routes.  
В приложении имеются три таблицы. При развертывании приложения таблицы создаются и заполняются после отправки специального служебного запроса `POST /migrate`:
 - pic_cache - хранит информацию о кэше;
 - pic_sizes - хранит информацию о доступных размерах изображения;
 - pic_store - хранит информацию об исходниках изображений.
   

### Маршруты
- `GET /` - корневой маршрут. Сразу показывает галерею; 
- `GET /demo` - маршрут создан для соблюдения условия задачи (demo.php). Показывает галерею.
- `GET /generator?name={image_name}&size={size_name}` - маршрут возвращает сгенерированное изображение указанного размера. Параметр name - обязательный.  
- `GET /images` - маршрут возвращает список имен картинок в галерее. Параметров не принимает.
- `POST /images/add?url={url}` - маршрут служебного назначения. Предназначен для добавления в галерею нового изображения. Параметр url содержит ссылку на картинку в интернете.
- `POST /migrate` - маршрут служебного назначения. Предназначен для первичного создания необходимых таблиц в базе данных и их заполнения. Маршрут безопасен и не задублирует записи в таблицах.

### Обработка ошибок
 - Если при отправке запроса `/generator` не указать параметр name, то будет возвращен json-объект с сообщением об ошибке:`{"status": "error", "message": "Image name and size are not specified"}`;
 - Если не указать параметр size, то система по умолчанию возьмет размер "mic";
 - Если указать не существующий размер, то система вернет сообщение об ошибке: `{"status": "error", "message": "Invalid image size specified. Valid list: big, med, min, mic"}`, где список доступных размеров берется из базы данных;
 - Если попытаться сгенерировать картинку с именем, которого нет в исходниках, то система вернет сообщение об ошибке: `{"status": "error", "message": "Image with this name not found"}`;
 - Если по каким-то причинам не удалось сгенерировать новое изображение (например, закончилось место на диске), то система вернет сообщение об ошибке: `{"status": "error", "message": "Image generation failure"}`;
 - Если при отправке запроса `POST /images/add?url={url}` указать не верную ссылку, или не указать параметр вообще, то система вернет json-объект с сообщением об ошибке:`{"status": "error", "message": "Download image failure"}`;
 
 ### Улучшения
 - Применена архитектура MVC вместо двух скриптов demo.php и generator.php;
 - Добавлена функция добавления новых изображений в галерею;
 - Добавлена упрощенная система миграции через маршрут `/migrage`;
 
  ### Протокол тестирования
  - `Исходники картинок хранятся в папке gallery.` - в корне приложения создан каталог gallery, куда попадают исходники картинок после добавления через служебный маршрут `POST /images/add?url={url}`;
  - `Скрипт принимает Get-параметры name и size` - можно проверить воспользовавшись описанием маршрута `GET /generator?name={image_name}&size={size_name}`, или посмотреть исходный код создания галереи в файле `public/assets/js/gallery.js`;
  - `Список кодов и размеров для генерации картинок хранится в MySql` - можно посмотреть в таблице pic_sizes или в файле `App\Controllers\ServiceController.php`;
  - `Указаны максимальные размеры сторон, при масштабировании пропорции сохраняются.` - можно проверить, добавив вертикально-ориентированную картинку. За изменение масштаба отвечает код в методе `createThumb()` в файле  `App\Controllers\ImagesController.php`;
  - `Результат работы скрипта – jpg-картинка заданного размера.` - можно проверить перейдя по ссылке: `http://winestyle.strilchuk.ru/generator?name=6a59d608d1b8f8.jpg&size=big`;
  - `Сгенерированное изображение сохраняется в папке cache. Если есть кеш, повторно не генерируем.` - в корне приложения создан каталог cache, куда попадают сгенерированные картинки;
  - `SRC картинок указывает на generator.php(с нужными параметрами).` - можно посмотреть при создании галереи в файле `public/assets/js/gallery.js`;
  - `Для демонстрации работы генератора, плиткой выводим 10 превью-картинок.` - выводится ровно 10 картинок даже если в базе их больше;
  - `Ограничения в размере картинок для мобильных устройств и desckop'a` - отображаемый размер выводится в качестве подписи в галереи.
  
  
  ### Используемые библиотеки
  - php-библиотеки
    - [klein/klein](https://github.com/klein/klein.php) - работа с http-маршрутизацией;
    - [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) - возможность выносить параметры приложения в .env-файл;
  - frontend-библиотеки
    - [jquery 3.5.1](https://jquery.com/download/);
    - [magnific-popup](https://dimsemenov.com/plugins/magnific-popup/) - модальная галерея
    - [bootstrap v3.3.7](http://getbootstrap.com)
    
  