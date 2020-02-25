<?php

namespace reketaka\helps\modules\basket\interfaces;

interface IBasketProduct{

    public function getId();

    public function getPrice();

    public function getTitle();

    public function getBasketField($fieldName);

    public function getTotalAmount();

}