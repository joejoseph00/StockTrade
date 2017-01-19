var dmStockTradeID = document.getElementById('dm-stock-trade');
var thisScript = document.getElementById('dm-stock-trade-js');
var parent = thisScript.parentElement;
var z = document.createElement('iframe');
z.setAttribute('id','dm-stock-trade');
z.setAttribute('src','http://encapglobal.com/widget/demotrader');
z.setAttribute('width','100%');
z.setAttribute('height','400px');
z.setAttribute('frameborder','0');
z.setAttribute('allowtransparency','true');
z.setAttribute('scrolling','yes');
parent.appendChild(z);
var dmStockTradeID = document.getElementById('dm-stock-trade');
dmStockTradeID.addEventListener("load",function(){
    var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
    eventer(messageEvent, function(e) {
        if (isNaN(e.data)) return;
        dmStockTradeID.style.height = e.data + 'px';
    }, false);
});
