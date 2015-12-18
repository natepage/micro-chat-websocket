/**
 * Web Sockets
 */
var webSocket = WS.connect("ws://localhost:8080");

webSocket.on("socket/connect", function(session){
    //session is an Autobahn JS WAMP session.
    console.log("Successfully Connected!");

    session.subscribe("messages", function(uri, payload){
        console.log("Received message", payload.msg);
    });
});

webSocket.on("socket/disconnect", function(error){
    //error provides us with some insight into the disconnection: error.reason and error.code
    console.log("Disconnected for " + error.reason + " with code " + error.code);
});