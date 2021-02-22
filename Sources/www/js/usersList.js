function usersList(){
    let select = document.getElementById("userSelect");
    let searchUser = document.getElementById('searchUser');
    let url= '../config/config.php?formType=usersList'

    valueSearchUser = (searchUser.value).trim();

    if (select.value != -1) {
        url += '&userSelect='+select.value;
    }

    if (valueSearchUser != "") {
        url += '&searchUser=' + valueSearchUser;
    }
    const request = new XMLHttpRequest();
    request.open('GET', url);
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            let divUsersList = document.getElementById('usersList');
            divUsersList.innerHTML = "";
            divUsersList.innerHTML = request.responseText;

        }
    }
    request.send();
}

function showUserDetails(idUser){
    let divMoreUserInfo = document.getElementById('moreInfoUser'+idUser);

    if (divMoreUserInfo.innerHTML == "") {
        let tabMoreUserInfo = document.getElementsByClassName('moreInfoUser');

        for (let i = 0; i < tabMoreUserInfo.length; i++) {
            tabMoreUserInfo[i].innerHTML = "";
        }

        const request = new XMLHttpRequest();
        request.open('GET', '../config/config.php?formType=showUserDetails&idUser='+idUser);
        request.onreadystatechange = function(){
            if(request.readyState === 4){
                divMoreUserInfo.innerHTML = request.responseText;
            }
        }
        request.send();
    }else {
        divMoreUserInfo.innerHTML = "";
    }

}

function deleteUser(idUser){
    console.log(idUser);
    const request = new XMLHttpRequest();
    request.open('POST','../config/config.php',true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            console.log(request.responseText);
            usersList();
            ordersList();
        }
    }
    request.send("formType=deleteUser&idUser="+idUser);
}
