//userList module
function usersList(){
	let select = document.getElementById("userSelect");
	let searchUser = document.getElementById('searchUser');
	let url= '../config/config?formType=usersList'
	
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
		request.open('GET', '../config/config?formType=showUserDetails&idUser='+idUser);
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
	request.open('POST','../config/config',true);
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

//orderList module
function ordersList(){
	let select = document.getElementById("orderSelect");
	let searchOrder = document.getElementById('searchOrder');
	let url= '../config/config?formType=ordersList'
	
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
		request.open('GET', '../config/config?formType=showOrderDetails&idOrder='+idOrder);
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
	request.open('POST','../config/config',true);
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

//packageList module
function packagesList(){
	let select = document.getElementById("packageSelect");
	let searchPackage = document.getElementById('searchPackage');
	let url= '../config/config?formType=packagesList'
	
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
		request.open('GET', '../config/config?formType=showPackageDetails&idPackage='+idPackage);
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
	request.open('POST','../config/config',true);
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

//