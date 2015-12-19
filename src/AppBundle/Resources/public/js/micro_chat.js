/**
 * Web Sockets
 */
var webSocket = WS.connect("ws://localhost:8080");

webSocket.on("socket/connect", function(session){
    var form = $('#micro-chat-form');
    var container = $('#micro-chat-content');

    session.subscribe("messages", function(uri, payload){
        container.append(payload.msg);
        container.scrollTop(container.prop('scrollHeight'));
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
    //error provides us with some insight into the disconnection: error.reason and error.code
    console.log("Disconnected for " + error.reason + " with code " + error.code);
});