# Отправка sms через сервис sms-ru
- Для отправки создан класс Handler, который при создании экземпляра класса
принимает на вход api-key в виде строки
- У класса есть единственный публичный метод, с помощью которого отправляется сообщение:

```
$handler = new Handler($apiKey);

$responseAsJson = $handler->sendSms(string $text, array $to);
```