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
                    <div class="padded content">

                        <!-- 區段分隔線 -->
                        <div class="ts section divider"></div>
                        <!-- / 區段分隔線 -->

                        <h3>可投票系級</h3>
                        <p>
                            <a @click="deptLabel(voter)" class="ts horizontal link label" v-for="(voter, index) in activitys.voter" :key="index">{{ voter }}</a>
                        </p>
                        <div class="ts hidden divider"></div>
                        <!-- 特色項目群組 -->
                        <div class="ts tiny images">
                            <h3>精彩圖集</h3>
                            <img v-for="(item, index) in items.photo" :key="index" v-img:name v-bind:src="item" />
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
        metaInfo() {
            return {
                title: this.items.groups,
                meta: [
                    {
                        property: 'og:title',
                        content: this.items.groups,
                        vmid: 'og:title'
                    },
                    {
                        property: 'og:url',
                        content: window.location.href,
                        vmid: 'og:url'
                    },
                    {
                        property: 'og:image',
                        content: this.items.img,
                        vmid: 'og:image'
                    },
                    {
                        property: 'og:description',
                        content: '擇你所愛，選你所擇',
                        vmid: 'og:description'
                    }
                ]
            }
        },
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
                axios.get('/logout')
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
                    text: '輸入您的愛觸摸帳號'
                },{
                    title: '輸入您的密碼',
                    inputType: "password"
                }]).then((result) => {
                    
                    if (result.value) {
                        axios.post('api/logindemo', {
                            username: result.value[0],
                            password: result.value[1]
                        })
                        .then(function (response) {

                            let res = response.data;

                            if (!res.status) {
                                switch (res.msg) {
                                    case 1:
                                        self.errorMessage("偵測到沒投票的權利，你一定是系邊", "我是邊緣人QQ");
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
                swal.fire({
                    position: 'top-end',
                    type: 'info',
                    title: `你點擊了${dept}，但不會因此變成學霸`,
                    showConfirmButton: false,
                    timer: 2000
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

                        axios.post('api/voting', {
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
                window.open('https://www.facebook.com/sharer/sharer.php?kid_directed_site=0&sdk=joey&u=' + window.location.href + '/&display=popup&ref=plugin&src=share_button', '分享這組', 'height=400, width=600');
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

            axios.post('/group/info',{
                id: this.$route.params.id,
                token: sessionStorage.getItem('token'),
                username: sessionStorage.getItem('username')

            }).then(function (res) {

                self.items = res.data.info;
                self.vote = res.data.voting;

                document.title = res.data.info.groups;

                let metaList = document.getElementsByTagName("META");
                metaList[4].setAttribute("content", res.data.info.groups);
                metaList[5].setAttribute("content", res.data.info.img);
                metaList[5].setAttribute("content", window.location);
            });            
        },
        created: function () {
            var self    = this;

            axios.get('/activity/info/' + this.$route.params.id).then(response => {
                if (!response.data.status) {
                    self.$router.push({path: '404'});
                }
                else {
                    self.activitys = response.data.result;
                }
            });
        }
    }
</script>




