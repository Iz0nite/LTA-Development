function setExternalUserIdMethod(idUser){
	let myCustomUniqueUserId = idUser.toString();
	// console.log(myCustomUniqueUserId);
	OneSignal.push(function (){
		OneSignal.setExternalUserId(myCustomUniqueUserId);
	});
	
	
	OneSignal.push(function (){
		OneSignal.sendTag("idUser", myCustomUniqueUserId, function (tagsSent){
			// Callback called when tags have finished sending
		});
	});
}
