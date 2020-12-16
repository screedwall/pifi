const HOST = 'localhost';

const express = require('express');
var fs = require('fs');
var https = require('https');
const socketIO = require('socket.io');

var options = {
    key: fs.readFileSync('/etc/letsencrypt/live/pi-fi.ru/privkey.pem'),
    cert: fs.readFileSync('/etc/letsencrypt/live/pi-fi.ru/fullchain.pem')
};

const app = express();
const server = https.Server(options, app);
const port = 3000;
const io = socketIO(server, {
    cors: {
        origin: "https://pi-fi.ru",
        methods: ["GET", "POST"]
    }
});

const redis = require('redis');
const client = redis.createClient();

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
