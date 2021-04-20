function addVehicle(){

    let registration = document.getElementById('registration');
    let volumeSize = document.getElementById('volumeSize');

    const request = new XMLHttpRequest();
    request.open('GET', '../config/config.php?formType=addVehicle&registration='+registration.value+'&volumeSize='+volumeSize.value);
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            let divVehicleList = document.getElementById('listVehicle');
            divVehicleList.innerHTML = request.responseText;
        }
    }
    request.send();
}

function deleteVehicle(idVehicle){

    const request = new XMLHttpRequest();
    request.open('DELETE', '../config/config.php?formType=deleteVehicle&idVehicle='+idVehicle);
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            let divVehicleList = document.getElementById('listVehicle');
            divVehicleList.innerHTML = request.responseText;
        }
    }
    request.send();
    
}
