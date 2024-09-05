<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Чат с телеграм</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="accordion" id="accordionExample">
        @foreach($conversations as $chatId => $chat)
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$chatId}}" aria-expanded="true" aria-controls="collapseOne">
                        Чат: {{$chatId}}
                    </button>
                </h2>
                <div id="collapse-{{$chatId}}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Сообщение</th>
                                    <th scope="col">Время</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($chat as $conversation)
                                    <tr class="fw-bold">
                                        <td>{{$conversation->message}}</td>
                                        <td>{{$conversation->updated_at ?? $conversation->created_at}}</td>
                                        <td>
                                            <button type="button" onclick="openModal({{$conversation}})" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#conversationModal">
                                                Ответить на сообщение пользователя
                                            </button>
                                        </td>
                                        <td class="fw-light fst-italic">
                                            @if($conversation->source == 'user')
                                                сообщение пользователя
                                            @endif
                                        </td>
                                    </tr>
                                    @if(!empty($conversation->replies))
                                        @foreach($conversation->replies as $adminMessage)
                                            <tr class="fw-light fst-italic">
                                               <td>{{$adminMessage->message}}</td>
                                               <td>{{$adminMessage->updated_at ?? $adminMessage->created_at}}</td>
                                                <td>
                                                    <button type="button" onclick="openModal({{$adminMessage}})" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#conversationModal">
                                                        Ответить на свое сообщение
                                                    </button>
                                                </td>
                                                <td>
                                                    @if($adminMessage->source == 'admin')
                                                        сообщение администратора
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="conversationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="conversion-modal-text"></h5>
                <button type="button" id="modal-close" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Leave a comment here" id="admin_message" style="height: 100px"></textarea>
                    <label for="admin_message">Сообщение</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="send-message" onclick="sendMessage()">Отправить</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script>
    function openModal(conversation){
        let conv = JSON.parse(JSON.stringify(conversation))
        document.getElementById('conversion-modal-text').innerText = conv['message'];
        document.getElementById('send-message').setAttribute('data-conversation', conv['id']);
    }

    function sendMessage(){
        let message = document.getElementById('admin_message').value
        let convId = document.getElementById('send-message').getAttribute('data-conversation');
        let csrf = document.querySelector('meta[name="csrf-token"]').content;


        console.log(message, convId)

        fetch("/add_message", {
            method: "POST",
            body: JSON.stringify({
                admin_message: message,
                conversation: convId
            }),
            headers: {
                "Content-type": "application/json; charset=UTF-8",
                'X-CSRF-Token': csrf
            }
        }).then(function (r) {
            if (r.status === 201) {
                document.getElementById('send-message').removeAttribute('data-conversation');
                document.getElementById('modal-close').click();
            }
        });
    }
</script>
</body>
</html>
