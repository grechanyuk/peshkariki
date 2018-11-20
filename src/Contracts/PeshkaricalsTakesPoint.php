<?php
namespace Grechanyuk\Peshkariki\Contracts;

interface PeshkaricalsTakesPoint {
    /**
     * Получаем имя отправителя
     */
    public function getPeshkaricalsTakesPointName();

    /**
     * Получаем телефон отправителя
     */
    public function getPeshkaricalsTakesPointTelephone();

    /**
     * Получаем пригород отправителя, если false - отправлено не будет
     */
    public function getPeshkaricalsTakesPointSuburb();

    /**
     * Получаем улицу отправителя
     */
    public function getPeshkaricalsTakesPointStreet();

    /**
     * Получаем номер дома отправителя
     */
    public function getPeshkaricalsTakesPointBuilding();

    /**
     * Получаем квартиру отправителя
     */
    public function getPeshkaricalsTakesPointApartment();

    /**
     * Получаем ID станции метро отправителя, если false - отправлено не будет
     */
    public function getPeshkaricalsTakesPointSubwayId();

    /**
     * Получаем комментарий отправителя для курьера
     */
    public function getPeshkaricalsTakesPointComment();
}