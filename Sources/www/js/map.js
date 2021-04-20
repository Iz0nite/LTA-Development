/* Setup the map with course */

function setUpMap(idUser){

    let divIdVehicle = document.getElementById("selectVehicle");
    let idVehicle = divIdVehicle.value;

    const request = new XMLHttpRequest();
    request.open('GET', "./../config/testDelivery?formType=newDelivery&idUser="+idUser+"&idVehicle="+idVehicle);
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            if (request.responseText === "Sorry no route could be found. Retry later."){
                alert(request.responseText);
            }else{
                let idRoadMap = request.responseText;
                getPolyline(idRoadMap);
                choiceButton(idRoadMap);
            }
        }
    }
    request.send();

}

function getPolyline(idRoadMap){
    const request = new XMLHttpRequest();
    request.open('GET', "./../config/testDelivery?formType=getPolyline&idRoadMap="+idRoadMap);
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            let polyline = request.responseText;
            initialize(polyline);

        }
    }
    request.send();
}

function choiceButton(idRoadMap){
    const request = new XMLHttpRequest();
    request.open('GET', "./../config/testDelivery?formType=choiceButton&idRoadMap="+idRoadMap);
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            let divPreparation = document.getElementById("preparation");
            let divChoice = document.getElementById("choice");
            divPreparation.innerHTML = "";
            divChoice.innerHTML = request.responseText;
        }
    }
    request.send();
}

function acceptDelivery(idRoadMap){
    const request = new XMLHttpRequest();
    request.open('GET', "./../config/testDelivery?formType=acceptDelivery&idRoadMap="+idRoadMap);
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            let divPreparation = document.getElementById("preparation");
            let divChoice = document.getElementById("choice");
            divChoice.innerHTML = "";
            divPreparation.innerHTML = "";
            divChoice.style.flexDirection = "column";
            divChoice.style.width = "80%";
            divChoice.innerHTML = request.responseText;

        }
    }
    request.send();
}

function checkPickUp(idPackage){
    let checkDiv = document.getElementById('check'+idPackage);

    let url;
    if (checkDiv.checked === true){
        url = "./../config/testDelivery?formType=inVehicle&idPackage="+idPackage
    }else {
        url = "./../config/testDelivery?formType=outOfVehicle&idPackage="+idPackage
    }
    const request = new XMLHttpRequest();
    request.open('GET',url);
    request.onreadystatechange = function(){
        if(request.readyState === 4){

        }
    }
    request.send();
}

function checkAllPackagesInVehicle(idRoadMap){
    const request = new XMLHttpRequest();
    request.open('GET', "./../config/testDelivery?formType=checkAllPackagesInVehicle&idRoadMap="+idRoadMap);
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            let divChoice = document.getElementById("choice");
            let divPreparation = document.getElementById("preparation");
            divPreparation.innerHTML = "";

            if (request.responseText !== ""){
                divChoice.innerHTML = "";
                divChoice.innerHTML = request.responseText;
            }else{
                alert("You need to take all the packages in the list !");
            }

        }
    }
    request.send();
}

function checkDeliver(idPackage){
    let checkDiv = document.getElementById('check'+idPackage);

    let url;
    if (checkDiv.checked === true){
        url = "./../config/testDelivery?formType=deliver&idPackage="+idPackage
    }else {
        url = "./../config/testDelivery?formType=notDeliver&idPackage="+idPackage
    }
    const request = new XMLHttpRequest();
    request.open('GET',url);
    request.onreadystatechange = function(){
        if(request.readyState === 4){

        }
    }
    request.send();
}

function checkFinishDelivery(idRoadMap){
    const request = new XMLHttpRequest();
    request.open('GET', "./../config/testDelivery?formType=checkFinishDelivery&idRoadMap="+idRoadMap);
    request.onreadystatechange = function(){
        if(request.readyState === 4){
            let divChoice = document.getElementById("choice");

            if (request.responseText !== ""){
                divChoice.innerHTML = "";
                divChoice.innerHTML = request.responseText;
            }else{
                alert("You need to deliver all the packages in the list !")
            }

        }
    }
    request.send();
}

function initialize(encoded_polyline)
{
    console.log(encoded_polyline);
    let decoded_polyline = google.maps.geometry.encoding.decodePath(encoded_polyline);
    console.log(decoded_polyline.length);

    let myCenter = decoded_polyline[0];
    let mapProp = {
        center:myCenter,
        zoom:15,
        travelMode:google.maps.TravelMode.DRIVING
    };

    console.log(document.getElementById("map").innerHTML);
    let map = new google.maps.Map(document.getElementById("map"),mapProp);

    console.log(decoded_polyline.length);

    let itinerary = new google.maps.Polyline({
    	path: decoded_polyline,        //chemin du tracé
    	strokeColor: "#4a69bd",        //couleur du tracé
    	strokeOpacity: 1.0,            //opacité du tracé
    	strokeWeight: 2                //grosseur du tracé
    });

    itinerary.setMap(map);
}
