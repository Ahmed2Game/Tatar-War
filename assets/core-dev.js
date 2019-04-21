// using
//  http://closure-compiler.appspot.com/home
// for compression

var ld;									// fetched date at page loading
var gt;									// global timer handler
var mreq = true;
var elems = new Array();
var felems = new Array();
var _tt = null;
var _frame;
var _flag = 1;
var _vflag = false;

function _(id) {
    return document.getElementById(id);
}

function _tcls(elem, cls) {
    var exists = false;
    var newClass = '';
    var arr = elem.getAttribute("class").split(" ");
    for (var i = 0, count = arr.length; i < count; i++) {
        if (arr[i] != cls) {
            if (newClass != "") {
                newClass += " ";
            }
            newClass += arr[i];
        } else {
            exists = true;
        }
    }

    elem.setAttribute("class", newClass + (exists ? '' : " " + cls));
}

function _rcls(elem, cls) {
    var newClass = '';
    var arr = elem.getAttribute("class").split(" ");
    for (var i = 0, count = arr.length; i < count; i++) {
        if (arr[i] != cls) {
            if (newClass != "") {
                newClass += " ";
            }
            newClass += arr[i];
        }
    }

    elem.setAttribute("class", newClass);
}

function init() {
    ld = new Date().getTime();

    // adjust the button image
    var imgElements = document.getElementsByTagName("input");
    for (var i = 0, count = imgElements.length; i < count; i++) {
        var curElement = imgElements[i];
        if (curElement.getAttribute("type") == "image" && curElement.className == "dynamic_img") {
            curElement.onmouseover = function () {
                this.className = "dynamic_img over";
            };
            curElement.onmouseout = function () {
                this.className = "dynamic_img";
            };
            curElement.onmousedown = function () {
                this.className = "dynamic_img clicked";
            };
        }
    }

    // adjust the table highlight
    var tElements = document.getElementsByTagName("table");
    for (var i = 0, count = tElements.length; i < count; i++) {
        var curElement = tElements[i];
        if (curElement.hasAttribute("class") && curElement.getAttribute("class").indexOf("row_table_data") > -1) {
            trs = curElement.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
            for (var j = 0, count2 = trs.length; j < count2; j++) {
                trs[j].onmouseover = function () {
                    this.setAttribute("class", this.getAttribute("class") + " hlight");
                };
                trs[j].onmouseout = function () {
                    _rcls(this, "hlight");
                };
                trs[j].onmousedown = function () {
                    _tcls(this, "marked");
                };
            }
        }
    }

    // initialize the field elements
    felems = new Array();
    for (var i = 1; i < 5; i++) {
        var curElement = _("l" + i);
        if (curElement == null) {
            continue;
        }
        var farr = curElement.innerHTML.split("/");

        felems.push({
            "e": curElement,
            "r": parseFloat(curElement.getAttribute("title")),		// rate
            "cv": parseInt(farr[0]),		// current value
            "v": parseInt(farr[0]),		// start value
            "x": parseInt(farr[1])		// max limit
        });
    }

    // initialize the timer elements
    elems = new Array();
    var labels = document.getElementsByTagName("span");
    for (var i = 0, count = labels.length; i < count; i++) {
        var curElement = labels[i];
        if (curElement.getAttribute("id") != "timer1" && curElement.getAttribute("id") != "timer2") {
            continue;
        }

        var tarr = curElement.innerHTML.split(":");
        if (isNaN(tarr[2])) {
            continue;
        }

        var totalSeconds = new Number(tarr[0]) * 3600 + new Number(tarr[1]) * 60 + new Number(tarr[2]);
        elems.push({
            "e": curElement,
            "s": totalSeconds,
            "f": (curElement.getAttribute("id") == "timer1") ? -1 : 1
        });
    }

    // start the render timer
    gt = window.setInterval(render, 1000);
}

function render() {
    var elapsedSeconds = parseInt((new Date().getTime() - ld) / 1000);

    // update the resource fields
    for (var i = 0, count = felems.length; i < count; i++) {
        var curElem = felems[i];
        var newValue = Math.floor(curElem["v"] + parseFloat((elapsedSeconds / 3600.0) * curElem["r"]));
        if (newValue > curElem["x"]) {
            newValue = curElem["x"];
        }
        curElem["cv"] = newValue;

        curElem["e"].innerHTML = newValue + "/" + curElem["x"];
    }

    // update the timer labels
    for (var i = 0, count = elems.length; i < count; i++) {
        var curElem = elems[i];
        var totalSeconds = curElem["s"];
        var remainingSeconds = totalSeconds + elapsedSeconds * curElem["f"];
        if (remainingSeconds < 0) {
            window.clearInterval(gt);
            document.location.reload();
            break;
        }

        var h = Math.floor(remainingSeconds / 3600);
        var m = Math.floor((remainingSeconds % 3600) / 60);
        var s = Math.floor(remainingSeconds % 60);

        curElem["e"].innerHTML = h + ":" + ((m < 10) ? "0" : "") + m + ":" + ((s < 10) ? "0" : "") + s;
    }
}

function setLang(lng) {
    document.cookie = 'lng=' + lng + '; expires=Wed, 1 Jan 2250 00:00:00 GMT';
}

function toggleLevels() {
    var e1 = _("lswitch");
    var e2 = _("levels");
    var isOn = (e1.className == "on");
    e1.className = e2.className = isOn ? "" : "on";

    document.cookie = (isOn ? 'lvl=0' : 'lvl=1') + '; expires=Wed, 1 Jan 2250 00:00:00 GMT';
}

function showManual(c, id) {
    p = document.getElementById("ce");
    if (p != null) {
        p.innerHTML = '<div id="_pwin" class="popup3"><div id="drag" onmousedown="dragStart(event, \'_pwin\')"></div><a href="#" onClick="hideManual(); return false;"><img src="assets/x.gif" border="1" class="popup4" alt="Move"></a><iframe frameborder="0" id="Frame" src="help.php?c=' + c + '&id=' + id + '" width="412" height="440" border="0"></iframe></div>';
    }

    return false;
}

function hideManual() {
    p = document.getElementById("ce");
    if (p != null) {
        p.innerHTML = '';
    }
}

function showInfo(x, y) {
    var e = _mp["mtx"][x][y], p = e[5], o = e[6];
    _("map_infobox").setAttribute("class", (p ? "village" : "oasis_empty"));
    _("mbx_11").innerHTML = "-";
    _("mbx_12").innerHTML = "-";
    _("mbx_13").innerHTML = "-";
    if (p) {
        _("mbx_1").innerHTML = o ? textb.t3 : '<span class="tribe tribe' + e[7] + '">' + e[10] + '</span>';
        _("mbx_11").innerHTML = e[9];
        _("mbx_12").innerHTML = o ? '-' : e[8];
        _("mbx_13").innerHTML = e[11] != '' ? e[11] : '-';
    } else {
        _("mbx_1").innerHTML = o ? textb.t4 : textb.t2 + " " + textb.f[e[7]];
    }
}

function hideInfo() {
    _("map_infobox").setAttribute("class", "default");
    _("mbx_1").innerHTML = textb.t1;
    _("mbx_11").innerHTML = "-";
    _("mbx_12").innerHTML = "-";
    _("mbx_13").innerHTML = "-";
}

function createRequestObject() {
    var http = null;
    try {
        http = new XMLHttpRequest();
    } catch (e) {
        try {
            http = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            http = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }

    return http;
}

function renderMap(obj, largeMap) {
    if (!mreq) {
        return false;
    }

    var xmlHttp = createRequestObject();

    var id = obj.getAttribute("vid");
    var url = "map?id=" + id + (largeMap ? '&l' : '');

    if (xmlHttp == null) {
        window.location = url;
        mreq = true;
        return false;
    }

    mreq = false;
    url += "&_a1_";
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            mreq = true;
            if (xmlHttp.responseText.length > 0) {
                eval(xmlHttp.responseText);
                _("x").innerHTML = _mp["x"];
                _("y").innerHTML = _mp["y"];
                _("mcx").setAttribute("value", _mp["x"]);
                _("mcy").setAttribute("value", _mp["y"]);
                _("ma_n1").setAttribute("vid", _mp["n1"]);
                _("ma_n2").setAttribute("vid", _mp["n2"]);
                _("ma_n3").setAttribute("vid", _mp["n3"]);
                _("ma_n4").setAttribute("vid", _mp["n4"]);
                _("ma_n1p7").setAttribute("vid", _mp["n1p7"]);
                _("ma_n2p7").setAttribute("vid", _mp["n2p7"]);
                _("ma_n3p7").setAttribute("vid", _mp["n3p7"]);
                _("ma_n4p7").setAttribute("vid", _mp["n4p7"]);

                for (var i = 0, count = _mp["mtx"].length; i < count; i++) {
                    var _mpa = _mp["mtx"][i];
                    for (var j = 0, count1 = _mpa.length; j < count1; j++) {
                        var _mpb = _mpa[j];
                        _("i_" + i + "_" + j).setAttribute("class", _mpb[3]);
                        var ea = _("a_" + i + "_" + j);
                        ea.setAttribute("title", _mpb[4]);
                        ea.setAttribute("href", "village3?id=" + _mpb[0]);

                        if (i == 0) {
                            _("my" + j).innerHTML = _mpb[2];
                        }
                        if (j == 0) {
                            _("mx" + i).innerHTML = _mpb[1];
                        }
                    }
                }
            }
        }
    };

    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
    return false;
}

function slm() {
    var w = window.open("map?l&id=" + _mp["mtx"][3][3][0], "map", "top=100,left=25,width=1007,height=585");
    w.focus();
    return false;
}

function add_res(id) {
    set_res(id, _("r" + id).value + carry);
}

function upd_res(id, max) {
    set_res(id, max ? merchNum * carry : isNaN(_("r" + id).value) ? 0 : _("r" + id).value);
}

function set_res(id, v) {
    if (v > felems[4 - id]["cv"]) {
        v = felems[4 - id]["cv"];
    }
    if (v > merchNum * carry) {
        v = merchNum * carry;
    }

    if (v == 0) {
        v = "";
    }
    _("r" + id).value = v;
}

// Determine browser and version.

function Browser() {
    var ua, s, i;

    this.isIE = false;
    this.isNS = false;
    this.version = null;

    ua = navigator.userAgent;

    s = "MSIE";
    if ((i = ua.indexOf(s)) >= 0) {
        this.isIE = true;
        this.version = parseFloat(ua.substr(i + s.length));
        return;
    }

    s = "Netscape6/";
    if ((i = ua.indexOf(s)) >= 0) {
        this.isNS = true;
        this.version = parseFloat(ua.substr(i + s.length));
        return;
    }

    // Treat any other "Gecko" browser as NS 6.1.
    s = "Gecko";
    if ((i = ua.indexOf(s)) >= 0) {
        this.isNS = true;
        this.version = 6.1;
        return;
    }
}

var browser = new Browser();

// Global object to hold drag information.
var dragObj = new Object();
dragObj.zIndex = 0;

function dragStart(event, id) {
    var el;
    var x, y;

    // If an element id was given, find it. Otherwise use the element being clicked on.
    if (id)
        dragObj.elNode = document.getElementById(id);
    else {
        if (browser.isIE)
            dragObj.elNode = window.event.srcElement;
        if (browser.isNS)
            dragObj.elNode = event.target;

        // If this is a text node, use its parent element.
        if (dragObj.elNode.nodeType == 3)
            dragObj.elNode = dragObj.elNode.parentNode;
    }

    // Get cursor position with respect to the page.
    if (browser.isIE) {
        x = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
        y = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
    }
    if (browser.isNS) {
        x = event.clientX + window.scrollX;
        y = event.clientY + window.scrollY;
    }

    // Save starting positions of cursor and element.
    dragObj.cursorStartX = x;
    dragObj.cursorStartY = y;
    //dragObj.elStartLeft  = parseInt(dragObj.elNode.style.left, 10);
    dragObj.elStartLeft = parseInt(dragObj.elNode.style.right, 10);
    dragObj.elStartTop = parseInt(dragObj.elNode.style.top, 10);

    if (isNaN(dragObj.elStartLeft)) dragObj.elStartLeft = d3l;
    if (isNaN(dragObj.elStartTop)) dragObj.elStartTop = 99;

    // Update element's z-index.
    dragObj.elNode.style.zIndex = ++dragObj.zIndex;

    // Capture mousemove and mouseup events on the page.
    if (browser.isIE) {
        document.attachEvent("onmousemove", dragGo);
        document.attachEvent("onmouseup", dragStop);
        window.event.cancelBubble = true;
        window.event.returnValue = false;
    }
    if (browser.isNS) {
        document.addEventListener("mousemove", dragGo, true);
        document.addEventListener("mouseup", dragStop, true);
        event.preventDefault();
    }
}

function dragGo(event) {
    var x, y;

    // Get cursor position with respect to the page.
    if (browser.isIE) {
        x = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
        y = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
    }
    if (browser.isNS) {
        x = event.clientX + window.scrollX;
        y = event.clientY + window.scrollY;
    }

    // Move drag element by the same amount the cursor has moved.
    //dragObj.elNode.style.left = (dragObj.elStartLeft + x - dragObj.cursorStartX) + "px";
    dragObj.elNode.style.right = (dragObj.elStartLeft - x + dragObj.cursorStartX) + "px";
    dragObj.elNode.style.top = (dragObj.elStartTop + y - dragObj.cursorStartY) + "px";

    if (browser.isIE) {
        window.event.cancelBubble = true;
        window.event.returnValue = false;
    }
    if (browser.isNS)
        event.preventDefault();
}

function dragStop(event) {
    // Stop capturing mousemove and mouseup events.
    if (browser.isIE) {
        document.detachEvent("onmousemove", dragGo);
        document.detachEvent("onmouseup", dragStop);
    }
    if (browser.isNS) {
        document.removeEventListener("mousemove", dragGo, true);
        document.removeEventListener("mouseup", dragStop, true);
    }
}

function showTask() {
    if (_tt != null) {
        return;
    }

    var obj = _("anm");
    obj.style.visibility = "visible";
    if (_flag == 1) {
        _frame = {
            'right': 0,
            'top': 25,
            'width': 118,
            'height': 142
        };
    } else {
        var p = _("ce");
        if (p != null) {
            p.innerHTML = '';
        }
    }

    _tt = window.setInterval(renderTask, browser.isIE ? 5 : 10, new Date);
}

function renderTask() {
    var obj = _("anm");
    _frame.right -= 22 * _flag;
    if (_frame.right < -700) {
        _frame.right = -700;
    }
    if (d3l > 0) {
        obj.style.right = _frame.right + "px";
    } else {
        obj.style.left = _frame.right + "px";
    }
    _frame.top -= 3 * _flag;
    if (_frame.top < -70) {
        _frame.top = -70;
    }
    obj.style.top = _frame.top + "px";
    _frame.width += 10 * _flag;
    if (_frame.width > 430) {
        _frame.width = 430;
    }
    obj.style.width = _frame.width + "px";
    _frame.height += 7 * _flag;
    if (_frame.height > 456) {
        _frame.height = 456;
    }
    obj.style.height = _frame.height + "px";

    if ((_frame.right == -700 && _frame.top == -70 && _frame.width == 430 && _frame.height == 456) || _frame.right >= 25) {
        window.clearInterval(_tt);
        _flag *= -1;
        obj.style.visibility = "hidden";

        if (_flag == -1) {
            goto_guide();
        } else {
            if (_vflag) {
                goto_guide('f');
            } else {
                _tt = null;
            }
        }
    }
}

function goto_guide(value) {
    var p = _("ce");
    if (p != null) {
        if (!_vflag) {
            p.innerHTML = '<div id="_pwin" class="popup3 quest"><div id="drag" onmousedown="dragStart(event, \'_pwin\')"></div><a href="#" onClick="showTask();return false;"><img src="assets/x.gif" border="1" class="popup4" alt="Move"></a><img src="assets/default/plus/loading.gif" width="48" height="48" alt="loading"></div>';
        }

        var xmlHttp = createRequestObject();
        xmlHttp.open('get', 'guide' + (value == undefined ? '' : '?v=' + value));
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4) {
                if (xmlHttp.status == 200 && _flag == -1 && !_vflag) {
                    if (xmlHttp.responseText != '') {
                        p.innerHTML = '<div id="_pwin" class="popup3 quest"><div id="drag" onmousedown="dragStart(event, \'_pwin\')"></div><a href="#" onClick="showTask();return false;"><img src="assets/x.gif" border="1" class="popup4" alt="Move"></a>' + xmlHttp.responseText + '</div>';
                        init();
                    }

                    var gquiz = xmlHttp.getResponseHeader("gquiz");
                    if (gquiz == 1 || gquiz == 0) {
                        hightlight_guide(gquiz == 1);
                    } else if (gquiz == 2) {
                        var clsName = _('n5').className;
                        var n = clsName[clsName.length - 1];
                        if (n == 4) {
                            n = 2;
                        } else if (n == 3) {
                            n = 1;
                        }
                        _('n5').className = 'i' + n;

                        hightlight_guide(false);
                    } else if (gquiz == 100) {
                        var t = _('qge');
                        if (t != null) {
                            t.style.display = 'none';
                        }
                    }
                }
                _vflag = false;
                _tt = null;
            }
        };
        xmlHttp.send(null);
    }
}

function hightlight_guide(hightlight) {
    var clsName = _('qgei').className;
    var isOn = clsName[clsName.length - 1] == 'g';
    if (hightlight) {
        if (!isOn) {
            _('qgei').className += 'g';
        }
    } else {
        if (isOn) {
            _('qgei').className = clsName.substring(0, clsName.length - 1);
        }
    }
}

function free_guide() {
    _vflag = true;
    showTask();
}