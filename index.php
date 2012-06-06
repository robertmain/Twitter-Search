<!DOCTYPE html>
<html>
    <head>
        <title>Bootstrap Test</title>
        <link rel="Stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://github.com/phstc/jquery-twitter/raw/master/src/jquery.twitter.js"></script>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="span2 well trending">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="nav-header">Trending Topics</li>
                    </ul>
                </div>


                <div class="span9">
                    <form class="well form-search">
                        <input type="text" placeholder="Write Something" name="search-query" class="input-large search-query">
                        <button type="submit" class="btn btn-success"><i class="icon-white icon-search"></i> Search Twitter</button>
                        <button type="button" class="clear btn btn-danger"><i class="icon-white icon-trash"></i> Clear Tweets</button>
                    </form>
                    <div id="tweets"></div>
                    <script type="text/javascript">
                        var tweetsErrorCallback = function(errorMessage, tweet){
                            var msg = 'Oops! \ <a href="Twitter">dev.twitter.com/pages/rate-limiting">Twitter Rate Limit Exceeded</a>. \ Try again later or search \ <a href="twitter.com/search?q=' + tweet.toQuery() + '">directly on Twitter</a>'; $('#tweets').html(msg);
                        }
                        var tweetsSuccessCallback = function(data){
                            console.log(data);
                            var tweetsLi = '';
                            for(var i = 0; i < data.results.length; i++){
                                var tweet = data.results[i];
                                var dateTweet = new Date(tweet.created_at);
                                var dateNow   = new Date();
                                var dateDiff  = dateNow - dateTweet;
                                var hours     = Math.round(dateDiff/(1000*60*60));
                                $('<div class="tweet well">\n\
                                    <div class="span1">\n\
                                        <img src="' + tweet.profile_image_url + '" alt="' + tweet.from_user + '\'s Profile Picture" class="thumbnail" title="' + tweet.from_user + '\'s Profile Picture" />\n\
                                    </div>\n\
                                    <div class="span10">\n\
                                        <h4><a href="http://twitter.com/' + tweet.from_user + '" target="__blank">' + tweet.from_user + '</a></h4>\n\
                                        <h5><a href="https://twitter.com/' + tweet.from_user + '/status/' + tweet.id +  '" target="__blank">' + hours + ' hours ago</a></h5>\n\
                                        <h6>Posted: ' + tweet.created_at + '</h6>\n\
                                        <p>' + tweet.text + '</p>\n\
                                    </div>\n\
                                </div>').appendTo('#tweets').hide().fadeIn(1500);
                                                    $('div.loading').remove();
                                                }
                                                $('#tweets').wrap('<div class="row-fluid">');
                                            }
						
						
                                            function search(keyword){
                                                var tweets = Twitter.tweets();
                                                $('#tweets').html('<div class="loading"><h6>Loading...<h6><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></div>');
                                                tweets.containing(keyword).all(tweetsSuccessCallback, tweetsErrorCallback);
                                                $('title').html(keyword)
                                            }
						
                                            $('form').on('submit', function(event){
                                                var searchQuery = $('input[name="search-query"]').val();
                                                if(searchQuery.length <= 0){
                                                    alert("Search Query Is Empty");
                                                }
                                                else{
                                                    search(searchQuery);
                                                }
                                                event.preventDefault();
                                            });
                        
                                            $('.nav-pills').on('click','li a',function(event){
                                                var clicked = $(this);
                                                clicked.parent().parent().find('li.active').removeClass('active');
                                                clicked.parent().addClass('active');
                                                search(clicked.attr('data-word'));
                                                $('input[name="search-query"]').val(clicked.attr('data-word'));
                                                event.preventDefault();
                                            });
                                            var date = new Date();
                                            $.ajax({
                                                url: 'https://api.twitter.com/1/trends/daily.json?date=' + date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate(),
                                                type:'GET',
                                                dataType:'jsonp',
                                                crossDomain: true,
                                                error: function(jqXHR, error, errorThrown) {
                                                    console.log(jqXHR.status + ' ' +errorThrown);
                                                },
                                                success: function(response) {
                                                    var trends = response['trends'];
                                                    var k ;
                                                    var obj;
                                                    var last;
                                                    for(k in trends){
                                                        obj = trends[k];
                                                    }
                                                    for(item in obj){
                                                        var TrendingTopic = obj[item];
                                                        $('<li><a data-word="' + TrendingTopic.query + '" href="">' + TrendingTopic.name + '</a></li>').appendTo('.nav-pills')
                                                    }
                                                    console.log(obj);
                                                }
                                            });
                                            
                                            $('.clear').click(function(){
                                                $('#tweets').html(null);
                                            });
                    </script>
                </div>
            </div>
            <hr>
            <footer>
                <p>Twitter Search Client By Robert Main &copy; <?=date('Y')?></p>
                <p>Powered by <a href="https://github.com/phstc/jquery-twitter" target="__blank">phstc/jquery-twitter</a></p>
            </footer>
        </div>
    </body>
</html>
