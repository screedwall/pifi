const HOST = 'localhost';

const express = require('express');
const http = require('http');
const socketIO = require('socket.io');

const app = express();
const server = http.Server(app);
const port = 3000;
const io = socketIO(server, {
    cors: {
        origin: "http://localhost",
        methods: ["GET", "POST"]
    }
});

//app.use(express.static('./public'));

const redis = require('redis');
const client = redis.createClient();

//app.get('/', (req, res) => {
//	res.sendFile(__dirname + '/public/index.html');
//});

server.listen(port, () => {
    console.log('listening on *:' + port);
});

io.on('connection', (socket) => {
    socket.user = socket.handshake.query.user;
    socket.room = socket.handshake.query.room;

    client.lrange(`chat:${socket.room}`, 0, 20, (err, res) => {
        socket.emit('prev messages', res);
    });

    socket.join(socket.room);
    socket.to(socket.room).emit('user joined', socket.user);

    socket.on('chat message', message => {
        client.lpush(`chat:${socket.room}`, `{"username":"${socket.user}", "message":"${message}"}`, (err, res) => {
            if(res > 20) client.rpop(`chat:${socket.room}`);
            client.expire(`chat:${socket.room}`, 1800);
        });
        io.to(socket.room).emit('chat message', {
            username: socket.user,
            message,
        });
    });

    socket.on('disconnect', () => {
        socket.to(socket.room).emit('user left', socket.user);
    });
});
