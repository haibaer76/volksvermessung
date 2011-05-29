/* vim: set number autoindent tabstop=2 shiftwidth=2 softtabstop=2: */
// +----------------------------------------------------------------------+
// | Javascript                                                           |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt                                   |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Giuseppe Dessì<thesee@fastwebnet.it>                       |
// |                                                                      |
// | Original JavaScript code                                             |
// | Bitflux GmbH <devel@bitflux.ch>                                      |
// | http://blog.bitflux.ch/wiki/LiveSearch/#Source_Code                  |
// +----------------------------------------------------------------------+
// $Id: live.js,v 1.3 2006/05/25 09:24:41 thesee Exp $

function liveSearchHover(el)
{
    highlight = document.getElementById("LSHighlight");
    if (highlight) {
        highlight.removeAttribute("id");
    }
    el.parentNode.setAttribute("id","LSHighlight");
}

function liveSearchClicked (valore, text, elementId, realName)
{
    highlight = document.getElementById("LSHighlight");
    if (highlight) {
        highlight.removeAttribute("id");
    }
    document.getElementById(elementId).value = text;
    document.getElementById(realName).value = valore;
}

function liveSearchHide (GetResult)
{
    document.getElementById(GetResult).style.display = "none";
    highlight = document.getElementById("LSHighlight");
    if (highlight) {
        highlight.removeAttribute("id");
    }
}

function liveSearchKeyPress (Obj, event, GetResult, GetShadow, elementId , realName, SearchZero )
{
    isIE = false;
    if (navigator.userAgent.indexOf("Safari") > 0) {
        //
    } else if (navigator.product == "Gecko") {
        //
    } else {
        isIE = true;
    }
    if (event.keyCode == 37 || event.keyCode == 39) {
        liveSearchHide(GetResult);
    } else {
        document.getElementById(realName).value = '';
        if (event.keyCode == 13 ) {

            liveSearchHide(GetResult);
        }
        if (event.keyCode == 40 )
        //KEY DOWN
        {
            highlight = document.getElementById("LSHighlight");
            if (!highlight) {
                highlight = document.getElementById(GetShadow).firstChild.firstChild;
            } else {
                highlight.removeAttribute("id");
                highlight = highlight.nextSibling;
            }
            if (highlight) {
                highlight.setAttribute("id","LSHighlight");
                document.getElementById(elementId).value = highlight.firstChild.getAttribute("text");
                document.getElementById(realName).value = highlight.firstChild.getAttribute("value");
            }
        if (!isIE) { event.preventDefault(); }
        }
        //KEY UP
        else if (event.keyCode == 38 ) {
            highlight = document.getElementById("LSHighlight");
            if (!highlight) {
                highlight = document.getElementById(GetResult).firstChild.firstChild.lastChild;
            }
            else {
                highlight.removeAttribute("id");
                highlight = highlight.previousSibling;
            }
            if (highlight) {
                    highlight.setAttribute("id","LSHighlight");
                document.getElementById(elementId).value = highlight.firstChild.getAttribute("text");
                document.getElementById(realName).value = highlight.firstChild.getAttribute("value");
            }
        if (!isIE) { event.preventDefault(); }
        }
        //ESC
        else if (event.keyCode == 27) {
            highlight = document.getElementById("LSHighlight");
            if (highlight) {
                highlight.removeAttribute("id");
            }
            document.getElementById(GetResult).style.display = "none";
        } else if (Obj.value.length == 0 && SearchZero == 1) {
            searchRequest(Obj, elementId);
        } else if (Obj.value.length > 0)
            searchRequest(Obj, elementId);
    }
}