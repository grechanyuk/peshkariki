<?php
namespace Grechanyuk\Peshkariki\Contracts;

interface Peshkaricals {
    /**
     * Получаем ID заказа
     */
    public function getPeshkaricalsOrderId();

    /**
     * Получаем краткое описание к заказу, если false - комментарий не будет указан
     */
    public function getPeshkaricalsShortDescription();

    /**
     * Получаем название пригорода, если false - комментарий не будет указан
     */
    public function getPeshkaricalsSuburb();

    /**
     * Получаем имя покупателя
     */
    public function getPeshkaricalsClientName();

    /**
     * Получаем телефон покупателя
     */
    public function getPeshkaricalsClientTelephone();

    /**
     * Получаем номер дома покупателя
     */
    public function getPeshkaricalsClientBuilding();

    /**
     * Получаем номер квартиры покупателя
     */
    public function getPeshkaricalsClientApartment();

    /**
     * Получаем станцию метро покупателя, если false - параметр указан не будет
     */
    public function getPeshkaricalsClientSubwayId();

    /**
     * Получаем время с кторого покупатель будет ждать заказ, если false - параметр указан не будет
     */
    public function getPeshkaricalsClientTimeFrom();

    /**
     * Получаем время до которого покупатель будет ждать заказ, если false - параметр указан не будет
     */
    public function getPeshkaricalsClientTimeTo();

    /**
     * Получаем комментарий для курьера, если false - параметр указан не будет
     */
    public function getPeshkaricalsCommentToCourier();

    /**
     * Получаем сумму, которую курьер должен вернуть продавцу, если false - параметр указан не будет
     */
    public function getPeshkaricalsReturnMoneyAmount();

    /**
     * Получаем ID города, передаваемый в Пешкарики
     * @return integer
     */
    public function getPeshkaricalsCityId();

    /**
     * Получаем улицу покупателя
     * @return string
     */
    public function getPeshkaricalsClientStreet();

    /**
     * Получаем товары в заказе
     * @return array
     */
    public function getPeshkaricalsProducts();

    /**
     * Получаем пешкариковский ID заказа
     * @return integer
     */
    public function getPeshkaricalsOrderIdP();
}