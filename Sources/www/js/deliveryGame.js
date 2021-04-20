import * as THREE from './three.js-master/build/three.module.js';

import Stats from './three.js-master/examples/jsm/libs/stats.module.js'; //Afficher les stats (FPS)

let stats, container; //set the stats to display the FPS
let clock, delta; //set the clock for the animation

import { PointerLockControls } from './three.js-master/examples/jsm/controls/PointerLockControls.js';	//PointerLockControls

let control;

import { ColladaLoader } from './three.js-master/examples/jsm/loaders/ColladaLoader.js';	//Loader pour les entitées statics

let lotissement, camion, boxPackage;

import { FBXLoader } from './three.js-master/examples/jsm/loaders/FBXLoader.js';	//Loader pour les entitées animées
let client, action;

let camera, scene, renderer;

//allLights
let spotLight, ambient, lightHelper, shadowCameraHelper;
let blockSun;

let divApprovePayment = document.getElementById("approvePayment");

let rulesLabel = document.getElementById("rules");
let checkPackage = 0;

init();

/* *** Init fonctions *** */
pointerLockControl();

ground();
sun();

clientDefine();

buildLotissement();

buildCamion();
buildBoxPackage();

animate();

function init(){

	camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 1, 5000 );
	camera.position.set(300, 30, -100);
	camera.lookAt(300, 300, -800);

	scene = new THREE.Scene();
	scene.background = new THREE.Color( 0xbfd1e5 );

	clock = new THREE.Clock();

	stats = new Stats();

	renderer = new THREE.WebGLRenderer({antialias: true});
	renderer.setPixelRatio(window.devicePixelRatio);
	renderer.setSize( window.innerWidth, window.innerHeight - (document.getElementById("footer").clientHeight + document.getElementById("header").offsetHeight) );
	renderer.shadowMap.enabled = true;
	renderer.shadowMap.type = THREE.PCFSoftShadowMap;
	renderer.outputEncoding = THREE.sRGBEncoding;
	document.body.appendChild(renderer.domElement);

	container = document.getElementById( 'container' );
	container.appendChild( renderer.domElement );
	container.appendChild( stats.dom );

	window.addEventListener( 'resize', onWindowResize, false );
}

function pointerLockControl(){
	control = new PointerLockControls( camera, document.body );
	control.maxPolarAngle = Math.PI * 0.5;
	control.minPolarAngle = Math.PI * 0.5;

	// control.lock();

	const blocker = document.getElementById( 'blocker' );
	const instructions = document.getElementById( 'instructions' );

	instructions.addEventListener( 'click', function (){
		control.lock();
	}, false );

	control.addEventListener( 'lock', function (){
		instructions.style.display = 'none';
		blocker.style.display = 'none';
	} );

	control.addEventListener( 'unlock', function (){
		blocker.style.display = 'block';
		instructions.style.display = '';
	} );

	scene.add( control.getObject() );

	const onKeyDown = function ( event ){
		switch( event.keyCode ){
			case 38: // Forward
			case 90: // z
				camera.translateZ(-delta*1000);
				camera.position.y = 30;
				// camion.translateZ(-delta*1000);
				// camion.position.y = 5;
				break;
			case 37: // left
			case 81: // q
				camera.translateX(-delta*1000);
				camera.position.y = 30;
				// camion.translateX(-delta*1000);
				// camion.position.y = 5;
				break;
			case 40: // down
			case 83: // s
				camera.translateZ(delta*1000);
				camera.position.y = 30;
				// camion.translateZ(delta*1000);
				// camion.position.y = 5;
				break;
			case 39: // right
			case 68: // d
				camera.translateX(delta*1000);
				camera.position.y = 30;
				// camion.translateX(delta*1000);
				// camion.position.y = 5;
				break;
			case 32: // space
				camera.translateY(15);
				setTimeout(function(){
					camera.translateY(-15);
				}, 150);
				break;
		}
	};
	document.addEventListener( 'keydown', onKeyDown );
}

function degToRad(degrees){
	  var pi = Math.PI;
	  return degrees * (pi/180);
	}

function sun(){
	ambient = new THREE.AmbientLight(0xffffff, .2);
	scene.add(ambient);

	spotLight = new THREE.SpotLight(0xffffff, .7);
	spotLight.position.set(300, 1000, 500);

	spotLight.intensity = 2;
	spotLight.penumbra = 0.1;
	spotLight.decay = 1.5;
	spotLight.distance = 3000;

	spotLight.castShadow = true;
	spotLight.shadow.camera.far = 3000;
	spotLight.shadow.focus = .7;
	scene.add(spotLight);

	// lightHelper = new THREE.SpotLightHelper( spotLight );
	// scene.add( lightHelper );
	//
	// shadowCameraHelper = new THREE.CameraHelper( spotLight.shadow.camera );
	// scene.add( shadowCameraHelper );

	var geometry = new THREE.BoxBufferGeometry( 0, 0, 0 );
	let texture = new THREE.TextureLoader().load( './../js/three.js-master/examples/textures/alphaMap.jpg' );
	let material = new THREE.MeshPhongMaterial( { map: texture } );
	blockSun = new THREE.Mesh( geometry, material );
	blockSun.position.set(300, 50, -1000);
	blockSun.castShadow = true;
	blockSun.receiveShadow = true;
	scene.add(blockSun);

	spotLight.target = blockSun;
	spotLight.target.updateMatrixWorld();
}

function shadows(object){
	object.castShadow = true;
	object.receiveShadow = true;
}

function ground(){

	var texture = new THREE.TextureLoader().load( "./../js/three.js-master/examples/textures/terrain/grasslight-big.jpg" );
	var geometry = new THREE.PlaneBufferGeometry( 1500, 3000 );
	var material = new THREE.MeshPhongMaterial( { color: 0xffffff, map: texture } );

	var ground = new THREE.Mesh( geometry, material );
	ground.rotation.x = - Math.PI / 2;
	ground.position.set(500, 0, -1500);
	ground.material.map.repeat.set( 64, 64 );
	ground.material.map.wrapS = THREE.RepeatWrapping;
	ground.material.map.wrapT = THREE.RepeatWrapping;
	ground.material.map.encoding = THREE.sRGBEncoding;
	// note that because the ground does not cast a shadow, .castShadow is left false
	ground.castShadow = true;
	ground.receiveShadow = true;

	scene.add( ground );

}

function buildLotissement(){

	// loading manager

	let loadingManager1 = new THREE.LoadingManager( function (){

		lotissement.scale.set(10, 10, 10);
		lotissement.position.set(0, 1, 0);

		lotissement.traverse( function ( child ){
			if( child.isMesh ){
				child.castShadow = true;
				child.receiveShadow = true;

			}
		} );

		scene.add( lotissement );

	} );

	// collada object

	let loader1 = new ColladaLoader( loadingManager1 );
	loader1.load( './../js/three.js-master/examples/models/lotissement/model.dae', function ( collada ){

		lotissement = collada.scene;

	} );

}

function buildCamion(){

	// loading manager

	let loadingManager2 = new THREE.LoadingManager( function (){

		camion.scale.set(13, 13, 13);
		camion.position.set(300, 30, -100);

		camion.traverse( function ( child ){
			if( child.isMesh ){
				child.castShadow = true;
				child.receiveShadow = true;

			}
		} );

		scene.add( camion );

	} );

	// collada object

	let loader2 = new ColladaLoader( loadingManager2 );
	loader2.load( './../js/three.js-master/examples/models/camion/model.dae', function ( collada ){

		camion = collada.scene;

	} );

}

function buildBoxPackage(){

	// loading manager

	let loadingManager3 = new THREE.LoadingManager( function (){

		boxPackage.scale.set(0.08, 0.08, 0.08);
		boxPackage.position.set(150, 1, -830);

		boxPackage.traverse( function ( child ){
			if( child.isMesh ){
				child.castShadow = true;
				child.receiveShadow = true;

			}
		} );

		scene.add( boxPackage );

	} );

	// collada object

	let loader3 = new ColladaLoader( loadingManager3 );
	loader3.load( './../js/three.js-master/examples/models/package/model.dae', function ( collada ){

		boxPackage = collada.scene;

	} );

}

function contactHitBox(object){
	if((camera.position.x >= object.position.x-50 && camera.position.x <= object.position.x+50) && (camera.position.y >= object.position.y-5 && camera.position.y <= object.position.y+80) && (camera.position.z >= object.position.z-50 && camera.position.z <= object.position.z+50)){
		return true;
	}else{
		return false;
	}
}

function clientDefine(){
	// client
	let loader4 = new FBXLoader();
	loader4.load( './../js/three.js-master/examples/models/client/waving.fbx', function ( objectClient ) {
		client = new THREE.AnimationMixer( objectClient );
		action = client.clipAction( objectClient.animations[ 0 ] );
		// action.timeScale = 1/5 ;	//slow down animation
		// action.setLoop( THREE.LoopOnce );
		action.play();
		objectClient.traverse( function(child){
			if(child.isMesh){
				child.castShadow = true;
				child.receiveShadow = true;
			}
		} );
		objectClient.scale.set(0.08, 0.08, 0.08);
		// objectClient.rotateY(degToRad(-60));
		// objectClient.position.set(500, 1, -1000);
		scene.add( objectClient );
	} );
}

function randomNumber(numberMax){
	return (Math.round((Math.random() * numberMax+1))); //numberMax exclu
}

function randomClientPosition(object){
	switch(randomNumber(7)){
		case 1:
			object._root.position.set(490, 1, -600);
			object._root.rotateY(degToRad(-60));
			break;
		case 2:
			object._root.position.set(200, 1, -1015);
			object._root.rotateY(degToRad(100));
			break;
		case 3:
			object._root.position.set(220, 1, -1565);
			object._root.rotateY(degToRad(50));
			break;
		case 4:
			object._root.position.set(310, 1, -1650);
			object._root.rotateY(degToRad(0));
			break;
		case 5:
			object._root.position.set(600, 1, -1650);
			object._root.rotateY(degToRad(0));
			break;
		case 6:
			object._root.position.set(470, 1, -100);
			object._root.rotateY(degToRad(-50));
			break;
		case 7:
			object._root.position.set(500, 1, -1000);
			object._root.rotateY(degToRad(-60));
			break;
	}
}

function moveCaracters(object){
	if(object.position.x <= -50){
		object.position.x += 4;
	}else if(object.position.x >= 800){
		object.position.x -= 4;
	}else if(object.position.z <= -2100){
		object.position.z += 4;
	}else if(object.position.z >= 0){
		object.position.z -= 4;
	}
}

function onWindowResize(){

	camera.aspect = window.innerWidth / window.innerHeight;
	camera.updateProjectionMatrix();

	renderer.setSize( window.innerWidth, window.innerHeight );
	stats.update();

}

function animate(){
	requestAnimationFrame( animate );

	render();
}

function render(){

	stats.update(); //update the FPS

	delta = clock.getDelta();	//set the delta

	if ( client ) client.update( delta );

	if(client != undefined){
		if(client._root.position.x == 0 && client._root.position.y == 0 && client._root.position.z == 0){
			randomClientPosition(client);
		}
	}

	if(camion != undefined){
		camion.position.set(camera.position.x, 30, camera.position.z); //move the positions
		camion.quaternion.rotateTowards(camera.quaternion, delta * 2 );
	}

	moveCaracters(camera);

	if(boxPackage != undefined){
		if(contactHitBox(boxPackage) == true){
			scene.remove( boxPackage );
			checkPackage = 1;
		}else if(checkPackage == 1){
			rulesLabel.innerHTML = "<p style='font-size: 1.8em;'>Well done !</p><p style='font-size: 1.3em;'>Now find the person to deliver and drive to him to give him the package</p>";
			if(contactHitBox(client._root) == true){
				container.style.display = "none";
		        divApprovePayment.style.display = "flex";
				instructions.innerHTML = '<span style="font-size: 2em">Félicitations, vous avez complété le jeu !</span>';
				instructions.style.cursor = "default";
				control.unlock();
			}
		}else{
			rulesLabel.innerHTML = "<p style='font-size: 1.8em;'>Welcome in the game !</p><p style='font-size: 1.3em;'>Your first goal is to drive to the package to take it</p>";
		}
	}

	if(instructions.style.display != "none"){
        container.style.display = "none";
        divApprovePayment.style.display = "flex";
    }else if(instructions.style.display != "flex"){
        container.style.display = "block";
        divApprovePayment.style.display = "none";
    }

	renderer.render(scene, camera);

}
