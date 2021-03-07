function ordersList(){
    let select = document.getElementById("orderSelect");
    let searchOrder = document.getElementById('searchOrder');
    let url= '../config/config.php?formType=ordersList'

    valueSearchOrder = (searchOrder.value).trim();

    if (select.value != -1) {
        url += '&orderSelect='+select.value;
    }

    if (valueSearchOrder != "") {
        url += '&searchOrder=' + valueSearchOrder;
    }
    const request = new XMLHttpRequest();
    request.open('GET', url);
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            let divOrdersList = document.getElementById('ordersList');
            divOrdersList.innerHTML = "";
            divOrdersList.innerHTML = request.responseText;

        }
    }
    request.send();
}

function showOrderDetails(idOrder){
    let divMoreOrderInfo = document.getElementById('moreInfoOrder'+idOrder);

    if (divMoreOrderInfo.innerHTML == "") {
        let tabMoreOrderInfo = document.getElementsByClassName('moreInfoOrder');

        for (let i = 0; i < tabMoreOrderInfo.length; i++) {
            tabMoreOrderInfo[i].innerHTML = "";
        }

        const request = new XMLHttpRequest();
        request.open('GET', '../config/config.php?formType=showOrderDetails&idOrder='+idOrder);
        request.onreadystatechange = function(){
            if(request.readyState === 4){
                divMoreOrderInfo.innerHTML = request.responseText;
            }
        }
        request.send();
    }else {
        divMoreOrderInfo.innerHTML = "";
    }

}

function deleteOrder(idOrder){
    const request = new XMLHttpRequest();
    request.open('POST','../config/config.php',true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            console.log(request.responseText);
            ordersList();
            packageList();
        }
    }
    request.send("formType=deleteOrder&idOrder="+idOrder);
}
