var ld, gt, mreq = !0,
    elems = [],
    felems = [],
    _tt = null,
    _frame, _flag = 1,
    _vflag = !1;

function _(a) {
    return document.getElementById(a)
}

function _tcls(a, b) {
    for (var c = !1, d = "", e = a.getAttribute("class").split(" "), f = 0, i = e.length; f < i; f++) e[f] != b ? (d != "" && (d += " "), d += e[f]) : c = !0;
    a.setAttribute("class", d + (c ? "" : " " + b))
}

function _rcls(a, b) {
    for (var c = "", d = a.getAttribute("class").split(" "), e = 0, f = d.length; e < f; e++) d[e] != b && (c != "" && (c += " "), c += d[e]);
    a.setAttribute("class", c)
}

function init() {
    ld = (new Date).getTime();
    for (var a = document.getElementsByTagName("input"), b = 0, c = a.length; b < c; b++) {
        var d = a[b];
        if (d.getAttribute("type") == "image" && d.className == "dynamic_img") d.onmouseover = function () {
            this.className = "dynamic_img over"
        }, d.onmouseout = function () {
            this.className = "dynamic_img"
        }, d.onmousedown = function () {
            this.className = "dynamic_img clicked"
        }
    }
    a = document.getElementsByTagName("table");
    b = 0;
    for (c = a.length; b < c; b++) if (d = a[b], d.hasAttribute("class") && d.getAttribute("class").indexOf("row_table_data") > -1) {
        trs = d.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
        for (var d = 0, e = trs.length; d < e; d++) trs[d].onmouseover = function () {
            this.setAttribute("class", this.getAttribute("class") + " hlight")
        }, trs[d].onmouseout = function () {
            _rcls(this, "hlight")
        }, trs[d].onmousedown = function () {
            _tcls(this, "marked")
        }
    }
    felems = [];
    for (b = 1; b < 5; b++) d = _("l" + b), d != null && (felems.push({
        e: d,
        r: parseFloat(d.getAttribute("title")),
        cv: parseInt(d.innerHTML),
        v: parseInt(d.innerHTML),
        x: d.getAttribute("id") == "l1" ? parseInt(document.getElementById('granary').innerHTML) : parseInt(document.getElementById('warehouse').innerHTML)
    }));
    elems = [];
    a = document.getElementsByTagName("span");
    b = 0;
    for (c = a.length; b < c; b++) d = a[b], d.getAttribute("id") != "timer1" && d.getAttribute("id") != "timer2" || (e = d.innerHTML.split(":"), isNaN(e[2]) || (e = new Number(e[0]) * 3600 + new Number(e[1]) * 60 + new Number(e[2]), elems.push({
        e: d,
        s: e,
        f: d.getAttribute("id") == "timer1" ? -1 : 1
    })));
    gt = window.setInterval(render, 1E3)
}

function render() {
    for (var a = parseInt(((new Date).getTime() - ld) / 1E3), b = 0, c = felems.length; b < c; b++) {
        var d = felems[b],
            e = Math.floor(d.v + parseFloat(a / 3600 * d.r));
        e > d.x && (e = d.x);
        d.cv = e;
        d.e.innerHTML = e
    }
    b = 0;
    for (c = elems.length; b < c; b++) {
        d = elems[b];
        e = d.s + a * d.f;
        if (e < 0) {
            window.clearInterval(gt);
            document.location.reload();
            break
        }
        var f = Math.floor(e % 3600 / 60),
            i = Math.floor(e % 60);
        d.e.innerHTML = Math.floor(e / 3600) + ":" + (f < 10 ? "0" : "") + f + ":" + (i < 10 ? "0" : "") + i
    }
}

function setLang(a) {
    document.cookie = "lng=" + a + "; expires=Wed, 1 Jan 2250 00:00:00 GMT"
}

function toggleLevels() {
    var a = _("lswitch"),
        b = _("levels"),
        c = a.className == "on";
    a.className = b.className = c ? "" : "on";
    document.cookie = (c ? "lvl=0" : "lvl=1") + "; expires=Wed, 1 Jan 2250 00:00:00 GMT"
}

function showManual(a, b) {
    p = document.getElementById("ce");
    if (p != null) p.innerHTML = '<div id="_pwin" class="popup3"><div id="drag" onmousedown="dragStart(event, \'_pwin\')"></div><a href="#" onClick="hideManual(); return false;"><img src="assets/x.gif" border="1" class="popup4" alt="Move"></a><iframe frameborder="0" id="Frame" src="help?c=' + a + '&id=' + b + '" width="412" height="440" border="0"></iframe></div>';
    return !1
}

function hideManual() {
    p = document.getElementById("ce");
    if (p != null) p.innerHTML = ""
}

function showInfo(a, b) {
    var c = _mp.mtx[a][b],
        d = c[5],
        e = c[6];
    _("map_infobox").setAttribute("class", d ? "village" : "oasis_empty");
    _("mbx_11").innerHTML = "-";
    _("mbx_12").innerHTML = "-";
    _("mbx_13").innerHTML = "-";
    d ? (_("mbx_1").innerHTML = e ? textb.t3 : '<span class="tribe tribe' + c[7] + '">' + c[10] + "</span>", _("mbx_11").innerHTML = c[9], _("mbx_12").innerHTML = e ? "-" : c[8], _("mbx_13").innerHTML = c[11] != "" ? c[11] : "-") : _("mbx_1").innerHTML = e ? textb.t4 : textb.t2 + " " + textb.f[c[7]]
}

function hideInfo() {
    _("map_infobox").setAttribute("class", "default");
    _("mbx_1").innerHTML = textb.t1;
    _("mbx_11").innerHTML = "-";
    _("mbx_12").innerHTML = "-";
    _("mbx_13").innerHTML = "-"
}

function createRequestObject() {
    var a = null;
    try {
        a = new XMLHttpRequest
    } catch (b) {
        try {
            a = new ActiveXObject("Msxml2.XMLHTTP")
        } catch (c) {
            a = new ActiveXObject("Microsoft.XMLHTTP")
        }
    }
    return a
}

function renderMap(a, b) {
    if (!mreq) return !1;
    var c = createRequestObject(),
        d = "map?id=" + a.getAttribute("vid") + (b ? "&l" : "");
    if (c == null) return window.location = d, mreq = !0, !1;
    mreq = !1;
    d += "&_a1_";
    c.onreadystatechange = function () {
        if (c.readyState == 4 || c.readyState == "complete") if (mreq = !0, c.responseText.length > 0) {
            eval(c.responseText);
            _("x").innerHTML = _mp.x;
            _("y").innerHTML = _mp.y;
            _("mcx").setAttribute("value", _mp.x);
            _("mcy").setAttribute("value", _mp.y);
            _("ma_n1").setAttribute("vid", _mp.n1);
            _("ma_n2").setAttribute("vid", _mp.n2);
            _("ma_n3").setAttribute("vid", _mp.n3);
            _("ma_n4").setAttribute("vid", _mp.n4);
            _("ma_n1p7").setAttribute("vid", _mp.n1p7);
            _("ma_n2p7").setAttribute("vid", _mp.n2p7);
            _("ma_n3p7").setAttribute("vid", _mp.n3p7);
            _("ma_n4p7").setAttribute("vid", _mp.n4p7);
            for (var a = 0, d = _mp.mtx.length; a < d; a++) for (var b = _mp.mtx[a], g = 0, k = b.length; g < k; g++) {
                var h = b[g];
                _("i_" + a + "_" + g).setAttribute("class", h[3]);
                var j = _("a_" + a + "_" + g);
                j.setAttribute("title", h[4]);
                j.setAttribute("href", "village3?id=" + h[0]);
                if (a == 0) _("my" + g).innerHTML = h[2];
                if (g == 0) _("mx" + a).innerHTML = h[1]
            }
        }
    };
    c.open("GET", d, !0);
    c.send(null);
    return !1
}

function slm() {
    window.open("map?l&id=" + _mp.mtx[3][3][0], "map", "top=100,left=25,width=1007,height=585").focus();
    return !1
}

function add_res(a) {
    set_res(a, _("r" + a).value + carry)
}

function upd_res(a, b) {
    set_res(a, b ? merchNum * carry : isNaN(_("r" + a).value) ? 0 : _("r" + a).value)
}

function set_res(a, b) {
    b > felems[4 - a].cv && (b = felems[4 - a].cv);
    b > merchNum * carry && (b = merchNum * carry);
    b == 0 && (b = "");
    _("r" + a).value = b
}

function Browser() {
    var a, b, c;
    this.isNS = this.isIE = !1;
    this.version = null;
    a = navigator.userAgent;
    b = "MSIE";
    if ((c = a.indexOf(b)) >= 0) this.isIE = !0, this.version = parseFloat(a.substr(c + b.length));
    else if (b = "Netscape6/", (c = a.indexOf(b)) >= 0) this.isNS = !0, this.version = parseFloat(a.substr(c + b.length));
    else if (a.indexOf("Gecko") >= 0) this.isNS = !0, this.version = 6.1
}

var browser = new Browser,
    dragObj = {
        zIndex: 0
    };

function dragStart(a, b) {
    var c, d;
    if (b) dragObj.elNode = document.getElementById(b);
    else {
        if (browser.isIE) dragObj.elNode = window.event.srcElement;
        if (browser.isNS) dragObj.elNode = a.target;
        if (dragObj.elNode.nodeType == 3) dragObj.elNode = dragObj.elNode.parentNode
    }
    browser.isIE && (c = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft, d = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop);
    browser.isNS && (c = a.clientX + window.scrollX, d = a.clientY + window.scrollY);
    dragObj.cursorStartX = c;
    dragObj.cursorStartY = d;
    dragObj.elStartLeft = parseInt(dragObj.elNode.style.right, 10);
    dragObj.elStartTop = parseInt(dragObj.elNode.style.top, 10);
    if (isNaN(dragObj.elStartLeft)) dragObj.elStartLeft = d3l;
    if (isNaN(dragObj.elStartTop)) dragObj.elStartTop = 99;
    dragObj.elNode.style.zIndex = ++dragObj.zIndex;
    if (browser.isIE) document.attachEvent("onmousemove", dragGo), document.attachEvent("onmouseup", dragStop), window.event.cancelBubble = !0, window.event.returnValue = !1;
    browser.isNS && (document.addEventListener("mousemove", dragGo, !0), document.addEventListener("mouseup", dragStop, !0), a.preventDefault())
}

function dragGo(a) {
    var b, c;
    browser.isIE && (b = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft, c = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop);
    browser.isNS && (b = a.clientX + window.scrollX, c = a.clientY + window.scrollY);
    dragObj.elNode.style.right = dragObj.elStartLeft - b + dragObj.cursorStartX + "px";
    dragObj.elNode.style.top = dragObj.elStartTop + c - dragObj.cursorStartY + "px";
    if (browser.isIE) window.event.cancelBubble = !0, window.event.returnValue = !1;
    browser.isNS && a.preventDefault()
}

function dragStop() {
    browser.isIE && (document.detachEvent("onmousemove", dragGo), document.detachEvent("onmouseup", dragStop));
    browser.isNS && (document.removeEventListener("mousemove", dragGo, !0), document.removeEventListener("mouseup", dragStop, !0))
}

function showTask() {
    if (_tt == null) {
        _("anm").style.visibility = "visible";
        if (_flag == 1) _frame = {
            right: 0,
            top: 25,
            width: 118,
            height: 142
        };
        else {
            var a = _("ce");
            if (a != null) a.innerHTML = ""
        }
        _tt = window.setInterval(renderTask, browser.isIE ? 5 : 10, new Date)
    }
}

function renderTask() {
    var a = _("anm");
    _frame.right -= 22 * _flag;
    if (_frame.right < -700) _frame.right = -700;
    d3l > 0 ? a.style.right = _frame.right + "px" : a.style.left = _frame.right + "px";
    _frame.top -= 3 * _flag;
    if (_frame.top < -70) _frame.top = -70;
    a.style.top = _frame.top + "px";
    _frame.width += 10 * _flag;
    if (_frame.width > 430) _frame.width = 430;
    a.style.width = _frame.width + "px";
    _frame.height += 7 * _flag;
    if (_frame.height > 456) _frame.height = 456;
    a.style.height = _frame.height + "px";
    if (_frame.right == -700 && _frame.top == -70 && _frame.width == 430 && _frame.height == 456 || _frame.right >= 25) window.clearInterval(_tt), _flag *= -1, a.style.visibility = "hidden", _flag == -1 ? goto_guide() : _vflag ? goto_guide("f") : _tt = null
}

function goto_guide(a) {
    var b = _("ce");
    if (b != null) {
        if (!_vflag) b.innerHTML = '<div id="_pwin" class="popup3 quest"><div id="drag" onmousedown="dragStart(event, \'_pwin\')"></div><a href="#" onClick="showTask();return false;"><img src="assets/x.gif" border="1" class="popup4" alt="Move"></a><img src="assets/default/plus/loading.gif" width="48" height="48" alt="loading"></div>';
        var c = createRequestObject();
        c.open("get", "guide" + (a == void 0 ? "" : "?v=" + a));
        c.onreadystatechange = function () {
            if (c.readyState == 4) {
                if (c.status == 200 && _flag == -1 && !_vflag) {
                    if (c.responseText != "") b.innerHTML = '<div id="_pwin" class="popup3 quest"><div id="drag" onmousedown="dragStart(event, \'_pwin\')"></div><a href="#" onClick="showTask();return false;"><img src="assets/x.gif" border="1" class="popup4" alt="Move"></a>' + c.responseText + "</div>", init();
                    var a = c.getResponseHeader("gquiz");
                    if (a == 1 || a == 0) hightlight_guide(a == 1);
                    else if (a == 2) a = _("n5").className, a = a[a.length - 1], a == 4 ? a = 2 : a == 3 && (a = 1), _("n5").className = "i" + a, hightlight_guide(!1);
                    else if (a == 100 && (a = _("qge"), a != null)) a.style.display = "none"
                }
                _vflag = !1;
                _tt = null
            }
        };
        c.send(null)
    }
}

function hightlight_guide(a) {
    var b = _("qgei").className,
        c = b[b.length - 1] == "g";
    if (a) c || (_("qgei").className += "g");
    else if (c) _("qgei").className = b.substring(0, b.length - 1)
}

function free_guide() {
    _vflag = !0;
    showTask()
};

function Allmsg() {
    for (var x = 0; x < document.msg.elements.length; x++) {
        var y = document.msg.elements[x];
        if (y.name != 's10') y.checked = document.msg.s10.checked;
    }
}