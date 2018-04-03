<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 6:34 PM
 */

$slide = $DB->sql("SELECT * FROM slider WHERE `status` = '1'", 0, 0, 0);

if (count($slide) > 0) {
    shuffle($slide);
    $picture = $slide[0]["image_base64"];
}

//$headerBorder = base64_encode($time);
//$headerToken = base64_encode($headerBorder . '|SAFARI_PARK_APP|' . $headerBorder);
$headerToken = $encryption->enc_token($ip, 10);
ob_start("sanitize_output");
?>
<!DOCTYPE html>
<html lang="en-US" class="no-js" ng-app="safariPark">
<head>

    <title><?php echo $lang_app_name[$lang]; ?></title>

    <!-- META DATA -->
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <!-- CSS Global Compulsory -->
    <link rel="stylesheet" href="/assets/site/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/site/style.css">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="/assets/site/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/site/css/ionicons.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/site/css/animate.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/site/css/flexslider.css" type="text/css">
    <link rel="stylesheet" href="/assets/site/css/swiper.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/site/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="/assets/site/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="/assets/site/css/select2.min.css">
    <link rel="stylesheet" href="/assets/site/css/vegas.min.css">

    <link rel="stylesheet" href="/assets/site/css/custom.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <script src="/assets/site/js/respond.min.js"></script>
    <![endif]-->

    <!--[if lt IE 11]>
    <link rel="stylesheet" type="text/css" href="/assets/site/css/ie.css">
    <![endif]-->

    <!-- FONTS -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic%7CRoboto:400,300,100,500,700,900'
          rel='stylesheet' type='text/css'>

    <!-- JS -->
    <script type="text/javascript" src="/assets/site/js/modernizr.js"></script>


    <script type="text/javascript" src="/assets/site/js/angular.min.js"></script>
    <script type="text/javascript" src="/assets/site/js/moment.min.js"></script>
    <script type="text/javascript" src="/assets/site/js/angular.moment.min.js"></script>
    <script type="text/javascript" src="/assets/site/js/angular-storage.js"></script>
    <!-- JS -->
    <script type="text/javascript" src="/assets/site/js/jquery.min.js"></script>

    <script>
        var safariPark=angular.module('safariPark',['angularMoment','angular-storage']);safariPark.filter('sce',['$sce',function($sce){return $sce.trustAsHtml}]);safariPark.directive('parkDirective',function(){return{require:'ngModel'}});safariPark.controller('jsonCtrl',function($scope,$http,$filter,moment,store){$scope.totalPrice=0;$scope.totalSafari=[];$scope.totalEntry=[];$scope.todayDate='';$scope.nextDate='';$scope.visitDate='';$scope.dateList=[];$scope.entrySchedule=[];$scope.safariSchedule=[];$scope.entryTickets=[];$scope.safariTickets=[];$scope.entryVisitDate={};$scope.safariVisitDate={};$scope.userTicket={};$scope.warning='';$scope.checkout=!1;$scope.entryTicketsCheck=[];$scope.safariTicketsCheck=[];$scope.paymentURI='';$scope.entryTravleDate='';$scope.entryTicketValid='';$scope.entryTimeName='';$scope.entryTimeID='';$scope.safariTravleDate='';$scope.safariTicketValid='';$scope.safariTimeName='';$scope.safariTimeID='';$scope.totalEntryCheck=0;$scope.totalSafariCheck=0;$scope.purchaseVat='';$scope.totalPriceCheck=0;$scope.totalEntryPrice=0;$scope.totalSafariPrice=0;$scope.subTotal=0;$scope.userState=!1;$scope.LoginWarning='';$scope.LoginUserName='';$scope.LoginPassWord='';$scope.userTokenData='';$scope.checkoutWarning='';$scope.loader=!1;$scope.userFullName='';$scope.bookingData=[];$scope.moment=moment;$scope.singleBooking=[];$scope.RegisterWarning='';$scope.RegisterFullName='';$scope.RegisterUserEmail='';$scope.RegisterUserPhone='';$scope.RegisterPassWord='';$scope.RegisterPassWordRe='';$scope.RegisterSuccess='';$scope.ForgetWarning='';$scope.ForgetUserName='';$scope.ForgetSuccess='';$scope.PasswordReset=!1;$scope.UserPass={};$scope.UserPassWarning='';$scope.UserPassSuccess='';var userToken=store.get('userTokenEnc');if(userToken!==null){$scope.userState=!0;$scope.userTokenData=userToken}
            var appDom='<?php echo APP_DOM;?>';$http.get(appDom+'api/v2/tickets',{headers:{'X-Auth-Ibn':'<?php echo $headerToken; ?>'}}).then(function(data){if(data.data){$scope.tickets=data.data;$scope.entryTickets=$scope.tickets.entry_tickets;$scope.safariTickets=$scope.tickets.safari_tickets;$scope.todayDate=moment().format("DD-MM-YYYY");$scope.nextDate=moment($scope.todayDate,"DD-MM-YYYY").add($scope.tickets.advance_date,'days').format("DD-MM-YYYY");$scope.visitDate=$scope.todayDate;var dateKey=moment($scope.todayDate,"DD-MM-YYYY").format("DD/MM/YYYY");for(var k=0;k<$scope.tickets.advance_date;k++){var makedate=moment($scope.todayDate,"DD-MM-YYYY").add(k,'days').format("DD-MM-YYYY");$scope.dateList.push(makedate)}
                if($scope.tickets.schedules.entry_tickets.dates[dateKey]){if($scope.tickets.schedules.entry_tickets.dates[dateKey]=='closed'){$scope.entrySchedule=[]}else{var checkEntrySchedule=$scope.tickets.schedules.entry_tickets.dates[dateKey].shifts;if(checkEntrySchedule.length>0){$scope.entrySchedule=$scope.tickets.schedules.entry_tickets.dates[dateKey].shifts}else{var NewObject=[];NewObject.push({id:'1',name:'সারাদিন',start:$scope.tickets.schedules.entry_tickets.dates[dateKey].open,end:$scope.tickets.schedules.entry_tickets.dates[dateKey].close,tickets:$scope.tickets.schedules.entry_tickets.dates[dateKey].tickets});$scope.entrySchedule=NewObject}}}
                if($scope.tickets.schedules.safari_tickets.dates[dateKey]){if($scope.tickets.schedules.safari_tickets.dates[dateKey]=='closed'){$scope.safariSchedule=[]}else{var checkSafariSchedule=$scope.tickets.schedules.safari_tickets.dates[dateKey].shifts;if(checkSafariSchedule.length>0){$scope.safariSchedule=$scope.tickets.schedules.safari_tickets.dates[dateKey].shifts}else{var NewObject=[];NewObject.push({id:'1',name:'সারাদিন',start:$scope.tickets.schedules.safari_tickets.dates[dateKey].open,end:$scope.tickets.schedules.safari_tickets.dates[dateKey].close,tickets:$scope.tickets.schedules.safari_tickets.dates[dateKey].tickets});$scope.safariSchedule=NewObject}}}
                var entryNum=$scope.entryTickets.length;for(var i=0;i<entryNum;i++){var enID=$scope.entryTickets[i].slug;$scope.totalEntry[enID]=[];$scope.totalEntry[enID]={"total":0}}
                var safariNum=$scope.safariTickets.length;for(var i=0;i<safariNum;i++){var enID=$scope.safariTickets[i].slug;$scope.totalSafari[enID]=[];$scope.totalSafari[enID]={"total":0}}}});$scope.range=function(min,max,step){step=step||1;var input=[];for(var i=min;i<=max;i+=step){input.push(i)}
                return input};$scope.entryTicket=function(ticketIndex,ticketID,ticketPrice,ticketNum,ticketName,ticketRequire){var price=parseFloat(ticketPrice)*parseFloat(ticketNum);$scope.totalEntry[ticketIndex]=[];$scope.totalEntry[ticketIndex].total=price;$scope.totalEntry[ticketIndex].ticketID=ticketID;$scope.totalEntry[ticketIndex].ticketPrice=ticketPrice;$scope.totalEntry[ticketIndex].ticketNum=ticketNum;$scope.totalEntry[ticketIndex].ticketName=ticketName;$scope.totalEntry[ticketIndex].ticketRequire=ticketRequire;var entrySum=0;var totalEntry=$scope.totalEntry;Object.keys(totalEntry).forEach(function(key){entrySum+=parseFloat(totalEntry[key].total)});var safariSum=0;var totalSafari=$scope.totalSafari;Object.keys(totalSafari).forEach(function(key){safariSum+=parseFloat(totalSafari[key].total)});var total=entrySum+safariSum;$scope.totalPrice=total};$scope.safariTicket=function(ticketIndex,ticketID,ticketPrice,ticketNum,ticketName){var price=parseFloat(ticketPrice)*parseFloat(ticketNum);$scope.totalSafari[ticketIndex]=[];$scope.totalSafari[ticketIndex].total=price;$scope.totalSafari[ticketIndex].ticketID=ticketID;$scope.totalSafari[ticketIndex].ticketPrice=ticketPrice;$scope.totalSafari[ticketIndex].ticketNum=ticketNum;$scope.totalSafari[ticketIndex].ticketName=ticketName;var entrySum=0;var totalEntry=$scope.totalEntry;Object.keys(totalEntry).forEach(function(key){entrySum+=parseFloat(totalEntry[key].total)});var safariSum=0;var totalSafari=$scope.totalSafari;Object.keys(totalSafari).forEach(function(key){safariSum+=parseFloat(totalSafari[key].total)});var total=entrySum+safariSum;$scope.totalPrice=total};$scope.getVisitDate=function(date){$scope.visitDate=date;var dateKey=moment(date,"DD/MM/YYYY").format("DD/MM/YYYY");if($scope.tickets.schedules.entry_tickets.dates[dateKey]){if($scope.tickets.schedules.entry_tickets.dates[dateKey]=='closed'){$scope.entrySchedule=[]}else{var checkEntrySchedule=$scope.tickets.schedules.entry_tickets.dates[dateKey].shifts;if(checkEntrySchedule.length>0){$scope.entrySchedule=$scope.tickets.schedules.entry_tickets.dates[dateKey].shifts}else{var NewObject=[];NewObject.push({id:'1',name:'সারাদিন',start:$scope.tickets.schedules.entry_tickets.dates[dateKey].open,end:$scope.tickets.schedules.entry_tickets.dates[dateKey].close,tickets:$scope.tickets.schedules.entry_tickets.dates[dateKey].tickets});$scope.entrySchedule=NewObject}}}
                if($scope.tickets.schedules.safari_tickets.dates[dateKey]){if($scope.tickets.schedules.safari_tickets.dates[dateKey]=='closed'){$scope.safariSchedule=[]}else{var checkSafariSchedule=$scope.tickets.schedules.safari_tickets.dates[dateKey].shifts;if(checkSafariSchedule.length>0){$scope.safariSchedule=$scope.tickets.schedules.safari_tickets.dates[dateKey].shifts}else{var NewObject=[];NewObject.push({id:'1',name:'সারাদিন',start:$scope.tickets.schedules.safari_tickets.dates[dateKey].open,end:$scope.tickets.schedules.safari_tickets.dates[dateKey].close,tickets:$scope.tickets.schedules.safari_tickets.dates[dateKey].tickets});$scope.safariSchedule=NewObject}}}};$scope.saveEntryShift=function(shidtID){var dateVal=moment(this.visitDate,"DD/MM/YYYY").format("DD/MM/YYYY");$scope.entryVisitDate={date:dateVal,shiftID:shidtID}};$scope.saveSafariShift=function(shidtID){moment.locale('en');var dateVal=moment(this.visitDate,"DD/MM/YYYY").format("DD/MM/YYYY");$scope.safariVisitDate={date:dateVal,shiftID:shidtID}};$scope.reviewOrder=function(){var require='invalid';var entryNum=0;var entryJson=[];var totalEntry=$scope.totalEntry;Object.keys(totalEntry).forEach(function(key){if(totalEntry[key].ticketNum){if(totalEntry[key].ticketNum>0){entryJson[key]={"ticketID":totalEntry[key].ticketID,"ticketName":totalEntry[key].ticketName,"ticketNum":totalEntry[key].ticketNum,"ticketPrice":totalEntry[key].ticketPrice,"total":totalEntry[key].total};if(totalEntry[key].ticketRequire==0){require='valid'}
                entryNum+=parseInt(totalEntry[key].ticketNum)}}});var safariNum=0;var safariJson=[];var totalSafari=$scope.totalSafari;Object.keys(totalSafari).forEach(function(key){if(totalSafari[key].ticketNum){if(totalSafari[key].ticketNum>0){safariJson[key]={"ticketID":totalSafari[key].ticketID,"ticketName":totalSafari[key].ticketName,"ticketNum":totalSafari[key].ticketNum,"ticketPrice":totalSafari[key].ticketPrice,"total":totalSafari[key].total}}
                safariNum+=parseInt(totalSafari[key].ticketNum)}});$scope.userTicket={"entry_tickets":entryJson,"safari_tickets":safariJson,"entryDate":this.entryVisitDate,"safariDate":this.safariVisitDate,"platform":"web"};var todayDate=moment().format("DD/MM/YYYY");var nowUnix=moment().unix();var selectedTime='';if($scope.entryVisitDate.shiftID){var entryDateData=this.entryVisitDate.shiftID;var dateData=entryDateData.split("|");var timeCheck=dateData[3];selectedTime=moment(todayDate+' '+timeCheck,"DD/MM/YYYY hh:mm A").format("X")}
                if($scope.safariVisitDate.shiftID){var entryDateData=this.safariVisitDate.shiftID;var dateData=entryDateData.split("|");var timeCheck=dateData[3];selectedTime=moment(todayDate+' '+timeCheck,"DD/MM/YYYY hh:mm A").format("X")}
                if(Object.keys(entryJson).length>0&&require=='invalid'){$scope.warning='<?php echo($lang == 'bn' ? '<span class="bangla">নির্বাচিত টিকেট এর জন্য অভিভাবক প্রয়োজন</span>' : 'You need guardian for selected ticket.'); ?>'}
                else if(Object.keys(entryJson).length>0&&!$scope.entryVisitDate.shiftID){$scope.warning='<?php echo($lang == 'bn' ? '<span class="bangla">তারিখ এবং সময় নির্বাচন করুন</span>' : 'Please select date and time.'); ?>'}
                else if(Object.keys(safariJson).length>0&&!$scope.safariVisitDate.shiftID){$scope.warning='<?php echo($lang == 'bn' ? '<span class="bangla">তারিখ এবং সময় নির্বাচন করুন</span>' : 'Please select date and time.'); ?>'}
                else if(this.safariVisitDate.date&&$scope.safariVisitDate.date==todayDate&&nowUnix>parseInt(selectedTime)||$scope.entryVisitDate.date&&$scope.entryVisitDate.date==todayDate&&nowUnix>parseInt(selectedTime)){$scope.warning='<?php echo($lang == 'bn' ? '<span class="bangla">নির্বাচিত সময় ইতিমধ্যে অতিক্রম করেছে</span>' : 'Selected time already passed.'); ?>'}
                else if(Object.keys(entryJson).length>0||Object.keys(safariJson).length>0){if(Object.keys(entryJson).length>0){var entryDateData=this.entryVisitDate.shiftID;var dateData=entryDateData.split("|");if(dateData[2]&&entryNum<parseInt(dateData[2])){$scope.checkout=!0;$scope.warning=''}else{$scope.warning='<?php echo($lang == 'bn' ? '<span class="bangla">আপনার নির্বাচিত তারিখ এবং সময়ে যথেষ্ট টিকিট নেই</span>' : 'There are not enough tickets on your selected date.'); ?>'}}
                    if(Object.keys(safariJson).length>0){var safariDateData=$scope.safariVisitDate.shiftID;var dateData=safariDateData.split("|");if(dateData[2]&&safariNum<parseInt(dateData[2])){$scope.checkout=!0;$scope.warning=''}else{$scope.warning='<?php echo($lang == 'bn' ? '<span class="bangla">আপনার নির্বাচিত তারিখ এবং সময়ে যথেষ্ট টিকিট নেই</span>' : 'There are not enough tickets on your selected date.'); ?>'}}}else{$scope.warning='<?php echo($lang == 'bn' ? '<span class="bangla">আপনি কোন টিকেট নির্বাচন করেননি</span>' : 'You have not selected any tickets.'); ?>'}};$scope.checkOutInit=function(){$scope.entryTicketsCheck=$scope.userTicket.entry_tickets;$scope.safariTicketsCheck=this.userTicket.safari_tickets;var vatPrice=$scope.tickets.fee;var validTill=$scope.tickets.valid;$scope.paymentURI=$scope.tickets.payment;if($scope.userTicket.entryDate.date){var datePick=$scope.userTicket.entryDate.date;var dateMoment=moment(datePick,"DD/MM/YYYY");var dateAdd=dateMoment.add(validTill,'days');$scope.entryTravleDate=datePick;$scope.entryTicketValid=dateAdd.format("DD/MM/YYYY");var entryDateData=$scope.userTicket.entryDate.shiftID;var dateData=entryDateData.split("|");$scope.entryTimeName=dateData[1];$scope.entryTimeID=dateData[0]}
                if($scope.userTicket.safariDate.date){var datePick=$scope.userTicket.safariDate.date;var dateMoment=moment(datePick,"DD/MM/YYYY");var dateAdd=dateMoment.add(validTill,'days');$scope.safariTravleDate=datePick;$scope.safariTicketValid=dateAdd.format("DD/MM/YYYY");var entryDateData=$scope.userTicket.safariDate.shiftID;var dateData=entryDateData.split("|");$scope.safariTimeName=dateData[1];$scope.safariTimeID=dateData[0]}
                var entryJson=[];var totalEntry=$scope.entryTicketsCheck;Object.keys(totalEntry).forEach(function(key){entryJson.push({"ticketID":totalEntry[key].ticketID,"ticketName":totalEntry[key].ticketName,"ticketNum":totalEntry[key].ticketNum,"ticketPrice":totalEntry[key].ticketPrice,"total":totalEntry[key].total})});$scope.entryTicketsCheck=entryJson;var safariJson=[];var totalSafari=$scope.safariTicketsCheck;Object.keys(totalSafari).forEach(function(key){safariJson.push({"ticketID":totalSafari[key].ticketID,"ticketName":totalSafari[key].ticketName,"ticketNum":totalSafari[key].ticketNum,"ticketPrice":totalSafari[key].ticketPrice,"total":totalSafari[key].total})});$scope.safariTicketsCheck=safariJson;$scope.totalEntryCheck=Object.keys(entryJson).length;$scope.totalSafariCheck=Object.keys(safariJson).length;var entrySum=0;Object.keys(totalEntry).forEach(function(key){entrySum+=totalEntry[key].total});var safariSum=0;Object.keys(totalSafari).forEach(function(key){safariSum+=totalSafari[key].total});var total=entrySum+safariSum;var vatcalculate=(vatPrice/100)*total;$scope.totalEntryPrice=entrySum;$scope.totalSafariPrice=safariSum;$scope.subTotal=total;$scope.purchaseVat=vatcalculate.toFixed(2);$scope.totalPriceCheck=total+vatcalculate};$scope.confirmOrder=function(){if($scope.userState){var entryJson=[];var totalEntry=$scope.userTicket.entry_tickets;Object.keys(totalEntry).forEach(function(key){entryJson.push({"ticketID":totalEntry[key].ticketID,"ticketQuantity":totalEntry[key].ticketNum})});var safariJson=[];var totalSafari=$scope.userTicket.safari_tickets;Object.keys(totalSafari).forEach(function(key){safariJson.push({"ticketID":totalSafari[key].ticketID,"ticketQuantity":totalSafari[key].ticketNum})});var jsonSend=[];jsonSend.push({"entry_tickets":entryJson,"safari_tickets":safariJson,"entry_travel_date":$scope.entryTravleDate,"entry_valid_date":$scope.entryTicketValid,"entry_timeID":$scope.entryTimeID,"safari_travel_date":$scope.safariTravleDate,"safari_valid_date":$scope.safariTicketValid,"safari_timeID":$scope.safariTimeID,"platform":"web"});$scope.loader=!0;$http.get($scope.paymentURI+$scope.userTokenData+'/'+JSON.stringify(jsonSend)).then(function(data){if(data.data.Status){if(data.data.Status=='407'){$scope.checkoutWarning=data.data.Message}else if(data.data.Status=='408'){$scope.checkoutWarning='';var win=window.open(data.data.URL,'_blank');win.location;win.focus();window.location.reload()}
                $scope.loader=!1}else{$scope.checkoutWarning='<?php echo($lang == 'bn' ? '<span class="bangla">আপনার অনুরোধের সাথে সমস্যা হচ্ছে।</span>' : 'Having problems with your request.'); ?>';$scope.loader=!1}})}else{window.location.reload()}};$scope.userLogin=function(){var userName=$scope.LoginUserName;var passWord=$scope.LoginPassWord;$scope.loader=!0;$http.post(appDom+'api/v2/login',{"username":userName,"password":passWord,"platform":"Web","language":"<?php echo $lang;?>"},{headers:{'X-Auth-Ibn':'<?php echo $headerToken; ?>'}}).then(function(data){if(data.data.Status){if(data.data.Status=='407'||data.data.Status=='405'){$scope.LoginWarning=data.data.Message}else if(data.data.Status=='408'){$scope.LoginWarning='';$scope.userState=!0;store.set('userTokenEnc',data.data.Token);$scope.userTokenData=data.data.Token;$("#loginModal").modal('hide')}
                $scope.loader=!1}else{$scope.LoginWarning='<?php echo($lang == 'bn' ? '<span class="bangla">আপনার অনুরোধের সাথে সমস্যা হচ্ছে।</span>' : 'Having problems with your request.'); ?>';$scope.loader=!1}})};$scope.userRegister=function(){var fullName=$scope.RegisterFullName;var userEmail=$scope.RegisterUserEmail;var userPhone=$scope.RegisterUserPhone;var passWord=$scope.RegisterPassWord;var passWordRe=$scope.RegisterPassWordRe;$scope.loader=!0;$http.post(appDom+'api/v2/register',{"name":fullName,"email":userEmail,"phone":userPhone,"password":passWord,"password_re":passWordRe,"language":"<?php echo $lang;?>"},{headers:{'X-Auth-Ibn':'<?php echo $headerToken; ?>'}}).then(function(data){if(data.data.Status){if(data.data.Status=='407'){$scope.RegisterWarning=data.data.Message}else if(data.data.Status=='408'){$scope.RegisterWarning='';$scope.RegisterSuccess=data.data.Message}
                $scope.loader=!1}else{$scope.LoginWarning='<?php echo($lang == 'bn' ? '<span class="bangla">আপনার অনুরোধের সাথে সমস্যা হচ্ছে।</span>' : 'Having problems with your request.'); ?>';$scope.loader=!1}})};$scope.userForget=function(){var userName=$scope.ForgetUserName;$scope.loader=!0;$http.post(appDom+'api/v2/forget',{"email":userName,"language":"<?php echo $lang;?>"},{headers:{'X-Auth-Ibn':'<?php echo $headerToken; ?>'}}).then(function(data){if(data.data.Status){if(data.data.Status=='407'||data.data.Status=='405'){$scope.ForgetWarning=data.data.Message}else if(data.data.Status=='408'){$scope.ForgetWarning='';$scope.ForgetSuccess=data.data.Message}
                $scope.loader=!1}else{$scope.LoginWarning='<?php echo($lang == 'bn' ? '<span class="bangla">আপনার অনুরোধের সাথে সমস্যা হচ্ছে।</span>' : 'Having problems with your request.'); ?>';$scope.loader=!1}})};$scope.resetBooking=function(){window.location.reload()};$scope.reload=function(){setTimeout(function(){reload()},2000);function reload(){window.location.reload()}};$scope.userAccount=function(){$http.get(appDom+'api/v2/account/'+$scope.userTokenData,{headers:{'X-Auth-Ibn':'<?php echo $headerToken; ?>'}}).then(function(data){if(data.data.account){$scope.userFullName=data.data.account.name;if(data.data.bookings){$scope.bookingData=data.data.bookings}}else{$scope.userState=!1;store.remove('userTokenEnc')}})};$scope.bookingDetail=function(bookingID){$scope.singleBooking=$filter('filter')($scope.bookingData,{id:bookingID})};$scope.bookBack=function(){$scope.singleBooking=[];$scope.PasswordReset=!1};$scope.logOut=function(){$scope.userState=!1;store.remove('userTokenEnc');window.location.reload()};$scope.closeLogin=function(){$("#loginModal").modal('hide');$("#forgetModal").modal('hide')};$scope.closeRegister=function(){$("#registerModal").modal('hide');$("#forgetModal").modal('hide')};$scope.closeBoth=function(){$("#loginModal").modal('hide');$("#registerModal").modal('hide')};$scope.accountSettings=function(){$scope.PasswordReset=!0};$scope.userPassword=function(){var userPass=$scope.UserPass.password;var userPassRe=$scope.UserPass.confirm;$scope.loader=!0;$http.post(appDom+'api/v2/password/'+$scope.userTokenData,{"password":userPass,"re_password":userPassRe,"language":"<?php echo $lang;?>"},{headers:{'X-Auth-Ibn':'<?php echo $headerToken; ?>'}}).then(function(data){if(data.data.Status){if(data.data.Status=='407'){$scope.UserPassWarning=data.data.Message}else if(data.data.Status=='408'){$scope.UserPassWarning='';$scope.UserPassSuccess=data.data.Message}
                $scope.loader=!1}else{$scope.UserPassWarning='<?php echo($lang == 'bn' ? '<span class="bangla">আপনার অনুরোধের সাথে সমস্যা হচ্ছে।</span>' : 'Having problems with your request.'); ?>';$scope.loader=!1}})}})
    </script>


    <!-- FAVICONS -->
    <link rel="shortcut icon" href="/assets/admin/images/favicon.ico">

    <link href="/assets/stylesheet.css" rel="stylesheet">

    <style>
        .bangla {
            font-family: 'SolaimanLipi' !important;
        }
    </style>

</head>
<body class="smooth-scroller" ng-controller="jsonCtrl">

<div id="preloader">
    <div id="loading-animation"></div>
</div>

<div class="main-container">

    <div id="top"></div>

    <header class="main-header style-1 transparent">
        <div class="header-wrapper">

            <nav class="header-nav light">
                <div class="nav-wrapper">
                    <div class="container-fluid">

                        <div class="nav-block left block-widgets">
                            <button type="button" class="nav-toggle">
                                <i class="ion-navicon"></i>
                            </button>
                            <div class="header-logo">
                                <a href="/">
                                    <img src="/images/Logo.png" class="logo-light" alt="">
                                    <img src="/images/Logo.png" class="logo-dark" alt="">
                                </a>
                            </div>
                        </div>

                        <div class="nav-block right block-widgets">
                            <div class="header-functions">
                                <div class="header-widgets">
                                    <!-- Top Cart -->
                                    <div class="header-widget top-cart" data-trigger="click">
                                        <a href="#" ng-if="!userState" data-toggle="modal"
                                           data-target="#loginModal" class="trigger-widget">
                                            <i class="ion-person"></i>
                                        </a>

                                        <a href="#" ng-if="userState" ng-click="userAccount()" data-toggle="modal"
                                           data-target="#accountModal" class="trigger-widget">
                                            <i class="ion-person"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </nav>

        </div>
    </header>
    <!-- .main-header end -->


    <!-- Page Header -->
    <section class="intro-section fullscreen-element text-center">
        <div class="overlay">
            <div class="overlay-wrapper">
                <div class="overlay-inner cover-background"
                     style="background-image: url('<?php echo(isset($picture) ? $picture : ''); ?>');"></div>
                <div class="overlay-inner background-dark-4 opacity-10"></div>
            </div>
        </div>

        <div class="container">

            <div class="row" ng-if="warning != ''">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i
                                    class="fa fa-times"></i></button>
                        <strong><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_warning[$lang] . '</span>' : $lang_warning[$lang]); ?></strong>
                        <span ng-bind-html="warning | sce"></span>
                        <i class="icon icon-alerts-01"></i>
                    </div>
                </div>
            </div>

            <div class="row" ng-if="!checkout">
                <div class="col-md-10 col-md-offset-1">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs customMid">
                        <li class="active">
                            <a href="#entryTicket" data-toggle="tab">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_entry_menu[$lang] . '</span>' : $lang_ticket_entry_menu[$lang]); ?>
                            </a>
                        </li>
                        <li>
                            <a href="#safariTicket" data-toggle="tab">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_safari_menu[$lang] . '</span>' : $lang_ticket_safari_menu[$lang]); ?>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content customBG">
                        <div class="tab-pane active" id="entryTicket">

                            <!-- Component -->
                            <div class="list-group-item" ng-if="!tickets">Loading....</div>

                            <div ng-if="tickets" class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <ul class="list-group">
                                        <li class="list-group-item" ng-repeat="ticket in tickets.entry_tickets">
                                            <span class="bangla">{{ticket.name}}</span>
                                            <span class="badge closeBadge">{{ticket.price}} BDT</span>

                                            <span class="ticketPick">
                                                <select ng-model="ticketNum"
                                                        ng-change="entryTicket(ticket.slug, ticket.id, ticket.price, ticketNum, ticket.name, ticket.require)">
                                                    <option ng-repeat="n in range(0,tickets.limit)"
                                                            value="{{n}}">{{n}}</option>
                                                </select>
                                            </span>
                                        </li>

                                        <li class="list-group-item">
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_visit_date[$lang] . '</span>' : $lang_ticket_visit_date[$lang]); ?>

                                            <span class="ticketPick">
                                                <select ng-model="visitDate" ng-change="getVisitDate(visitDate)">
                                                    <option ng-repeat="date in dateList"
                                                            value="{{date}}">{{date}}</option>
                                                </select>
                                                <span ng-if="entrySchedule.length == 0">
                                                        <span class="bangla">পার্ক বন্ধ আছে</span>
                                                </span>
                                                <span ng-if="entrySchedule.length > 0">
                                                <select class="bangla" ng-model="visitTime"
                                                        ng-change="saveEntryShift(visitTime)">
                                                    <option ng-repeat="time in entrySchedule"
                                                            value="{{time.id}}|{{time.name}} ({{time.start}} - {{time.end}})|{{time.tickets}}|{{time.end}}">
                                                        {{time.name}} ({{time.start}} - {{time.end}})
                                                    </option>
                                                </select>
                                                </span>
                                            </span>
                                        </li>

                                        <li class="list-group-item">
                                            <span style="font-weight: bold">
                                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_order_total[$lang] . '</span>' : $lang_order_total[$lang]); ?>
                                                :
                                                {{totalPrice}} BDT
                                            </span>

                                            <span class="ticketPick" style="margin-top: -6px;">
                                                <button ng-click="reviewOrder()"
                                                        class="btn btn-sm btn-black btn-noradius">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_checkout[$lang] . '</span>' : $lang_checkout[$lang]); ?>
                                                </button>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Component -->

                        </div>


                        <div class="tab-pane" id="safariTicket">
                            <!-- Component -->
                            <div class="list-group-item" ng-if="!tickets">Loading....</div>

                            <div ng-if="tickets" class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <ul class="list-group">
                                        <li class="list-group-item" ng-repeat="ticket in tickets.safari_tickets">
                                            <span class="bangla">{{ticket.name}}</span>
                                            <span class="badge closeBadge">{{ticket.price}} BDT</span>

                                            <span class="ticketPick">
                                                <select ng-model="ticketNum"
                                                        ng-change="safariTicket(ticket.slug, ticket.id, ticket.price, ticketNum, ticket.name)">
                                                    <option ng-repeat="n in range(0,tickets.limit)"
                                                            value="{{n}}">{{n}}</option>
                                                </select>
                                            </span>
                                        </li>

                                        <li class="list-group-item">
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_visit_date[$lang] . '</span>' : $lang_ticket_visit_date[$lang]); ?>

                                            <span class="ticketPick">
                                                <select ng-model="visitDate" ng-change="getVisitDate(visitDate)">
                                                    <option ng-repeat="date in dateList"
                                                            value="{{date}}">{{date}}</option>
                                                </select>
                                                <span ng-if="safariSchedule.length == 0">
                                                        <span class="bangla">পার্ক বন্ধ আছে</span>
                                                </span>
                                                <span ng-if="safariSchedule.length > 0">
                                                <select class="bangla" ng-model="visitTime"
                                                        ng-change="saveSafariShift(visitTime)">
                                                    <option ng-repeat="time in safariSchedule"
                                                            value="{{time.id}}|{{time.name}} ({{time.start}} - {{time.end}})|{{time.tickets}}|{{time.end}}">
                                                        {{time.name}} ({{time.start}} - {{time.end}})
                                                    </option>
                                                </select>
                                                </span>
                                            </span>
                                        </li>

                                        <li class="list-group-item">
                                            <span style="font-weight: bold">
                                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_order_total[$lang] . '</span>' : $lang_order_total[$lang]); ?>
                                                :
                                                {{totalPrice}} BDT
                                            </span>

                                            <span class="ticketPick" style="margin-top: -6px;">
                                                <button ng-click="reviewOrder()"
                                                        class="btn btn-sm btn-black btn-noradius">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_checkout[$lang] . '</span>' : $lang_checkout[$lang]); ?>
                                                </button>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Component -->
                        </div>
                    </div>
                </div>
            </div>


            <div class="row" ng-if="checkout" ng-init="checkOutInit()">

                <div class="col-md-10 col-md-offset-1">
                    <div class="tab-content customBG">
                        <div class="tab-pane active parentDiv">

                            <div class="resetBooking" ng-click="resetBooking()">
                                <i class="ion-close"></i>
                            </div>

                            <div ng-If="totalEntryCheck > 0 && totalSafariCheck > 0">
                                <div class="row" ng-init="reload()">
                                    <div class="col-md-10 col-md-offset-1">
                                        <h4 align="center" style="color: red">Multi category ticket purchasing not
                                            available now.</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="row" ng-if="checkoutWarning != ''">
                                <div class="col-md-12">
                                    <div class="alert alert-warning">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i
                                                    class="fa fa-times"></i></button>
                                        <strong><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_warning[$lang] . '</span>' : $lang_warning[$lang]); ?></strong>
                                        <span class="bangla">{{checkoutWarning}}</span>
                                        <i class="icon icon-alerts-01"></i>
                                    </div>
                                </div>
                            </div>

                            <div ng-If="totalEntryCheck > 0">
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <h3 class="bangla">
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_entry_menu[$lang] . '</span>' : $lang_ticket_entry_menu[$lang]); ?>
                                        </h3>
                                        <ul class="list-group">
                                            <li class="list-group-item" ng-repeat="ticket in entryTicketsCheck">
                                                <span class="bangla">{{ticket.ticketName}}</span>
                                                <span class="badge closeBadge">{{ticket.ticketNum}} x {{ticket.ticketPrice}}</span>

                                                <span class="ticketPick">
                                                {{ticket.total}} BDT
                                            </span>
                                            </li>
                                            <li class="list-group-item" ng-if="totalSafariCheck > 0">
                                                <span style="font-weight: bold">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_order_total[$lang] . '</span>' : $lang_order_total[$lang]); ?>
                                                </span>

                                                <span style="font-weight: bold" class="ticketPick">
                                                    {{totalEntryPrice}} BDT
                                                </span>
                                            </li>

                                            <li class="list-group-item">
                                                <span style="font-weight: bold">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_visit_date[$lang] . '</span>' : $lang_ticket_visit_date[$lang]); ?>
                                                </span>

                                                <span style="font-weight: bold" class="ticketPick">
                                                    {{entryTravleDate}}
                                                </span>
                                            </li>

                                            <li class="list-group-item">
                                                <span style="font-weight: bold">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_visit_time[$lang] . '</span>' : $lang_ticket_visit_time[$lang]); ?>
                                                </span>

                                                <span style="font-weight: bold" class="ticketPick">
                                                    {{entryTimeName}}
                                                </span>
                                            </li>

                                            <li class="list-group-item">
                                                <span style="font-weight: bold">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_expire_date[$lang] . '</span>' : $lang_ticket_expire_date[$lang]); ?>
                                                </span>

                                                <span style="font-weight: bold" class="ticketPick">
                                                    {{entryTicketValid}}
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>


                            <div ng-If="totalSafariCheck > 0">
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <h3 class="bangla">
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_safari_menu[$lang] . '</span>' : $lang_ticket_safari_menu[$lang]); ?>
                                        </h3>
                                        <ul class="list-group">
                                            <li class="list-group-item" ng-repeat="ticket in safariTicketsCheck">
                                                <span class="bangla">{{ticket.ticketName}}</span>
                                                <span class="badge closeBadge">{{ticket.ticketNum}} x {{ticket.ticketPrice}}</span>

                                                <span class="ticketPick">
                                                {{ticket.total}} BDT
                                            </span>
                                            </li>
                                            <li class="list-group-item" ng-if="totalEntryCheck > 0">
                                                <span style="font-weight: bold">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_order_total[$lang] . '</span>' : $lang_order_total[$lang]); ?>
                                                </span>

                                                <span style="font-weight: bold" class="ticketPick">
                                                    {{totalSafariPrice}} BDT
                                                </span>
                                            </li>

                                            <li class="list-group-item">
                                                <span style="font-weight: bold">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_visit_date[$lang] . '</span>' : $lang_ticket_visit_date[$lang]); ?>
                                                </span>

                                                <span style="font-weight: bold" class="ticketPick">
                                                    {{safariTravleDate}}
                                                </span>
                                            </li>

                                            <li class="list-group-item">
                                                <span style="font-weight: bold">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_visit_time[$lang] . '</span>' : $lang_ticket_visit_time[$lang]); ?>
                                                </span>

                                                <span style="font-weight: bold" class="ticketPick">
                                                    {{safariTimeName}}
                                                </span>
                                            </li>

                                            <li class="list-group-item">
                                                <span style="font-weight: bold">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_expire_date[$lang] . '</span>' : $lang_ticket_expire_date[$lang]); ?>
                                                </span>

                                                <span style="font-weight: bold" class="ticketPick">
                                                    {{safariTicketValid}}
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">

                                    <ul class="list-group">
                                        <li class="list-group-item">
                                           <span>
                                               <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_invoice_sub_total[$lang] . '</span>' : $lang_invoice_sub_total[$lang]); ?>
                                           </span>

                                            <span class="ticketPick">
                                                {{subTotal}} BDT
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                                <span>
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_invoice_process_fee[$lang] . '</span>' : $lang_invoice_process_fee[$lang]); ?>
                                                </span>

                                            <span class="ticketPick">
                                                {{purchaseVat}} BDT
                                            </span>
                                        </li>

                                        <li class="list-group-item">
                                                <span style="font-weight: bold">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_invoice_total[$lang] . '</span>' : $lang_invoice_total[$lang]); ?>
                                                </span>

                                            <span style="font-weight: bold" class="ticketPick">
                                                {{totalPriceCheck}} BDT
                                            </span>
                                        </li>

                                        <li class="list-group-item">
                                            <span style="font-weight: bold">
                                                <span ng-if="!userState"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_sign_in[$lang] . '</span>' : $lang_sign_in[$lang]); ?></span>
                                                <span ng-if="userState"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm[$lang] . '</span>' : $lang_confirm[$lang]); ?></span>
                                            </span>
                                            <span class="ticketPick" style="margin-top: -6px;">
                                                <button ng-if="userState" ng-click="confirmOrder()"
                                                        class="btn btn-sm btn-success btn-noradius">
                                                    <i ng-if="loader"
                                                       class="fa fa-circle-o-notch fa-spin"></i>
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm[$lang] . '</span>' : $lang_confirm[$lang]); ?>
                                                </button>

                                                <button ng-if="!userState" data-toggle="modal"
                                                        data-target="#loginModal"
                                                        class="btn btn-sm btn-info btn-noradius">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_sign_in[$lang] . '</span>' : $lang_sign_in[$lang]); ?>
                                                </button>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>


        </div>

    </section>


    <!--LOGIN Modal-->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="loginModalLabel">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_sign_in[$lang] . '</span>' : $lang_sign_in[$lang]); ?>
                    </h4>
                </div>
                <div class="modal-body">

                    <div class="row" ng-if="LoginWarning != ''">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i
                                            class="fa fa-times"></i></button>
                                <strong><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_warning[$lang] . '</span>' : $lang_warning[$lang]); ?></strong>
                                <span <?php echo($lang == 'bn' ? 'class="bangla"' : ''); ?>>{{LoginWarning}}</span>
                                <i class="icon icon-alerts-01"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control <?php echo($lang == 'bn' ? 'bangla' : ''); ?>"
                               placeholder="<?php echo $lang_email_phone[$lang]; ?>"
                               ng-model="LoginUserName">
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control <?php echo($lang == 'bn' ? 'bangla' : ''); ?>"
                               placeholder="<?php echo $lang_password[$lang]; ?>"
                               ng-model="LoginPassWord">
                    </div>

                    <div class="form-group">
                        <span>
                            &nbsp;
                        </span>

                        <span class="ticketPick">
                            <a href="#" ng-click="closeLogin()" data-toggle="modal" data-target="#registerModal">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_register[$lang] . '</span>' : $lang_register[$lang]); ?>
                            </a>

                            |

                            <a href="#" ng-click="closeBoth()" data-toggle="modal" data-target="#forgetModal">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_forget_password[$lang] . '</span>' : $lang_forget_password[$lang]); ?>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm btn-noradius" data-dismiss="modal">Close
                    </button>
                    <button type="button" ng-click="userLogin()" class="btn btn-success btn-sm btn-noradius">
                        <i ng-if="loader" class="fa fa-circle-o-notch fa-spin"></i>
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_sign_in[$lang] . '</span>' : $lang_sign_in[$lang]); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--LOGIN Modal-->


    <!--Register Modal-->
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="registerModalLabel">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_register[$lang] . '</span>' : $lang_register[$lang]); ?>
                    </h4>
                </div>
                <div class="modal-body">

                    <div class="row" ng-if="RegisterSuccess != ''">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i
                                            class="fa fa-times"></i></button>
                                <strong><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_success[$lang] . '</span>' : $lang_success[$lang]); ?></strong>
                                <span <?php echo($lang == 'bn' ? 'class="bangla"' : ''); ?>>{{RegisterSuccess}}</span>
                                <i class="icon icon-alerts-01"></i>
                            </div>
                        </div>
                    </div>


                    <div class="row" ng-if="RegisterWarning != ''">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i
                                            class="fa fa-times"></i></button>
                                <strong><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_warning[$lang] . '</span>' : $lang_warning[$lang]); ?></strong>
                                <span class="bangla">{{RegisterWarning}}</span>
                                <i class="icon icon-alerts-01"></i>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <input type="text" class="form-control <?php echo($lang == 'bn' ? 'bangla' : ''); ?>"
                               placeholder="<?php echo $lang_search_name[$lang]; ?>"
                               ng-model="RegisterFullName">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control <?php echo($lang == 'bn' ? 'bangla' : ''); ?>"
                               placeholder="<?php echo $lang_user_email[$lang]; ?>"
                               ng-model="RegisterUserEmail">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control <?php echo($lang == 'bn' ? 'bangla' : ''); ?>"
                               placeholder="<?php echo $lang_user_mobile[$lang]; ?>"
                               ng-model="RegisterUserPhone">
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control <?php echo($lang == 'bn' ? 'bangla' : ''); ?>"
                               placeholder="<?php echo $lang_password[$lang]; ?>"
                               ng-model="RegisterPassWord">
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control <?php echo($lang == 'bn' ? 'bangla' : ''); ?>"
                               placeholder="<?php echo $lang_re_password[$lang]; ?>"
                               ng-model="RegisterPassWordRe">
                    </div>

                    <div class="form-group">
                        <span>
                            &nbsp;
                        </span>

                        <span class="ticketPick">
                            <a href="#" ng-click="closeRegister()" data-toggle="modal" data-target="#loginModal">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_sign_in[$lang] . '</span>' : $lang_sign_in[$lang]); ?>
                            </a>

                            |

                            <a href="#" ng-click="closeBoth()" data-toggle="modal" data-target="#forgetModal">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_forget_password[$lang] . '</span>' : $lang_forget_password[$lang]); ?>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm btn-noradius" data-dismiss="modal">Close
                    </button>
                    <button type="button" ng-click="userRegister()" class="btn btn-success btn-sm btn-noradius">
                        <i ng-if="loader" class="fa fa-circle-o-notch fa-spin"></i>
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_register[$lang] . '</span>' : $lang_register[$lang]); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--Register Modal-->


    <!--Forget Modal-->
    <div class="modal fade" id="forgetModal" tabindex="-1" role="dialog" aria-labelledby="forgetModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="forgetModalLabel">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_forget_password[$lang] . '</span>' : $lang_forget_password[$lang]); ?>
                    </h4>
                </div>
                <div class="modal-body">

                    <div class="row" ng-if="ForgetSuccess != ''">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i
                                            class="fa fa-times"></i></button>
                                <strong><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_success[$lang] . '</span>' : $lang_success[$lang]); ?></strong>
                                <span <?php echo($lang == 'bn' ? 'class="bangla"' : ''); ?>>{{ForgetSuccess}}</span>
                                <i class="icon icon-alerts-01"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row" ng-if="ForgetWarning != ''">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i
                                            class="fa fa-times"></i></button>
                                <strong><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_warning[$lang] . '</span>' : $lang_warning[$lang]); ?></strong>
                                <span <?php echo($lang == 'bn' ? 'class="bangla"' : ''); ?>>{{ForgetWarning}}</span>
                                <i class="icon icon-alerts-01"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control <?php echo($lang == 'bn' ? 'bangla' : ''); ?>"
                               placeholder="<?php echo $lang_email_phone[$lang]; ?>"
                               ng-model="ForgetUserName">
                    </div>


                    <div class="form-group">
                        <span>
                            &nbsp;
                        </span>

                        <span class="ticketPick">
                            <a href="#" ng-click="closeLogin()" data-toggle="modal" data-target="#registerModal">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_register[$lang] . '</span>' : $lang_register[$lang]); ?>
                            </a>

                            |

                            <a href="#" ng-click="closeRegister()" data-toggle="modal" data-target="#loginModal">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_sign_in[$lang] . '</span>' : $lang_sign_in[$lang]); ?>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm btn-noradius" data-dismiss="modal">Close
                    </button>
                    <button type="button" ng-click="userForget()" class="btn btn-success btn-sm btn-noradius">
                        <i ng-if="loader" class="fa fa-circle-o-notch fa-spin"></i>
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_reset_pass[$lang] . '</span>' : $lang_reset_pass[$lang]); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--Forget Modal-->

    <!--Account Modal-->
    <div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="accountModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="accountModalLabel">
                        Hello, {{userFullName}}
                        <i ng-click="accountSettings()" data-toggle="tooltip" title="Settings" class="fa fa-gear"
                           style="font-size: 20px; cursor: pointer; margin: 0 5px"></i>

                        <i ng-click="logOut()" data-toggle="tooltip" title="Logout" class="fa fa-sign-out"
                           style="font-size: 20px; cursor: pointer; margin: 0 5px"></i>
                    </h4>
                </div>
                <div class="modal-body">

                    <div class="row" ng-if="PasswordReset">
                        <div class="col-md-10 col-md-offset-1">
                            <div style="position: relative">
                                <div ng-click="bookBack()"
                                     style="position: absolute; left: 0; top:0; font-size: 20px; cursor: pointer; z-index: 15">
                                    <i class="fa fa-arrow-left"></i>
                                </div>

                                <h4 style="text-align: center">
                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_reset_pass[$lang] . '</span>' : $lang_reset_pass[$lang]); ?>
                                </h4>

                                <div class="row" ng-if="UserPassSuccess != ''">
                                    <div class="col-md-12">
                                        <div class="alert alert-success">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                <i
                                                        class="fa fa-times"></i></button>
                                            <strong><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_success[$lang] . '</span>' : $lang_success[$lang]); ?></strong>
                                            <span <?php echo($lang == 'bn' ? 'class="bangla"' : ''); ?>>{{UserPassSuccess}}</span>
                                            <i class="icon icon-alerts-01"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" ng-if="UserPassWarning != ''">
                                    <div class="col-md-12">
                                        <div class="alert alert-warning">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                <i
                                                        class="fa fa-times"></i></button>
                                            <strong><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_warning[$lang] . '</span>' : $lang_warning[$lang]); ?></strong>
                                            <span <?php echo($lang == 'bn' ? 'class="bangla"' : ''); ?>>{{UserPassWarning}}</span>
                                            <i class="icon icon-alerts-01"></i>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <input type="password"
                                           class="form-control <?php echo($lang == 'bn' ? 'bangla' : ''); ?>"
                                           placeholder="<?php echo $lang_password[$lang]; ?>"
                                           ng-model="UserPass.password">
                                </div>

                                <div class="form-group">
                                    <input type="password"
                                           class="form-control <?php echo($lang == 'bn' ? 'bangla' : ''); ?>"
                                           placeholder="<?php echo $lang_re_password[$lang]; ?>"
                                           ng-model="UserPass.confirm">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row" ng-if="singleBooking.length > 0 && !PasswordReset">
                        <div class="col-md-10 col-md-offset-1">
                            <div ng-repeat="book in singleBooking" style="position: relative">
                                <div ng-click="bookBack()"
                                     style="position: absolute; left: 0; top:0; font-size: 20px; cursor: pointer; z-index: 15">
                                    <i class="fa fa-arrow-left"></i>
                                </div>

                                <h4 ng-if="book.type == 'entryTicket'" style="text-align: center">
                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_entry_menu[$lang] . '</span>' : $lang_ticket_entry_menu[$lang]); ?>
                                </h4>

                                <h4 ng-if="book.type == 'safariTicket'" style="text-align: center">
                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_safari_menu[$lang] . '</span>' : $lang_ticket_safari_menu[$lang]); ?>
                                </h4>

                                <hr>

                                <div class="row bottomLine">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_status[$lang] . '</span>' : $lang_user_status[$lang]); ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <span ng-if="book.status == 0">
                                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_peyment_default[$lang] . '</span>' : $lang_peyment_default[$lang]); ?>
                                            </span>
                                            <span ng-if="book.status == 1">
                                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_peyment_canceled[$lang] . '</span>' : $lang_peyment_canceled[$lang]); ?>
                                            </span>
                                            <span ng-if="book.status == 2">
                                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_peyment_failed[$lang] . '</span>' : $lang_peyment_failed[$lang]); ?>
                                            </span>
                                            <span ng-if="book.status == 3">
                                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_peyment_expired[$lang] . '</span>' : $lang_peyment_expired[$lang]); ?>
                                            </span>
                                            <span ng-if="book.status == 4">
                                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_peyment_used[$lang] . '</span>' : $lang_peyment_used[$lang]); ?>
                                            </span>
                                            <span ng-if="book.status == 5">
                                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_peyment_paid[$lang] . '</span>' : $lang_peyment_paid[$lang]); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row bottomLine">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_invoice[$lang] . '</span>' : $lang_ticket_invoice[$lang]); ?>
                                        </div>
                                        <div class="col-sm-6">
                                            {{book.invoice_id}}
                                        </div>
                                    </div>
                                </div>

                                <div class="row bottomLine">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_search_mobile[$lang] . '</span>' : $lang_search_mobile[$lang]); ?>
                                        </div>
                                        <div class="col-sm-6">
                                            {{book.phone}}
                                        </div>
                                    </div>
                                </div>


                                <div class="row bottomLine">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_pnrNumber[$lang] . '</span>' : $lang_ticket_pnrNumber[$lang]); ?>
                                        </div>
                                        <div class="col-sm-6">
                                            {{book.PNR}}
                                        </div>
                                    </div>
                                </div>


                                <div class="row bottomLine">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_total_paid[$lang] . '</span>' : $lang_ticket_total_paid[$lang]); ?>
                                        </div>
                                        <div class="col-sm-6">
                                            {{book.total_amount}} BDT
                                        </div>
                                    </div>
                                </div>

                                <div class="row bottomLine">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_visit_date[$lang] . '</span>' : $lang_ticket_visit_date[$lang]); ?>
                                        </div>
                                        <div class="col-sm-6">
                                            {{moment.unix(book.visit_date).format("DD/MM/YYYY")}}
                                        </div>
                                    </div>
                                </div>

                                <div class="row bottomLine">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_visit_time[$lang] . '</span>' : $lang_ticket_visit_time[$lang]); ?>
                                        </div>
                                        <div class="col-sm-6" ng-bind-html="book.visit_time | sce">

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_expire_date[$lang] . '</span>' : $lang_ticket_expire_date[$lang]); ?>
                                        </div>
                                        <div class="col-sm-6">
                                            {{moment.unix(book.valid_date).format("DD/MM/YYYY")}}
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div ng-repeat="ticket in book.tickets">
                                    <div class="bangla" style="margin-top: 5px">
                                        {{ticket.name}}
                                        <span style="float: right">{{ticket.total}} টিকেট</span>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div ng-if="singleBooking.length == 0 && !PasswordReset" class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <ul class="list-group">
                                <li ng-if="bookingData.length > 0" ng-repeat="booking in bookingData"
                                    class="list-group-item orderList" style="padding: 0; border:none">
                                    <div ng-click="bookingDetail(booking.id)"
                                         ng-class="{'OrderDefault': booking.status == 0,'OrderCancel' : booking.status == 1,'OrderFail':booking.status == 2, 'OrderExpire':booking.status == 3, 'OrderUsed':booking.status == 4,'OrderSuccess':booking.status == 5}">
                                        <div>
                                        <span style="font-weight: bold">
                                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_invoice[$lang] . '</span>' : $lang_ticket_invoice[$lang]); ?>
                                            :
                                        {{booking.invoice_id}}
                                        </span>

                                            <span class="ticketPick">
                                        {{booking.total_amount}} BDT
                                        </span>
                                        </div>

                                        <div>
                                        <span>
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_purchase_date[$lang] . '</span>' : $lang_ticket_purchase_date[$lang]); ?>
                                            : {{moment.unix(booking.created_at).format("DD/MM/YYYY")}}
                                        </span>

                                            <span class="ticketPick">
                                            <span ng-if="booking.type == 'entryTicket'">
                                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_entry_menu[$lang] . '</span>' : $lang_ticket_entry_menu[$lang]); ?>
                                            </span>
                                            <span ng-if="booking.type == 'safariTicket'">
                                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_safari_menu[$lang] . '</span>' : $lang_ticket_safari_menu[$lang]); ?>
                                            </span>
                                        </span>
                                        </div>
                                    </div>
                                </li>


                                <li ng-if="bookingData.length == 0" style="text-align: center" class="list-group-item">
                                    <span>
                                        No orders found
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm btn-noradius" data-dismiss="modal">Close
                    </button>

                    <button ng-if="PasswordReset" type="button" ng-click="userPassword()"
                            class="btn btn-success btn-sm btn-noradius">
                        <i ng-if="loader" class="fa fa-circle-o-notch fa-spin"></i>
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_reset_pass[$lang] . '</span>' : $lang_reset_pass[$lang]); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--Account Modal-->

    <!-- Footer -->
    <footer class="site-footer section pb-0 background-grey">
        <!-- Copyright -->
        <div class="copyright background-white">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-6 text-left">
                        <strong><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_app_name[$lang] . '</span>' : $lang_app_name[$lang]); ?></strong>
                        - <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_app_copyright[$lang] . '</span>' : $lang_app_copyright[$lang]); ?>
                        &copy; <?php echo date("Y"); ?>
                    </div>
                    <div class="col-md-6 col-sm-6" style="text-align: right">
                        <?php echo($lang == 'bn' ? '<a class="text-dark" href="/index.php?lang=en">English</a> | <span class="bangla">বাংলা</span>' : 'English | <a class="text-dark" href="/index.php?lang=bn"><span class="bangla">বাংলা</span></a>'); ?>
                    </div>
                </div>
            </div>
        </div><!-- .copyright end -->
    </footer><!-- .site-footer end -->

    <a class="backToTop scrollto" href="#"><i class="fa fa-angle-up"></i></a>

</div>


<script type="text/javascript" src="/assets/site/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/site/js/plugins.js"></script>
<script type="text/javascript" src="/assets/site/js/main.js"></script>


</body>
</html>