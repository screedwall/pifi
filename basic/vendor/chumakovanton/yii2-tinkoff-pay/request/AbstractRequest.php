<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 07.09.17
 * Time: 15:17
 */

namespace chumakovanton\tinkoffPay\request;

use yii\helpers\Url;

/**
 * Class AbstractRequest
 *
 *
 * @package chumakovanton\tinkoffPay\request
 */
abstract class AbstractRequest implements RequestInterface
{
    /**
     * Идентификатор терминала, выдается Продавцу Банком
     * @var string(20)
     */
    protected $_terminalKey;
    /**
     * Секретный ключ терминала
     * @var string
     */
    protected $_secretKey;

    /**
     * Массив полей запроса
     * @var array
     */
    protected $_dataFields;

    /**
     * Функция заполняет свойство $_dataFields значениями полей запроса
     */
    abstract protected function buildDataFields(): void;

    /**
     * Сериализовать объект
     * @return null|string
     */
    public function serialize(): ?string
    {
        $this->buildDataFields();

        $this->_dataFields['TerminalKey'] = $this->_terminalKey;

//        $this->_dataFields['SuccessUrl'] = Url::to(['/pay/success'], true);
//        $this->_dataFields['FailURL'] = Url::to(['/pay/success'], true);
//        $this->_dataFields['NotificationURL'] = Url::to(['/pay/success'], true);
//        $this->_dataFields['PayType'] = "O";

        unset($this->_dataFields['Token']);

        unset($this->_dataFields['DATA']);

        $this->_dataFields['Token'] = $this->_generateToken();

        unset($this->_dataFields['Password']);

        return json_encode($this->_dataFields);
    }

    /**
     * Generates token
     * @return null|string
     */
    private function _generateToken(): ?string
    {
        $token = '';
        $this->_dataFields['Password'] = $this->_secretKey;
        ksort($this->_dataFields);
        foreach ($this->_dataFields as $field) {
            $token .= $field;
        }

        return hash('sha256', $token);
    }

    /**
     * @param string $terminalKey
     * @return RequestInterface
     */
    public function setTerminalKey(string $terminalKey): RequestInterface
    {
        $this->_terminalKey = $terminalKey;
        return $this;
    }

    /**
     * @param string $secretKey
     * @return RequestInterface
     */
    public function setSecretKey(string $secretKey): RequestInterface
    {
        $this->_secretKey = $secretKey;
        return $this;
    }
}