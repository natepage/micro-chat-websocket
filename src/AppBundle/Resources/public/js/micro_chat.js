/**
 * Web Sockets
 */
var webSocket = WS.connect("ws://localhost:8080");
var form = $('#micro-chat-form');
var container = $('#micro-chat-container');
var content = $('#micro-chat-content');
var alert = $('#micro-chat-alert');
var spinner = $('#micro-chat-spinner');

webSocket.on("socket/connect", function(session){
    spinner.addClass('hidden');
    alert.addClass('hidden');
    container.removeClass('hidden');

    session.subscribe("messages", function(uri, payload){
        content.append(payload.msg);
        content.scrollTop(content.prop('scrollHeight'));
    });

    form.submit(function(e){
        var input = $('#micro-chat-input');

        if(input.val() != ''){
            session.publish("messages", input.val());
            input.val('');
        }

        e.preventDefault();
    });
});

webSocket.on("socket/disconnect", function(error){
    var alertContent = '<div class="alert alert-warning">[ ' + error.code + ' ] ' + error.reason + '</div>';

    container.addClass('hidden');
    spinner.addClass('hidden');
    alert.html(alertContent).removeClass('hidden');
});