(function () {
    "use strict";

    var socket = new WebSocket("ws://localhost:6502");

    var events = {
        in: {
            JOIN_GAME: "s2cJoinGame",
            MARK: "s2cMark",
            SET_TURN: "s2cSetTurn",
            GAME_OVER: "s2cGameOver",
            ERROR: "s2cError",
            QUIT: "s2cQuit"
        },
        out: {
            JOIN_GAME: "c2sJoinGame",
            MARK: "c2sMark",
            QUIT: "c2sQuit"
        }
    };

    var board;
    var statusArea;
    var thisPlayer = {};

    $(document).ready(function () {
        $("#game-section").hide();
        statusArea = [$("#player1"), $("#player2")];
        board = new Board(statusArea);
        board.onMark = function (playerId, cellId) {
            socket.send(makeMessage(events.out.MARK, {playerId: playerId, cellId: cellId}));
        };
        //TODO TEIL 3: registrieren Sie einen Click-Eventhandler auf den Start-Button
        $("#start-button").on("click", onStartClicked);
        });

    function onStartClicked() {
        //TODO TEIL 3: Lesen Sie den Namen des Spielers aus, verschicken Sie den
        //Event out.JOIN_GAME und machen Sie das Formular unsichtbar
        thisPlayer.name = $("#nickname").val();
            socket.send(makeMessage(events.out.JOIN_GAME, {playerName: thisPlayer.name}));
        $("#username-section").hide();
    }

    function makeMessage(action, data) {
        var response = {
            action: action,
            data: data
        };
        return JSON.stringify(response);
    }

    function startGame() {
        if (board.players.length === 1) {
            statusArea[1].html("warte auf Gegner...");
        }
        //TODO TEIL 3: Zeigen Sie das Spielfeld an

        $("game-section").show();
        board.disableAll();
    }

    socket.onmessage = function (event) {
        console.log(">>> received message: " + event.data);
        var msg = JSON.parse(event.data);

        switch (msg.action) {
            case events.in.ERROR:
                alert("Error: " + msg.data);
                break;
            case events.in.JOIN_GAME:
                board.addPlayer(msg.data);
                if (msg.data.name === thisPlayer.name) {
                    thisPlayer = msg.data;
                    startGame();
                }
                break;
            case events.in.MARK:
                board.doMark(msg.data.cellId, msg.data.player.sign);
                break;
            case events.in.SET_TURN:
                board.showCurrentPlayer(msg.data);
                board.ready = true;
                if (msg.data.id === thisPlayer.id) {
                    board.myTurn();
                }
                break;
            case events.in.GAME_OVER:
                if (msg.data.player) {
                    board.showWinner(msg.data.player);
                } else {
                    board.showDraw();
                }
                socket.send(makeMessage(events.out.QUIT, thisPlayer.id));
                break;
            case events.in.QUIT:
                socket.close();
                break;
        }
    };
})();