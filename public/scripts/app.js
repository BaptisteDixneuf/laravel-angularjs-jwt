(function () {
    'use strict';

    angular.module('app', [
        'ngStorage',
        'ngRoute',
        'angular-loading-bar',
        'ngResource'
    ])
        .constant('urls', {
            BASE: 'http://192.168.59.103/laravel5-angular-jwt/public',
            BASE_API: 'http://192.168.59.103/laravel5-angular-jwt/public/api/v1'
        })
        .config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {
            $routeProvider.
                when('/', {
                    templateUrl: 'partials/home.html',
                    controller: 'HomeController'
                }).
                when('/signin', {
                    templateUrl: 'partials/signin.html',
                    controller: 'HomeController'
                }).
                when('/signup', {
                    templateUrl: 'partials/signup.html',
                    controller: 'HomeController'
                }).
                when('/restricted', {
                    templateUrl: 'partials/restricted.html',
                    controller: 'RestrictedController'
                }).
                when('/postsIndex', {
                    templateUrl: 'partials/posts/postsIndex.html',
                    controller: 'PostsController'
                }).
                when('/postCreate', {
                    templateUrl: 'partials/posts/postCreate.html',
                    controller: 'PostsController'
                }).
                when('/postView/:idPost', {
                    templateUrl: 'partials/posts/postView.html',
                    controller: 'PostsController'
                }).
                when('/postUpdate/:idPost', {
                    templateUrl: 'partials/posts/postUpdate.html',
                    controller: 'PostsController'
                }).
                otherwise({
                    redirectTo: '/'
                });

            $httpProvider.interceptors.push(['$q', '$location', '$localStorage', 'urls', function ($q, $location, $localStorage, urls) {
                return {
                    'request': function (config) {
                        config.headers = config.headers || {};
                        if ($localStorage.token) {
                            config.headers.Authorization = 'Bearer ' + $localStorage.token;
                        }
                        return config;
                    },
                    'responseError': function (response) {
                        if (response.status === 401 || response.status === 403) {
                            delete $localStorage.token;
                            setTimeout(function(){
                                window.location = urls.BASE;
                            }, 500);
                        }
                        return $q.reject(response);
                    }
                };
            }]);
        }
        ]).run(function($rootScope, $location, $localStorage) {
            $rootScope.$on( "$routeChangeStart", function(event, next) {                
                if ($localStorage.token == null) {
                    if ( next.templateUrl === "partials/restricted.html") {
                        $location.path("/signin");
                    }
                }
            });
        });
})();