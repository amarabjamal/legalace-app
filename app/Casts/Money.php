<?php

namespace App\Casts;

use Brick\Money\Money as BrickMoney;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Money implements CastsAttributes
{
    /**
     * @var string
     */
    private $priceField;
    /**
     * @var string
     */
    private $currency;
    /**
     * @var bool
     */
    private $useMinor;

    public function __construct ($priceField = 'price', $currency = 'MYR',$useMinor = true) {
        $this->priceField = $priceField;
        $this->currency = $currency;
        $this->useMinor = $useMinor;
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (!$this->useMinor) {
            return BrickMoney::of($attributes[$this->priceField], $this->currency);
        }

        return BrickMoney::ofMinor($attributes[$this->priceField], $this->currency);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (!$value instanceof BrickMoney) {
            throw new \InvalidArgumentException(
                sprintf('value must be of type %s', \Brick\Money\Money::class)
            );
        }

        return [
            $this->priceField => $this->useMinor ? $value->getMinorAmount()->toInt() : $value->getAmount()->toFloat(),
        ];
    }
}
