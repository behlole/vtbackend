const app = require('express')();
const http = require('http').createServer(app);
const io = require('socket.io')(http, {
    cors: {
        origins: ['http://vt.qalamguru.com']
    }
});

app.get('/', (req, res) => {
    res.send('<h1>Hey Socket.io</h1>');
});

io.on('connection', (socket) => {
    console.log("socket connected");
    socket.on('open-mic', (data) => {
        console.log(data);
    });
    socket.on('disconnect', () => {
        console.log('socket disconnected');
    });

    //MIC FUNCTIONALITY
    /*
        1. Turn on Mic
     */
    socket.on('turn-mic-on', function (email) {
        db.query('select role_id from vt_users where email=' + email).on('result', function (result) {
            db.query('update vt_students_checks set mic=1 where student_id=' + result)
                .on('result', function (data) {

                    //Notify Change
                    socket.emit('return-student-checks', data);
                })
                .on('end', function () {
                    // Only emit notes after query has been completed
                    // socket.emit('initial notes', notes)
                })
        });
    });

    /*
        2. Turn Mic Off
     */

    socket.on('turn-mic-off', function (email) {
        db.query('select role_id from vt_users where email=' + email).on('result', function (result) {
            db.query('update vt_students_checks set mic=0 where student_id=' + result)
                .on('result', function (data) {

                    //Notify Change
                    socket.emit('return-student-checks', data);
                })
                .on('end', function () {
                    // Only emit notes after query has been completed
                    // socket.emit('initial notes', notes)
                })
        });
    });


    /*
        Turn Video On
     */
    socket.on('turn-video-on', function (email) {
        db.query('select role_id from vt_users where email=' + email).on('result', function (result) {
            db.query('update vt_students_checks set video=1 where student_id=' + result)
                .on('result', function (data) {

                    //Notify Change
                    socket.emit('return-student-checks', data);
                })
                .on('end', function () {
                    // Only emit notes after query has been completed
                    // socket.emit('initial notes', notes)
                })
        });
    });

    /*
        Turn Video off
     */
    socket.on('turn-video-on', function (email) {
            db.query('update vt_student_checks set video=1 where student_id=' + email)
                .on('result', function (data) {

                    //Notify Change
                    socket.emit('return-student-checks', data);
                    socket.emit('toggle-video', data);
                    console.log("Video turuned on");
                })
                .on('end', function () {
                    // Only emit notes after query has been completed
                    // socket.emit('initial notes', notes)
                })
    });

    /*
        Turn Screen ON
     */

    socket.on('turn-screen-on', function (email) {
        db.query('select role_id from vt_users where email=' + email).on('result', function (result) {
            db.query('update vt_students_checks set screen=1 where student_id=' + result)
                .on('result', function (data) {

                    //Notify Change
                    socket.emit('return-student-checks', data);
                })
                .on('end', function () {
                    // Only emit notes after query has been completed
                    // socket.emit('initial notes', notes)
                })
        });
    });

    /*
        Turn Screen Off
     */

    socket.on('turn-screen-off', function (email) {
        db.query('select role_id from vt_users where email=' + email).on('result', function (result) {
            db.query('update vt_students_checks set screen=0 where student_id=' + result)
                .on('result', function (data) {

                    //Notify Change
                    socket.emit('return-student-checks', data);
                })
                .on('end', function () {
                    // Only emit notes after query has been completed
                    // socket.emit('initial notes', notes)
                })
        });
    });


    socket.on('fetch-student-check', function (email) {
        db.query(`select role_id from vt_users where email='${email}'`).on('result', function (result) {
            db.query('SELECT * FROM vt_student_checks where student_id=' + result.role_id)
                .on('result', function (data) {
                    socket.emit('return-student-checks', data);
                    console.log(data);
                })
                .on('end', function () {
                    // Only emit notes after query has been completed
                    // socket.emit('initial notes', notes)
                })
        });

    });

});
const mysql = require('mysql');
var db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    database: 'VT'
})
db.connect(function (err) {
    if (err) console.log(err)
})
http.listen(3000, () => {
    console.log('listening on *:3000');
});

