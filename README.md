## Задание №1

Следующее требование не совсем понятно:

> "Важно учесть, если запрос будет происходить одновременно, не должно возникнуть такой ситуации, что двум разным заказам присвоился один номер."

У каждого заказа уже есть уникальный идентификатор (первичный ключ `id`). Что имеется в виду под "одним номером"?

## Задание №2 - Нормализация до 3-НФ

Сначала создадим новую таблицу для типов билетов, включая цену и название каждого типа:

```sql
CREATE TABLE `ticket_types` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL,
    `price` INT NOT NULL
);
```



Выполним нормализацию исходной таблицы, добавив в нее поле `ticket_type`, которое будем ссылаться на записи главную таблицу `ticket_types`.

Исходная таблица находится в 2-НФ, так как удовлетворяет требованию 1-НФ (выполнено условие атомарности атрибутов отношения, т.е. аттрибуты являются скалярными) и каждый ее неключевой атрибут полностью зависит от первичного ключа.

Для нормализации до 3-НФ нужно выделить функциональные зависимости (например, поля event_date, ticket_adult_price, ticket_kid_price зависят от event_id) и исключить транзитивные зависимости.



SQL-код для создания результирующего набора таблиц:

```sql
CREATE TABLE `orders` (
	`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`event_id` INT UNSIGNED NOT NULL,
	`ticket_type` INT UNSIGNED NULL,
	`barcode` VARCHAR(120) NOT NULL UNIQUE,
	`equal_price` INT NOT NULL,
	`created` DATETIME NOT NULL,
	FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE,
	FOREIGN KEY (`ticket_type`) REFERENCES `ticket_types`(`id`) ON DELETE SET NULL
);

CREATE TABLE `order_tickets` (
	`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`order_id` INT UNSIGNED NOT NULL,
	`ticket_type` ENUM('adult', 'child') NOT NULL,
	`quantity` INT UNSIGNED NOT NULL,
	FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
);

CREATE TABLE `events` (
	`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR(255) NOT NULL,
	`description` TEXT NOT NULL,
	`schedule` DATETIME NOT NULL,
	`ticket_adult_price` INT NOT NULL,
	`ticket_kid_price` INT NOT NULL
);

CREATE TABLE `ticket_types` (
	`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR(50) NOT NULL,
	`price` INT NOT NULL
);
```


## Задание №3 - Сопроводить документацией своё решение.

Имелось в виду, нужно описать созданный API c использованием OpenAPI?

Swagger UI доступен по URL-пути '/swagger'.
