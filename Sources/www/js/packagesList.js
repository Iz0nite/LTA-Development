function packagesList(){
    let select = document.getElementById("packageSelect");
    let searchPackage = document.getElementById('searchPackage');
    let url= '../config/config.php?formType=packagesList'

    valueSearchPackage = (searchPackage.value).trim();

    if (select.value != -1) {
        url += '&packageSelect='+select.value;
    }

    if (valueSearchPackage != "") {
        url += '&searchPackage=' + valueSearchPackage;
    }
    const request = new XMLHttpRequest();
    request.open('GET', url);
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            let divPackagesList = document.getElementById('packagesList');
            divPackagesList.innerHTML = "";
            divPackagesList.innerHTML = request.responseText;

        }
    }
    request.send();
}

function showPackageDetails(idPackage){
    let divMorePackageInfo = document.getElementById('moreInfoPackage'+idPackage);

    if (divMorePackageInfo.innerHTML == "") {
        let tabMorePackageInfo = document.getElementsByClassName('moreInfoPackage');

        for (let i = 0; i < tabMorePackageInfo.length; i++) {
            tabMorePackageInfo[i].innerHTML = "";
        }

        const request = new XMLHttpRequest();
        request.open('GET', '../config/config.php?formType=showPackageDetails&idPackage='+idPackage);
        request.onreadystatechange = function(){
            if(request.readyState === 4){
                divMorePackageInfo.innerHTML = request.responseText;
            }
        }
        request.send();
    }else {
        divMorePackageInfo.innerHTML = "";
    }

}

function deletePackage(idPackage){
    const request = new XMLHttpRequest();
    request.open('POST','../config/config.php',true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            console.log(request.responseText);
            packagesList();
            ordersList();
        }
    }
    request.send("formType=deletePackage&idPackage="+idPackage);
}
