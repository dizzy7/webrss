app.controller('feedsController', ['$scope', '$http', '$rootScope', function ($scope, $http, $rootScope) {
    $scope.feeds = {};
    $scope.loaded = false;

    $http.post(Routing.generate('api_get_feeds')).success(function (data) {
        angular.forEach(data.data, function (feed) {
            $scope.feeds[feed.id] = feed;
        });
        $scope.loaded = true;
    }).catch(function(e){
        console.log(e);
    });

    $scope.changeFeed = function () {
        angular.forEach($scope.feeds, function (value, key) {
            $scope.feeds[key].active = false;
        });
        var e = this.feed;
        e.active = true;
        $rootScope.$broadcast('changeFeed', {feed: e.id, title: e.title})
    };

    $rootScope.$on('postRead', function (e, data) {
        $scope.feeds[data.feed].unreadCount--;
    });
}]);

app.controller('mainController', ['$scope', '$http', '$rootScope', '$sce', function ($scope, $http, $rootScope, $sce) {
    $scope.h1 = 'Все ленты';
    $scope.loaded = false;
    $scope.loading = false;
    $scope.posts = [];
    $scope.lastId = '0';
    $scope.currentFeed = 'all';

    $scope.setRead = function () {
        var post = this.post;


        $http.post(Routing.generate('api_set_post_read', {post: post.id})).success(function (data) {
            if (data.success) {
                post.read = true;
                $rootScope.$broadcast('postRead', {feed: post.feed_id, post: post.id});
            }
        });
    };

    $scope.saveToPocket = function(post){
        new Image().src = 'http://getpocket.com/edit?url='+encodeURIComponent(post.url);
        var msg = $("#msg");
        $("#msgText").text('Сохранено в Pocket!');
        setTimeout(function(){
            msg.addClass('in');
            setTimeout(function(){
                msg.addClass('out');
                setTimeout(function(){
                    msg.removeClass('out').removeClass('in');
                },2000);
            },500);
        },1000);
    };

    $scope.nextPage = function () {
        if ($scope.loading) {
            return;
        }

        $scope.loading = true;

        $http.post(Routing.generate('api_get_posts', {
            feed: $scope.currentFeed,
            fromId: $scope.lastId
        })).success(function (data) {
            angular.forEach(data, function (e) {
                $scope.lastId = e.id;
                var date = moment(e.created.date, 'YYYY-MM-DD HH:mm:ss');
                date = date.format('DD.MM.YYYY HH:mm');
                e.created = date;
                e.title = $sce.trustAsHtml(e.title);
                e.body = $sce.trustAsHtml(e.body);
                $scope.posts.push(e);
            });
            $scope.loaded = true;
            $scope.loading = false;
        });
    };

    $rootScope.$on('changeFeed', function (e, data) {
        $scope.lastId = '0';
        $scope.currentFeed = data.feed;
        $scope.h1 = data.title;
        $scope.posts = [];
        $scope.lastDate = '0000-00-00 00:00:00';
        $scope.nextPage();
    });

}]);