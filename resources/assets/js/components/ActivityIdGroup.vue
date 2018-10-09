<template>
    <div>
         <!-- 頂部固定選單 -->
        <div class="ts large link attached inverted info menu">
            <div class="ts container">
                <div class="header item">Hi, {{ name }}</div>
            </div>
        </div>
        <!-- / 頂部固定選單 -->

        <!-- 主要容器 -->
        <div class="ts very padded text container">
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

                    <!-- CTA：矚目動作按鈕 -->
                    <button class="ts positive button" @click="votingGroup()">投給這組 😃</button>
                    <!-- / CTA：矚目動作按鈕 -->
                    <div class="fb-share-button" data-href="http://cr.im.cycu.edu.tw:90/" data-layout="button" data-size="large" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">快點分享</a></div>

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
            <!-- 左側欄位 -->
            <div class="twelve wide column">
                <br>
                <!-- 頂部聊天室資訊欄位 -->
                <div class="ts top attached segment">
                    <div class="ts list">
                        <div class="item">
                            <div class="ts mini rounded image">
                                <img src="https://examples.tocas-ui.com/images/user.png">
                            </div>
                            <div class="content">
                                <div class="header">評論</div>
                                <div class="meta">
                                    <span>@ {{ items.groups }}</span>
                                </div>
                            </div>
                            <div class="actions">
                                <div class="ts secondary icon button">
                                    <i class="vertical ellipsis icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / 頂部聊天室資訊欄位 -->

                <!-- 中部主要聊天訊息 -->
                <div class="ts attached secondary segment">
                    <!-- 對話氣泡 -->
                    <div class="ts speeches">
                        <!-- 左側群組 -->
                        <div class="circular group">
                            <div class="speech">
                                <div class="avatar">
                                    <img src="https://examples.tocas-ui.com/images/user.png">
                                </div>
                                <div class="content">喔！你醒了啊？</div>
                            </div>
                            <div class="speech">
                                <div class="avatar">
                                    <img src="https://examples.tocas-ui.com/images/user.png">
                                </div>
                                <div class="content">我已經連續兩天沒休息了。</div>
                            </div>
                            <div class="speech">
                                <div class="avatar">
                                    <img src="https://examples.tocas-ui.com/images/user.png">
                                </div>
                                <div class="content">猜猜我在做什麼？</div>
                            </div>
                        </div>
                        <!-- / 左側群組 -->

                        <!-- 右側群組 -->
                        <div class="right circular group">
                            <div class="speech">
                                <div class="avatar">
                                    <img src="https://examples.tocas-ui.com/images/user2.png">
                                </div>
                                <div class="content">額⋯⋯</div>
                            </div>
                            <div class="speech">
                                <div class="avatar">
                                    <img src="https://examples.tocas-ui.com/images/user2.png">
                                </div>
                                <div class="content">跟我聊天？</div>
                            </div>
                        </div>
                        <!-- / 右側群組 -->
                    </div>
                    <!-- / 對話氣泡 -->
                </div>
                <!-- / 中部主要聊天訊息 -->

                <!-- 底部輸入欄位 -->
                <div class="ts bottom attached segment">
                    <div class="ts grid">
                        <div class="stretched column">
                            <div class="ts fluid input">
                                <textarea rows="5" placeholder="在此輸入文字⋯⋯"></textarea>
                            </div>
                        </div>
                        <div class="column">
                            <div class="ts button">送出</div>
                        </div>
                    </div>
                </div>
                <!-- / 底部輸入欄位 -->
            </div>
            <!-- / 左側欄位 -->
        </div>
        <!-- / 主要容器 -->
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
                voting: '',
                activity: '',
                isLogin: false,
                verifResult:'',
                username: '',
                password: '',
                token: '',
                level: ''
            }
        },
        methods: {
            mess: function (text, type) {

                this.$swal(text, '', type)
            },
            logout: function () {
                axios.get('//127.0.0.1:8000/logout')
                this.$router.go('/');
            },
            login: function () {
                this.$router.push('/login');
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
                                        swal({
                                            title: '偵測到沒投票的權利，你一定是邊緣人',
                                            width: 600,
                                            padding: '3em',
                                            confirmButtonText: "我是邊緣人QQ",
                                            backdrop: `
                                                rgba(0,0,123,0.4)
                                                url("https://sweetalert2.github.io//images/nyan-cat.gif")
                                                center left
                                                no-repeat
                                            `
                                        });
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
                    let self = this;

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
                            let self = this;

                            if (res.status) {
                                swal({
                                    position: 'top-end',
                                    type: 'success',
                                    title: '投票成功！',
                                    text: '這個競賽剩下 ' + res.count + ' 次投票機會',
                                    showConfirmButton: false,
                                    timer: 2700
                                });
                                return;
                            }
                            switch (res.msg) {
                                case 1:
                                    swal({
                                        title: '你沒機會投了，明年再來',
                                        width: 600,
                                        padding: '3em',
                                        confirmButtonText: "喔喔",
                                        backdrop: `
                                            rgba(0,0,123,0.4)
                                            url("https://sweetalert2.github.io//images/nyan-cat.gif")
                                            center left
                                            no-repeat
                                        `
                                    });
                                    break;
                                case 2:
                                    swal({
                                        title: '偵測到沒投票的權利，你一定是邊緣人',
                                        width: 600,
                                        padding: '3em',
                                        confirmButtonText: "我是邊緣人QQ",
                                        backdrop: `
                                            rgba(0,0,123,0.4)
                                            url("https://sweetalert2.github.io//images/nyan-cat.gif")
                                            center left
                                            no-repeat
                                        `
                                    });
                                    break;
                                case 3:
                                    swal({
                                        title: 'token 失效，請重新登入！',
                                        width: 600,
                                        padding: '3em',
                                        confirmButtonText: "嗯嗯",
                                        backdrop: `
                                            rgba(0,0,123,0.4)
                                            url("https://sweetalert2.github.io//images/nyan-cat.gif")
                                            center left
                                            no-repeat
                                        `
                                    });
                                    //TODO:: call 重新登入的 function
                                    break;
                                    default:
                                        break;
                                }
                                return false;
                        });
                    });
                }
            }
        },
        mounted: function() {

            var self    = this;
            var router  = this.$router;

            if (sessionStorage.getItem('name') != null) {
                this.name = sessionStorage.getItem('name');
            }

            axios.get('//127.0.0.1:8000/group/info/' + this.$route.params.id).then(function (res) 
                {
                    self.items = res.data.info;
                    self.voting = res.data.voting;
                    self.message = res.data.status;
                    self.activity = res.data.info.activity;
                });
            axios.get('//127.0.0.1:8000/activity/info/' + this.$route.params.id).then(response => {this.activitys = response.data})
            axios.get('//127.0.0.1:8000/login/status')
            .then(function (res) {

                if (res.data.status == false)
                {
                    return false;
                }
                self.isLogin    = true;
                self.token      = res.data.token;
                self.username   = res.data.username;
                self.level      = res.data.level;
            });
        }
    }
</script>




