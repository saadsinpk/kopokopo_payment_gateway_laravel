<style>
    body.modal-open {
        overflow: hidden;
        position: fixed;
    }

    .modal-dialog {
        overflow-y: initial !important
    }

    .modal-body {
        height: 65vh;
        overflow-y: auto;
    }

    .dz-details,
    .dz-error-mark,
    .dz-progress,
    .dz-size,
    .dz-success-mark {
        display: none
    }

    .no-margin-bottom {
        margin-bottom: 0 !important
    }

    .messages-panel img.img-circle {
        border: 1px solid rgba(0, 0, 0, .1)
    }

    .medium-image {
        width: 45px;
        height: 45px;
        margin-right: 5px
    }

    .img-circle {
        border-radius: 50%
    }

    .messages-panel {
        width: 100%;
        height: calc(100vh - 150px);
        min-height: 460px;
        background-color: #fbfcff;
        display: inline-block;
        border-top-left-radius: 5px;
        margin-bottom: 0
    }

    .messages-panel img.img-circle {
        border: 1px solid rgba(0, 0, 0, .1)
    }

    .messages-panel .tab-content {
        border: none;
        background-color: transparent
    }

    .message-body {
        background-color: #fbfcff;
        width: calc(100% - 305px);
        float: right
    }

    .message-body .message-top {
        display: inline-block;
        width: 100%;
        position: relative;
        min-height: 53px;
        height: auto;
        background-color: #fff;
        border-bottom: 1px solid rgba(205, 211, 237, .5)
    }

    .message-body .message-top .new-message-wrapper {
        width: 100%
    }

    .message-body .message-top .new-message-wrapper>.form-group {
        width: 100%;
        padding: 10px 10px 0 10px;
        height: 50px
    }

    .message-body .message-top .new-message-wrapper .form-group .form-control {
        width: calc(100% - 50px);
        float: left
    }

    .message-body .message-top .new-message-wrapper .form-group a {
        width: 40px;
        padding: 6px 6px 6px 6px;
        text-align: center;
        display: block;
        float: right;
        margin: 0
    }

    .message-body .message-top>.btn {
        height: 53px;
        line-height: 53px;
        padding: 0 20px;
        float: right;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
        margin: 0;
        font-size: 15px;
        opacity: .9
    }

    .message-body .message-top>.btn.active,
    .message-body .message-top>.btn:focus,
    .message-body .message-top>.btn:hover {
        opacity: 1
    }

    .message-body .message-top>.btn>i {
        margin-right: 5px;
        font-size: 18px
    }

    .new-message-wrapper {
        position: absolute;
        max-height: 400px;
        top: 53px;
        background-color: #fff;
        z-index: 105;
        padding: 20px 15px 30px 15px;
        border-bottom: 1px solid #cfdbe2;
        border-bottom-left-radius: 3px;
        border-bottom-right-radius: 3px;
        box-shadow: 0 7px 10px rgba(0, 0, 0, .25);
        transition: .5s;
        display: none
    }

    .new-message-wrapper.closed {
        opacity: 0;
        max-height: 0
    }

    .message-chat {
        width: 100%;
        overflow: hidden
    }

    .message {
        position: relative;
        width: 100%
    }

    .message br {
        clear: both
    }

    .message .message-body {
        position: relative;
        width: auto;
        max-width: calc(100% - 100px);
        float: right;
        background-color: #fff;
        border-radius: 4px;
        border: 1px solid #dbe3e8;
        margin: 0 15px 20px 5px;
        color: #788288;
    }

    .message:after {
        content: "";
        position: absolute;
        top: 11px;
        left: auto;
        right: 63px;
        float: right;
        z-index: 100;
        border-top: 10px solid transparent;
        border-right: none;
        border-bottom: 10px solid transparent;
        border-left: 13px solid #fff
    }

    .message:before {
        content: "";
        position: absolute;
        top: 10px;
        right: 62px;
        float: right;
        z-index: 99;
        border-top: 11px solid transparent;
        border-right: none;
        border-bottom: 11px solid transparent;
        border-left: 13px solid #dbe3e8;
    }

    .message .medium-image {
        float: right;
        margin-right: 10px
    }

    .message .message-info {
        width: 100%;
        height: 22px
    }

    .message .message-info>h5>i {
        font-size: 11px;
        font-weight: 700;
        margin: 0 2px 0 0;
        color: #a2b8c5
    }

    .message .message-info>h5 {
        color: #a2b8c5;
        margin: 8px 0 0 0;
        font-size: 12px;
        float: right;
        padding-right: 10px
    }

    .message .message-info>h4 {
        font-size: 14px;
        font-weight: 600;
        margin: 7px 13px 0 10px;
        color: #65addd;
        float: left
    }

    .message hr {
        margin: 4px 2%;
        width: 96%;
        opacity: .75
    }

    .message .message-text {
        text-align: left;
        padding: 3px 13px 10px 13px;
        font-size: 14px
    }

    .message.my-message .message-body {
        float: left;
        margin: 0 5px 20px 15px
    }

    .message.my-message:after {
        content: "";
        position: absolute;
        top: 11px;
        left: 63px;
        right: auto;
        float: right;
        z-index: 100;
        border-top: 10px solid transparent;
        border-left: none;
        border-bottom: 10px solid transparent;
        border-right: 13px solid #fff
    }

    .message.my-message:before {
        content: "";
        position: absolute;
        top: 10px;
        left: 62px;
        right: auto;
        float: right;
        z-index: 99;
        border-top: 11px solid transparent;
        border-left: none;
        border-bottom: 11px solid transparent;
        border-right: 13px solid #dbe3e8
    }

    .message.my-message .medium-image {
        float: left;
        margin-left: 10px;
        margin-right: 5px
    }

    .message.my-message .message-info>h5 {
        float: right;
        padding-left: 0px;
        padding-right: 10px
    }

    .message.my-message .message-info>h4 {
        float: left
    }

    .message.sended-message .message-body {
        background-color: #2da9e9;
        border: 1px solid #2da9e9;
        color: #fff
    }

    .message.sended-message:after,
    .message.sended-message:before {
        border-left: 13px solid #2da9e9
    }

    .message.success .message-body {
        background-color: #0ec8a2;
        border: 1px solid #0ec8a2;
        color: #fff
    }

    .message.success:after,
    .message.success:before {
        border-right: 13px solid #0ec8a2
    }

    .message.warning .message-body {
        background-color: #ff9e2a;
        border: 1px solid #ff9e2a;
        color: #fff
    }

    .message.warning:after,
    .message.warning:before {
        border-right: 13px solid #ff9e2a
    }

    .message.danger .message-body {
        background-color: #f95858;
        border: 1px solid #f95858;
        color: #fff
    }

    .message.danger:after,
    .message.danger:before {
        border-right: 13px solid #f95858
    }

    .message.dark .message-body {
        background-color: #314557;
        border: 1px solid #314557;
        color: #fff
    }

    .message.dark:after,
    .message.dark:before {
        border-right: 13px solid #314557
    }

    .message.danger .message-info>h4,
    .message.dark .message-info>h4,
    .message.sended-message .message-info>h4,
    .message.success .message-info>h4,
    .message.warning .message-info>h4 {
        color: #fff
    }

    .message.danger .message-info>h5,
    .message.danger .message-info>h5>i,
    .message.dark .message-info>h5,
    .message.dark .message-info>h5>i,
    .message.sended-message .message-info>h5,
    .message.sended-message .message-info>h5>i,
    .message.success .message-info>h5,
    .message.success .message-info>h5>i,
    .message.warning .message-info>h5,
    .message.warning .message-info>h5>i {
        color: #fff;
        opacity: .9
    }

    .chat-footer {
        position: relative;
        width: 100%;
        padding: 0 80px
    }

    .chat-footer .send-message-text {
        position: relative;
        display: block;
        width: 100%;
        background-color: #fff;
        border-radius: 5px;
        padding: 5px 95px 5px 10px;
        font-size: 13px;
        resize: vertical;
        outline: 0;
        border: 1px solid #e0e6eb
    }

    .chat-footer .send-message-button {
        display: block;
        position: absolute;
        width: 35px;
        height: 35px;
        right: 100px;
        top: 0;
        bottom: 0;
        margin: auto;
        border: 1px solid rgba(0, 0, 0, .05);
        outline: 0;
        font-weight: 600;
        border-radius: 50%;
        padding: 0
    }

    .chat-footer .send-message-button>i {
        font-size: 16px;
        margin: 0 0 0 -2px
    }

    .chat-footer label.upload-file input[type=file] {
        position: fixed;
        top: -1000px
    }

    .chat-footer .upload-file {
        display: block;
        position: absolute;
        right: 150px;
        height: 30px;
        font-size: 20px;
        top: 0;
        bottom: 0;
        margin: auto;
        opacity: .25
    }

    .chat-footer .upload-file:hover {
        opacity: 1
    }

    @media screen and (max-width:767px) {
        .messages-panel {
            min-width: 0;
            display: inline-block
        }

        .chat-footer {
            margin-bottom: 20px;
            padding: 0 20px
        }

        .chat-footer .send-message-button {
            right: 40px
        }

        .chat-footer .upload-file {
            right: 90px
        }

        .message-body .message-top>.btn {
            border-radius: 0;
            width: 100%
        }
    }

    .dropzone.dz-started .dz-message {
        display: none !important;
    }
</style>

<div id="chatModal" class="modal fade dropzone image" id="image" data-field="image" tabindex="-1" role="dialog"
    aria-hidden="true">
    <input id="new-image" type="hidden" name="image">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header align-items-stretch">
                <h5 class="modal-title flex-grow-1">{{ __('Order Chat') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
            </div>
            <div class="modal-body">
                <div id="chatModal-error-message" class="d-none" style="min-height: 30vh; width: 100%">
                    <h2 class="text-center">{{ __('Failed to fetch Messages, please reload the page.') }}</h2>
                </div>
                <div class="card loader" style="height: 50vh">
                    <div class="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
                <div id="message-items" class="row message-items">

                </div>
            </div>

            @if (auth()->user()->hasRole('driver') ||
                auth()->user()->hasRole('client'))
                <div class="modal-footer image">
                    <div class="dz-message" data-dz-message></div>
                    <div id="modal-footer">
                    </div>
                    <div class="chat-footer">
                        <textarea id="text-new-message" class="send-message-text"></textarea>
                        <label class="upload-file">
                            <i class="fa fa-paperclip" style="font-size: 24px"></i>
                        </label>
                        <button id="button-send" type="button" class="send-message-button btn-info"><i
                                class="fa fa-paper-plane"></i> </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@prepend('scripts')
    {{-- dropzone --}}
    <script src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>
    <script type="text/javascript">
        Dropzone.autoDiscover = false;
        var dropzoneFields = [];
    </script>
    <script type="text/javascript">
        $(".dropzone.image").dropzone({
            url: "{{ url('api/messages') }}",
            paramName: "file",
            addRemoveLinks: true,
            autoProcessQueue: false,
            previewsContainer: "#modal-footer",
            acceptedFiles: 'image/*,application/pdf',
            maxFiles: 1,
            thumbnailHeight: 75,
            clickable: '.upload-file',
            init: function() {
                this.on('addedfile', function(file) {
                    console.log(file);
                    var imageName = document.createElement('span');
                    var html = '';
                    if (file.type == 'application/pdf') {
                        imageName.innerHTML =
                            '<i style="font-size:14px;color:red" class="fa mr-1">&#xf1c1;</i>' + file
                            .name;
                        $('#modal-footer').prepend(imageName);
                    }

                });
                this.on('removedfile', function(file) {
                    $('#modal-footer').empty();
                });

            },
        });
    </script>
@endprepend

@push('scripts')
    <script>
        var order_id;
        var interval;
        var messageItems = $('#message-items');
        var messages = [];
        var gettingMessages = false;

        $(document).on('click', '#openChat', function() {
            order_id = $(this).data('order_id');
            getMessages(true);
            interval = window.setInterval(function() {
                if (!gettingMessages) {
                    getMessages();
                }
            }, 3000);
        });

        $('#chatModal').on('hide.bs.modal', function(e) {
            clearInterval(interval);
            messageItems.html('');
            $('#chatModal-error-message').addClass('d-none');
            $('.card.loader').removeClass('d-none');
            messages = [];
        });

        function getMessages(firstRequest = false) {
            gettingMessages = true;
            $.ajax({
                url: "{{ url('api/messages') }}",
                type: 'GET',
                data: {
                    api_token: '{{ auth()->user()->api_token }}',
                    order_id: order_id,
                    last_message_datetime: (messages.length > 0 ? messages[messages.length - 1]['created_at'] : '')
                },
                success: function(data, status) {
                    addMessages(data['data'], firstRequest);
                },
                error: function(data, status, error) {
                    $('#chatModal-error-message').removeClass('d-none');
                },
                complete: function(data, status) {
                    gettingMessages = false;
                    $('.card.loader').addClass('d-none');
                }
            });
        }

        function addMessages(data, firstRequest) {
            if (data.length == 0) {
                if (firstRequest) {
                    messageItems.append(
                        '<div style="min-height: 30vh; width: 100%"><h2 class="text-center">{{ __('No messages founded') }}</h2></div>'
                    );
                }
            } else {
                if (messages.length == 0) {
                    messageItems.html('');
                }
                messages = messages.concat(data);
                var html = '';
                for (var x = 0; x < data.length; x++) {
                    if (data[x]['sender_id'] != {{ auth()->user()->id }}) {
                        html += htmlReceivedMessage(data[x]);
                    } else {
                        html += htmlMyMessage(data[x]);
                    }
                }
                messageItems.append(html);
                $('.modal-body').stop().animate({
                    scrollTop: $('.modal-body')[0].scrollHeight
                }, 800);
            }
        }

        function htmlMyMessage(data) {
            html = '<div class="message sended-message"><img alt="Imagem Recebida" class="img-circle medium-image" src="' +
                (
                    data['sender']['has_media'] ?
                    data["sender"]["media"][0]["original_url"] : '{{ asset('img/avatardefault.png') }}') +
                '"><div class="message-body"><div class="message-body-inner"><div class="message-info"><h4> Eu </h4><h5> <i class="fa fa-clock-o"></i>' +
                new Date(data[
                    'created_at'])
                .toLocaleString(
                    [], {
                        year: 'numeric',
                        month: 'numeric',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) + '</h5></div><hr><div class="message-text">' + (data["message"] != null ? data["message"] : '') +
                '</div></div>';

            if (data['has_media']) {
                html += '<a href="' + data["media"][0]["original_url"] + '" target="_blank"><img src="' + data["media"][0][
                        "original_url"
                    ] +
                    '" alt="{{ __('Sended Image') }}" class="img-thumbnail mx-auto d-block" style="max-height: 300px"></a>';
            }

            html += '</div><br> </div>';

            return html;
        }


        function htmlReceivedMessage(data) {
            html =
                '<div class="message my-message"> <img alt="{{ __('Received Image') }}" class="img-circle medium-image" src="' +
                (
                    data['sender']['has_media'] ?
                    data["sender"]["media"][0]["original_url"] : '{{ asset('img/avatardefault.png') }}') +
                '"><div class="message-body"><div class="message-body-inner"><div class="message-info"><h4> ' + data[
                    "sender"]['name'] + ' </h4><h5> <i class="fa fa-clock-o"></i>' + new Date(data['created_at'])
                .toLocaleString(
                    [], {
                        year: 'numeric',
                        month: 'numeric',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) + '</h5></div><hr><div class="message-text">' + (data["message"] != null ? data["message"] : '') +
                '</div></div>';

            if (data['has_media']) {
                html += '<a href="' + data["media"][0]["original_url"] + '" target="_blank"><img src="' + data["media"][0][
                        "original_url"
                    ] +
                    '" alt="{{ __('Received Image') }}" class="img-thumbnail mx-auto d-block" style="max-height: 300px"></a>';
            }

            html += '</div><br></div>';

            return html;
        }

        function getValues() {
            var formData = new FormData();
            var file = $(".dropzone.image")[0].dropzone.getAcceptedFiles()[0];
            formData.append("order_id", order_id);
            if (file != null) {
                formData.append('file', file);
            }
            if ($("#text-new-message").val() != null && $("#text-new-message").val() != '') {
                formData.append("message", $("#text-new-message").val());
            }

            return formData;
        }

        $("#button-send").click(function() {
            var values = getValues();
            if (values.get('order_id') != null && ((values.get('message') != null && values.get('message').length >
                    0) || values.get('file') != null)) {
                $("#text-new-message").val('');
                $('#new-image').val('');
                $(".dropzone.image")[0].dropzone.removeAllFiles();
                $.ajax({
                    url: "{{ url('api/messages') }}",
                    type: 'POST',
                    data: values,
                    processData: false,
                    contentType: false,
                    headers: {
                        'Authorization': 'Bearer {{ auth()->user()->api_token }}',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data, status) {
                        if (messages.length == 0) {
                            messageItems.html('');
                        }

                        messageItems.append(htmlMyMessage(data['data']));

                        $('.modal-body').stop().animate({
                            scrollTop: $('.modal-body')[0].scrollHeight
                        }, 800);
                    },
                });
            }

        });
    </script>
@endpush
