<?php

return [

    /*
    |----------------------------------------------------------------------------
    | Логин в пешкариках
    |----------------------------------------------------------------------------
    */
    'login' => '',

    /*
   |----------------------------------------------------------------------------
   | Пароль в пешкариках
   |----------------------------------------------------------------------------
   */
    'password' => '',

    /*
   |----------------------------------------------------------------------------
   | Код платежной системы. Необходим, если курьер получит наличные за товар
   |----------------------------------------------------------------------------
   */
    'ewalletType' => '',

    /*
   |----------------------------------------------------------------------------
   | Реквизиты, на которые необходимо перечислить
   | полученные деньги. (номер банковской карты, номер кошелька
   | яндекс.денег и т.д.)
   |----------------------------------------------------------------------------
   */
    'ewallet' => '',

    /*
   |----------------------------------------------------------------------------
   | Время, с которого можно забирать товар с точки забора
   | В формате H:i:s
   |----------------------------------------------------------------------------
   */
    'time_from' => '09:00:00',

    /*
   |----------------------------------------------------------------------------
   | Время, до которого можно забирать товар с точки забора
   | В формате H:i:s
   |----------------------------------------------------------------------------
   */
    'time_to' => '18:00:00'
];
