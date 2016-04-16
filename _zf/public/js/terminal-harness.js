var terminalHarness = (function () {
    /**
     * Create the path to the player dir for the local filesystem
     *
     * Result should be something like: file:///Users/Ssullivan/Projects/videorx-test-harness/videorx/
     *
     * @return {[type]} [description]
     */
    var buildPathToTerminal = function () {
            var path = '';
            //path += window.location.pathname; // append current pathname (includes file test.html)
            //path = path.replace(/test.html|angular.html|scaletofit.html/,''); // remove test.html from the url
            //path += 'videorx/'; // append the rest of the path to the videorx player res dir

            console.log(path);

            return path;
        },

        /**
         * Store a reference to the player element (as a jQuery object)
         *
         * @type {Object}
         */
        $terminalElement = null,

        /**
         * Instance of the VideoRX Player
         * @type {[type]}
         */
        terminal = null,

        callTerminalMethod = function (evt) {
            evt.preventDefault();

            var methodName = $(this).html(),
                result = eval("terminal."+methodName),
                logMessage = "Called " + methodName + " on terminal";

            if(result !== undefined) {
                logMessage += " and received return value of: "+ result;
            }

            console.log(logMessage);
        },

        onEventReceivedFromTerminal = function (event, args) {
            console.log("Terminal has sent an event: " + event);
            console.log(args);
        },

        onReady = function () {},

        /**
         * Build the VideoRX Player
         * @return {[type]} [description]
         */
        buildTerminal = function () {
            // Need to cache bust..
            require.config({
                paths: {
                    "terminal": "_zf/public/js/terminal/terminal"//?cachebust=" + (new Date()).getTime()
                }
            });

            require(["terminal"], function (Terminal) {
                var terminalOptions = {

                    target: "#terminal-element"

                };

                terminal = Terminal(terminalOptions);

            });
        }

    return {
        /**
         * Player constructor
         *
         * @return {[type]} [description]
         */
        construct: function () {
            $terminalElement = $('#terminal-element');

            buildTerminal();

            require.config({
                baseUrl: buildPathToTerminal()
            });
        },

        getTerminal: function () {
            return terminal;
        }
    };
})();

$(document).ready(terminalHarness.construct);