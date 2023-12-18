var express = require( 'express' );
var http = require( 'http' );
var app = express();
var server = http.createServer( app );
var socket = require( 'socket.io' );
var port = 8000;
var path = require('path');
var io = require( 'socket.io' )(server,{
  cors: {
    	origin: "*"
	 },
	 allowEIO3: true
});

app.use('/socket', express.static(__dirname + '/node_modules/socket.io/client-dist/'));
io.sockets.on( 'connection', function( client ) {
	client.on( 'updateToken', function( data ) {
			io.sockets.emit( 'updateToken', data );
	});
});
server.listen( port );

