(function () {
    'use strict';

    angular.module('app')
        .controller('HomeController', ['$rootScope', '$scope', '$location', '$localStorage', 'Auth', 'urls',
            function ($rootScope, $scope, $location, $localStorage, Auth, urls) {
                function successAuth(res) {                    
                    $localStorage.token = res.token;
                    setTimeout(function(){
                        window.location = urls.BASE;
                    }, 500);
                }

                $scope.signin = function () {
                    var formData = {
                        email: $scope.email,                       
                        password: $scope.password
                    };

                    Auth.signin(formData, successAuth, function () {
                        $rootScope.error = 'Invalid credentials.';
                    })
                };

                $scope.signup = function () {
                    var formData = {
                        email: $scope.email,
                        name: $scope.name,
                        password: $scope.password
                    };

                    Auth.signup(formData, successAuth, function (res) {                        
                        $rootScope.error = res.error || 'Failed to sign up.';
                    })
                };

                $scope.logout = function () {
                    Auth.logout(function () {
                        window.location = urls.BASE;
                    });
                };
                $scope.token = $localStorage.token;
                $scope.tokenClaims = Auth.getTokenClaims();
            }])

        .controller('RestrictedController', ['$rootScope', '$scope', 'Data', 'urls',
            function ($rootScope, $scope, Data, urls) {
                Data.getRestrictedData(function (res) {
                    $scope.data = res.data;
                }, function () {
                    $rootScope.error = 'Failed to fetch restricted content.';
                });
                Data.getApiData(function (res) {
                    $scope.api = res.data;
                }, function () {
                    $rootScope.error = 'Failed to fetch restricted API content.';
                });
        }]);
})();