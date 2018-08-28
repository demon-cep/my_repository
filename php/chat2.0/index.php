<?
include_once($_SERVER['DOCUMENT_ROOT'].'/class/composer.php');
// require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
 global $USER;
?>
 <?
 if (AllFunction::get_mesage_to_element_id($_GET['ID_ELMENT_MESAGE'])) {
     $id_mesage=AllFunction::get_mesage_to_element_id($_GET['ID_ELMENT_MESAGE']);
    
 }

 if (isset($_GET['ID_MESAGE'])) {
      $id_mesage=$_GET['ID_MESAGE'];
 }
  ?>
    <!-- подключаем библиотеку jquery -->
    <script type="text/javascript" src="/chat2.0/jquery.js"></script>
    <style>
        .my_mesage{
            margin-left: 100px;
        }
    </style>
    <script type="text/javascript">

    function show() {
        // выводим сообщения в блок #messages
        $.ajax({
            url: '/chat2.0/show.php',
            timeout: 10000, // время ожидания загрузки сообщений 10 секунд (или 10000 миллисекунд)
            type: 'get',
            data: {'ID_MESAGE': '<?=(int)$id_mesage?>'},
            success: function(data) {
                $('#messages').html(data);
            },
            error: function() {
                $('#messages').html("Не удалось загрузить сообщения");
            }
        });
    }
    
    function send() {
        // функция отправки сообщения
        var sender = '<?=$USER->GetID()?>'; // отправитель
        var message = $('#message').val(); // сообщение
        
        if(sender.length > 0 && message.length > 0) { // проверка полей "Имя" и "Сообщение"
            $.ajax({
                url: '/chat2.0/send.php',
                type: 'post',
                timeout: 10000, // время ожидания отправки сообщения 10 сек.
                data: {'sender': sender, 'message': message,'ID_MESAGE':'<?=(int)$_GET['ID_MESAGE']?>','ID_ELMENT_MESAGE':<?=(int)$_GET['ID_ELMENT_MESAGE']?>},
                success: function(data) {
                    document.getElementById('message').value = ""; // удаляем содержимое поля "Сообщение", если сообщение было успешно отправлено
                    $('#send_message_result').html("");
                    <?
                    if (!$id_mesage) {

                        ?>
                        alert('Создана беседа');
                        location.reload();
                        <?
                    }
                    ?>
                    

                },
                error: function() {
                    $('#send_message_result').html("Не удалось отправить сообщение");
                }
            });     
        } else if(sender.length == 0) {
            $('#send_message_result').html("Ошибка авторизации");   
        } else if(message.length == 0) {
            $('#send_message_result').html("Введите сообщение!");
        }
    }
    
    var interval = 1000; // количество миллисекунд для авто-обновления сообщений (1 секунда = 1000 миллисекунд)
    
    show();
    
    setInterval('show()', interval);
    </script>
  </head>
  <body>
  
    <div id="messages"></div>
    
    <div class="chat-controls">
        
        <span>Сообщение</span>
        <textarea id="message" placeholder="Введите здесь ваше сообщение"></textarea>
        
        <input type="submit" onclick="send();" value="Отправить сообщение">
        
        <div id="send_message_result"></div>
    </div>



    <?
// require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>