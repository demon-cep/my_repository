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
    <!-- ���������� ���������� jquery -->
    <script type="text/javascript" src="/chat2.0/jquery.js"></script>
    <style>
        .my_mesage{
            margin-left: 100px;
        }
    </style>
    <script type="text/javascript">

    function show() {
        // ������� ��������� � ���� #messages
        $.ajax({
            url: '/chat2.0/show.php',
            timeout: 10000, // ����� �������� �������� ��������� 10 ������ (��� 10000 �����������)
            type: 'get',
            data: {'ID_MESAGE': '<?=(int)$id_mesage?>'},
            success: function(data) {
                $('#messages').html(data);
            },
            error: function() {
                $('#messages').html("�� ������� ��������� ���������");
            }
        });
    }
    
    function send() {
        // ������� �������� ���������
        var sender = '<?=$USER->GetID()?>'; // �����������
        var message = $('#message').val(); // ���������
        
        if(sender.length > 0 && message.length > 0) { // �������� ����� "���" � "���������"
            $.ajax({
                url: '/chat2.0/send.php',
                type: 'post',
                timeout: 10000, // ����� �������� �������� ��������� 10 ���.
                data: {'sender': sender, 'message': message,'ID_MESAGE':'<?=(int)$_GET['ID_MESAGE']?>','ID_ELMENT_MESAGE':<?=(int)$_GET['ID_ELMENT_MESAGE']?>},
                success: function(data) {
                    document.getElementById('message').value = ""; // ������� ���������� ���� "���������", ���� ��������� ���� ������� ����������
                    $('#send_message_result').html("");
                    <?
                    if (!$id_mesage) {

                        ?>
                        alert('������� ������');
                        location.reload();
                        <?
                    }
                    ?>
                    

                },
                error: function() {
                    $('#send_message_result').html("�� ������� ��������� ���������");
                }
            });     
        } else if(sender.length == 0) {
            $('#send_message_result').html("������ �����������");   
        } else if(message.length == 0) {
            $('#send_message_result').html("������� ���������!");
        }
    }
    
    var interval = 1000; // ���������� ����������� ��� ����-���������� ��������� (1 ������� = 1000 �����������)
    
    show();
    
    setInterval('show()', interval);
    </script>
  </head>
  <body>
  
    <div id="messages"></div>
    
    <div class="chat-controls">
        
        <span>���������</span>
        <textarea id="message" placeholder="������� ����� ���� ���������"></textarea>
        
        <input type="submit" onclick="send();" value="��������� ���������">
        
        <div id="send_message_result"></div>
    </div>



    <?
// require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>