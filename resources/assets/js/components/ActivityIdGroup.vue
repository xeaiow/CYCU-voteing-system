<template>
    <div>
        <div class="ui inverted menu cycuvote-theme cycuvote-menu fixed">
            <a class="active item font-style" @click="$router.push('/')">中原大學資訊管理學系投票系統</a>
            <a class="item font-style" @click="$router.push('/')">活動列表</a>
            <div class="right menu">
                <a class="item font-style" v-if="token">{{ this.username }} 您好</a>
                <a class="item font-style" v-if="token" @click="logout">登出</a>
                <a class="item font-style" v-if="!token" @click="$router.push('/login')">登入</a>
            </div>
        </div>

        <div class="ui grid stackable cycuvote-container">
            
            <div class="eight wide column centered center aligned" v-if="!message">
                <div class="ui negative message">
                    <div class="header">
                        The page you were looking for doesn't exist.
                    </div>
                    <p>
                        You may have mistyped the address or the page may have moved.
                    </p>
                </div>
            </div>

            <div class="four wide column" v-if="message">
                <div class="ui card fluid">
                    <div class="image">
                        <img v-bind:src="items.img">
                    </div>
                    <div class="content center aligned">
                        <a class="header font-style">{{ items.groups }}</a>
                    </div>
                </div>
                <div class="ui vertical fluid menu">
                    <div class="ui segment basic">
                        <div class="ui list">
                            <div class="item">
                                <i class="user icon"></i>
                                <div class="content font-style">
                                    {{ activitys.permission }}
                                </div>
                            </div>
                            <div class="item">
                                <i class="calendar icon"></i>
                                <div class="content font-style">
                                    {{ activitys.started }} ~ {{ activitys.deadline }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ui bottom black attached button font-style" @click="inputItouch">投給他</div>
            </div>
            <div class="twelve wide column" v-if="message">
                <div class="ui piled segment project-content-text">
                    {{ items.discription }}
                </div>
                <h4 class="ui horizontal divider header">
                    <i class="image icon"></i> 海報預覽
                </h4>
                <div class="ui fluid images">
                    <img v-for="(item, index) in items.photo" :key="index" v-bind:src="item" class="team-images" />
                </div>
            </div>
          
        </div>
    </div>
</template>

<script>
    export default {

        data: function () {

            return {
                items: {},
                activitys: {},
                message: '',
                verifResult:'',
                username: '',
                password: '',
                token: ''
            }
        },
        methods: {
            success: function (message) {

                this.$swal(message, '', 'success')
            },
            failed: function (message) {

                this.$swal(message, '', 'error')
            },
            logout: function () {
                axios.get('//127.0.0.1:8000/logout')
                this.$router.go('/');
            },
            inputItouch: function () {
                
                var selfRoute = this.$route.params.id;
                var self = this;
                
                self.$swal({

                    title: '請輸入 iTouch 驗證身分',
                    html:
                        '<p>經送出後不可反悔，每組學號只有乙次投票機會。</p>' + 
                        '帳號：<input class="swal2-input" name="username" value="10244257" />' +
                        '密碼：<input class="swal2-input" name="password" value="Dream0919" />' + 
                        '<input type="hidden" name="_token" value="{{ csrf_token() }}">',
                    focusConfirm: true,
                    showCancelButton: true,
                    confirmButtonText: "確定",
                    cancelButtonText: "燒等"

                    }).then(function (result) {

                        // 輸出使用者輸入的帳密
                        console.log(document.querySelector("input[name=username]").value+", "+document.querySelector("input[name=password]").value);

                        var student_id = document.querySelector("input[name=username]").value;

                        // 針對該組投票
                        axios.post('//127.0.0.1:8000/loginitouch', {
                            userId: student_id,
                            password: document.querySelector("input[name=password]").value
                        })
                        .then(function (response) {
                            
                            var verifResult = response.data[0].substr(-7, 4); // 取得系級跟班級
                            var dept_class = ["資管一甲", "資管一乙", "資管二甲", "資管二乙", "資管三甲", "資管三乙", "資管四甲", "資管四乙"];

                            // 判斷是否為合格投票者
                            if (dept_class.indexOf(verifResult) != -1) {

                                //針對該組投票
                                axios.post('//127.0.0.1:8000/group/' + selfRoute + '/vote', {
                                    id: selfRoute,
                                    student_id: student_id,
                                    class: verifResult
                                })
                                .then(function (response) {
                                    
                                    // response message
                                    if (response.data != false)
                                    {
                                        self.failed('你投過了！')
                                    }
                                    else
                                    {
                                        self.success('已完成投票！')
                                    }
                                })
                                .catch(function (error) {
                                    
                                });
                            }
                        })
                        .catch(function (error) {
                            
                        });
                    
                }).catch()
            }     
        },
        mounted: function() {

            axios.get('//127.0.0.1:8000/group/info/' + this.$route.params.id).then(response => {this.items = response.data.info;this.message = response.data.status;})
            axios.get('//127.0.0.1:8000/activity/info/' + this.$route.params.id).then(response => {this.activitys = response.data})
            axios.get('//127.0.0.1:8000/login/status').then(response => {this.token = response.data.token;this.username = response.data.username})
        }
    }
</script>




