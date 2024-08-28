# Документация к проекту Shop

#### git https://github.com/boszhansale/BoszhanShopBack

#### главный домен https://backshop.boszhan.kz/

#### админка https://backshop.boszhan.kz/admin

#### База данных MySql 5.7

#### php 8.2

все доступы находятся в файле .env

## Контроллеры Админки app/Http/Controllers/Admin

- AuthController авторизацию
- BrandController отвечает за логику бренд и категории
- DiscountCard дисконт карты
- CategoryController отвечает за логику категории
- InventoryController отвечает за логику инвентаризация
- MovingController отвечает за логику перемещение
- ReceiptController отвечает за логику поступления
- MainController это главый контроллер отвечает за главный страницу (только статистика)
- OrderController заказы
- ProductController вся номенклатура
- RefundController возвраты
- RefundProducerController возвраты от производителей
- RejectController списание
- StoreController торговые точки
- UserController пользователи



### api https://backshop.boszhan.kz/api

все апи прописаны Boszhan shop.postman_collection

## Контроллеры Апи

- AuthController отвечает за авторизацию пользователей
- BrandController предоставляет список брендов
- CategoryController предоставляет список брендов
- LabelController предоставляет список этикетов
- ProductController предоставляет список продуктов
- StoreController предоставляет список ТТ
- StorageController предоставляет список складов
- InventoryController отвечает за логику инвентаризация
- MovingController отвечает за логику перемещение
- ReceiptController отвечает за логику поступления
- CounteragentController предоставляет список контрагентов
- OrderController отвечает за создание,удаление и изменение заказов
- RefundController возвраты
- RefundProducerController возвраты от производителей
- RejectController списание

## Консольные команды Artisan

- import:brand импорт брендов
- import:category импорт категории
- import:counteragent импорт контрагенто
- import:price-type  импорт категории цен
- import:productBarcode импорт штрихкодов
- import:product импорт продуктов
- import:productPriceType импорт цен
- import:user импорт пользователей
- onec:inventory  экспорт 1c 
- onec:moving  экспорт  1c
- onec:refund  экспорт 1c
- onec:reject экспорт 1c
- db:backup бекап бд
