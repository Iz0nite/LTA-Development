let buttonState = 0;

function showPackage(idOrder){
    let orderButton1 = document.getElementById("orderButtonOpen"+idOrder);
    let divPackages = document.getElementById("packages"+idOrder);
    let divOrderAndPackages = document.getElementById("orderAndPackages"+idOrder);

    if (divPackages.innerHTML==""){
        let tabPackages = document.getElementsByClassName("packages");
        let tabOrderAndPackages = document.getElementsByClassName("orderAndPackages");
        let tabButtonPackages = document.getElementsByClassName("buttonPackages");

        for (var i = 0; i < tabOrderAndPackages.length; i++) {
            tabOrderAndPackages[i].style.border = "none"
            //tabOrderAndPackages[i].style.borderRadius = "none"
            tabOrderAndPackages[i].style.animationName = "none";
            tabOrderAndPackages[i].style.animationPlayState = 'paused';
        }

        for (var i = 0; i < tabButtonPackages.length; i++) {
            buttonState = 0;
            tabButtonPackages[i].style.display = "flex";

        }

        for (var i = 0; i < tabPackages.length; i++) {
            tabPackages[i].style.animationName = "none";
            tabPackages[i].style.animationPlayState = 'paused';
            tabPackages[i].innerHTML="";
        }

        const request = new XMLHttpRequest();
        request.open('GET', '../config/config.php?formType=showPackage&idOrder='+idOrder);
        request.onreadystatechange = function(){
            if(request.readyState === 4){ // la requete est terminée
                divPackages.innerHTML = request.responseText;
                if(buttonState == 0){
                    let orderButton2 = document.getElementById("orderButtonClose"+idOrder);

                    orderButton1.style.display = "none";
                    orderButton2.style.display = "flex";
                    buttonState = 1;
                }
                //divOrderAndPackages.style.borderRadius = "10px";
                divOrderAndPackages.style.animationName = "showBorder";
                divOrderAndPackages.style.animationDuration = '2s';
                divOrderAndPackages.style.animationPlayState = 'running';
                divOrderAndPackages.style.border = "solid 1px";

                divPackages.style.animationName = "showDivPackage";
                divPackages.style.animationDuration = '2s';
                divPackages.style.animationPlayState = 'running';
            }
        }
        request.send();
    }else{
        divPackages.style.animationName = "hideDivPackage";
        divPackages.style.animationDuration = '0.5s';
        divPackages.style.animationPlayState = 'running';
        divOrderAndPackages.style.border = "none";
        //divOrderAndPackages.style.borderRadius = "none";
        divOrderAndPackages.style.animationName = "hideBorder";
        divOrderAndPackages.style.animationDuration = '0.5s';
        divOrderAndPackages.style.animationPlayState = 'running';

        if(buttonState == 1){
            orderButton1.style.display = "flex";
            buttonState = 0

        }

        setTimeout(function(){
            divPackages.innerHTML="";
        }, 500);



    }
}
