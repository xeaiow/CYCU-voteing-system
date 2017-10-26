@extends('layout.main') @section('content')

<div class="ui grid cycuvote-container">

    <div class="sixteen wide column">
        <h2 class="ui icon header center aligned">
            <i class="lab icon"></i>
            <div class="content font-style">
                106 中原資管專題複賽
            </div>
            <div class="sub header">每人一票，且不得重複 ｜ 大一 ～ 大三 ｜ 2017/10/26 ~ 2017/12/31</div>
        </h2>
    </div>

    <div class="sixteen wide column">

        <div class="ui stackable three column grid example-2">
            <div class="column pointer" v-on:click="greet">
                <div class="ui cards stackable">
                    <div class="card">
                        <div class="content">
                            <div class="right floated meta"><i class="comment icon"></i> 29</div>
                            第一組
                        </div>
                        <div class="image">
                            <img src="https://static.collectui.com/shots/3874110/the-night-king-large">
                        </div>
                    </div>
                </div>
            </div>
            <div class="column pointer" v-on:click="greet">
                <div class="ui cards stackable">
                    <div class="card">
                        <div class="content">
                            <div class="right floated meta"><i class="comment icon"></i> 1</div>
                            第二組
                        </div>
                        <div class="image">
                            <img src="https://static.collectui.com/shots/3874525/walk-large">
                        </div>
                    </div>
                </div>
            </div>
            <div class="column pointer" v-on:click="greet">
                <div class="ui cards stackable">
                    <div class="card">
                        <div class="content">
                            <div class="right floated meta"><i class="comment icon"></i> 10</div>
                            第三組
                        </div>
                        <div class="image">
                            <img src="https://static.collectui.com/shots/3873033/fuel-cantina-large">
                        </div>
                    </div>
                </div>
            </div>
            <div class="column pointer" v-on:click="greet">
                <div class="ui cards stackable">
                    <div class="card">
                        <div class="content">
                            <div class="right floated meta"><i class="comment icon"></i> 3</div>
                            第四組
                        </div>
                        <div class="image">
                            <img src="https://static.collectui.com/shots/3872850/homework-large">
                        </div>
                    </div>
                </div>
            </div>
            <div class="column pointer" v-on:click="greet">
                <div class="ui cards stackable">
                    <div class="card">
                        <div class="content">
                            <div class="right floated meta"><i class="comment icon"></i> 71</div>
                            第五組
                        </div>
                        <div class="image">
                            <img src="https://static.collectui.com/shots/3867965/animated-paper-shuttle-launch-large">
                        </div>
                    </div>
                </div>
            </div>
            <div class="column pointer" v-on:click="greet">
                <div class="ui cards stackable">
                    <div class="card">
                        <div class="content">
                            <div class="right floated meta"><i class="comment icon"></i> 52</div>
                            第六組
                        </div>
                        <div class="image">
                            <img src="https://static.collectui.com/shots/3867574/mystic-castle-detail-large">
                        </div>
                    </div>
                </div>
            </div>
            <div class="column pointer" v-on:click="greet">
                <div class="ui cards stackable">
                    <div class="card">
                        <div class="content">
                            <div class="right floated meta"><i class="comment icon"></i> 18</div>
                            第七組
                        </div>
                        <div class="image">
                            <img src="https://static.collectui.com/shots/3872096/washington-post-large">
                        </div>
                    </div>
                </div>
            </div>
            <div class="column pointer" v-on:click="greet">
                <div class="ui cards stackable">
                    <div class="card">
                        <div class="content">
                            <div class="right floated meta"><i class="comment icon"></i> 36</div>
                            第八組
                        </div>
                        <div class="image">
                            <img src="https://static.collectui.com/shots/3873419/town-grems-colormakerz-large">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var example2 = new Vue({
        el: '.example-2',
        data: {
            name: 'Vue.js'
        },
        // define methods under the `methods` object
        methods: {
            greet: function(event) {
                window.location.href = '1/info';
            }
        }
    })
</script>


@endsection()