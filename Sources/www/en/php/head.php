<meta charset="utf-8">
<link rel="icon" href="./../../img/LTADevelopmentLogo.ico" />
<link rel="stylesheet" type="text/css" href="./../../css/home.css">
<link rel="stylesheet" type="text/css" href="./../../css/header.css">
<link rel="stylesheet" type="text/css" href="./../../css/footer.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.js"></script>
<script src="./../../js/pages.js"></script>
<!--API One signal-->
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
   var OneSignal = window.OneSignal || [];
    var initConfig = {
        appId: "304775f8-d0e7-4491-925d-cbd0110c11b5",
        notifyButton: {
            enable: true
        },
    };
    OneSignal.push(function () {
        OneSignal.SERVICE_WORKER_PARAM = { scope: '/js/apiOneSignal/' };
        OneSignal.SERVICE_WORKER_PATH = 'js/apiOneSignal/OneSignalSDKWorker.js'
        OneSignal.SERVICE_WORKER_UPDATER_PATH = 'js/apiOneSignal/OneSignalSDKUpdaterWorker.js'
        OneSignal.init(initConfig);
    });
</script>
