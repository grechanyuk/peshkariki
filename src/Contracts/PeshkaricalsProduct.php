<?php
namespace Grechanyuk\Peshkariki\Contracts;
interface PeshkaricalsProduct {
    /**
     * Получаем название товара
     * @return string
     */
    public function getPeshkaricalsProductName();

    /**
     * Получаем стоимость товара
     * @return float
     */
    public function getPeshkaricalsProductPrice();

    /**
     * Получаем вес товара
     * @return string
     */
    public function getPeshkaricalsProductWeight();

    /**
     * Получаем количество товара
     * @return int
     */
    public function getPeshkaricalsProductQuantity();
}