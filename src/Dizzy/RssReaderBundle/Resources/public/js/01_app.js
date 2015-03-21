var app = angular.module('rssApp',['ngResource','infinite-scroll']);

app.config(['$resourceProvider',function ($resourceProvider) {
    $resourceProvider.defaults.stripTrailingSlashes = false;
}]);

app.config(['$interpolateProvider',function($interpolateProvider){
        $interpolateProvider.startSymbol('[[').endSymbol(']]');
    }
]);

app.filter('orderObjectBy', function(){
    return function(input, attribute) {
        if (!angular.isObject(input)) return input;

        var array = [];
        for(var objectKey in input) {
            array.push(input[objectKey]);
        }

        array.sort(function(a, b){
            return a[attribute].localeCompare(b[attribute]);
        });
        return array;
    }
});

app.filter('filterReadedFeed', function() {
    return function(input) {
        var result = [];
        for(var objectKey in input) {
            if(parseInt(input[objectKey].unreadCount)>0){
                result.push(input[objectKey]);
            }
        }

        return result;
    };
});