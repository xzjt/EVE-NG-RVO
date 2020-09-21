function syslogController($scope, $http, $rootScope) {
	$scope.testAUTH("/syslog"); //TEST AUTH
	$('body').removeClass().addClass('hold-transition skin-blue layout-top-nav');
	$scope.fileselect=false;
	$scope.lineCount=20;
	$scope.searchText='';
	$scope.logInfo=[];
	$scope.accessLog=[];
	$scope.apiLog=[];
	$scope.errorLog=[];
	$scope.php_errorsLog=[];
	$scope.unl_wrapperLog=[];
	$scope.blockButtons=false;
	$scope.blockButtonsClass='';
	$scope.logfiles= ['access.txt', 'api.txt','error.txt','php_errors.txt','unl_wrapper.txt','cpulimit.log']
	
	$scope.readFile = function(filename){
		//console.log(filename)
		filename = (filename === undefined) ? $scope.fileSelection : filename
		$scope.blockButtons=true;
		$scope.blockButtonsClass='m-progress';
		$scope.logInfo=[];
		$http.get('/api/logs/'+filename+'/'+$scope.lineCount+'/'+$scope.searchText).then(
			function successCallback(response) {
				//console.log(response.data)
				$scope.fileselect=true;	
				$scope.logInfo=response.data;
				$.unblockUI();
				$scope.blockButtons=false;
				$scope.blockButtonsClass='';
			}, 
			function errorCallback(response) {
				console.log(response)
				console.log("Unknown Error. Why did API doesn't respond?"); $location.path("/login");
				$.unblockUI();
				$scope.blockButtons=false;
				$scope.blockButtonsClass='';
				}	
		);
	}
	$scope.readFile('access.txt')
}

// // Stop All Nodes
// //$app -> delete('/api/status', function() use ($app, $db) {
// $scope.stopAll = function() {
// 	$http({method: 'DELETE', url: '/api/status'}).then(
// 		function successCallback(response) {
// 				console.log(response)
// 		},
// 		function errorCallback(response) {
// 				console.log(response)
// 		}
// 	);
// }

// // Fix Permissions
// $scope.fixpermissions = function() {
// 	html_loader = "<div id='progress-loader'><label style='float:left'>Fix Permissions...</label><div class='loader'></div></div>";
// 	$(".content-wrapper").append(html_loader);
// 	$http({
// 		method: 'GET',
// 		url: '/actions.php?action=fix'
// 	})
// 	.then(
// 		function successCallback(response) {
// 			$("#progress-loader").remove();
// 			toastr["success"]('Fix permission Successfully!', 'Success');
// 		},
// 		function errorCallback(response) {
// 			$("#progress-loader").remove();
// 			toastr["error"]('Fix permission Failed!', 'Error');
// 		}
// 	);
// }

// // IOU License
// $scope.IOUlicense = function() {
// 	html_loader = "<div id='progress-loader'><label style='float:left'>Generateing License...</label><div class='loader'></div></div>";
// 	$(".content-wrapper").append(html_loader);
// 	$http({
// 		method: 'GET',
// 		url: '/actions.php?action=iol'
// 	})
// 	.then(
// 		function successCallback(response) {
// 			$("#progress-loader").remove();
// 			toastr["success"]('IOU License Generate Successfully!', 'Success');
// 		},
// 		function errorCallback(response) {
// 			$("#progress-loader").remove();
// 			toastr["error"]('IOU License Generate Failed!', 'Error');
// 		}
// 	);
// }