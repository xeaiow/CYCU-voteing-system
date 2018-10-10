<template>
    <div>

        <div class="ts secondary inverted negative message hover-pointer" v-if="errorPage" @click="$router.push('/');">
            <div class="header">嗚嗚怎麼沒有東西</div>
            <p>你欲拜訪的活動可能因時效過了而下架。</p>
        </div>

        <div v-else v-cloak>
            <!-- 頂部固定選單 -->
            <div class="ts large link attached inverted info menu" v-if="name != ''">
                <div class="ts container">
                    <div class="header item hover-pointer" @click="logout()">登出 {{ name }}</div>
                </div>
            </div>
            <!-- / 頂部固定選單 -->

            <!-- 主要容器 -->
            <div class="ts very padded text container">

                <div class="ts fluid stackable buttons margin-navbar">
                    <button class="ts button" @click="$router.push('/activity/' + activitys._id)">回列表</button>
                    <button class="ts primary button" @click="votingGroup()" :disabled="vote">投給這組 😃</button>
                    <button class="ts button" @click="share()">分享到臉書 🙏</button>
                </div>
                <!-- 主要信件卡片 -->
                <div class="ts card">
                    <!-- 頂部內容與標題 -->
                    <div class="center aligned padded content">
                        <div class="ts large header">
                            {{ items.groups }}
                            <div class="sub header">
                                {{ items.description }}
                            </div>
                        </div>
                    </div>
                    <!-- / 頂部內容與標題 -->

                    <!-- 特色圖片 -->
                    <div class="image">
                        <img v-bind:src="items.img">
                    </div>
                    <!-- / 特色圖片 -->

                    <!-- 主要推銷內容 -->
                    <div class="center aligned padded content">

                        <!-- 區段分隔線 -->
                        <div class="ts section divider"></div>
                        <!-- / 區段分隔線 -->

                        <h3>可投票系級</h3>
                        <p>
                            <a @click="deptLabel(voter)" class="ts horizontal link label" v-for="(voter, index) in activitys.voter" :key="index">{{ voter }}</a>
                        </p>

                        <!-- 特色項目群組 -->
                        <div class="ts relaxed items">
                            <h3>精彩圖集</h3>
                            <!-- 單個項目 -->
                            <div class="item">
                                <div class="image" v-for="(item, index) in items.photo" :key="index">
                                    <img v-img:name v-bind:src="item" />
                                </div>
                            </div>
                            <!-- / 單個項目 -->
                        </div>
                        <!-- / 特色項目群組 -->
                    </div>
                    <!-- / 主要推銷內容 -->
                </div>
                <!-- / 主要信件卡片 -->
            </div>
            <!-- / 主要容器 -->
        </div>
    </div>
</template>

<script>
    export default {

        data: function () {
            return {
                token: '',
                name: '',
                dept: '',
                items: {},
                activitys: {},
                voter: '',
                vote: false,
                errorPage: false
            }
        },
        methods: {
            logout: function () {
                axios.get('//127.0.0.1:8000/logout')
                this.$router.go('/');
            },
            inputItouch: function () {
                
                var selfRoute   = this.$route.params.id;
                var self        = this;
                
                swal.mixin({
                    input: 'text',
                    confirmButtonText: '繼續 &rarr;',
                    showCancelButton: true,
                    progressSteps: ['1', '2']
                }).queue([
                {
                    title: '身分驗證',
                    text: '請輸入您的 i-Touch 帳號'
                },
                '輸入您 i-Touch 密碼',
                ]).then((result) => {
                    
                    if (result.value) {
                        axios.post('//127.0.0.1:8000/api/loginitouch', {
                            userId: result.value[0],
                            password: result.value[1]
                        })
                        .then(function (response) {

                            let res = response.data;

                            if (!res.status) {
                                switch (res.msg) {
                                    case 1:
                                        self.errorMessage("偵測到沒投票的權利，你一定是邊緣人", "我是邊緣人QQ");
                                        break;
                                    default:
                                        swal({
                                            type: 'error',
                                            title: '糟糕',
                                            text: '帳號或密碼錯誤！'
                                        });
                                        return false;
                                        break;
                                }
                                return false;
                            }

                            sessionStorage.setItem('token', res.token);
                            sessionStorage.setItem('name', res.name);
                            sessionStorage.setItem('username', res.username);
                            sessionStorage.setItem('dept', res.dept);
                            self.token = res.token;
                            self.name = res.name;
                            self.dept = res.dept;

                            self.votingGroup();
                        });
                    }
                })
            },
            deptLabel: function (dept) {
                swal({
                    position: 'top-end',
                    type: 'error',
                    title: '啊就' + dept + '有什麼好點的！',
                    showConfirmButton: false,
                    timer: 1500
                })
            },
            votingGroup: function () {

                if (sessionStorage.getItem('token') == null) {

                    this.inputItouch();
                }
                else {

                    var self = this;

                    this.$swal({
                        title: '將神聖一票投給這組？',
                        text: "送出後不可反悔，且不可重複投同一組。",
                        showCancelButton: true,
                        confirmButtonText: "確定",
                        cancelButtonText: "考慮"                 
                    }).then(function (res) {

                        if (res.dismiss == "cancel") {
                            return false;
                        }

                        let group_id = self.$route.params.id;

                        axios.post('//127.0.0.1:8000/api/voting', {
                            token: sessionStorage.getItem('token'),
                            group_id: group_id
                        })
                        .then(function (response) {

                            let res = response.data;

                            if (res.status) {
                                swal({
                                    position: 'top-end',
                                    type: 'success',
                                    title: '投票成功！',
                                    text: '這個競賽剩下 ' + res.count + ' 次投票機會',
                                    showConfirmButton: false,
                                    timer: 2700
                                });
                                self.vote = true;
                                return;
                            }
                            switch (res.msg) {
                                case 1: 
                                    self.errorMessage("你沒機會投了，明年再來", "喔喔");
                                    self.vote = true;
                                    break;
                                case 2:
                                    self.errorMessage("投過了喇，還沒睡醒？", "知道了");
                                    self.vote = true;
                                    break;
                                case 3:
                                    self.errorMessage("token 失效，請重新登入！", "嗯嗯");
                                    self.inputItouch();
                                    break;
                                default:
                                    break;
                            } 
                        });
                    });
                }
            },
            errorMessage: function (title, buttonText) {
                return swal({
                    title: title,
                    width: 600,
                    padding: '3em',
                    confirmButtonText: buttonText,
                    backdrop: `
                        rgba(0,0,123,0.4)
                        url("https://sweetalert2.github.io//images/nyan-cat.gif")
                        center left
                        no-repeat
                    `
                });
            },
            share: function () {
                window.open('https://www.facebook.com/sharer/sharer.php?kid_directed_site=0&sdk=joey&u=' + window.location.href + '&display=popup&ref=plugin&src=share_button', '分享這組', 'height=400, width=600');
            },
            logout: function () {
                sessionStorage.clear();
                this.name = '';
                this.vote = false;
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: '已登出。',
                    showConfirmButton: false,
                    timer: 700
                });
            }
        },
        mounted: function() {

            var self    = this;
            var router  = this.$router;

            if (sessionStorage.getItem('name') != null) {
                this.name = sessionStorage.getItem('name');
            }

            axios.post('//127.0.0.1:8000/group/info',{
                id: this.$route.params.id,
                token: sessionStorage.getItem('token'),
                username: sessionStorage.getItem('username')

            }).then(function (res) {

                self.items = res.data.info;
                self.vote = res.data.voting;
            });
            
        },
        created: function () {
            var self    = this;

            axios.get('//127.0.0.1:8000/activity/info/' + this.$route.params.id).then(response => {
                if (!response.data.status) {
                    self.$router.push({path: '/404'});
                }
                else {
                    self.activitys = response.data.result;
                }
            });
        }
    }
</script>




