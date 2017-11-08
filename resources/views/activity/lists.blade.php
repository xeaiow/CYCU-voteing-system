@extends('layout.main') @section('content')

<div class="ui grid cycuvote-container">

    <div class="sixteen wide column">

        <div class="ui stackable three column grid example-2">

            <div class="column pointer" v-on:click="greet">
                <div class="ui cards stackable">
                    <div class="card">
                        <div class="content">
                            <div class="right floated meta"><i class="comment icon"></i> 29</div>
                            106 資管專題競賽
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
                            105 資管專題競賽
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
                            104 資管專題競賽
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
                            103 資管專題競賽
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
                            102 資管專題競賽
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
                            101 資管專題競賽
                        </div>
                        <div class="image">
                            <img src="https://static.collectui.com/shots/3867574/mystic-castle-detail-large">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


        {{--  // define methods under the `methods` object
        // methods: {
        //     greet: function(event) {
        //         window.location.href = 'activity/1';
        //     },
        //     get_activity: function(event) {
        //         axios.get('//localhost/vote/public/get')
        //             .then(function(response) {
        //                 example2.info = response.data;
        //             })
        //             .catch(function(error) {
        //                 /* 失敗，發生錯誤，然後...*/
        //             });
        //         console.log(example2.info);
        //     }
        // },
        // mounted: function() {
        //     this.get_activity()
        // }  --}}


@endsection()