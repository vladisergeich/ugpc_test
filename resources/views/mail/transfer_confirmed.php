<html>
<body>
  <p>Создано новое перемещение:</p>
  <ul>
    <li><strong>Отправитель:</strong> {{ $transfer->sender->name }}</li>
    <li><strong>Получатель:</strong> {{ $transfer->recipient->name }}</li>
    <li><strong>Номер заявки:</strong> {{ $transfer->id }}</li>
    <li><strong>Количество валов:</strong> {{ $count }}</li>
  </ul>
</body>
</html>