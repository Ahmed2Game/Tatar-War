var tid = null;
var i = null;
var ii = null;
var timer = 0;
function CheckAll(checked) {
  var e= document.farm.elements.length;
  var cnt=0;
  for(cnt=0;cnt<e;cnt++)
  {
    if(document.farm.elements[cnt].name=="list[]")
    {
     document.farm.elements[cnt].checked=checked;
    }
  }
}
function farming() {
document.getElementById('farming').innerHTML = '<font color="#FF6F0F"><br>'+texting+'</font><div class="clear"></div><br><div style="width: 0%; height: 10px; background-color: rgb(255, 0, 0);" id="loading"></div>';
i = 1;
tid = setTimeout(farming1, 3000);
}
function farming1() {
  var e= document.farm.elements.length;
  var cnt=0;
  var not=true;
  var farmingdata = null;
  ii = 0;
  for(cnt=0;cnt<e;cnt++)
  {
    if(document.farm.elements[cnt].name=="list[]")
    {
     if(document.farm.elements[cnt].checked)
     {
        ii += 1
     }
    }
  }
farming2();
}
function farming2() {
  var troop = null;
  var e= document.farm.elements.length;
  var cnt=0;
  var not=true;
  var farmingdata = null;
  for(cnt=0;cnt<e;cnt++)
  {
    if(document.farm.elements[cnt].name=="list[]")
    {
     if(document.farm.elements[cnt].checked)
     {
        not = false;
        document.farm.elements[cnt].checked = false;
        document.getElementById('a'+cnt).style.backgroundColor="#FFFF5F";
        document.getElementById('b'+cnt).style.backgroundColor="#FFFF5F";
        document.getElementById('c'+cnt).style.backgroundColor="#FFFF5F";
        document.getElementById('d'+cnt).style.backgroundColor="#FFFF5F";
        document.getElementById('e'+cnt).style.backgroundColor="#FFFF5F";
        document.getElementById('loading').style.width=((i/ii)*100)+'%';
        farmingdata = document.farm.elements[cnt].value.split("|");
        post_to_url("v2v",{'id':farmingdata[0],'c':'4','tro':farmingdata[1],'farm':'1'});
        i += 1;
        //tid = setTimeout(farming2, 500);
        break;
     }
    }
  }
  if(not==true) {
  farming3();
  }
}
function farming3() {
clearTimeout(tid);
document.getElementById('loading').style.width='100%';
}
function post_to_url(path, params) {
$.post(path, params).complete(function() {tid = setTimeout(farming2, 3000);});
}