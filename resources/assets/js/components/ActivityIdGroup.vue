<template>
    <div>
        <div class="ui inverted menu cycuvote-theme cycuvote-menu fixed">
            <a class="active item font-style" @click="$router.push('/')">中原大學資訊管理學系投票系統</a>
            <a class="item font-style" @click="$router.push('/')">活動列表</a>
            <a class="item font-style" @click="$router.push('/finished')">公佈欄</a>
            <div class="right menu">
                <a class="item font-style" v-if="token">{{ this.username }} 您好</a>
                <a class="item font-style" v-if="token" @click="logout">登出</a>
                <a class="item font-style" v-if="!token" @click="$router.push('/login')">登入</a>
            </div>
        </div>

        <div class="ui grid stackable cycuvote-container">

            <div class="four wide column">
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
                                <i class="calendar icon"></i>
                                <div class="content font-style">
                                    {{ activitys.started }} ~ {{ activitys.deadline }}
                                </div>
                            </div>
                            <div class="item">
                                <i class="user icon"></i>
                                <div class="content font-style">
                                    <div class="ui list">
                                        <div class="item" v-for="(voter, index) in activitys.voter" :key="index">{{ voter }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="isLogin">
                    <div class="ui bottom black attached button font-style" @click="inputItouch" v-if="voting == 0">投給這組</div>
                    <div class="ui bottom disabled attached button font-style" v-else>finished</div>
                </div>
                <div class="ui bottom black attached button font-style" v-else @click="login">投給這組</div>
                
            </div>
            <div class="twelve wide column">
                <div class="ui piled segment project-content-text">
                    {{ items.description }}
                </div>
                <h4 class="ui horizontal divider header">
                    <i class="image icon"></i> 海報預覽
                </h4>
                <div class="ui fluid images">
                    <img v-img:name v-for="(item, index) in items.photo" :key="index" v-bind:src="item" class="team-images" />
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
                voting: '',
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
                axios.get('//140.135.112.191/logout')
                this.$router.go('/');
            },
            login: function () {
                this.$router.push('/login');
            },
            inputItouch: function () {
                
                var selfRoute   = this.$route.params.id;
                var self        = this;
                var dept        = [ "資管一甲", "資管一乙", "資管二甲", "資管二乙", "資管三甲", "資管三乙", "資管四甲", "資管四乙"];
                
                self.$swal({

                    title: '將神聖一票投給這組？',
                    text: "送出後不可反悔，且不可重複投票。",
                    showCancelButton: true,
                    confirmButtonText: "確定",
                    cancelButtonText: "考慮"

                    }).then(function (res) {

                        if (dept.indexOf(self.level) == -1)
                        {
                            self.$swal({
                                title: '您的系級不適用此活動！',
                                text: self.level + "尚無權限參與此投票",
                                confirmButtonText: "知道了",
                            });
                            return false;
                        }

                        axios.get('//140.135.112.191/voting/' + selfRoute)
                        .then(function (res) {

                            if (res.data.status == true)
                            {
                                self.mess('已完成投票！', 'success');
                                self.voting = 1;
                                return false;
                            }
                            self.mess('投票失敗！', 'error');
                        });

                    }).catch(function () {

                    });
            }     
        },
        mounted: function() {

            var self    = this;
            var router  = this.$router;

            axios.get('//140.135.112.191/group/info/' + this.$route.params.id).then(function (res) 
                {
                    self.items = res.data.info;
                    self.voting = res.data.voting;
                    self.message = res.data.status;
                    console.log(self.items);
                });
            axios.get('//140.135.112.191/activity/info/' + this.$route.params.id).then(response => {this.activitys = response.data})
            axios.get('//140.135.112.191/login/status')
                .then(function (res) {

                    if (res.data.status == false)
                    {
                        return false;
                    }
                    self.isLogin    = true;
                    self.token      = res.data.token;
                    self.username   = res.data.username;
                    self.level      = res.data.level;
                })
                .catch(function () {

                });
        }
    }
</script>




