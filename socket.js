
var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');
var redis = new Redis();

var socketioJwt = require("socketio-jwt";)

redis.subscribe('test-channel');

io.use(socketioJwt.authorize({
  secret: 'your secret or public key',
  handshake: true
}));

redis.on('message', function(channel, message){
  message = JSON.parse(message);
  console.log(message);
  console.log(channel);
  // io.emit(channel + ':' + message.event, message.data);
  // io.emit("testEmit", message.data);

  io.emit(channel + ':' + message.event, message.data);

})

server.listen(3000);