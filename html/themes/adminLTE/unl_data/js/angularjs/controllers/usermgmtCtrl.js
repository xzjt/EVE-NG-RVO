function usermgmtController($scope, $http, $rootScope, $uibModal, $log) {
	$scope.testAUTH("/usermgmt"); //TEST AUTH
	$scope.userdata='';
	//Invisible columns//START
	// $scope.sessionTime=false;
	// $scope.sessionIP=false;
	// $scope.currentFolder=false;
	// $scope.currentLab=false;
	$scope.sessionTime='';
	$scope.sessionIP='';
	$scope.currentFolder='';
	$scope.currentLab='';
	$scope.edituser='';
	////Invisible columns//END
	$('body').removeClass().addClass('hold-transition skin-blue layout-top-nav');
	//Get users //START
	$scope.getUsersInfo = function(){
	$http.get('/api/users/').then(
			function successCallback(response) {
				//console.log(response.data.data);
				$scope.userdata=response.data.data;
				$.unblockUI();
			}, 
			function errorCallback(response) {
				$.unblockUI();
				console.log("Unknown Error. Why did API doesn't respond?"); $location.path("/login");}	
	);
	}
	$scope.getUsersInfo()
	//Get users //END
	/////////////////
	//Delete user //START
	$scope.deleteUser = function(username){
		if (username == 'admin'){
			toastr["error"]("Admin is not allowed to deleted!", "Error");
		}else if (confirm('Are you sure you want to delete user '+username+'?')) {
			$http({
				method: 'DELETE',
				url: '/api/users/'+username})
			.then(
				function successCallback(response) {
					//console.log(response)
					$scope.getUsersInfo()
				}, 
				function errorCallback(response) {
					console.log(response)
					console.log("Unknown Error. Why did API doesn't respond?")
					$location.path("/login");
				}
			);
		} else return;
	}
	//Delete user //END
	//////////////////
	//Time converter //START
	$scope.timeConverter = function(UNIX_timestamp){
		var a = new Date(UNIX_timestamp * 1000);
		var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
		var year = a.getFullYear();
		var month = months[a.getMonth()];
		var date = a.getDate();
		var hour = a.getHours();
		var min = a.getMinutes();
		var sec = a.getSeconds();
		var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
		return time;
	}
	//Time converter //END
	///////////////////////
	//More controllers //START
	ModalCtrl($scope, $uibModal, $log)
	//More controllers //END
}

// Stop All Nodes
//$app -> delete('/api/status', function() use ($app, $db) {
$scope.stopAll = function() {
	$http({method: 'DELETE', url: '/api/status'}).then(
		function successCallback(response) {
				console.log(response)
		},
		function errorCallback(response) {
				console.log(response)
		}
	);
}

// Fix Permissions
$scope.fixpermissions = function() {
	html_loader = "<div id='progress-loader'><label style='float:left'>Fix Permissions...</label><div class='loader'></div></div>";
	$(".content-wrapper").append(html_loader);
	$http({
		method: 'GET',
		url: '/actions.php?action=fix'
	})
	.then(
		function successCallback(response) {
			$("#progress-loader").remove();
			toastr["success"]('Fix permission Successfully!', 'Success');
		},
		function errorCallback(response) {
			$("#progress-loader").remove();
			toastr["error"]('Fix permission Failed!', 'Error');
		}
	);
}

// IOU License
$scope.IOUlicense = function() {
	html_loader = "<div id='progress-loader'><label style='float:left'>Generateing License...</label><div class='loader'></div></div>";
	$(".content-wrapper").append(html_loader);
	$http({
		method: 'GET',
		url: '/actions.php?action=iol'
	})
	.then(
		function successCallback(response) {
			$("#progress-loader").remove();
			toastr["success"]('IOU License Generate Successfully!', 'Success');
		},
		function errorCallback(response) {
			$("#progress-loader").remove();
			toastr["error"]('IOU License Generate Failed!', 'Error');
		}
	);
}