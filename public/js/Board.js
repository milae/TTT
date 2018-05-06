var Board = (function () {
    "use strict";

    function Board(statusArea) {
        this.statusArea = statusArea;
        this.players = [];
        this.ready = false;
        this.init();
    }

    Board.prototype.showWinner = function (player) {
        alert(player.name + " hat gewonnen!");
        this.disableAll();
    };

    Board.prototype.showDraw = function () {
        alert("Wir haben ein Unentschieden!");
    };

    Board.prototype.enableAll = function () {
        $("td").addClass("active");
    };

    Board.prototype.disableAll = function () {
        $("td").removeClass("active");
    };

    Board.prototype.init = function () {
        //TODO TEIL 3: registrieren Sie einen Click-Eventhandler auf den einzelnen
        //Tabellenzellen und rufen Sie mark() auf
        var boardThis = this;
        $("td").on(function (event) {
            boardThis.mark(event);
        });
    };

    Board.prototype.myTurn = function () {
        this.enableAll();
    };

    Board.prototype.mark = function (event) {
        var $square = $(event.target);

        if (this.ready && $square.hasClass("active")) {
            $square.removeClass("active");
            var sign = this.getCurrentPlayer();
            $square.append("<div class=" + sign + "-label" + ">" + sign + "</div>");
            var playerId = parseInt($(".current-player").data("playerid"));
            var cellId = parseInt($square.attr("id"));
            this.onMark(playerId, cellId);
            this.disableAll();
        }
    };

    Board.prototype.showCurrentPlayer = function (player) {
        this.statusArea.forEach(function (sb) {
            sb.removeClass("current-player");
        });
        this.statusArea[player.id].addClass("current-player");
    };

    Board.prototype.getCurrentPlayer = function () {
        return $(".current-player").data("sign");
    };

    Board.prototype.showMark = function (cellId, sign) {
        var $square = $("#" + cellId);
        $square.append("<div class=" + sign + "-label" + ">" + sign + "</div>");
    };

    Board.prototype.getCellContent = function (cellId) {
        return $("td").get(cellId).textContent;
    };

    Board.prototype.isNewPlayer = function(player) {
        return this.players.filter(function (p) {
            return p.id == player.id;
        }).length === 0;
    };

    Board.prototype.addPlayer = function (player) {
        if (this.players.length < 2) {
            if (this.isNewPlayer(player)) {
                this.players.push(player);
                this.ready = this.players.length === 2;

                var $playerScore = this.statusArea[this.players.length - 1];
                if (this.players.length === 1) {
                    $playerScore.html(player.sign + " " + player.name);
                } else {
                    $playerScore.html(player.name + " " + player.sign);
                }
                $playerScore.data("sign", player.sign);
                $playerScore.data("playerid", player.id);

                if (this.ready) {
                    this.enableAll();
                }
            }
        }
    };

    return Board;
})();
