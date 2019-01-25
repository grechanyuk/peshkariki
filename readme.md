# Peshkariki

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Для установки с помощью Composer, используйте команду:

``` bash
$ composer require grechanyuk/peshkariki
```

Далее используйте команду:

``` bash
$ php artisan vendor:publish --provider="Grechanyuk\Peshkariki\PeshkarikiServiceProvider" --tag="peshkariki.config"
```

Далее настройте файл конфигурации в <code>config/peshkariki.php</code>
Укажите логин, пароль и время забора

####Для Laravel >= 5.5 настройка закончена.

####Для Laravel 5:
Укажите в <code>config/app.php</code> в массиве <code>'providers'</code>
``` bash
Grechanyuk\Peshkariki\PeshkarikiServiceProvider:class,
```

В <code>'aliases'</code> укажите:
``` bash
'Peshkariki' => Grechanyuk\Peshkariki\Facades\Peshkariki::class,
```

Наслаждайтесь!

## Использование

Для использования пакета в вашей модели заказа необходимо наследовать интерфейс <code>Peshkaricals</code>
Например:

```
use Grechanyuk\Peshkariki\Contracts\Peshkaricals;
use Illuminate\Database\Eloquent\Model;

class Order extends Model implements Peshkaricals
{
//
}
```

Так же необходимо унаследовать интерфейс <code>PeshkaricalsProduct</code> для модели, в которой хранятся товары данного заказа, напр.

```
use Grechanyuk\Peshkariki\Contracts\PeshkaricalsProduct;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model implements PeshkaricalsProduct
{
//
}
```

Для указания точки забора необходимо унаследовать интерфейс <code>PeshkaricalsTakesPoint</code>, напр.

```
use Grechanyuk\Peshkariki\Contracts\PeshkaricalsProduct;
use Illuminate\Database\Eloquent\Model;

class TakesPoints extends Model implements PeshkaricalsTakesPoint
{
//
}
```

####Работа с заказами
#####Для получения стоимости доставки необходимо вызвать метод:

```
Peshkariki::addDeliveryRequest(Peshkaricals, PeshkaricalsTakesPoint, true);
```
Данный метод вернет стоимость доставки


#####Для создания заказа необходимо вызвать метод:
```
Peshkariki::addDeliveryRequest(Peshkaricals, PeshkaricalsTakesPoint);
```
Данный метод вернет ID заказа в пешкариках


#####Другие доступные методы:
```
Peshkariki::cancelDeliveryRequest(Peshkaricals); //Отмена заказа
Peshkariki::orderDetails(Peshkaricals); //Детали заказа
Peshkariki::checkBalance(); //Проверка баланса, бонусного счета и замороженных счетов
Peshkariki::checkTelephone($telephone); //Проверка телефона
```


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Credits

- [Egor G.][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/grechanyuk/peshkariki.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/grechanyuk/peshkariki.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/grechanyuk/peshkariki/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/grechanyuk/peshkariki
[link-downloads]: https://packagist.org/packages/grechanyuk/peshkariki
[link-travis]: https://travis-ci.org/grechanyuk/peshkariki
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/grechanyuk
[link-contributors]: ../../contributors]