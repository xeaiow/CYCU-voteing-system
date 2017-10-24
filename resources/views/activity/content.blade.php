@extends('layout.main') @section('content')

<div class="ui grid cycuvote-container">
    <!-- card -->
    <div class="four wide column">
        <div class="ui card">
            <div class="image">
                <img src="http://i.imgur.com/1VxbDzN.png">
            </div>
            <div class="content  center aligned">
                <a class="header font-style">Meet 覓</a>
            </div>
        </div>
        <div class="ui vertical fluid menu">
            <div class="ui segment basic">
                <div class="ui list">
                    <div class="item">
                        <i class="bookmark icon"></i>
                        <div class="content font-style">
                            B 場次第十二組
                        </div>
                    </div>
                    <div class="item">
                        <i class="user icon"></i>
                        <div class="content font-style">
                            大一 ~ 大三
                        </div>
                    </div>
                    <div class="item">
                        <i class="calendar icon"></i>
                        <div class="content font-style">
                            2017/10/24 ~ 2017/12/31
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui bottom black attached button font-style">投給他</div>
    </div>
    <div class="nine wide column">
        <div class="ui piled segment">
            現今社交平台豐富多樣化，而Facebook 最廣為人知， 大家都有屬於自己的帳號，因此我們使用爬蟲的方式， 抓取使用者間接透露的興趣及關注的事物，加以分析後， 利用關聯性來讓大家媒合，呈現手牽手的概念來創造商業價值。
        </div>
        <div class="ui small images">
            <img src="http://140.135.67.5/Project/102GP/B12/images/1.png">
            <img src="http://140.135.67.5/Project/102GP/B12/images/2.png">
            <img src="http://140.135.67.5/Project/102GP/B12/images/3.png">
            <img src="http://140.135.67.5/Project/102GP/B12/images/4.png">
        </div>
    </div>
</div>

@endsection()