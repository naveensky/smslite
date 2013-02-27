'use strict';

//service for loader module
angular.module('LoaderServices', [])
    .config(function ($httpProvider) {
        $httpProvider.responseInterceptors.push('appHttpInterceptor');
        var spinnerFunction = function (data, headersGetter) {
            $('#ajax-loader').show();
            return data;
        };
        $httpProvider.defaults.transformRequest.push(spinnerFunction);
    })
    // register the interceptor as a service, intercepts ALL angular ajax http calls
    .factory('appHttpInterceptor', function ($q, $window) {
        return function (promise) {
            return promise.then(function (response) {
                $('#ajax-loader').hide();
                return response;
            }, function (response) {
                $('#ajax-loader').hide();
                return $q.reject(response);
            });
        };
    });

// Declare app level module which depends on filters, and services
angular.module('app', ['LoaderServices']).
    config(['$routeProvider', function ($routeProvider) {
    $routeProvider.when('/user/login', {templateUrl:'user/login', controller:'User_Login'})
        .when('/user/register', {templateUrl:'/user/register', controller:'User_Register'})
        .when('/user/register/1', {templateUrl:'/user/register', controller:'User_Register'})
        .when('/user/register/2', {templateUrl:'/user/register/2', controller:'User_Register'})
        .when('/user/register/3', {templateUrl:'/user/register/3', controller:'User_Register'})
        .when('/user/register/4', {templateUrl:'/user/register/4', controller:'User_Register'})
        .when('/user/forgot-password', {templateUrl:'/user/forgot_password', controller:'User_Forgot_Password'})
        .when('/user/password_reset_success', {templateUrl:'/user/password_reset_success', controller:'User_Change_Password'})
        .when('/user/invalid_code', {templateUrl:'/user/invalid_code' });

    //student routes
    $routeProvider
        .when('/student', {templateUrl:'/student', controller:'Student_List'})
        .when('/student/upload', {templateUrl:'/student/upload', controller:'Student_Upload'})
        .when('/student/list', {templateUrl:'/student/list', controller:'Student_List'});

    ///teacher routes
    $routeProvider
        .when('/teacher', {templateUrl:'/teacher', controller:'Teacher_List'})
        .when('/teacher/upload', {templateUrl:'/teacher/upload', controller:'Teacher_Upload'})
        .when('/teacher/list', {templateUrl:'/teacher/list', controller:'Teacher_List'});

        ///Report Routes
        $routeProvider
            .when('/report/sms', {templateUrl:'/report/sms', controller:'Report_SMS'});


    $routeProvider.otherwise({redirectTo:'/'});
}]);

function initComponents() {
    initUploader();
}

function initUploader() {
    $('.file-uploader').each(function (i, o) {
        //get current element
        var currentElement = $(this);

        //wrapper element for all markup
        var wrapperDiv = $('<div>');
        wrapperDiv.addClass('file-uploader-wrapper');

        //progress bar section markup
        var progressWrapper = $('<div class="span3 progress-wrapper"><span class="progress-file-name"></span><span class="pull-right progress-percentage"></span></div>');
        var progressDiv = $('<div class="progress progress-blue progress-striped active"><div class="bar" style="width: 0%;"></div></div>');

        progressWrapper.append(progressDiv).hide();

        //create new id for input element
        var id = "id-" + (new Date().getTime());
        var fileInput = $('<input>');
        fileInput.attr('type', 'file').hide();
        fileInput.attr('id', id);

        //add uploader api for file input
        fileInput.fileupload({
            dataType:'json',
            url:currentElement.data('url'), //url for data to be posted
            add:function (e, data) {
                //init all texts, progress and other things
                $(this).parent().find('.progress-file-name').text(data.files[0].name);
                $(this).parent().find('.progress-percentage').text("");
                $(this).parent().find('.progress > .bar ').width("0%");
                $(this).parent().find('.progress-wrapper').show();

                //submit file to upload
                data.submit();
            },
            done:function (e, data) {
                //called when upload is finished
                //find call back function on finish done
                var callback = currentElement.data('done');

                //if callback is not null, find angular scope, and call scope function
                if (callback != undefined && callback != null) {
                    var scope = angular.element(currentElement).scope();
                    scope.$apply(scope[callback](data));        //use $apply to evaluate it correctly
                }

                $(this).parent().find('.progress-wrapper').hide();
            },
            progressall:function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $(this).parent().find('.progress-percentage').text(progress + " %");
                $(this).parent().find('.progress > .bar ').width(progress + "%");
            }
        });

        currentElement.data('fid', id);
        wrapperDiv.insertBefore(currentElement);
        wrapperDiv.append(currentElement);
        wrapperDiv.append(fileInput);
        wrapperDiv.append(progressWrapper);

        $(this).click(function (e) {
            e.preventDefault();
            $('#' + $(this).data('fid')).trigger('click');
        });
    });
}